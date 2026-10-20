<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\Aggregator\Aggregator;
use Illuminate\Console\Command;

/**
 * Pré-chauffe le cache de l'aggrégateur.
 *
 * Interroge toutes les entrées WordPress / WooCommerce que les pages du site
 * utilisent réellement (accommodations, circuits, articles, etc.) de sorte
 * qu'aucun visiteur ne subisse les 5-8 s de cold start. À planifier une fois
 * par jour, un peu avant l'expiration du TTL de 24 h.
 */
final class AggregatorWarm extends Command
{
    protected $signature = 'aggregator:warm {--quiet-summary : n\'affiche que le total}';

    protected $description = 'Pré-chauffe le cache de l\'aggrégateur Dahomey Discovery / Wanilo / Petit Train.';

    public function handle(Aggregator $agg): int
    {
        $start = microtime(true);
        $silent = (bool) $this->option('quiet-summary');
        $rows = 0;

        $tasks = [
            /* Accommodations : chaque type + variantes de limite utilises dans les vues. */
            'accommodations · all · 6'      => fn () => $agg->accommodations([], 6),
            'accommodations · hotel · 6'    => fn () => $agg->accommodations(['accommodation_type' => 'hotel'], 6),
            'accommodations · villa · 6'    => fn () => $agg->accommodations(['accommodation_type' => 'villa'], 6),
            'accommodations · guest · 6'    => fn () => $agg->accommodations(['accommodation_type' => 'guest-house'], 6),
            'accommodations · hotel · 1'    => fn () => $agg->accommodations(['accommodation_type' => 'hotel'], 1),
            'accommodations · villa · 1'    => fn () => $agg->accommodations(['accommodation_type' => 'villa'], 1),
            'accommodations · guest · 1'    => fn () => $agg->accommodations(['accommodation_type' => 'guest-house'], 1),

            /* Circuits : global + filtres thematiques + villes */
            'circuits · all'                => fn () => $agg->circuits([], 6),
            'circuits · safari'             => fn () => $agg->circuits(['tourism_type' => 'safari'], 6),
            'circuits · immersion'          => fn () => $agg->circuits(['tourism_type' => 'immersion'], 6),
            'circuits · city cotonou'       => fn () => $agg->circuits(['city' => 'cotonou'], 6),
            'circuits · city abomey'        => fn () => $agg->circuits(['city' => 'abomey'], 6),

            /* Vehicles & transferts : limite haute utilisee dans certaines vues */
            'vehicles · 20'                 => fn () => $agg->vehicles([], 20),
            'transfers · 20'                => fn () => $agg->transfers([], 20),

            /* Attractions : global + villes */
            'attractions · all'             => fn () => $agg->attractions([], 6),
            'attractions · cotonou'         => fn () => $agg->attractions(['city' => 'cotonou'], 6),
            'attractions · abomey'          => fn () => $agg->attractions(['city' => 'abomey'], 6),

            /* Contenu editorial (Dahomey Mag + Wanilo Studio) */
            'dahomeyArticles · 3'           => fn () => $agg->dahomeyArticles(3),
            'waniloArticles · 3'            => fn () => $agg->waniloArticles(3),

            /* Petit Train de Cotonou */
            'petitTrainProducts · 6'        => fn () => $agg->petitTrainProducts(6),
            'petitTrainCircuits · 3'        => fn () => $agg->petitTrainCircuits(3),
        ];

        foreach ($tasks as $label => $task) {
            $t0 = microtime(true);
            try {
                $items = $task();
                $count = is_countable($items) ? count($items) : 0;
                $rows += $count;
                if (! $silent) {
                    $this->line(sprintf('  %-30s %3d items · %5.2fs', $label, $count, microtime(true) - $t0));
                }
            } catch (\Throwable $e) {
                if (! $silent) {
                    $this->error(sprintf('  %-30s FAILED · %s', $label, $e->getMessage()));
                }
            }
        }

        $this->info(sprintf('Aggregator warm · %d entries in %.2fs', $rows, microtime(true) - $start));

        return self::SUCCESS;
    }
}
