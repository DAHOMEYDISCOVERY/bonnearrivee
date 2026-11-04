<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\Page;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Rendu du contenu personnalisé d'une page (`pages.content_override`).
 *
 * Le back-office pré-remplit ce champ avec le source de la vue Blade d'origine.
 * Ce source contient des composants (`<x-media.figure>`, `<x-card>`,
 * `<x-aggregator.*>`) qui, injectés tels quels par `{!! !!}`, restaient des
 * balises inconnues du navigateur : héros, cartes et blocs disparaissaient.
 *
 * Deux garanties, sans rien retirer du rendu :
 *   1. `isPristine()` reconnaît une copie identique à la vue d'origine ; le
 *      contrôleur sert alors la vue elle-même, à l'identique du code.
 *   2. `render()` compile les composants d'un contenu réellement modifié. En
 *      cas d'échec de compilation, le HTML brut est servi comme avant et
 *      l'erreur est journalisée : le rendu n'est jamais moins bon qu'avant.
 */
final class OverrideHtml
{
    /**
     * Compile les composants Blade présents dans le contenu personnalisé.
     *
     * Les directives (@if, @include, {{ }}…) ont déjà été retirées à
     * l'enregistrement par `Page::stripBladeArtefacts()` ; il ne reste que du
     * HTML et des balises de composants, sans risque de récursion
     * (@extends / @section absents).
     *
     * @param  array<string, mixed>  $data
     */
    public static function render(string $html, array $data = []): string
    {
        if (! str_contains($html, '<x-')) {
            return $html;
        }

        try {
            return Blade::render($html, $data, deleteCachedView: false);
        } catch (\Throwable $e) {
            Log::warning('Contenu personnalisé : compilation des composants impossible, HTML brut servi', [
                'error' => $e->getMessage(),
            ]);

            return $html;
        }
    }

    /**
     * Le contenu personnalisé est-il une simple copie de la vue d'origine ?
     *
     * La comparaison ignore les blancs (sauts de ligne Windows, indentation
     * modifiée par l'éditeur). Le résultat est mis en cache par empreinte du
     * contenu et date de modification de la vue : la vue modifiée sur disque
     * invalide automatiquement la décision.
     */
    public static function isPristine(?string $content, ?string $view): bool
    {
        if ($content === null || $content === '' || $view === null || $view === '') {
            return false;
        }

        $file = resource_path('views/'.str_replace('.', '/', $view).'.blade.php');
        if (! is_file($file)) {
            return false;
        }

        $key = 'override_pristine:'.md5($content).':'.md5($view).':'.filemtime($file);

        return (bool) Cache::rememberForever($key, static function () use ($content, $view): bool {
            $original = Page::extractContentFromView($view);
            if ($original === null) {
                return false;
            }

            return self::normalise($original) === self::normalise(Page::stripBladeArtefacts($content));
        });
    }

    private static function normalise(string $html): string
    {
        return preg_replace('/\s+/u', '', $html) ?? $html;
    }
}
