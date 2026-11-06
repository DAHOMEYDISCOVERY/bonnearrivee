<?php

namespace Database\Seeders;

use App\Models\AdCampaign;
use Illuminate\Database\Seeder;

/**
 * Campagne de démarrage pour l'espace publicitaire de la page Liens utiles.
 *
 * Dahomey Discovery est une agence réelle (projet de l'éditeur du site), donc
 * il s'agit d'une vraie campagne, non d'une publicité fictive. Idempotent :
 * relancer ce seeder met simplement la campagne à jour sans la dupliquer.
 *
 *   php artisan db:seed --class=Database\\Seeders\\AdCampaignSeeder
 */
class AdCampaignSeeder extends Seeder
{
    public function run(): void
    {
        AdCampaign::updateOrCreate(
            ['title' => 'Découvrez le Bénin autrement avec Dahomey Discovery'],
            [
                'advertiser'   => 'Dahomey Discovery',
                'image_path'   => '/assets/img/dahomey-discovery-banner.webp',
                'image_alt'    => 'Village lacustre du Bénin au coucher du soleil',
                'description'  => 'Circuits sur mesure, hébergements et transferts partout au Bénin — de la cité lacustre de Ganvié aux palais royaux d’Abomey.',
                'button_label' => 'Découvrir les circuits',
                'url'          => 'https://dahomeydiscovery.com',
                'is_active'    => true,
                'sort_order'   => 0,
            ]
        );
    }
}
