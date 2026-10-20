<?php

declare(strict_types=1);

namespace App\Services\Aggregator;

/**
 * Représentation normalisée d'un contenu venant d'un site externe.
 *
 * Chaque source (Dahomey Discovery, Petit Train, Wanilo) mappe ses
 * champs propres vers cette forme commune : le portail n'a plus qu'à
 * afficher une liste d'ExternalItem, quelle que soit l'origine.
 */
final class ExternalItem
{
    public function __construct(
        public readonly string $source,      // 'dahomey', 'petit-train', 'wanilo'
        public readonly string $id,          // identifiant côté source (unique par source)
        public readonly string $title,
        public readonly string $url,         // URL canonique sur le site source
        public readonly ?string $excerpt = null,
        public readonly ?string $image = null,
        public readonly ?string $city = null,
        public readonly ?string $type = null,     // circuit, hotel, lodge, vehicle…
        public readonly ?string $price = null,    // texte libre : « 35 000 FCFA », « À partir de … »
        public readonly ?string $publishedAt = null,
    ) {
    }
}
