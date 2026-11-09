<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;

/**
 * Traductions de contenu par langue et par page, appliquées au rendu.
 *
 * Même principe que PageContentStore (modifications « Textes et images »), mais
 * par langue : une variante traduite par clé d'élément (les mêmes clés stables
 * que ContentLayer). Au rendu d'une page dans une langue étrangère, ces
 * variantes priment ; ce qui n'est pas traduit retombe sur le français.
 *
 * Clés site_settings : « traduction:<locale>:/chemin » + un index par langue.
 * Le français n'est jamais stocké ici (c'est la source).
 */
final class TranslationStore
{
    public const PREFIX = 'traduction:';

    private static function key(string $locale, string $path): string
    {
        return self::PREFIX.$locale.':'.ContentLayer::normalisePath($path);
    }

    private static function indexKey(string $locale): string
    {
        return self::PREFIX.$locale.':index';
    }

    private static function indexCache(string $locale): string
    {
        return 'traduction-index:'.$locale.':v1';
    }

    /**
     * Variantes traduites d'une page, prêtes pour ContentLayer::apply().
     *
     * @return array<string, array<string, mixed>> clé => ['value' => …, 'alt' => …]
     */
    public static function get(string $locale, string $path): array
    {
        $path = ContentLayer::normalisePath($path);
        if (! in_array($path, self::paths($locale), true)) {
            return [];
        }
        $data = SiteSetting::read(self::key($locale, $path));

        return is_array($data['items'] ?? null) ? $data['items'] : [];
    }

    /** @param array<string, array<string, mixed>> $items */
    public static function save(string $locale, string $path, array $items): void
    {
        $path = ContentLayer::normalisePath($path);
        $key = self::key($locale, $path);

        if ($items === []) {
            SiteSetting::forgetKey($key);
        } else {
            SiteSetting::write($key, ['path' => $path, 'locale' => $locale, 'items' => $items, 'updated_at' => now()->toIso8601String()]);
            Cache::forget(SiteSetting::cacheKey($key));
        }

        $paths = array_values(array_diff(self::paths($locale), [$path]));
        if ($items !== []) {
            $paths[] = $path;
        }
        sort($paths);
        $paths === []
            ? SiteSetting::forgetKey(self::indexKey($locale))
            : SiteSetting::write(self::indexKey($locale), ['paths' => $paths]);
        Cache::forget(SiteSetting::cacheKey(self::indexKey($locale)));
        Cache::forget(self::indexCache($locale));
    }

    /** @return list<string> */
    public static function paths(string $locale): array
    {
        try {
            return Cache::rememberForever(self::indexCache($locale), static function () use ($locale): array {
                $index = SiteSetting::read(self::indexKey($locale));

                return array_values(array_filter((array) ($index['paths'] ?? []), 'is_string'));
            });
        } catch (\Throwable) {
            return [];
        }
    }
}
