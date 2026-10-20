<?php

declare(strict_types=1);

namespace App\Support;

/**
 * Contrat d'accès aux pages éditoriales du site.
 *
 * Deux implémentations coexistent pendant la transition vers le back-office :
 *   · {@see FilePageRepository} lit `resources/pages.php` — la source d'origine,
 *     générée par `tools/convert.py` depuis le miroir.
 *   · {@see DatabasePageRepository} lit la table `pages` — la source qu'édite
 *     Filament, et qui devient autoritative dès qu'elle est peuplée.
 *
 * Le contrat reste minimal : un dépôt sait rendre une page à partir de son
 * chemin d'URL, et énumérer toutes les pages qu'il connaît. Les vues et le
 * contrôleur voient l'interface — jamais l'implémentation.
 */
interface PageRepository
{
    /**
     * Retourne la page servie à ce chemin, ou null si aucune n'y répond.
     *
     * @return array<string, mixed>|null
     */
    public function find(string $path): ?array;

    /**
     * @return array<string, array<string, mixed>> toutes les pages, indexées par chemin
     */
    public function all(): array;
}
