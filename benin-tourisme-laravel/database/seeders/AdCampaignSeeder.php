<?php

namespace Database\Seeders;

use App\Models\AdCampaign;
use Illuminate\Database\Seeder;

/**
 * Campagnes de l'espace publicitaire de la page Liens utiles.
 *
 * Entreprises réelles (compagnie aérienne nationale du Bénin et agence
 * réceptive éditrice du portail) : vraies campagnes, non fictives. Idempotent —
 * calé sur l'annonceur : relancer met à jour sans créer de doublon.
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
                'title'        => 'Amazone Airlines — les Ailes du Bénin',
                'image_path'   => '/assets/img/cotonou-1920x1080.webp',
                'image_alt'    => 'Cotonou, porte d’entrée du Bénin',
                'description'  => 'La compagnie aérienne nationale du Bénin. Envolez-vous au départ et à destination de Cotonou.',
                'button_label' => 'Réserver un vol',
                'url'          => 'https://flyamazoneair.bj',
                'is_active'    => true,
                'sort_order'   => 0,
            ]
        );

        AdCampaign::updateOrCreate(
            ['advertiser' => 'Dahomey Discovery'],
            [
                'title'        => 'Découvrez le Bénin avec sens sur Dahomey Discovery',
                'image_path'   => '/assets/img/dahomey-discovery-banner.webp',
                'image_alt'    => 'Village lacustre du Bénin au coucher du soleil',
                'description'  => 'Circuits sur mesure, hébergements et transferts partout au Bénin — de la cité lacustre de Ganvié aux palais royaux d’Abomey.',
                'button_label' => 'Découvrir les circuits',
                'url'          => 'https://dahomeydiscovery.com',
                'is_active'    => true,
                'sort_order'   => 1,
            ]
        );
    }
}
