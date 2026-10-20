<?php

namespace App\Providers;

use App\Models\Page;
use App\Services\Aggregator\DahomeyDiscoverySource;
use App\Services\Aggregator\PetitTrainSource;
use App\Services\Aggregator\WpRestClient;
use App\Support\DatabasePageRepository;
use App\Support\FilePageRepository;
use App\Support\PageRepository;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bascule progressive vers la base : dès que la table `pages` existe et
        // qu'elle est peuplée, elle est la source autoritative ; sinon on
        // retombe sur `resources/pages.php`. Ce fallback couvre l'installation
        // minimale (sans migration) et le premier boot avant seeder.
        $this->app->singleton(
            PageRepository::class,
            function (): PageRepository {
                if ($this->baseAlimentée()) {
                    return new DatabasePageRepository;
                }

                return new FilePageRepository(resource_path('pages.php'));
            },
        );

        // Aggregator : chaque source reçoit son propre client, préconfiguré
        // avec l'URL et les éventuelles clés WooCommerce du site correspondant.
        $this->app->singleton(DahomeyDiscoverySource::class, function ($app) {
            $cfg = config('services.aggregator.dahomey');
            $verify = (bool) config('services.aggregator.verify_ssl', true);

            return new DahomeyDiscoverySource(
                new WpRestClient($cfg['base_url'], $cfg['consumer_key'] ?? null, $cfg['consumer_secret'] ?? null, $verify),
            );
        });

        $this->app->singleton(PetitTrainSource::class, function ($app) {
            $cfg = config('services.aggregator.petit_train');
            $verify = (bool) config('services.aggregator.verify_ssl', true);

            return new PetitTrainSource(
                new WpRestClient($cfg['base_url'], $cfg['consumer_key'] ?? null, $cfg['consumer_secret'] ?? null, $verify),
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }

    /**
     * La table `pages` existe et contient au moins une entrée.
     *
     * On isole le test de schéma dans un try/catch : sur une installation neuve
     * sans migration, `Schema::hasTable` peut lever, et on doit alors retomber
     * proprement sur le fichier plutôt que planter le boot de l'application.
     */
    private function baseAlimentée(): bool
    {
        try {
            return Schema::hasTable('pages') && Page::query()->exists();
        } catch (\Throwable) {
            return false;
        }
    }
}
