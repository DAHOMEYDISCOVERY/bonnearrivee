<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

/**
 * Alimente la table `pages` à partir de `resources/pages.php`.
 *
 * Ce seeder est idempotent : il utilise updateOrCreate sur le chemin d'URL,
 * si bien qu'on peut le relancer sans doublon après chaque régénération de
 * `resources/pages.php` par `tools/convert.py`.
 */
final class PageSeeder extends Seeder
{
    public function run(): void
    {
        $registre = require resource_path('pages.php');
        $créés = 0;
        $mis_à_jour = 0;
        foreach ($registre as $path => $entrée) {
            $page = Page::updateOrCreate(
                ['path' => $path],
                [
                    'view' => $entrée['view'],
                    'title' => $entrée['title'],
                    'description' => $entrée['description'] ?? null,
                    'active_menu' => $entrée['activeMenu'] ?? null,
                    'head_profile' => $entrée['headProfile'] ?? 'standard',
                    'standalone' => (bool) ($entrée['standalone'] ?? false),
                ]
            );
            $page->wasRecentlyCreated ? $créés++ : $mis_à_jour++;
        }
        $this->command?->info("  {$créés} pages créées, {$mis_à_jour} mises à jour");
    }
}
