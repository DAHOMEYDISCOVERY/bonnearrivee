<?php

declare(strict_types=1);

namespace App\Services\Aggregator;

use Illuminate\Support\Collection;

/**
 * Façade unique appelée depuis les composants Blade.
 *
 * Elle sait à quelle source déléguer selon le "kind" demandé — les vues
 * n'ont jamais à connaître qu'un contenu vient de tel WordPress ou de
 * tel sitemap.
 */
final class Aggregator
{
    public function __construct(
        private readonly DahomeyDiscoverySource $dahomey,
        private readonly PetitTrainSource $petitTrain,
        private readonly WaniloSource $wanilo,
    ) {
    }

    /**
     * Circuits — bassin large : Dahomey Discovery (7 CPT touristiques) + Petit Train (circuits urbains).
     *
     * @param  array<string, scalar|null>  $filters
     * @return Collection<int, ExternalItem>
     */
    public function circuits(array $filters = [], int $limit = 6): Collection
    {
        $dahomey = $this->dahomey->fetch('circuit', $filters, $limit);
        // Le petit train n'est pertinent que sur Cotonou
        $city = $filters['city'] ?? null;
        $petit = ($city === null || str_contains(strtolower((string) $city), 'cotonou'))
            ? $this->petitTrain->fetch('circuit', [], 3)
            : collect();

        return $dahomey->concat($petit)->take($limit);
    }

    /**
     * Hébergements — Dahomey Discovery uniquement.
     *
     * @param  array<string, scalar|null>  $filters
     */
    public function accommodations(array $filters = [], int $limit = 6): Collection
    {
        return $this->dahomey->fetch('accommodation', $filters, $limit);
    }

    public function vehicles(array $filters = [], int $limit = 6): Collection
    {
        return $this->dahomey->fetch('vehicle', $filters, $limit);
    }

    public function transfers(array $filters = [], int $limit = 6): Collection
    {
        return $this->dahomey->fetch('transfer', $filters, $limit);
    }

    public function attractions(array $filters = [], int $limit = 6): Collection
    {
        return $this->dahomey->fetch('attraction', $filters, $limit);
    }

    /**
     * Circuits du petit train à Cotonou.
     */
    public function petitTrainCircuits(int $limit = 3): Collection
    {
        return $this->petitTrain->fetch('circuit', [], $limit);
    }

    /**
     * Produits WooCommerce du petit train (billetterie avec prix).
     */
    public function petitTrainProducts(int $limit = 5): Collection
    {
        return $this->petitTrain->fetch('product', [], $limit);
    }

    /**
     * Actualités du studio Wanilo.
     */
    public function waniloArticles(int $limit = 4): Collection
    {
        return $this->wanilo->fetch('article', [], $limit);
    }

    /**
     * Articles du blog éditorial Dahomey Mag (dahomeydiscovery.com/dahomey-mag).
     * Retourne les WordPress posts avec image, extrait et date.
     */
    public function dahomeyArticles(int $limit = 6): Collection
    {
        return $this->dahomey->fetch('post', [], $limit);
    }
}
