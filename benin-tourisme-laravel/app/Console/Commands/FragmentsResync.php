<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\SiteFragment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

/**
 * Resynchronise header + footer avec le HTML des partials sur disque.
 * Utile en cas de corruption du contenu DB (ex: seed avec la bascule
 * Blade qui provoque une recursion) sans passer par l'admin.
 */
final class FragmentsResync extends Command
{
    protected $signature = 'fragments:resync {--force : Ecrase meme les fragments non corrompus}';

    protected $description = 'Resynchronise les blocs site (header/footer) depuis les partials Blade sur disque.';

    public function handle(): int
    {
        $force = (bool) $this->option('force');
        $r = SiteFragment::seedFromPartials(force: $force);

        // Purge les caches memoire pour que la nouvelle version soit servie
        // immediatement a la prochaine requete.
        foreach (array_keys(SiteFragment::KNOWN) as $slug) {
            Cache::forget("site_fragment:{$slug}");
        }

        $this->info("Resync termine : {$r['created']} cree(s), " . SiteFragment::count() . " en base.");
        foreach (SiteFragment::all() as $f) {
            $this->line("  - {$f->slug} : " . strlen($f->content) . " chars");
        }
        return self::SUCCESS;
    }
}
