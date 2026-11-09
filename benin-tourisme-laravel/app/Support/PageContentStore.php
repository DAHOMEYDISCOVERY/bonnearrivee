<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;

/**
 * Enregistrement des modifications « Textes et images », page par page.
 *
 * Une ligne site_settings par page modifiée (clé « contenu:/chemin ») et un
 * index des chemins modifiés : à chaque visite, une seule lecture en cache
 * suffit pour savoir qu'une page n'a rien à appliquer.
 */
final class PageContentStore
{
    private const INDEX_CACHE = 'contenu-index:v1';

    /** @return array<string, array<string, mixed>> clé d'élément => modification */
    public static function edits(string $path): array
    {
        $path = ContentLayer::normalisePath($path);
        if (! in_array($path, self::paths(), true)) {
            return [];
        }
        $data = SiteSetting::read(ContentLayer::settingKey($path));

        return is_array($data['items'] ?? null) ? $data['items'] : [];
    }

    /** @param array<string, array<string, mixed>> $items */
    public static function save(string $path, array $items): void
    {
        $path = ContentLayer::normalisePath($path);
        $key = ContentLayer::settingKey($path);

        if ($items === []) {
            SiteSetting::forgetKey($key);
        } else {
            SiteSetting::write($key, ['path' => $path, 'items' => $items, 'updated_at' => now()->toIso8601String()]);
            Cache::forget(SiteSetting::cacheKey($key));
        }

        $paths = array_values(array_diff(self::paths(), [$path]));
        if ($items !== []) {
            $paths[] = $path;
        }
        sort($paths);
        $paths === []
            ? SiteSetting::forgetKey(ContentLayer::INDEX_KEY)
            : SiteSetting::write(ContentLayer::INDEX_KEY, ['paths' => $paths]);
        Cache::forget(SiteSetting::cacheKey(ContentLayer::INDEX_KEY));
        Cache::forget(self::INDEX_CACHE);

        // Journal des publications (audit, prévention) et recherche à jour.
        \Illuminate\Support\Facades\Log::info('Back-office : textes et images publiés', [
            'page' => $path,
            'modifications' => count($items),
            'utilisateur' => auth()->id(),
        ]);
        SearchIndex::refresh($path);

        // Met l'anglais à jour juste après la réponse (aucun worker requis).
        if ((string) config('services.deepl.key') !== '') {
            \App\Jobs\TranslatePageJob::dispatchAfterResponse($path);
        }
    }

    public static function count(string $path): int
    {
        return count(self::edits($path));
    }

    /** @return list<string> */
    public static function paths(): array
    {
        try {
            return Cache::rememberForever(self::INDEX_CACHE, static function (): array {
                $index = SiteSetting::read(ContentLayer::INDEX_KEY);

                return array_values(array_filter((array) ($index['paths'] ?? []), 'is_string'));
            });
        } catch (\Throwable) {
            return [];
        }
    }
}
