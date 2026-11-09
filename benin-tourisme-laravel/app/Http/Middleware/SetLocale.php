<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Détermine la langue de la requête et la MAINTIENT d'une page à l'autre.
 *
 * - URL préfixée /en  -> anglais : on retire le préfixe (les routes FR
 *   matchent), on mémorise le choix dans un cookie, et on réécrit les liens
 *   internes de la page en /en pour que la navigation reste en anglais.
 * - URL sans préfixe  -> si l'anglais a été choisi (cookie), on redirige vers
 *   la version /en (le rafraîchissement et les liens oubliés restent anglais) ;
 *   sinon français (langue par défaut, URLs inchangées).
 * - Bascule explicite via la route /langue/{fr|en} (voir routes/web.php) qui
 *   repositionne le cookie : on peut donc toujours revenir au français.
 *
 * Sécurité : en cas d'anomalie, on retombe silencieusement sur le français.
 */
class SetLocale
{
    /** @var string[] */
    public const SUPPORTED = ['fr', 'en'];
    public const DEFAULT = 'fr';

    /** Cookie de langue (1 an). */
    private const COOKIE = 'ba_locale';
    private const COOKIE_MIN = 525600;

    /**
     * Préfixes jamais préfixés /en et jamais redirigés : back-office, assets,
     * technique, route de langue, et zones déjà exclues de la traduction.
     *
     * @var string[]
     */
    private const SKIP = [
        'en', 'langue', 'admin', 'livewire', 'filament', 'storage', 'build',
        'assets', 'css', 'js', 'img', 'images', 'fonts', 'media', 'vendor',
        'api', 'sanctum', 'up', 'mes-restos', 'restaurants',
        'sitemap.xml', 'robots.txt', 'favicon.ico',
    ];

    public function handle(Request $request, Closure $next)
    {
        $locale = self::DEFAULT;

        try {
            $path = '/'.ltrim($request->getPathInfo(), '/');

            // Anglais explicite par l'URL.
            if ($path === '/en' || str_starts_with($path, '/en/')) {
                $locale = 'en';
                Cookie::queue(self::COOKIE, 'en', self::COOKIE_MIN);

                $stripped = substr($path, 3);
                $stripped = ($stripped === '' || $stripped === false) ? '/' : $stripped;
                $qs = $request->getQueryString();
                $newUri = $stripped.($qs ? '?'.$qs : '');

                $server = array_merge($request->server->all(), ['REQUEST_URI' => $newUri]);
                // duplicate() réinitialise le cache de pathInfo/requestUri : le
                // routeur verra donc le chemin FR dépréfixé.
                $rebuilt = $request->duplicate(null, null, null, null, null, $server);

                app()->instance('request', $rebuilt);
                app()->setLocale('en');
                $this->share('en', $stripped);

                return $this->injectAtlasI18n($this->localiseLinks($next($rebuilt)));
            }

            // Pas de préfixe : si l'anglais a été choisi, on y renvoie pour que
            // la langue reste la même (navigation, rafraîchissement, lien FR).
            if ($this->prefersEnglish($request, $path)) {
                $qs = $request->getQueryString();

                return redirect('/en'.($path === '/' ? '' : $path).($qs ? '?'.$qs : ''), 302);
            }

            Cookie::queue(self::COOKIE, 'fr', self::COOKIE_MIN);
            $this->share('fr', $path);
        } catch (\Throwable $e) {
            $locale = self::DEFAULT;
            $this->share(self::DEFAULT, '/');
        }

        app()->setLocale($locale);

        return $next($request);
    }

    /** Vrai si l'utilisateur a choisi l'anglais et que la page peut être redirigée. */
    private function prefersEnglish(Request $request, string $path): bool
    {
        if (! $request->isMethod('GET')) {
            return false;
        }
        if ($request->cookie(self::COOKIE) !== 'en') {
            return false;
        }
        if ($this->isSkipped($path)) {
            return false;
        }
        if (preg_match('#\.[a-z0-9]{2,5}$#i', $path)) {
            return false; // fichier (.css, .js, .png, .pdf…)
        }
        $accept = (string) $request->headers->get('Accept', '');
        if ($accept !== '' && ! str_contains($accept, 'text/html') && ! str_contains($accept, '*/*')) {
            return false;
        }

        return true;
    }

    /** Le chemin tombe-t-il dans une zone à ne jamais préfixer /en ? */
    private function isSkipped(string $path): bool
    {
        $p = preg_split('/[?#]/', $path)[0];
        foreach (self::SKIP as $skip) {
            if ($p === '/'.$skip || str_starts_with($p, '/'.$skip.'/')) {
                return true;
            }
        }

        return false;
    }

    /** En anglais, réécrit les liens internes de la page (<body>) en /en. */
    private function localiseLinks(Response $response): Response
    {
        try {
            if ($response instanceof StreamedResponse || $response instanceof BinaryFileResponse) {
                return $response;
            }
            $type = (string) $response->headers->get('Content-Type', '');
            if ($type !== '' && ! str_contains($type, 'text/html')) {
                return $response;
            }
            $html = (string) $response->getContent();
            if (stripos($html, '<html') === false) {
                return $response;
            }

            // On ne touche qu'au <body> : les <link> canonical/hreflang et les
            // feuilles de style du <head> restent intacts.
            if (! preg_match('#<body\b[^>]*>#i', $html, $m, PREG_OFFSET_CAPTURE)) {
                return $response;
            }
            $start = $m[0][1] + strlen($m[0][0]);
            $end = strripos($html, '</body>');
            if ($end === false || $end <= $start) {
                return $response;
            }

            $body = substr($html, $start, $end - $start);
            $body = preg_replace_callback(
                '#\b(href|action)\s*=\s*"(/[^"]*)"#i',
                fn ($mm) => $mm[1].'="'.$this->prefixPath($mm[2]).'"',
                $body
            ) ?? $body;

            $response->setContent(substr($html, 0, $start).$body.substr($html, $end));
        } catch (\Throwable $e) {
            report($e);
        }

        return $response;
    }

    /**
     * Sur les pages portant la carte interactive, injecte le dictionnaire de
     * traduction (public/assets/benin-atlas.en.json) juste avant le script de
     * la carte, pour que benin-atlas.js affiche ses fiches en anglais.
     */
    private function injectAtlasI18n(Response $response): Response
    {
        try {
            if ($response instanceof StreamedResponse || $response instanceof BinaryFileResponse) {
                return $response;
            }
            $type = (string) $response->headers->get('Content-Type', '');
            if ($type !== '' && ! str_contains($type, 'text/html')) {
                return $response;
            }
            $html = (string) $response->getContent();
            if (! preg_match('#<script[^>]*\bbenin-atlas\.js\b[^>]*>#i', $html, $m, PREG_OFFSET_CAPTURE)) {
                return $response; // pas de carte sur cette page
            }
            $file = public_path('assets/benin-atlas.en.json');
            if (! is_file($file)) {
                return $response;
            }
            $json = trim((string) @file_get_contents($file));
            if ($json === '' || json_decode($json) === null) {
                return $response;
            }

            $at = $m[0][1];
            $script = '<script>window.__ATLAS_I18N='.$json.';</script>';
            $response->setContent(substr($html, 0, $at).$script.substr($html, $at));
        } catch (\Throwable $e) {
            report($e);
        }

        return $response;
    }

    /** Préfixe un chemin interne de /en, sauf assets, ancres et zones techniques. */
    private function prefixPath(string $url): string
    {
        if ($url === '' || $url[0] !== '/' || str_starts_with($url, '//')) {
            return $url; // externe ou protocole-relatif
        }
        if ($url === '/en' || str_starts_with($url, '/en/')) {
            return $url; // déjà anglais
        }
        $pathOnly = preg_split('/[?#]/', $url)[0];
        if ($this->isSkipped($pathOnly)) {
            return $url;
        }
        if (preg_match('#\.[a-z0-9]{2,5}$#i', $pathOnly)) {
            return $url; // lien vers un fichier
        }

        return '/en'.($url === '/' ? '' : $url);
    }

    /** Partage la langue courante et les URLs alternées (sélecteur + hreflang). */
    private function share(string $locale, string $frPath): void
    {
        $frPath = '/'.ltrim($frPath, '/');
        $enPath = $frPath === '/' ? '/en' : '/en'.$frPath;

        View::share([
            'baLocale' => $locale,
            'baUrlFr' => $frPath,
            'baUrlEn' => $enPath,
        ]);
    }
}
