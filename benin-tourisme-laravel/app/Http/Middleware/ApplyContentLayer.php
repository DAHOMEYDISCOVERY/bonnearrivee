<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Support\ContentLayer;
use App\Support\PageContentStore;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Publie les modifications « Textes et images » faites au back-office : à
 * chaque page servie, les textes, images et liens modifiés remplacent ceux
 * d'origine (voir ContentLayer). Une page sans modification n'est pas lue.
 *
 * En cas d'erreur imprévue, la page d'origine est servie telle quelle et
 * l'erreur est journalisée : une modification ne peut pas casser le site.
 */
final class ApplyContentLayer
{
    private const EXCLUDED = ['admin', 'admin/*', 'livewire/*', 'filament/*', 'storage/*', 'mes-restos/*', 'restaurants/*', 'recherche/suggest', 'sitemap.xml'];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! $request->isMethod('GET')
            || $response->getStatusCode() !== 200
            || $response instanceof StreamedResponse
            || $response instanceof BinaryFileResponse
            || $request->is(...self::EXCLUDED)) {
            return $response;
        }

        $type = (string) $response->headers->get('Content-Type', '');
        if ($type !== '' && ! str_contains($type, 'text/html')) {
            return $response;
        }

        $path = $request->getPathInfo();
        $locale = app()->getLocale();

        // Version traduite : on part de la page FRANÇAISE ACTUELLE (avec les
        // modifications « Textes et images » du back-office, dont dépendent aussi
        // certains designs), puis la traduction s'applique sur toute la page
        // (en-tête, pied et blocs dynamiques inclus) via le mode « traduction ».
        // Ce qui n'est pas encore traduit reste en français actuel.
        if ($locale !== 'fr') {
            $edits = PageContentStore::edits($path);
            $translations = \App\Support\TranslationStore::get($locale, $path);
            try {
                $jsDict = class_exists(\App\Support\JsDictionary::class) ? \App\Support\JsDictionary::get($locale) : [];
            } catch (\Throwable $e) {
                report($e);
                $jsDict = [];
            }
            if ($edits === [] && $translations === [] && $jsDict === []) {
                return self::masquerAttente($response);
            }

            $html = (string) $response->getContent();
            if (stripos($html, '<html') === false) {
                return $response;
            }

            if ($edits !== []) {
                try {
                    $html = ContentLayer::apply($html, $edits);
                } catch (\Throwable $e) {
                    report($e);
                }
            }

            if ($translations !== []) {
                try {
                    $html = ContentLayer::apply($html, $translations, true);
                } catch (\Throwable $e) {
                    report($e);
                }
            }

            // Textes affichés par le JavaScript des pages : on joint à la page les
            // traductions dont elle a besoin, appliquées à l'affichage.
            if ($jsDict !== []) {
                try {
                    $needed = array_intersect_key($jsDict, array_flip(\App\Support\JsStrings::fromHtml($html)));
                    $tag = \App\Support\JsDictionary::script($needed);
                    if ($tag !== '' && preg_match('#<head\b[^>]*>#i', $html, $hm, PREG_OFFSET_CAPTURE)) {
                        $at = $hm[0][1] + strlen($hm[0][0]);
                        $html = substr($html, 0, $at).$tag.substr($html, $at);
                    }
                } catch (\Throwable $e) {
                    report($e);
                }
            }

            // Pages dont la mise en page est construite par ba-recit.js : il a besoin
            // de la version française de la page. On la demande dès l'en-tête, pour
            // qu'elle soit prête quand le script s'exécute (pas d'ancienne mise en page).
            if (str_contains($html, 'ba-recit-cfg') && preg_match('#<head\b[^>]*>#i', $html, $hm, PREG_OFFSET_CAPTURE)) {
                $at = $hm[0][1] + strlen($hm[0][0]);
                $lien = '<link rel="preload" as="fetch" href="'.e($path).'" crossorigin>';
                $html = substr($html, 0, $at).$lien.substr($html, $at);
            }

            $response->setContent(\App\Support\RecitPreHide::inject($html));

            return $response;
        }

        // Version française : modifications « Textes et images » uniquement.
        $edits = PageContentStore::edits($path);
        if ($edits === []) {
            return self::masquerAttente($response);
        }

        $html = (string) $response->getContent();
        if (stripos($html, '<html') === false) {
            return $response;
        }

        try {
            $response->setContent(ContentLayer::apply($html, $edits));
        } catch (\Throwable $e) {
            report($e);
        }

        return self::masquerAttente($response);
    }

    /** Masque d'emblée le texte que ba-recit.js va remettre en forme (voir RecitPreHide). */
    private static function masquerAttente(Response $response): Response
    {
        try {
            $html = (string) $response->getContent();
            if (str_contains($html, 'ba-recit-cfg') && stripos($html, '<html') !== false) {
                $response->setContent(\App\Support\RecitPreHide::inject($html));
            }
        } catch (\Throwable $e) {
            report($e);
        }

        return $response;
    }
}
