<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Page;
use Illuminate\Console\Command;

/**
 * Nettoie les pages dont `use_override=true` mais le `content_override`
 * est vide/invisible, parce que ces lignes causent une page blanche sur
 * le site public (seulement header + footer visibles, main vide).
 *
 * Appel : php artisan pages:clean-overrides [--dry-run]
 */
final class PagesCleanOverrides extends Command
{
    protected $signature = 'pages:clean-overrides {--dry-run : Simule sans ecrire}';
    protected $description = 'Reset use_override=false sur les pages avec contenu personnalise vide';

    public function handle(): int
    {
        $dry = (bool) $this->option('dry-run');
        $this->info($dry ? '[dry-run] simulation sans ecriture.' : 'nettoyage en cours.');

        $broken = Page::where('use_override', true)->get()->filter(
            static fn (Page $p): bool => ! $p->hasCustomContent()
        );

        if ($broken->isEmpty()) {
            $this->info('aucune page cassee. tout est propre.');
            return self::SUCCESS;
        }

        $this->warn("pages a reparer : {$broken->count()}");
        foreach ($broken as $p) {
            $len = strlen((string) $p->content_override);
            $this->line("  - {$p->path} (view={$p->view}, content={$len} chars)");
            if (! $dry) {
                $p->update(['use_override' => false, 'content_override' => null]);
            }
        }

        $this->info($dry ? 'simulation terminee.' : 'reparation terminee. les pages servent de nouveau leur vue d\'origine.');
        return self::SUCCESS;
    }
}
