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

        // Version traduite : la traduction s'applique sur TOUTE la page
        // (en-tête, pied et blocs dynamiques inclus) via le mode « traduction »
        // de ContentLayer, indépendamment des modifications FR du back-office.
        if ($locale !== 'fr') {
            $translations = \App\Support\TranslationStore::get($locale, $path);
            if ($translations === []) {
                return $response;
            }

            $html = (string) $response->getContent();
            if (stripos($html, '<html') === false) {
                return $response;
            }

            try {
                $response->setContent(ContentLayer::apply($html, $translations, true));
            } catch (\Throwable $e) {
                report($e);
            }

            return $response;
        }

        // Version française : modifications « Textes et images » uniquement.
        $edits = PageContentStore::edits($path);
        if ($edits === []) {
            return $response;
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

        return $response;
    }
}
