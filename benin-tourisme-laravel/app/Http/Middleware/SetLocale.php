<?php

namespace App\Http\Middleware;

use App\Support\Locales;
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
 * Langues : voir App\Support\Locales (fr source ; en, es, pt traduites).
 *
 * - URL préfixée /en, /es ou /pt -> cette langue : on retire le préfixe (les
 *   routes FR matchent), on mémorise le choix dans un cookie, et on réécrit les
 *   liens internes de la page avec le même préfixe pour que la navigation reste
 *   dans la langue choisie.
 * - URL sans préfixe -> si une langue étrangère a été choisie (cookie), on
 *   redirige vers sa version (rafraîchissement et liens oubliés restent dans la
 *   langue) ; sinon français (langue par défaut, URLs inchangées).
 * - Bascule explicite via la route /langue/{code} (voir routes/web.php) qui
 *   repositionne le cookie : on peut donc toujours revenir au français.
 *
 * Sécurité : en cas d'anomalie, on retombe silencieusement sur le français.
 */
class SetLocale
{
    /** @var string[] */
    public const SUPPORTED = ['fr', 'en', 'es', 'pt'];
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
        'en', 'es', 'pt', 'langue', 'admin', 'livewire', 'filament', 'storage', 'build',
        'assets', 'css', 'js', 'img', 'images', 'fonts', 'media', 'vendor',
        'api', 'sanctum', 'up', 'mes-restos', 'restaurants',
        'sitemap.xml', 'robots.txt', 'favicon.ico',
    ];

    public function handle(Request $request, Closure $next)
    {
        $locale = self::DEFAULT;

        try {
            $path = '/'.ltrim($request->getPathInfo(), '/');

            // Langue étrangère explicite par l'URL (/en, /es, /pt).
            if (preg_match('#^/('.Locales::prefixPattern().')(?:/|$)#', $path, $lm)) {
                $locale = $lm[1];
                Cookie::queue(self::COOKIE, $locale, self::COOKIE_MIN);

                $stripped = substr($path, strlen($locale) + 1);
                $stripped = ($stripped === '' || $stripped === false) ? '/' : $stripped;
                $qs = $request->getQueryString();
                $newUri = $stripped.($qs ? '?'.$qs : '');

                $server = array_merge($request->server->all(), ['REQUEST_URI' => $newUri]);
                // duplicate() réinitialise le cache de pathInfo/requestUri : le
                // routeur verra donc le chemin FR dépréfixé.
                $rebuilt = $request->duplicate(null, null, null, null, null, $server);

                app()->instance('request', $rebuilt);
                app()->setLocale($locale);
                $this->share($locale, $stripped);

                return $this->injectAtlasI18n($this->localiseLinks($next($rebuilt), $locale), $locale);
            }

            // Pas de préfixe : si une langue étrangère a été choisie, on y renvoie
            // pour que la langue reste la même (navigation, rafraîchissement, lien FR).
            if ($pref = $this->preferredForeign($request, $path)) {
                $qs = $request->getQueryString();

                return redirect(Locales::path($pref, $path).($qs ? '?'.$qs : ''), 302);
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

    /** Langue étrangère choisie (cookie) si la page peut y être redirigée, sinon null. */
    private function preferredForeign(Request $request, string $path): ?string
    {
        if (! $request->isMethod('GET')) {
            return null;
        }
        $pref = (string) $request->cookie(self::COOKIE);
        if (! Locales::isTarget($pref)) {
            return null;
        }
        if ($this->isSkipped($path)) {
            return null;
        }
        if (preg_match('#\.[a-z0-9]{2,5}$#i', $path)) {
            return null; // fichier (.css, .js, .png, .pdf…)
        }
        $accept = (string) $request->headers->get('Accept', '');
        if ($accept !== '' && ! str_contains($accept, 'text/html') && ! str_contains($accept, '*/*')) {
            return null;
        }

        return $pref;
    }

    /** Le chemin tombe-t-il dans une zone à ne jamais préfixer (/en, /es, /pt) ? */
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

    /** Dans une langue étrangère, réécrit les liens internes de la page (<body>) avec son préfixe. */
    private function localiseLinks(Response $response, string $locale): Response
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
                fn ($mm) => $mm[1].'="'.$this->prefixPath($mm[2], $locale).'"',
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
     * traduction (public/assets/benin-atlas.{langue}.json) juste avant le script
     * de la carte, pour que benin-atlas.js affiche ses fiches dans la langue.
     */
    private function injectAtlasI18n(Response $response, string $locale): Response
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
            $file = public_path('assets/benin-atlas.'.$locale.'.json');
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

    /** Préfixe un chemin interne avec la langue, sauf assets, ancres et zones techniques. */
    private function prefixPath(string $url, string $locale): string
    {
        if ($url === '' || $url[0] !== '/' || str_starts_with($url, '//')) {
            return $url; // externe ou protocole-relatif
        }
        if (preg_match('~^/('.Locales::prefixPattern().')(?:[/?#]|$)~', $url)) {
            return $url; // déjà dans une langue
        }
        $pathOnly = preg_split('/[?#]/', $url)[0];
        if ($this->isSkipped($pathOnly)) {
            return $url;
        }
        if (preg_match('#\.[a-z0-9]{2,5}$#i', $pathOnly)) {
            return $url; // lien vers un fichier
        }

        return '/'.$locale.($url === '/' ? '' : $url);
    }

    /** Partage la langue courante et les URLs alternées (sélecteur + hreflang). */
    private function share(string $locale, string $frPath): void
    {
        $frPath = '/'.ltrim($frPath, '/');
        $urls = [];
        foreach (Locales::codes() as $code) {
            $urls[$code] = Locales::path($code, $frPath);
        }

        View::share([
            'baLocale' => $locale,
            'baUrlFr' => $frPath,
            'baUrlEn' => $urls['en'],
            'baUrls' => $urls,
        ]);
    }
}
