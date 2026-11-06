<?php

namespace Database\Seeders;

use App\Models\AdCampaign;
use Illuminate\Database\Seeder;

/**
 * Campagnes de l'espace publicitaire.
 *
 * Entreprises réelles. Idempotent (calé sur l'annonceur) : relancer met à jour.
 * Emplacements : 'agences' (carrousel Liens utiles) / 'compagnies' (bannière
 * latérale de la page Comment venir).
 *
 *   php artisan db:seed --class=Database\\Seeders\\AdCampaignSeeder
 */
class AdCampaignSeeder extends Seeder
{
    public function run(): void
    {
        AdCampaign::updateOrCreate(
            ['advertiser' => 'Amazone Airlines'],
            [
                'placement'    => 'compagnies',
                'title'        => 'Les Ailes du Bénin',
                'image_path'   => '/assets/img/amazone-airlines-banner.webp',
                'image_alt'    => 'Avion Amazone Airlines au chargement au coucher du soleil',
                'description'  => 'La compagnie aérienne nationale du Bénin.',
                'button_label' => 'Réserver un vol',
                'url'          => 'https://flyamazoneair.bj',
                'is_active'    => true,
                'sort_order'   => 0,
            ]
        );

        AdCampaign::updateOrCreate(
            ['advertiser' => 'Dahomey Discovery'],
            [
                'placement'    => 'agences',
                'title'        => 'Découvrez le Bénin avec sens sur Dahomey Discovery',
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
