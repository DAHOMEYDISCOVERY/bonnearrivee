<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\Page;

/**
 * Dépôt qui lit la table `pages`.
 *
 * Source autoritative dès que le back-office Filament est en service. Le cache
 * en mémoire évite une requête par appel dans une même requête HTTP —
 * `PageController::show` appelle `find()` une fois par page servie, mais la
 * carte d'architecture et les liens croisés lisent `all()`.
 */
final class DatabasePageRepository implements PageRepository
{
    /** @var array<string, array<string, mixed>>|null */
    private ?array $pages = null;

    public function find(string $path): ?array
    {
        return $this->all()[$this->normalise($path)] ?? null;
    }

    public function all(): array
    {
        if ($this->pages !== null) {
            return $this->pages;
        }

        $this->pages = [];
        foreach (Page::all() as $page) {
            $this->pages[$page->path] = $page->toRegistryEntry();
        }

        return $this->pages;
    }

    private function normalise(string $path): string
    {
        $trimmed = trim($path, '/');

        return $trimmed === '' ? '/' : '/'.$trimmed;
    }
}
