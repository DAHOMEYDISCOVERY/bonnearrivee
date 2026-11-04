<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Versionne automatiquement les feuilles et scripts propres au projet.
 *
 * Les `?v=events111`, `?v=events97`, `?v=a043a557`… étaient tapés à la main
 * dans trois gabarits de <head> et une trentaine de vues, et `.htaccess` met
 * ces fichiers en cache un an (`immutable`). Résultat : une correction CSS
 * pouvait apparaître sur une page et rester invisible sur une autre.
 *
 * Ce middleware réécrit, dans chaque page HTML servie, le paramètre `?v=` de
 * `/assets/benin-*.css|js` avec la date de modification réelle du fichier.
 * Il agit au même endroit pour toutes les sources de rendu (vues, contenu
 * personnalisé en base, blocs header/footer en base). Une URL sans `?v=` est
 * laissée telle quelle.
 */
final class VersionProjectAssets
{
    private const PATTERN = '#(/assets/(benin-[a-z0-9.-]+\.(?:css|js)))\?v=[A-Za-z0-9_-]+#';

    /** @var array<string, string> */
    private array $versions = [];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $type = (string) $response->headers->get('Content-Type', '');
        if (! str_contains($type, 'text/html') || ! method_exists($response, 'getContent')) {
            return $response;
        }

        $html = $response->getContent();
        if (! is_string($html) || ! str_contains($html, '/assets/benin-')) {
            return $response;
        }

        $rewritten = preg_replace_callback(self::PATTERN, function (array $m): string {
            return $m[1].'?v='.$this->versionOf($m[2]);
        }, $html);

        if (is_string($rewritten)) {
            $response->setContent($rewritten);
        }

        return $response;
    }

    private function versionOf(string $file): string
    {
        if (! isset($this->versions[$file])) {
            $path = public_path('assets/'.$file);
            $this->versions[$file] = is_file($path) ? (string) filemtime($path) : 'absent';
        }

        return $this->versions[$file];
    }
}
