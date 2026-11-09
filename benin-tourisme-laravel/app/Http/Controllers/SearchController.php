<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * Recherche globale du site : interroge restaurants, pages statiques et
 * evenements (data file) a partir d'un query string `q`. Retourne une
 * page de resultats groupes par categorie.
 */
class SearchController extends Controller
{
    /**
     * Endpoint JSON pour l'autocomplete du header (dropdown live).
     * Retourne les 6-8 meilleurs resultats, a plat, pour une UI legere.
     */
    public function suggest(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        if (mb_strlen($q) < 2) {
            return response()->json(['q' => $q, 'results' => []]);
        }

        $locale = $this->locale($request);
        $prefix = $locale === 'en' ? '/en' : '';

        $flat = array_merge(
            array_slice($this->searchRestaurants($q, $locale), 0, 4),
            array_slice($this->searchEvents($q, $locale), 0, 2),
            array_slice($this->searchPages($q, $locale), 0, 2),
        );

        return response()->json([
            'q'       => $q,
            'results' => array_slice($flat, 0, 8),
            'moreUrl' => $prefix . '/recherche?q=' . urlencode($q),
        ]);
    }

    /**
     * Langue de la recherche : anglais si la page courante est en anglais
     * (app locale) ou si l'utilisateur a choisi l'anglais (cookie) — ce dernier
     * cas couvre l'appel AJAX /recherche/suggest, servi sans préfixe /en.
     */
    private function locale(Request $request): string
    {
        return (app()->getLocale() === 'en' || $request->cookie('ba_locale') === 'en') ? 'en' : 'fr';
    }

    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $results = [
            'restaurants' => [],
            'pages'       => [],
            'evenements'  => [],
        ];
        $total = 0;

        if (mb_strlen($q) >= 2) {
            $locale                 = $this->locale($request);
            $results['restaurants'] = $this->searchRestaurants($q, $locale);
            $results['pages']       = $this->searchPages($q, $locale);
            $results['evenements']  = $this->searchEvents($q, $locale);
            $total = count($results['restaurants'])
                   + count($results['pages'])
                   + count($results['evenements']);
        }

        return view('pages.recherche', [
            'title'         => $q !== '' ? __('messages.search_page.title')." : « {$q} » | Bonne Arrivée" : __('messages.search_page.title').' | Bonne Arrivée',
            'description'   => "Recherchez une adresse, un lieu, un evenement ou une info pratique sur Bonne Arrivée.",
            'activeMenu'    => null,
            'headProfile'   => 'standard',
            'customContent' => null,
            'q'             => $q,
            'results'       => $results,
            'total'         => $total,
        ]);
    }

    /** Libellés de catégorie en anglais (le nom et la ville restent tels quels). */
    private const CATEGORIES_EN = [
        'gastronomique' => 'Fine dining',
        'maquis'        => 'Local eatery & cuisine',
        'cafe_bar'      => 'Café / bar',
        'fast_food'     => 'Fast food',
        'international'  => 'International',
    ];

    private function searchRestaurants(string $q, string $locale = 'fr'): array
    {
        $en = $locale === 'en';
        $prefix = $en ? '/en' : '';

        return Restaurant::published()
            ->where(function ($w) use ($q) {
                $w->where('nom', 'like', "%{$q}%")
                  ->orWhere('sous_titre', 'like', "%{$q}%")
                  ->orWhere('ville', 'like', "%{$q}%")
                  ->orWhere('quartier', 'like', "%{$q}%")
                  ->orWhere('specialite', 'like', "%{$q}%");
            })
            ->orderByDesc('coup_de_coeur')
            ->orderBy('sort_order')
            ->limit(10)
            ->get(['id', 'nom', 'sous_titre', 'ville', 'categorie', 'coup_de_coeur'])
            ->map(function ($r) use ($en, $prefix) {
                $cat = $r->categorie
                    ? ($en ? (self::CATEGORIES_EN[$r->categorie] ?? $r->categorie) : (Restaurant::CATEGORIES[$r->categorie] ?? $r->categorie))
                    : '';

                // Le sous-titre est du texte libre en base : en anglais, on
                // réutilise sa traduction déjà faite sur la page restaurants.
                $subtitle = $en
                    ? \App\Support\PageTextMap::translate('en', '/sejourner/restaurants', $r->sous_titre)
                    : $r->sous_titre;

                return [
                    'title'    => $r->nom,
                    'subtitle' => $subtitle,
                    'excerpt'  => ($r->ville ?: '') . ($cat ? ' · ' . $cat : ''),
                    'url'      => $prefix . '/sejourner/restaurants',
                    'badge'    => $r->coup_de_coeur ? ($en ? 'Top pick' : 'Coup de cœur') : null,
                ];
            })->all();
    }

    private function searchPages(string $q, string $locale = 'fr'): array
    {
        // Index plein texte (texte réellement affiché de chaque page) quand il
        // a été construit ; les fiches événements ont leur propre rubrique.
        // En anglais, on interroge l'index anglais (repli fr automatique).
        if (\App\Support\SearchIndex::exists($locale) || \App\Support\SearchIndex::exists()) {
            $prefix = $locale === 'en' ? '/en' : '';
            $hits = array_filter(
                \App\Support\SearchIndex::search($q, 20, $locale),
                static fn (array $h) => ! str_starts_with($h['path'], '/benin-en-fete/'),
            );

            return array_values(array_map(static fn (array $h) => [
                'title'    => $h['title'],
                'subtitle' => null,
                'excerpt'  => $h['excerpt'],
                'url'      => $prefix . $h['path'],
                'badge'    => null,
            ], array_slice($hits, 0, 10)));
        }

        if (!class_exists(Page::class) || !Schema::hasTable('pages')) {
            return [];
        }
        return Page::where('title', 'like', "%{$q}%")
            ->orWhere('description', 'like', "%{$q}%")
            ->orWhere('content_override', 'like', "%{$q}%")
            ->limit(10)
            ->get(['id', 'path', 'title', 'description'])
            ->map(fn ($p) => [
                'title'    => self::titre((string) $p->title),
                'subtitle' => null,
                'excerpt'  => self::extrait($p->description),
                'url'      => '/' . ltrim((string) $p->path, '/'),
                'badge'    => null,
            ])->all();
    }

    private function searchEvents(string $q, string $locale = 'fr'): array
    {
        $en = $locale === 'en';

        // En anglais, les fiches événements traduites viennent de l'index
        // anglais (titres et extraits déjà traduits), avec repli sur les
        // données françaises si l'index anglais n'existe pas encore.
        if ($en && \App\Support\SearchIndex::exists('en')) {
            $hits = array_filter(
                \App\Support\SearchIndex::search($q, 10, 'en'),
                static fn (array $h) => str_starts_with($h['path'], '/benin-en-fete/'),
            );

            return array_values(array_map(static fn (array $h) => [
                'title'    => $h['title'],
                'subtitle' => null,
                'excerpt'  => $h['excerpt'],
                'url'      => '/en' . $h['path'],
                'badge'    => 'Event',
            ], array_slice($hits, 0, 10)));
        }

        $file = resource_path('data/benin-events.php');
        if (!is_file($file)) {
            return [];
        }
        $events = require $file;
        $hits   = [];
        $qLower = mb_strtolower($q);
        foreach ($events as $slug => $e) {
            $haystack = mb_strtolower(
                ($e['title'] ?? '') . ' '
                . ($e['subtitle'] ?? '') . ' '
                . ($e['description'] ?? '') . ' '
                . ($e['chapo'] ?? '') . ' '
                . ($e['lieu'] ?? '')
            );
            if (mb_strpos($haystack, $qLower) !== false) {
                $hits[] = [
                    'title'    => self::titre((string) ($e['title'] ?? $slug)),
                    'subtitle' => $e['subtitle'] ?? ($e['lieu'] ?? null),
                    'excerpt'  => self::extrait($e['description'] ?? null),
                    'url'      => ($en ? '/en' : '') . '/benin-en-fete/' . $slug,
                    'badge'    => $en ? 'Event' : 'Événement',
                ];
            }
            if (count($hits) >= 10) break;
        }
        return $hits;
    }

    /**
     * Titre affiché dans un résultat : sans le suffixe « | Bonne Arrivée »
     * hérité de la balise <title>, inutile dans une liste du même site.
     */
    private static function titre(string $titre): string
    {
        return trim((string) preg_replace('/\s*[|–—-]\s*Bonne\s+Arriv[ée]e\s*$/iu', '', $titre)) ?: $titre;
    }

    /**
     * Extrait de 160 caractères au plus, coupé entre deux mots, avec « … »
     * quand le texte continue.
     */
    private static function extrait(?string $texte): string
    {
        $propre = Str::squish(html_entity_decode(strip_tags((string) $texte), ENT_QUOTES | ENT_HTML5, 'UTF-8'));

        return Str::limit($propre, 160, '…', preserveWords: true);
    }
}
