<?php

declare(strict_types=1);

namespace App\Services\Aggregator;

use App\Services\Aggregator\Contracts\Source;
use Illuminate\Support\Collection;

/**
 * Source : petit-train-cotonou.com (WordPress + WooCommerce).
 *
 * Deux gisements complémentaires :
 *   - CPT `ptc_circuit` (les 3 circuits touristiques : Balade Découverte,
 *     Circuit Express, Cotonou Moderne)
 *   - `product` (WooCommerce, 5+ produits : privatisation, groupes,
 *     balade nocturne) — les prix vivent côté WC.
 */
final class PetitTrainSource implements Source
{
    private const SOURCE = 'petit-train';
    private const SITE_URL = 'https://www.petit-train-cotonou.com';

    public function __construct(private readonly WpRestClient $client)
    {
    }

    public function fetch(string $kind, array $filters = [], int $limit = 10): Collection
    {
        return match ($kind) {
            'circuit' => $this->fetchCircuits($limit),
            'product' => $this->fetchProducts($limit),
            default   => collect(),
        };
    }

    private function fetchCircuits(int $limit): Collection
    {
        $rows = $this->client->get('/wp-json/wp/v2/ptc_circuit', [
            'per_page' => max(1, min($limit, 20)),
            '_embed'   => 'wp:featuredmedia',
        ]);

        return collect($rows)->map(fn (array $row) => $this->mapCircuit($row))->filter()->values();
    }

    private function fetchProducts(int $limit): Collection
    {
        // WooCommerce : /wc/v3/products si les clés API sont fournies,
        // sinon fallback sur /wp/v2/product (sans prix)
        $rows = $this->client->get('/wp-json/wc/v3/products', [
            'per_page' => max(1, min($limit, 20)),
            'status'   => 'publish',
        ]);

        if (empty($rows)) {
            $rows = $this->client->get('/wp-json/wp/v2/product', [
                'per_page' => max(1, min($limit, 20)),
                '_embed'   => 'wp:featuredmedia',
            ]);

            return collect($rows)->map(fn (array $row) => $this->mapCircuit($row))->filter()->values();
        }

        return collect($rows)->map(fn (array $row) => $this->mapProduct($row))->filter()->values();
    }

    /** @param array<string, mixed> $row */
    private function mapCircuit(array $row): ?ExternalItem
    {
        if (empty($row['id']) || empty($row['title']['rendered'])) {
            return null;
        }

        $image = $this->extractImage($row);
        $excerpt = strip_tags((string) ($row['excerpt']['rendered'] ?? ''));
        $excerpt = trim(preg_replace('/\s+/', ' ', $excerpt)) ?: null;

        return new ExternalItem(
            source:      self::SOURCE,
            id:          (string) $row['id'],
            title:       html_entity_decode(strip_tags((string) $row['title']['rendered'])),
            url:         (string) ($row['link'] ?? self::SITE_URL),
            excerpt:     $excerpt ? mb_substr($excerpt, 0, 240) : null,
            image:       $image,
            city:        'Cotonou',
            type:        'circuit-urbain',
            price:       null,
            publishedAt: (string) ($row['date'] ?? null) ?: null,
        );
    }

    /** @param array<string, mixed> $row — format WooCommerce /wc/v3/products */
    private function mapProduct(array $row): ?ExternalItem
    {
        if (empty($row['id']) || empty($row['name'])) {
            return null;
        }

        // Image : on utilise systématiquement l'illustration locale du portail
        // (le site source bloque le hotlink et la plupart des produits n'ont
        // pas d'image propre). Fichier téléchargé et optimisé en WebP.
        $image = '/assets/img/petit-train-cotonou.webp';

        $price = null;
        if (! empty($row['price_html'])) {
            $price = trim(html_entity_decode(strip_tags((string) $row['price_html']), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
            $price = preg_replace('/\s+/u', ' ', $price);
        } elseif (! empty($row['price'])) {
            $price = number_format((float) $row['price'], 0, ',', ' ') . ' FCFA';
        }

        $excerpt = strip_tags((string) ($row['short_description'] ?? $row['description'] ?? ''));
        $excerpt = trim(preg_replace('/\s+/', ' ', $excerpt)) ?: null;
        // Description enrichie par défaut : le catalogue WooCommerce du
        // Petit Train est très minimaliste (short_description vide). On
        // injecte une phrase marketing selon le type de produit deviné
        // dans le titre — évite les cards avec une seule ligne creuse.
        if ($excerpt === null || mb_strlen($excerpt) < 40) {
            $t = mb_strtolower((string) $row['name']);
            $excerpt = match (true) {
                str_contains($t, 'privatisation') =>
                    'Réservez tout le petit train pour votre événement — mariage, anniversaire, sortie d\'entreprise ou tournage. Circuit personnalisé dans Cotonou, chauffeur et animation inclus.',
                str_contains($t, 'nocturne') =>
                    'Découvrez Cotonou by night : circuit commenté à la tombée du jour, vue sur la marina illuminée, le pont Nouveau et les enseignes lumineuses. Ambiance douce, idéal en couple.',
                str_contains($t, 'groupe 20') =>
                    'Formule groupe jusqu\'à 20 personnes : circuit commenté de 30 min à travers Cotonou. Idéal pour familles élargies, séminaires, associations ou classes en sortie découverte.',
                str_contains($t, 'groupe 10') =>
                    'Formule intime pour 10 personnes maximum : circuit commenté de 30 min à travers Cotonou, monuments, ambassades et quartiers historiques. Parfait pour groupes d\'amis.',
                str_contains($t, 'découverte') =>
                    'Circuit commenté de 30 min à travers Cotonou : monuments, ambassades, marché Dantokpa, quartiers historiques et modernes. Une première approche complète de la capitale économique du Bénin.',
                default =>
                    'Balade commentée à travers Cotonou en petit train — monuments, quartiers vivants et ambiance de la capitale économique du Bénin.',
            };
        }

        return new ExternalItem(
            source:      self::SOURCE,
            id:          (string) $row['id'],
            title:       html_entity_decode(strip_tags((string) $row['name'])),
            url:         (string) ($row['permalink'] ?? self::SITE_URL),
            excerpt:     $excerpt ? mb_substr($excerpt, 0, 320) : null,
            image:       $image,
            city:        'Cotonou',
            type:        'produit-billetterie',
            price:       $price,
            publishedAt: (string) ($row['date_created'] ?? null) ?: null,
        );
    }

    /** @param array<string, mixed> $row */
    private function extractImage(array $row): ?string
    {
        $media = $row['_embedded']['wp:featuredmedia'][0] ?? null;
        if (! is_array($media)) {
            return null;
        }

        $sizes = $media['media_details']['sizes'] ?? [];
        foreach (['medium_large', 'medium', 'large', 'full'] as $size) {
            if (! empty($sizes[$size]['source_url'])) {
                return (string) $sizes[$size]['source_url'];
            }
        }

        return isset($media['source_url']) ? (string) $media['source_url'] : null;
    }
}
