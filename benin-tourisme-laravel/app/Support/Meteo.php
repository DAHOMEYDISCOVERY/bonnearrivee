<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

/**
 * Météo actuelle à Cotonou, affichée dans l'en-tête du site.
 *
 * Source : Open-Meteo (gratuit, sans clé, données publiques). Le résultat est
 * mis en cache 30 minutes : au plus deux appels par heure, quel que soit le
 * trafic. En cas d'échec (réseau, service indisponible), rien n'est affiché et
 * le nouvel essai attend 10 minutes, pour ne jamais ralentir les pages.
 */
final class Meteo
{
    public const TIMEZONE = 'Africa/Porto-Novo';

    private const LAT = 6.3654;   // Cotonou
    private const LON = 2.4183;

    /**
     * @return array{temp:int, code:int, jour:bool}|null
     */
    public static function actuelle(): ?array
    {
        try {
            $data = Cache::get('meteo:cotonou:v2');
            if (is_array($data)) {
                return $data['temp'] === null ? null : $data;
            }

            $data = self::interroger();
            Cache::put('meteo:cotonou:v2', $data ?? ['temp' => null], $data ? now()->addMinutes(30) : now()->addMinutes(10));

            return $data;
        } catch (\Throwable) {
            return null;
        }
    }

    /** @return array{temp:int, code:int, jour:bool}|null */
    private static function interroger(): ?array
    {
        try {
            // Même règle que les appels DeepL : certificats de storage/app/cacert.pem
            // s'il existe (WAMP local), sinon ceux du système.
            $ca = storage_path('app/cacert.pem');
            $verify = is_file($ca) ? $ca : true;
            $resp = Http::timeout(3)->connectTimeout(2)->withOptions(['verify' => $verify])->acceptJson()->get('https://api.open-meteo.com/v1/forecast', [
                'latitude' => self::LAT,
                'longitude' => self::LON,
                'current' => 'temperature_2m,weather_code,is_day',
                'timezone' => self::TIMEZONE,
            ]);
            if (! $resp->ok()) {
                return null;
            }
            $c = $resp->json('current');
            if (! is_array($c) || ! is_numeric($c['temperature_2m'] ?? null)) {
                return null;
            }

            return [
                'temp' => (int) round((float) $c['temperature_2m']),
                'code' => (int) ($c['weather_code'] ?? 0),
                'jour' => (bool) ($c['is_day'] ?? true),
            ];
        } catch (\Throwable) {
            return null;
        }
    }

    /** Famille d'icône selon le code météo WMO. */
    public static function icone(int $code, bool $jour): string
    {
        return match (true) {
            $code === 0 => $jour ? 'soleil' : 'lune',
            $code <= 2 => $jour ? 'eclaircie' : 'lune',
            $code === 3 => 'nuage',
            $code === 45 || $code === 48 => 'brume',
            $code >= 95 => 'orage',
            ($code >= 51 && $code <= 67) || ($code >= 80 && $code <= 82) => 'pluie',
            default => 'nuage',
        };
    }

    /** Libellé court (infobulle et lecteurs d'écran), dans la langue de la page. */
    public static function libelle(string $icone, string $locale): string
    {
        $t = [
            'fr' => ['soleil' => 'Ensoleillé', 'lune' => 'Ciel dégagé', 'eclaircie' => 'Éclaircies', 'nuage' => 'Nuageux', 'brume' => 'Brume', 'orage' => 'Orageux', 'pluie' => 'Pluie'],
            'en' => ['soleil' => 'Sunny', 'lune' => 'Clear sky', 'eclaircie' => 'Partly cloudy', 'nuage' => 'Cloudy', 'brume' => 'Mist', 'orage' => 'Thunderstorms', 'pluie' => 'Rain'],
            'es' => ['soleil' => 'Soleado', 'lune' => 'Cielo despejado', 'eclaircie' => 'Parcialmente nublado', 'nuage' => 'Nublado', 'brume' => 'Neblina', 'orage' => 'Tormentas', 'pluie' => 'Lluvia'],
            'pt' => ['soleil' => 'Ensolarado', 'lune' => 'Céu limpo', 'eclaircie' => 'Parcialmente nublado', 'nuage' => 'Nublado', 'brume' => 'Névoa', 'orage' => 'Trovoadas', 'pluie' => 'Chuva'],
        ];

        return $t[$locale][$icone] ?? $t['fr'][$icone] ?? '';
    }
}
