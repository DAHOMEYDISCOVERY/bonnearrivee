<?php

declare(strict_types=1);

namespace App\Support;

/**
 * Dépôt qui lit `resources/pages.php`.
 *
 * Source d'origine des 89 pages, générée par `tools/convert.py` depuis le
 * miroir. Reste utile en dehors du back-office (installation minimale,
 * régénération, tests).
 */
final class FilePageRepository implements PageRepository
{
    /** @var array<string, array<string, mixed>>|null */
    private ?array $pages = null;

    public function __construct(private readonly string $path) {}

    public function find(string $path): ?array
    {
        return $this->all()[$this->normalise($path)] ?? null;
    }

    public function all(): array
    {
        return $this->pages ??= require $this->path;
    }

    private function normalise(string $path): string
    {
        $trimmed = trim($path, '/');

        return $trimmed === '' ? '/' : '/'.$trimmed;
    }
}
