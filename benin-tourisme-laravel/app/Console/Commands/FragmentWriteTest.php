<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\SiteFragment;
use Illuminate\Console\Command;

/**
 * Prouve que le modele SiteFragment est bien editable depuis le CLI :
 *   - lit le footer actuel
 *   - l ecrit avec un marqueur unique a la fin
 *   - relit pour verifier
 *
 * Si cette commande marque et que l admin ne marche pas, le probleme
 * n est pas la DB ni le modele - c est le pipeline Filament/Livewire.
 */
final class FragmentWriteTest extends Command
{
    protected $signature = 'fragment:write-test {slug=footer}';

    protected $description = 'Teste si on peut ecrire dans un SiteFragment depuis le CLI.';

    public function handle(): int
    {
        $slug = (string) $this->argument('slug');
        $f = SiteFragment::query()->where('slug', $slug)->first();
        if (!$f) {
            $this->error("Pas de fragment '{$slug}'.");
            return self::FAILURE;
        }

        $before = strlen($f->content);
        $marker = "<!-- CLI_TEST_" . now()->format('His') . " -->";

        $this->info("Avant : {$before} chars");
        $f->content = $f->content . "\n" . $marker;
        $saved = $f->save();
        $this->info("save() -> " . ($saved ? "true" : "false"));

        // Relit depuis la base, sans cache memoire
        $after = SiteFragment::query()->where('slug', $slug)->first();
        $hasMarker = str_contains($after->content, $marker);
        $this->info("Apres : " . strlen($after->content) . " chars");
        $this->info("Marqueur present dans la DB : " . ($hasMarker ? "OUI (ecriture OK)" : "NON (echec silencieux)"));

        return $hasMarker ? self::SUCCESS : self::FAILURE;
    }
}
