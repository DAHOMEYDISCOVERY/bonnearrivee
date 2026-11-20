<?php

/*
 * Contenu par défaut de l'en-tête et du pied de page.
 *
 * - Le BALISAGE vit dans resources/views/partials/header.blade.php et
 *   footer.blade.php : le modifier change le rendu immédiatement.
 * - Le CONTENU (libellés, liens, images, coordonnées) est éditable dans le
 *   back-office, page « En-tête et pied de page ». Tant que personne n'y a
 *   enregistré de modification, ce sont les valeurs ci-dessous qui s'affichent.
 *   Dès qu'une version est enregistrée en base, elle est prioritaire ; le
 *   bouton « Revenir aux valeurs du code » efface cette version.
 *
 * Valeurs extraites le 4 novembre 2026 des partials d'origine.
 */

return [
    'header' => [
        'menu' => [
            [
                'label' => 'Découvrez',
                'url' => '/explorez',
                'post_id' => '682',
                'blocks' => [
                    [
                        'type' => 'cards',
                        'position' => 'grid-col-start-1 grid-col-end-13 grid-row-start-1 grid-row-end-13',
                        'items' => [
                            [
                                'url' => '/explorez/le-littoral-et-les-lacs',
                                'post_id' => '713',
                                'title' => 'Le littoral, un terrain de jeu unique',
                                'image' => '/assets/img/menu-littoral-640x320.webp',
                                'alt' => 'Balade en quad sur la plage',
                                'published' => '2026-11-04 17:00:00',
                                'description' => 'Balade en quad sur la plage',
                                'caption' => 'Balade en quad sur la plage',
                            ],
                            [
                                'url' => '/explorez/les-cinq-benin',
                                'post_id' => '706',
                                'title' => 'Les cinq Bénin',
                                'image' => '/assets/img/menu-cinq-benin-640x320.webp',
                                'alt' => 'Pirogue sur un bras d\'eau, entre les palmiers',
                                'published' => '2026-11-04 17:00:00',
                                'description' => 'Pirogue sur un bras d\'eau, entre les palmiers',
                                'caption' => 'Pirogue sur un bras d\'eau, entre les palmiers',
                            ],
                            [
                                'url' => '/explorez/naturellement-sauvage',
                                'post_id' => '683',
                                'title' => 'Naturellement sauvage',
                                'image' => '/assets/img/menu-naturel-640x320.webp',
                                'alt' => 'Lionnes dans la savane',
                                'published' => '2026-11-04 17:00:00',
                                'description' => 'Lionnes dans la savane',
                                'caption' => 'Lionnes dans la savane',
                            ],
                            [
                                'url' => '/explorez/terre-de-traditions',
                                'post_id' => '735',
                                'title' => 'Terre de traditions',
                                'image' => '/assets/img/menu-traditions-640x320.webp',
                                'alt' => 'Sortie de masques egungun',
                                'published' => '2026-11-04 17:00:00',
                                'description' => 'Sortie de masques egungun',
                                'caption' => 'Sortie de masques egungun',
                            ],
                        ],
                        'columns' => '2cols',
                    ],
                ],
            ],
            [
                'label' => 'Vivez',
                'url' => '/activites',
                'post_id' => '764',
                'blocks' => [
                    [
                        'type' => 'cards',
                        'position' => 'grid-col-start-1 grid-col-end-13 grid-row-start-1 grid-row-end-13',
                        'items' => [
                            [
                                'url' => '/activites/sur-les-traces-de-lhistoire',
                                'post_id' => '765',
                                'title' => 'Sur les traces de l’Histoire',
                                'image' => '/assets/img/menu-histoire-640x320.webp',
                                'alt' => 'Temple Iya Abi Mesan, première divinité de Porto-Novo',
                                'published' => '2026-11-05 15:00:00',
                                'description' => 'Temple Iya Abi Mesan, première divinité de Porto-Novo',
                                'caption' => 'Temple Iya Abi Mesan, première divinité de Porto-Novo',
                            ],
                            [
                                'url' => '/activites/safari-et-randonnee',
                                'post_id' => '769',
                                'title' => 'Sur terre',
                                'image' => '/assets/img/menu-terre-640x320.webp',
                                'alt' => 'Case traditionnelle au toit de paille',
                                'published' => '2026-11-05 15:00:00',
                                'description' => 'Case traditionnelle au toit de paille',
                                'caption' => 'Case traditionnelle au toit de paille',
                            ],
                            [
                                'url' => '/activites/sejour-sur-le-lac',
                                'post_id' => '770',
                                'title' => 'Séjour sur le lac',
                                'image' => '/assets/img/menu-lac-640x320.webp',
                                'alt' => 'Pirogues chargées de légumes sur le lac',
                                'published' => '2026-11-05 15:00:00',
                                'description' => 'Pirogues chargées de légumes sur le lac',
                                'caption' => 'Pirogues chargées de légumes sur le lac',
                            ],
                            [
                                'url' => '/explorez/terre-de-traditions/les-traditions-locales',
                                'post_id' => '742',
                                'title' => 'Les traditions locales',
                                'image' => '/assets/img/menu-traditions-locales-640x320.webp',
                                'alt' => 'Sortie de masque egungun',
                                'published' => '2026-11-05 15:00:00',
                                'description' => 'Sortie de masque egungun',
                                'caption' => 'Sortie de masque egungun',
                            ],
                        ],
                        'columns' => '2cols',
                    ],
                ],
            ],
            [
                'label' => 'Organisez',
                'url' => '/sejourner',
                'post_id' => '796',
                'blocks' => [
                    [
                        'type' => 'cards',
                        'position' => 'grid-col-start-1 grid-col-end-13 grid-row-start-1 grid-row-end-13',
                        'items' => [
                            [
                                'url' => '/sejourner/hebergements',
                                'post_id' => '797',
                                'title' => 'Où dormir ?',
                                'image' => '/assets/img/menu-ou-dormir-640x320.webp',
                                'alt' => 'Salon lumineux d’un appartement meublé',
                                'published' => '2026-11-17 11:00:00',
                                'description' => 'Salon lumineux d’un appartement meublé',
                                'caption' => 'Salon lumineux d’un appartement meublé',
                            ],
                            [
                                'url' => '/sejourner/hebergements/hotels',
                                'post_id' => '798',
                                'title' => 'Hôtels',
                                'image' => '/assets/img/menu-hotels-640x320.webp',
                                'alt' => 'Chambre d’hôtel avec lit double et tête de lit capitonnée',
                                'published' => '2026-11-17 11:00:00',
                                'description' => 'Chambre d’hôtel avec lit double et tête de lit capitonnée',
                                'caption' => 'Chambre d’hôtel avec lit double et tête de lit capitonnée',
                            ],
                            [
                                'url' => '/sejourner/hebergements/chambres-dhotes',
                                'post_id' => '800',
                                'title' => 'Chambres d\'hôtes',
                                'image' => '/assets/img/menu-chambres-dhotes-640x320.webp',
                                'alt' => 'Chambre claire au linge blanc et coussins orange',
                                'published' => '2026-11-17 11:00:00',
                                'description' => 'Chambre claire au linge blanc et coussins orange',
                                'caption' => 'Chambre claire au linge blanc et coussins orange',
                            ],
                            [
                                'url' => '/sejourner/hebergements/gites-et-lodges',
                                'post_id' => '799',
                                'title' => 'Gîtes et lodges',
                                'image' => '/assets/img/menu-gites-640x320.webp',
                                'alt' => 'Lodges sur pilotis au bord de l’eau',
                                'published' => '2026-11-17 11:00:00',
                                'description' => 'Lodges sur pilotis au bord de l’eau',
                                'caption' => 'Lodges sur pilotis au bord de l’eau',
                            ],
                            [
                                'url' => '/sejourner/restaurants',
                                'post_id' => '801',
                                'title' => 'Où manger ?',
                                'image' => '/assets/img/menu-ou-manger-640x320.webp',
                                'alt' => 'Salle de restaurant aux nappes jaunes',
                                'published' => '2026-11-17 11:00:00',
                                'description' => 'Salle de restaurant aux nappes jaunes',
                                'caption' => 'Salle de restaurant aux nappes jaunes',
                            ],
                            [
                                'url' => '/sejourner/toutes-les-infos-pratiques',
                                'post_id' => '816',
                                'title' => 'Infos pratiques',
                                'image' => '/assets/img/zemidjan-640x320.webp',
                                'alt' => 'Zémidjan, le taxi-moto du Bénin',
                                'published' => '2026-11-17 11:00:00',
                                'description' => 'Zémidjan, le taxi-moto du Bénin',
                                'caption' => 'Zémidjan, le taxi-moto du Bénin',
                            ],
                        ],
                        'columns' => '2cols',
                    ],
                ],
            ],
        ],
    ],
    'footer' => [
        'tagline' => 'Portail du Tourisme et de l\'Attractivité au Bénin',
        'socials' => [
            [
                'icon' => 'wicon-000-facebook',
                'url' => 'https://www.facebook.com/dahomeydiscovery',
                'label' => 'Notre partenaire Dahomey Discovery sur Facebook (nouvel onglet)',
            ],
            [
                'icon' => 'wicon-011-instagram',
                'url' => 'https://www.instagram.com/dahomeydiscovery/',
                'label' => 'Notre partenaire Dahomey Discovery sur Instagram (nouvel onglet)',
            ],
        ],
        'company' => 'Bonne Arrivée',
        'address' => 'Rond-point Akpakpa, Ciné Concorde
Cotonou — Bénin',
        'phone' => '+229 01 67 11 02 20',
        'email' => 'contact@bonnearrivee.bj',
        'buttons' => [
            [
                'label' => 'Comment venir',
                'url' => '/sejourner/toutes-les-infos-pratiques/comment-venir',
                'title' => 'Comment venir',
            ],
            [
                'label' => 'Les liens utiles',
                'url' => '/sejourner/toutes-les-infos-pratiques/les-liens-utiles',
                'title' => 'Les liens utiles',
            ],
            [
                'label' => 'Bénin en Fête',
                'url' => '/benin-en-fete',
                'title' => 'Bénin en Fête',
            ],
            [
                'label' => 'À propos',
                'url' => '/a-propos',
                'title' => 'À propos de Bonne Arrivée',
            ],
            [
                'label' => 'Nous contacter',
                'url' => '/sejourner/toutes-les-infos-pratiques/nous-contacter',
                'title' => 'Nous contacter',
            ],
            [
                'label' => 'Mentions légales',
                'url' => '/mentions-legales',
                'title' => 'Mentions légales',
            ],
        ],
    ],
];
