<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Support\Facades\Route;

/**
 * Toutes les pages publiques du site, pour l'écran « Textes et images » :
 * pages du registre, pages déclarées directement dans routes/web.php, et
 * fiches événements de « Bénin en Fête ».
 */
final class SitePages
{
    /** Adresses techniques, privées ou sans page à modifier. */
    private const EXCLUDED = '#^/(admin|livewire|filament|storage|up$|sitemap\.xml|robots\.txt|wp-json|recherche|restaurants/|mes-restos|a-propos-de-lanpt)#';

    /** Titres des pages servies par une fonction (sans titre déclaré dans la route). */
    private const NAMED = ['restaurants' => 'Où manger au Bénin — Catalogue restaurants'];

    /** @return list<array{path:string,title:string,group:string}> */
    public static function all(): array
    {
        $pages = [];

        foreach (app(PageRepository::class)->all() as $path => $entry) {
            $pages[$path] = ['path' => $path, 'title' => self::clean((string) ($entry['title'] ?? $path)), 'group' => 'Pages du site'];
        }

        foreach (Route::getRoutes()->getRoutes() as $route) {
            if (! in_array('GET', $route->methods(), true) || str_contains($route->uri(), '{')) {
                continue;
            }
            $uses = is_string($route->getAction('uses')) ? $route->getAction('uses') : '';
            if (str_contains($uses, 'RedirectController')) {
                continue;
            }
            $path = ContentLayer::normalisePath('/'.$route->uri());
            if (preg_match(self::EXCLUDED, $path) || isset($pages[$path])) {
                continue;
            }
            $title = $route->defaults['data']['title'] ?? (self::NAMED[$route->getName() ?? ''] ?? ($route->getName() ?? $path));
            $pages[$path] = ['path' => $path, 'title' => self::clean((string) $title), 'group' => 'Pages spéciales'];
        }

        $file = resource_path('data/benin-events.php');
        if (is_file($file)) {
            foreach ((array) require $file as $slug => $event) {
                $path = '/benin-en-fete/'.$slug;
                $pages[$path] = ['path' => $path, 'title' => self::clean((string) ($event['title'] ?? $slug)), 'group' => 'Événements'];
            }
        }

        $order = ['Pages du site' => 0, 'Pages spéciales' => 1, 'Événements' => 2];
        $list = array_values($pages);
        usort($list, static fn (array $a, array $b) => [$order[$a['group']], $a['path']] <=> [$order[$b['group']], $b['path']]);

        return $list;
    }

    public static function exists(string $path): bool
    {
        $path = ContentLayer::normalisePath($path);
        foreach (self::all() as $page) {
            if ($page['path'] === $path) {
                return true;
            }
        }

        return false;
    }

    private static function clean(string $title): string
    {
        return trim((string) preg_replace('/\s*[|–—-]\s*Bonne\s+Arriv[ée]e\s*$/iu', '', $title)) ?: $title;
    }
}
