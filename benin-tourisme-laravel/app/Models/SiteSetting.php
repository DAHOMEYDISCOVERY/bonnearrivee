<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

/**
 * Un réglage de contenu éditable depuis le back-office (valeur JSON).
 *
 * Lecture mise en cache ; tout enregistrement ou suppression invalide le
 * cache, la modification est donc visible à la requête suivante.
 */
final class SiteSetting extends Model
{
    protected $fillable = ['key', 'value'];

    protected $casts = [
        'value' => 'array',
    ];

    /**
     * Valeur enregistrée pour cette clé, ou null si rien n'a été enregistré
     * (ou si la table n'existe pas encore, avant migration).
     */
    public static function read(string $key): ?array
    {
        try {
            return Cache::rememberForever(self::cacheKey($key), static function () use ($key) {
                if (! Schema::hasTable('site_settings')) {
                    return null;
                }
                $value = self::query()->where('key', $key)->value('value');
                $decoded = is_string($value) ? json_decode($value, true) : $value;

                return is_array($decoded) ? $decoded : null;
            });
        } catch (\Throwable) {
            return null;
        }
    }

    public static function write(string $key, array $value): void
    {
        self::query()->updateOrCreate(['key' => $key], ['value' => $value]);
    }

    public static function forgetKey(string $key): void
    {
        self::query()->where('key', $key)->delete();
        Cache::forget(self::cacheKey($key));
    }

    public static function cacheKey(string $key): string
    {
        return 'site_setting:'.$key;
    }

    protected static function booted(): void
    {
        static::saved(function (self $s): void {
            Cache::forget(self::cacheKey($s->key));
            if (! str_starts_with((string) $s->key, 'contenu:')) {
                \Illuminate\Support\Facades\Log::info('Back-office : réglage enregistré', ['cle' => $s->key, 'utilisateur' => auth()->id()]);
            }
        });
        static::deleted(fn (self $s) => Cache::forget(self::cacheKey($s->key)));
    }
}
