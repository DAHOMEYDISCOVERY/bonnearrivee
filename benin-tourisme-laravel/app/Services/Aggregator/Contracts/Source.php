<?php

declare(strict_types=1);

namespace App\Services\Aggregator\Contracts;

use App\Services\Aggregator\ExternalItem;
use Illuminate\Support\Collection;

/**
 * Un site externe capable de fournir des ExternalItem au portail.
 *
 * Chaque source est libre d'implémenter fetch() comme elle veut
 * (REST WordPress, GraphQL, sitemap XML, scraping HTML). L'appelant
 * ne voit que des ExternalItem — jamais du JSON brut.
 */
interface Source
{
    /**
     * @param  array<string, scalar|null>  $filters  filtres optionnels (city, type, tourism_type…)
     * @return Collection<int, ExternalItem>
     */
    public function fetch(string $kind, array $filters = [], int $limit = 10): Collection;
}
