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

        $flat = array_merge(
            array_slice($this->searchRestaurants($q), 0, 4),
            array_slice($this->searchEvents($q), 0, 2),
            array_slice($this->searchPages($q), 0, 2),
        );

        return response()->json([
            'q'       => $q,
            'results' => array_slice($flat, 0, 8),
            'moreUrl' => '/recherche?q=' . urlencode($q),
        ]);
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
            $results['restaurants'] = $this->searchRestaurants($q);
            $results['pages']       = $this->searchPages($q);
            $results['evenements']  = $this->searchEvents($q);
            $total = count($results['restaurants'])
                   + count($results['pages'])
                   + count($results['evenements']);
        }

        return view('pages.recherche', [
            'title'         => $q !== '' ? "Recherche : « {$q} » | Bonne Arrivée" : 'Recherche | Bonne Arrivée',
            'description'   => "Recherchez une adresse, un lieu, un evenement ou une info pratique sur Bonne Arrivée.",
            'activeMenu'    => null,
            'headProfile'   => 'standard',
            'customContent' => null,
            'q'             => $q,
            'results'       => $results,
            'total'         => $total,
        ]);
    }

    private function searchRestaurants(string $q): array
    {
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
            ->map(fn ($r) => [
                'title'    => $r->nom,
                'subtitle' => $r->sous_titre,
                'excerpt'  => ($r->ville ?: '') . ($r->categorie ? ' · ' . (Restaurant::CATEGORIES[$r->categorie] ?? $r->categorie) : ''),
                'url'      => '/sejourner/restaurants',
                'badge'    => $r->coup_de_coeur ? 'Coup de cœur' : null,
            ])->all();
    }

    private function searchPages(string $q): array
    {
        // Index plein texte (texte réellement affiché de chaque page) quand il
        // a été construit ; les fiches événements ont leur propre rubrique.
        if (\App\Support\SearchIndex::exists()) {
            $hits = array_filter(
                \App\Support\SearchIndex::search($q, 20),
                static fn (array $h) => ! str_starts_with($h['path'], '/benin-en-fete/'),
            );

            return array_values(array_map(static fn (array $h) => [
                'title'    => $h['title'],
                'subtitle' => null,
                'excerpt'  => $h['excerpt'],
                'url'      => $h['path'],
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

    private function searchEvents(string $q): array
    {
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
                    'url'      => '/benin-en-fete/' . $slug,
                    'badge'    => 'Événement',
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
