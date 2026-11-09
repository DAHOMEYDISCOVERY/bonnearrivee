<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Index de recherche plein texte (audit BUG-12), désormais PAR LANGUE.
 *
 * La recherche ne lisait que le titre, la description et le contenu
 * personnalisé enregistrés en base : le texte des vues et les pages
 * construites en code (agenda, carte, quand partir…) n'étaient jamais
 * trouvés. L'index contient le texte réellement affiché de chaque page — y
 * compris les modifications « Textes et images ».
 *
 * - Index français : texte affiché des pages (modifications comprises).
 * - Index anglais : même texte, mais après application des traductions
 *   (TranslationStore). Une recherche faite depuis la version anglaise
 *   interroge donc l'index anglais : taper « beaches » trouve la page des
 *   plages. Sans traduction pour une page, l'index anglais retombe sur le
 *   français (la page reste trouvable par ses noms propres).
 *
 * Construit par `php artisan search:index` (au déploiement et chaque nuit),
 * mis à jour page par page à chaque enregistrement au back-office. Tant qu'il
 * n'existe pas, la recherche garde son ancien fonctionnement.
 */
final class SearchIndex
{
    private const CACHE_PREFIX = 'search-index:';

    private const MAX_TEXT = 40000;

    /** Langues indexées (le français est la source). */
    public const LOCALES = ['fr', 'en'];

    /**
     * Pages qui ne répondent à aucune recherche de visiteur : la fiche de
     * relecture interne et la liste des crédits photos (elles ne font que
     * reprendre les légendes des autres pages). Elles restent en ligne.
     */
    private const NOT_SEARCHABLE = ['/fiche-de-relecture', '/credits-photos'];

    /** Fichier d'index d'une langue (le français garde l'ancien nom). */
    private static function file(string $locale): string
    {
        return $locale === 'fr' ? 'search/index.json' : 'search/index-'.$locale.'.json';
    }

    private static function cacheKey(string $locale): string
    {
        return self::CACHE_PREFIX.$locale.':v1';
    }

    public static function exists(string $locale = 'fr'): bool
    {
        try {
            return Storage::disk('local')->exists(self::file($locale));
        } catch (\Throwable) {
            return false;
        }
    }

    /**
     * (Re)construit l'index d'une langue ; une liste de chemins limite la
     * construction.
     *
     * @param  list<string>|null  $paths
     * @return int nombre de pages indexées
     */
    public static function build(?array $paths = null, ?callable $progress = null, string $locale = 'fr'): int
    {
        $pages = array_values(array_filter(SitePages::all(), static fn (array $p) => ! in_array($p['path'], self::NOT_SEARCHABLE, true)));
        if ($paths !== null) {
            $wanted = array_map([ContentLayer::class, 'normalisePath'], $paths);
            $pages = array_values(array_filter($pages, static fn (array $p) => in_array($p['path'], $wanted, true)));
        }

        $entries = [];
        foreach ($pages as $page) {
            $entry = self::entryFor($page['path'], $page['title'], $locale);
            if ($entry !== null) {
                $entries[$page['path']] = $entry;
            }
            if ($progress) {
                $progress($page['path'], $entry !== null);
            }
        }

        self::store($entries, $locale);

        return count($entries);
    }

    /** Met à jour une page dans tous les index existants (fr + langues traduites). */
    public static function refresh(string $path): void
    {
        foreach (self::LOCALES as $locale) {
            if (! self::exists($locale)) {
                continue;
            }
            try {
                $path = ContentLayer::normalisePath($path);
                if (in_array($path, self::NOT_SEARCHABLE, true)) {
                    return;
                }
                $entries = self::entries($locale);
                $title = $path;
                foreach (SitePages::all() as $page) {
                    if ($page['path'] === $path) {
                        $title = $page['title'];
                    }
                }
                $entry = self::entryFor($path, $title, $locale);
                if ($entry === null) {
                    unset($entries[$path]);
                } else {
                    $entries[$path] = $entry;
                }
                self::store($entries, $locale);
            } catch (\Throwable $e) {
                Log::warning('Index de recherche : mise à jour impossible', ['path' => $path, 'locale' => $locale, 'error' => $e->getMessage()]);
            }
        }
    }

    /** @return array<string, array{title:string,description:string,text:string}> */
    public static function entries(string $locale = 'fr'): array
    {
        if (! self::exists($locale)) {
            return [];
        }
        try {
            return Cache::rememberForever(self::cacheKey($locale), static function () use ($locale): array {
                $data = json_decode((string) Storage::disk('local')->get(self::file($locale)), true);

                return is_array($data['pages'] ?? null) ? $data['pages'] : [];
            });
        } catch (\Throwable) {
            return [];
        }
    }

    /**
     * Pages dont le titre, la description ou le texte contiennent tous les
     * mots cherchés (sans tenir compte des accents ni des majuscules).
     *
     * @return list<array{path:string,title:string,excerpt:string,score:int}>
     */
    public static function search(string $query, int $limit = 10, string $locale = 'fr'): array
    {
        // Repli sur l'index français si la langue demandée n'a pas d'index.
        if (! self::exists($locale)) {
            $locale = 'fr';
        }

        $words = array_values(array_filter(
            preg_split('/\s+/u', self::fold($query)) ?: [],
            static fn (string $w) => mb_strlen($w) >= 2,
        ));
        if ($words === []) {
            return [];
        }

        $hits = [];
        foreach (self::entries($locale) as $path => $entry) {
            $title = self::fold($entry['title']);
            $description = self::fold($entry['description']);
            $text = self::fold($entry['text']);
            $score = 0;
            foreach ($words as $word) {
                $inTitle = substr_count($title, $word);
                $inDescription = substr_count($description, $word);
                $inText = substr_count($text, $word);
                if ($inTitle + $inDescription + $inText === 0) {
                    continue 2;
                }
                $score += $inTitle * 20 + $inDescription * 5 + min($inText, 20);
            }
            $hits[] = [
                'path' => (string) $path,
                'title' => $entry['title'],
                'excerpt' => self::excerpt($entry['text'], $words[0]) ?: Str::limit($entry['description'], 160, '…', preserveWords: true),
                'score' => $score,
            ];
        }

        usort($hits, static fn (array $a, array $b) => $b['score'] <=> $a['score'] ?: strcmp($a['path'], $b['path']));

        return array_slice($hits, 0, $limit);
    }

    /** @return array{title:string,description:string,text:string}|null */
    private static function entryFor(string $path, string $fallbackTitle, string $locale = 'fr'): ?array
    {
        $html = PageSnapshot::html($path);
        if ($html === null) {
            return null;
        }

        if ($locale === 'fr') {
            $html = ContentLayer::apply($html, PageContentStore::edits($path));
        } else {
            // Texte réellement affiché dans la langue cible : on applique les
            // traductions sur toute la page (mode traduction). Sans traduction,
            // on garde le français (la page reste trouvable par ses noms propres).
            $translations = TranslationStore::get($locale, $path);
            $html = $translations === [] ? $html : ContentLayer::apply($html, $translations, true);
        }

        $title = preg_match('#<title\b[^>]*>(.*?)</title>#is', $html, $m) ? ContentLayer::plain($m[1]) : $fallbackTitle;
        $title = trim((string) preg_replace('/\s*[|–—-]\s*Bonne\s+Arriv[ée]e\s*$/iu', '', $title)) ?: $fallbackTitle;
        $description = preg_match('#<meta\b[^>]*name=["\']description["\'][^>]*content=(["\'])(.*?)\1#is', $html, $m) ? ContentLayer::plain($m[2]) : '';

        $start = preg_match('#<main\b[^>]*>#i', $html, $m, PREG_OFFSET_CAPTURE) ? $m[0][1] : 0;
        $end = strripos($html, '</main>') ?: strlen($html);
        $body = substr($html, $start, max(0, $end - $start));
        $body = (string) preg_replace('#<(script|style|svg|noscript|template)\b.*?</\1\s*>#is', ' ', $body);
        $body = (string) preg_replace('#<!--.*?-->#s', ' ', $body);
        $body = (string) preg_replace('#<(br|/p|/h[1-6]|/li|/div|/section)\b[^>]*>#i', ' $0', $body);
        $text = mb_substr(ContentLayer::plain(strip_tags($body)), 0, self::MAX_TEXT);

        return ['title' => $title, 'description' => $description, 'text' => $text];
    }

    /** @param array<string, array<string, string>> $entries */
    private static function store(array $entries, string $locale = 'fr'): void
    {
        ksort($entries);
        Storage::disk('local')->put(self::file($locale), (string) json_encode(
            ['built_at' => now()->toIso8601String(), 'locale' => $locale, 'pages' => $entries],
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES,
        ));
        Cache::forget(self::cacheKey($locale));
    }

    private static function fold(string $text): string
    {
        return mb_strtolower(Str::ascii($text));
    }

    private static function excerpt(string $text, string $word): string
    {
        $folded = self::fold($text);
        $pos = mb_strpos($folded, $word);
        if ($pos === false) {
            return '';
        }
        // Str::ascii garde le même nombre de caractères pour le français courant ;
        // à défaut on retombe sur le début du texte.
        if (mb_strlen($folded) !== mb_strlen($text)) {
            $pos = 0;
        }
        $from = max(0, $pos - 60);
        $snippet = mb_substr($text, $from, 180);
        if ($from > 0) {
            $snippet = '…'.ltrim((string) preg_replace('/^\S*\s/u', '', $snippet));
        }
        if ($from + 180 < mb_strlen($text)) {
            $snippet = rtrim((string) preg_replace('/\s\S*$/u', '', $snippet)).'…';
        }

        return $snippet;
    }
}
