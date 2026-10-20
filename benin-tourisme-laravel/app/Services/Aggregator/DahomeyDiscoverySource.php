<?php

declare(strict_types=1);

namespace App\Services\Aggregator;

use App\Services\Aggregator\Contracts\Source;
use Illuminate\Support\Collection;

/**
 * Source : dahomeydiscovery.com (WordPress + WooCommerce).
 *
 * Les CPT exposés côté /wp-json/wp/v2/* sont :
 *   circuit, accommodation, vehicle, attraction, transfer, immersion, establishment
 *
 * Les taxonomies utiles pour filtrer : city, tourism_type, accommodation_type,
 * star_rating, vehicle_type. Elles sont interrogeables par slug via l'API standard.
 */
final class DahomeyDiscoverySource implements Source
{
    private const SOURCE = 'dahomey';
    private const SITE_URL = 'https://dahomeydiscovery.com';

    /** @var array<string, string> mapping kind → CPT WordPress */
    private const KIND_TO_CPT = [
        'circuit'       => 'circuit',
        'accommodation' => 'accommodation',
        'vehicle'       => 'vehicle',
        'attraction'    => 'attraction',
        'transfer'      => 'transfer',
        'immersion'     => 'immersion',
        'establishment' => 'establishment',
        'post'          => 'posts',  // blog Dahomey Mag
    ];

    public function __construct(private readonly WpRestClient $client)
    {
    }

    public function fetch(string $kind, array $filters = [], int $limit = 10): Collection
    {
        $cpt = self::KIND_TO_CPT[$kind] ?? null;
        if ($cpt === null) {
            return collect();
        }

        $query = [
            'per_page' => max(1, min($limit, 50)),
            '_embed'   => 'wp:featuredmedia,wp:term',
        ];

        // WP REST attend un ID de terme, pas un slug. On résout à la volée
        // chaque filtre slug → id via l'API taxonomie (résultat mis en cache
        // 1 h côté WpRestClient).
        foreach (['city', 'tourism_type', 'accommodation_type', 'star_rating', 'vehicle_type'] as $tax) {
            if (empty($filters[$tax])) {
                continue;
            }
            $slugs = is_array($filters[$tax]) ? $filters[$tax] : explode(',', (string) $filters[$tax]);
            $ids = [];
            foreach ($slugs as $slug) {
                $slug = trim((string) $slug);
                if ($slug === '') {
                    continue;
                }
                $termId = $this->resolveTermId($tax, $slug);
                if ($termId !== null) {
                    $ids[] = $termId;
                }
            }
            if ($ids !== []) {
                $query[$tax] = implode(',', $ids);
            }
        }

        $rows = $this->client->get("/wp-json/wp/v2/{$cpt}", $query);

        return collect($rows)->map(fn (array $row) => $this->mapItem($row, $kind))->filter()->values();
    }

    private function resolveTermId(string $taxonomy, string $slug): ?int
    {
        $rows = $this->client->get("/wp-json/wp/v2/{$taxonomy}", ['slug' => $slug, 'per_page' => 1]);

        return isset($rows[0]['id']) ? (int) $rows[0]['id'] : null;
    }

    /**
     * @param  array<string, mixed>  $row
     */
    private function mapItem(array $row, string $kind): ?ExternalItem
    {
        if (empty($row['id']) || empty($row['title']['rendered'])) {
            return null;
        }

        $image = $this->extractFeaturedImage($row);
        $city = $this->extractTerm($row, 'city');
        $excerpt = strip_tags((string) ($row['excerpt']['rendered'] ?? ''));
        $excerpt = trim(preg_replace('/\s+/', ' ', $excerpt)) ?: null;

        return new ExternalItem(
            source:      self::SOURCE,
            id:          (string) $row['id'],
            title:       html_entity_decode(strip_tags((string) $row['title']['rendered'])),
            url:         (string) ($row['link'] ?? self::SITE_URL),
            excerpt:     $excerpt ? mb_substr($excerpt, 0, 240) : null,
            image:       $image,
            city:        $city,
            type:        $kind,
            price:       $this->extractPrice($excerpt),
            publishedAt: (string) ($row['date'] ?? null) ?: null,
        );
    }

    /** @param array<string, mixed> $row */
    private function extractFeaturedImage(array $row): ?string
    {
        $media = $row['_embedded']['wp:featuredmedia'][0] ?? null;
        if (! is_array($media)) {
            return null;
        }

        // Priorité : medium_large > medium > full source_url
        $sizes = $media['media_details']['sizes'] ?? [];
        foreach (['medium_large', 'medium', 'large', 'full'] as $size) {
            if (! empty($sizes[$size]['source_url'])) {
                return (string) $sizes[$size]['source_url'];
            }
        }

        return isset($media['source_url']) ? (string) $media['source_url'] : null;
    }

    /** @param array<string, mixed> $row */
    private function extractTerm(array $row, string $taxonomy): ?string
    {
        $groups = $row['_embedded']['wp:term'] ?? [];
        foreach ($groups as $group) {
            if (! is_array($group)) {
                continue;
            }
            foreach ($group as $term) {
                if (($term['taxonomy'] ?? null) === $taxonomy) {
                    return (string) $term['name'];
                }
            }
        }

        return null;
    }

    private function extractPrice(?string $excerpt): ?string
    {
        if (! $excerpt) {
            return null;
        }

        // « 35 000 FCFA », « À partir de 1 100 000 FCFA », « 25.000 FCFA »
        if (preg_match('/(?:à\s+partir\s+de\s+)?[\d\s\.\']{2,}\s*F\s*CFA/iu', $excerpt, $m)) {
            return trim($m[0]);
        }

        return null;
    }
}
