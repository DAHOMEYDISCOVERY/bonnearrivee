<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

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
        if (!class_exists(Page::class) || !Schema::hasTable('pages')) {
            return [];
        }
        return Page::where('title', 'like', "%{$q}%")
            ->orWhere('description', 'like', "%{$q}%")
            ->orWhere('content_override', 'like', "%{$q}%")
            ->limit(10)
            ->get(['id', 'path', 'title', 'description'])
            ->map(fn ($p) => [
                'title'    => $p->title,
                'subtitle' => null,
                'excerpt'  => mb_substr(strip_tags((string) ($p->description ?? '')), 0, 160),
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
                    'title'    => $e['title'] ?? $slug,
                    'subtitle' => $e['subtitle'] ?? ($e['lieu'] ?? null),
                    'excerpt'  => mb_substr(strip_tags((string) ($e['description'] ?? '')), 0, 160),
                    'url'      => '/benin-en-fete/' . $slug,
                    'badge'    => 'Événement',
                ];
            }
            if (count($hits) >= 10) break;
        }
        return $hits;
    }
}
