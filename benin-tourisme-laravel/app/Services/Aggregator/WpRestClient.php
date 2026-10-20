<?php

declare(strict_types=1);

namespace App\Services\Aggregator;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Client HTTP mutualisé pour les APIs WordPress REST des sites externes.
 *
 * Politique choisie : le portail doit rester debout même si un site
 * externe est lent ou indisponible. Chaque appel a un timeout court,
 * un cache long, et un fallback silencieux (tableau vide) plutôt qu'une
 * exception qui casserait la page hôte.
 *
 * Pattern de cache : « stale-while-revalidate ».
 *   - Réponse en cache et jeune  → renvoyée telle quelle
 *   - Réponse en cache mais expirée → renvoyée quand même, ET rafraîchie en
 *     tâche de fond après que la réponse du client soit envoyée
 *     (fastcgi_finish_request). Aucun visiteur ne subit d'attente.
 *   - Pas de réponse en cache → appel synchrone (premier chargement post-deploy)
 */
final class WpRestClient
{
    private const TIMEOUT     = 12;      // secondes — laisser le temps aux réponses _embed
    private const FRESH_FOR   = 21600;   // 6 h — au-delà, on tente un refresh en arrière-plan
    private const STORE_FOR   = 604800;  // 7 j  — durée maximale de conservation du dernier bon résultat

    public function __construct(
        private readonly string $baseUrl,
        private readonly ?string $consumerKey = null,
        private readonly ?string $consumerSecret = null,
        private readonly bool $verifySsl = true,
    ) {
    }

    /**
     * Retourne le JSON décodé, tableau vide en cas d'échec ou d'absence.
     *
     * @param  array<string, scalar|null>  $query
     * @return array<int, array<string, mixed>>
     */
    public function get(string $path, array $query = []): array
    {
        $cacheKey = 'agg:' . md5($this->baseUrl . '|' . $path . '|' . http_build_query($query));
        $stampKey = $cacheKey . ':fresh_until';

        $data       = Cache::get($cacheKey);
        $freshUntil = (int) Cache::get($stampKey, 0);
        $now        = time();

        // Cas nominal : on a de la donnée en cache.
        if (is_array($data)) {
            // Jeune → retour immédiat, rien à faire.
            if ($now < $freshUntil) {
                return $data;
            }

            // Stale : on retourne le vieux cache TOUT DE SUITE et on déclenche
            // le refresh en tâche de fond, après avoir clôturé la réponse HTTP.
            $this->scheduleBackgroundRefresh($cacheKey, $stampKey, $path, $query);

            return $data;
        }

        // Premier chargement : on paie l'appel synchrone une seule fois.
        return $this->fetchAndStore($cacheKey, $stampKey, $path, $query);
    }

    /**
     * Effectue l'appel HTTP et stocke le résultat en cache long.
     * En cas d'échec, on ne touche pas au cache existant : la version
     * précédente reste servie plutôt qu'un tableau vide.
     *
     * @param  array<string, scalar|null>  $query
     * @return array<int, array<string, mixed>>
     */
    private function fetchAndStore(string $cacheKey, string $stampKey, string $path, array $query): array
    {
        $url = rtrim($this->baseUrl, '/') . '/' . ltrim($path, '/');

        try {
            $request = Http::timeout(self::TIMEOUT)
                ->acceptJson()
                ->withUserAgent('BonneArriveePortal/1.0 (+https://bonnearrivee.bj)')
                ->withOptions(['verify' => $this->verifySsl]);

            if ($this->consumerKey && $this->consumerSecret) {
                $request = $request->withBasicAuth($this->consumerKey, $this->consumerSecret);
            }

            $response = $request->get($url, $query);

            if (! $response->successful()) {
                Log::warning('Aggregator: réponse non-2xx', ['url' => $url, 'status' => $response->status()]);

                return Cache::get($cacheKey, []); // conserve le dernier bon résultat
            }

            $json = $response->json();
            $data = is_array($json) ? $json : [];

            Cache::put($cacheKey, $data, self::STORE_FOR);
            Cache::put($stampKey, time() + self::FRESH_FOR, self::STORE_FOR);

            return $data;
        } catch (ConnectionException $e) {
            Log::info('Aggregator: source injoignable', ['url' => $url, 'msg' => $e->getMessage()]);

            return Cache::get($cacheKey, []);
        } catch (Throwable $e) {
            Log::warning('Aggregator: erreur imprévue', ['url' => $url, 'msg' => $e->getMessage()]);

            return Cache::get($cacheKey, []);
        }
    }

    /**
     * Programme un refresh du cache après la clôture de la réponse HTTP.
     *
     * Ne fonctionne réellement qu'avec les SAPI qui supportent
     * `fastcgi_finish_request` (PHP-FPM). Sur Apache mod_php ou
     * `artisan serve` (built-in PHP server), cette fonction n'existe pas —
     * un refresh en `register_shutdown_function` s'exécuterait alors AVANT
     * l'envoi de la réponse, transformant chaque expiration stale en
     * blocage de 3-6 s pour le visiteur. Dans ces cas, on préfère servir
     * le stale indéfiniment et déléguer le refresh à `php artisan
     * aggregator:warm` (lancé manuellement ou par cron/scheduler).
     *
     * @param  array<string, scalar|null>  $query
     */
    private function scheduleBackgroundRefresh(string $cacheKey, string $stampKey, string $path, array $query): void
    {
        // Environnement sans finish_request : on ne tente rien pour ne pas
        // bloquer le visiteur. Le stale reste servi jusqu'au prochain warm.
        if (! function_exists('fastcgi_finish_request')) {
            return;
        }

        // Verrou anti-concurrence : un seul refresh à la fois, 30 s max.
        $lockKey = $cacheKey . ':refreshing';
        if (Cache::get($lockKey)) {
            return;
        }
        Cache::put($lockKey, 1, 30);

        register_shutdown_function(function () use ($cacheKey, $stampKey, $lockKey, $path, $query): void {
            fastcgi_finish_request();
            try {
                $this->fetchAndStore($cacheKey, $stampKey, $path, $query);
            } finally {
                Cache::forget($lockKey);
            }
        });
    }
}
