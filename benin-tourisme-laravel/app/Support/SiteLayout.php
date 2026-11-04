<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\SiteSetting;

/**
 * Contenu de l'en-tête et du pied de page.
 *
 * Priorité : la version enregistrée dans le back-office (table site_settings),
 * sinon les valeurs par défaut de config/site_layout.php. Le balisage, lui,
 * reste dans les partials Blade : le code et le back-office ne se marchent
 * donc plus dessus.
 */
final class SiteLayout
{
    public const HEADER_KEY = 'layout.header';
    public const FOOTER_KEY = 'layout.footer';

    /** @var array<string, array{src:string, srcset:?string}> */
    private static array $images = [];

    /** @return array{menu: list<array<string, mixed>>} */
    public static function header(): array
    {
        $saved = SiteSetting::read(self::HEADER_KEY);
        if (is_array($saved) && isset($saved['menu']) && is_array($saved['menu']) && $saved['menu'] !== []) {
            return $saved;
        }

        return self::defaultHeader();
    }

    /** @return array<string, mixed> */
    public static function footer(): array
    {
        $saved = SiteSetting::read(self::FOOTER_KEY);
        if (is_array($saved) && $saved !== []) {
            return $saved + self::defaultFooter();
        }

        return self::defaultFooter();
    }

    /** @return array{menu: list<array<string, mixed>>} */
    public static function defaultHeader(): array
    {
        return (array) config('site_layout.header', ['menu' => []]);
    }

    /** @return array<string, mixed> */
    public static function defaultFooter(): array
    {
        return (array) config('site_layout.footer', []);
    }

    public static function hasSavedHeader(): bool
    {
        return SiteSetting::read(self::HEADER_KEY) !== null;
    }

    public static function hasSavedFooter(): bool
    {
        return SiteSetting::read(self::FOOTER_KEY) !== null;
    }

    /**
     * Source et jeu de résolutions d'une image de carte.
     *
     * Les images du site existent en quatre largeurs (`nom-640x320.webp`,
     * `nom-360x180.webp`…). Quand l'image choisie suit ce schéma et que les
     * variantes existent, on reconstruit le srcset d'origine ; sinon (image
     * téléversée depuis le back-office) l'image est servie seule.
     *
     * @return array{src:string, srcset:?string}
     */
    public static function image(string $src): array
    {
        if (isset(self::$images[$src])) {
            return self::$images[$src];
        }

        $srcset = null;
        if (preg_match('#^(/assets/img/.+)-(\d+)x(\d+)\.(webp|jpe?g|png)$#', $src, $m)) {
            $parts = [];
            foreach ([360, 640, 1200, 1920] as $w) {
                $candidate = sprintf('%s-%dx%d.%s', $m[1], $w, intdiv($w, 2), $m[4]);
                if (is_file(public_path(ltrim($candidate, '/')))) {
                    $parts[] = $candidate.' '.$w.'w';
                }
            }
            $srcset = $parts !== [] ? implode(', ', $parts) : null;
        }

        return self::$images[$src] = ['src' => $src, 'srcset' => $srcset];
    }

    /**
     * Libellé lu par les lecteurs d'écran : le titre, « & » écrit « et »
     * (« Cafés & bars » → « Cafés et bars »), comme dans le menu d'origine.
     */
    public static function aria(string $title): string
    {
        return str_replace(' & ', ' et ', $title);
    }

    /**
     * Numéro de téléphone affiché → valeur de lien `tel:` (chiffres et « + »).
     * « +229 01 67 11 02 20 » → « +2290167110220 ».
     */
    public static function telHref(string $display): string
    {
        $digits = preg_replace('/[^\d+]/', '', $display) ?? '';

        return $digits;
    }
}
