<?php

declare(strict_types=1);

namespace App\Support;

/**
 * Langues du site, en un seul endroit.
 *
 * Le français est la langue source (URLs sans préfixe). Chaque langue étrangère
 * est servie sous son préfixe (/en, /es, /pt) et traduite par DeepL.
 *
 *  - deepl     : langue cible DeepL (EN-GB : anglais britannique ; ES : espagnol
 *                d'Espagne ; PT-BR : portugais du Brésil, choisi pour les liens
 *                historiques Bénin-Brésil — Agudas, Ouidah ; PT-PT possible).
 *  - formality : registre DeepL. « prefer_more » = vouvoiement (usted / o senhor),
 *                cohérent avec le « vous » du site. L'anglais n'en a pas besoin.
 */
final class Locales
{
    public const SOURCE = 'fr';

    /** @var array<string, array{name:string, flag:string, hreflang:string, deepl:?string, formality:?string}> */
    public const ALL = [
        'fr' => ['name' => 'Français',  'flag' => '🇫🇷', 'hreflang' => 'fr', 'deepl' => null,    'formality' => null],
        'en' => ['name' => 'English',   'flag' => '🇬🇧', 'hreflang' => 'en', 'deepl' => 'EN-GB', 'formality' => null],
        'es' => ['name' => 'Español',   'flag' => '🇪🇸', 'hreflang' => 'es', 'deepl' => 'ES',    'formality' => 'prefer_more'],
        'pt' => ['name' => 'Português', 'flag' => '🇧🇷', 'hreflang' => 'pt', 'deepl' => 'PT-BR', 'formality' => 'prefer_more'],
    ];

    /** @return list<string> toutes les langues, français compris */
    public static function codes(): array
    {
        return array_keys(self::ALL);
    }

    /** @return list<string> langues traduites (sans le français) */
    public static function targets(): array
    {
        return array_values(array_diff(self::codes(), [self::SOURCE]));
    }

    public static function supported(string $code): bool
    {
        return isset(self::ALL[$code]);
    }

    public static function isTarget(string $code): bool
    {
        return $code !== self::SOURCE && self::supported($code);
    }

    public static function deepl(string $code): ?string
    {
        return self::ALL[$code]['deepl'] ?? null;
    }

    public static function formality(string $code): ?string
    {
        return self::ALL[$code]['formality'] ?? null;
    }

    /** Chemin dans une langue à partir du chemin français (« / » -> « /en »). */
    public static function path(string $code, string $frPath): string
    {
        $frPath = '/'.ltrim($frPath, '/');
        if (! self::isTarget($code)) {
            return $frPath;
        }

        return '/'.$code.($frPath === '/' ? '' : $frPath);
    }

    /** Alternation regex des préfixes étrangers (« en|es|pt »). */
    public static function prefixPattern(): string
    {
        return implode('|', array_map('preg_quote', self::targets()));
    }
}
