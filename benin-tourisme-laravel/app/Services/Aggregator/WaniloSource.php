<?php

declare(strict_types=1);

namespace App\Services\Aggregator;

use App\Services\Aggregator\Contracts\Source;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use SimpleXMLElement;
use Throwable;

/**
 * Source : wanilo.studio (Next.js — pas d'API publique).
 *
 * Le sitemap XML (plat, ~13 URLs) sert de graine ; pour chaque URL on
 * lit `<title>`, `<meta property="og:*">` et `<meta name="description">`
 * — assez pour composer une carte. Cache 6 h, sans lequel une visite
 * du portail déclencherait N requêtes vers wanilo.
 */
final class WaniloSource implements Source
{
    private const SOURCE = 'wanilo';
    private const SITE_URL = 'https://www.wanilo.studio';
    private const SITEMAP = 'https://www.wanilo.studio/sitemap.xml';
    private const CACHE_TTL = 21600;   // 6 h
    private const TIMEOUT = 3;

    public function fetch(string $kind, array $filters = [], int $limit = 10): Collection
    {
        return match ($kind) {
            'article', 'news', 'actualite' => $this->fetchArticles($limit, $filters),
            default                        => collect(),
        };
    }

    /** @param array<string, scalar|null> $filters — inutilisé pour l'instant, réservé pour un futur filtre par tag */
    private function fetchArticles(int $limit, array $filters): Collection
    {
        unset($filters);
        $urls = Cache::remember('agg:wanilo:sitemap', self::CACHE_TTL, function (): array {
            return $this->parseSitemap();
        });

        // On garde les URLs sous /actualites/... (sauf l'index /actualites lui-même)
        $urls = array_values(array_filter(
            $urls,
            fn (string $u) => str_contains($u, '/actualites/') && ! str_ends_with(rtrim($u, '/'), '/actualites')
        ));

        return collect($urls)
            ->take($limit)
            ->map(fn (string $url) => $this->fetchArticleMeta($url))
            ->filter()
            ->values();
    }

    /** @return list<string> */
    private function parseSitemap(): array
    {
        try {
            $body = Http::timeout(self::TIMEOUT)
                ->withUserAgent('BonneArriveePortal/1.0 (+https://bonnearrivee.bj)')
                ->withOptions(['verify' => (bool) config('services.aggregator.verify_ssl', true)])
                ->get(self::SITEMAP)
                ->body();

            $xml = @simplexml_load_string($body);
            if (! $xml instanceof SimpleXMLElement) {
                return [];
            }

            $urls = [];
            foreach ($xml->url as $entry) {
                $loc = (string) ($entry->loc ?? '');
                if ($loc !== '') {
                    $urls[] = $loc;
                }
            }

            return $urls;
        } catch (ConnectionException) {
            return [];
        } catch (Throwable $e) {
            Log::info('Aggregator wanilo: parseSitemap error', ['msg' => $e->getMessage()]);

            return [];
        }
    }

    private function fetchArticleMeta(string $url): ?ExternalItem
    {
        // On cache un tableau (donnée nue), pas l'objet ExternalItem : ainsi,
        // toute évolution du DTO n'invalide pas les caches sérialisés.
        $cacheKey = 'agg:wanilo:page:' . md5($url);

        $data = Cache::remember($cacheKey, self::CACHE_TTL, function () use ($url): ?array {
            try {
                $html = Http::timeout(self::TIMEOUT)
                    ->withUserAgent('BonneArriveePortal/1.0 (+https://bonnearrivee.bj)')
                    ->withOptions(['verify' => (bool) config('services.aggregator.verify_ssl', true)])
                    ->get($url)
                    ->body();
            } catch (Throwable) {
                return null;
            }

            $title = $this->matchMeta($html, 'og:title')
                ?? $this->matchTag($html, 'title');

            if ($title === null) {
                return null;
            }

            $desc = $this->matchMeta($html, 'og:description')
                ?? $this->matchNameMeta($html, 'description');

            $image = $this->matchMeta($html, 'og:image');
            if ($image !== null && ! str_starts_with($image, 'http')) {
                $image = rtrim(self::SITE_URL, '/') . '/' . ltrim($image, '/');
            }

            return [
                'title' => html_entity_decode($title),
                'excerpt' => $desc ? mb_substr(html_entity_decode($desc), 0, 240) : null,
                'image' => $image,
            ];
        });

        if (! is_array($data)) {
            return null;
        }

        return new ExternalItem(
            source:  self::SOURCE,
            id:      md5($url),
            title:   $data['title'],
            url:     $url,
            excerpt: $data['excerpt'] ?? null,
            image:   $data['image'] ?? null,
            city:    'Cotonou',
            type:    'article',
        );
    }

    private function matchMeta(string $html, string $property): ?string
    {
        if (preg_match('#<meta[^>]+property=["\']' . preg_quote($property, '#') . '["\'][^>]+content=["\']([^"\']+)["\']#i', $html, $m)) {
            return trim($m[1]);
        }

        return null;
    }

    private function matchNameMeta(string $html, string $name): ?string
    {
        if (preg_match('#<meta[^>]+name=["\']' . preg_quote($name, '#') . '["\'][^>]+content=["\']([^"\']+)["\']#i', $html, $m)) {
            return trim($m[1]);
        }

        return null;
    }

    private function matchTag(string $html, string $tag): ?string
    {
        if (preg_match('#<' . preg_quote($tag, '#') . '[^>]*>([^<]+)</' . preg_quote($tag, '#') . '>#i', $html, $m)) {
            return trim($m[1]);
        }

        return null;
    }
}
