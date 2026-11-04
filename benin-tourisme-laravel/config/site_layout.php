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
                                'image' => '/assets/img/ouidah-porte-640x320.webp',
                                'alt' => 'La Porte du Non-Retour, Ouidah',
                                'published' => '2021-12-13 14:15:59',
                                'description' => 'Les paysages de l’Atacora',
                                'caption' => 'La Porte du Non-Retour, Ouidah',
                            ],
                            [
                                'url' => '/activites/safari-et-randonnee',
                                'post_id' => '769',
                                'title' => 'Sur terre',
                                'image' => '/assets/img/pendjari-savane-640x320.webp',
                                'alt' => 'La savane du parc national de la Pendjari',
                                'published' => '2021-12-22 18:37:08',
                                'description' => 'Le lac Ahémé',
                                'caption' => 'Le lac Ahémé',
                            ],
                            [
                                'url' => '/activites/sejour-sur-le-lac',
                                'post_id' => '770',
                                'title' => 'Séjour sur le lac',
                                'image' => '/assets/img/pirogue-640x320.webp',
                                'alt' => 'Pirogue sur le lac Nokoué',
                                'published' => '2022-01-12 09:26:33',
                                'description' => 'Pirogue sur le lac Nokoué',
                                'caption' => 'Pirogue sur le lac Nokoué',
                            ],
                            [
                                'url' => '/explorez/terre-de-traditions/les-traditions-locales',
                                'post_id' => '742',
                                'title' => 'Les traditions locales',
                                'image' => '/assets/img/peuple-640x320.webp',
                                'alt' => 'Rencontres au Bénin',
                                'published' => '2023-03-02 08:25:12',
                                'description' => 'Rencontres au Bénin',
                                'caption' => 'Rencontres au Bénin',
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
                        'type' => 'links',
                        'position' => 'grid-col-start-1 grid-col-end-6 grid-row-start-1 grid-row-end-5',
                        'items' => [
                            [
                                'url' => '/sejourner/hebergements/hotels',
                                'post_id' => '798',
                                'title' => 'Hôtels',
                            ],
                            [
                                'url' => '/sejourner/hebergements/chambres-dhotes',
                                'post_id' => '800',
                                'title' => 'Chambres d\'hôtes',
                            ],
                            [
                                'url' => '/sejourner/hebergements/gites-et-lodges',
                                'post_id' => '799',
                                'title' => 'Gîtes',
                            ],
                        ],
                        'title' => 'Où dormir ?',
                        'url' => '/sejourner/hebergements',
                        'post_id' => '797',
                        'columns' => '1cols',
                    ],
                    [
                        'type' => 'links',
                        'position' => 'grid-col-start-1 grid-col-end-6 grid-row-start-5 grid-row-end-9',
                        'items' => [
                            [
                                'url' => '/sejourner/restaurants#gastronomique',
                                'post_id' => '802',
                                'title' => 'Restaurants gastronomiques',
                            ],
                            [
                                'url' => '/sejourner/restaurants#maquis',
                                'post_id' => '803',
                                'title' => 'Maquis & cuisine locale',
                            ],
                            [
                                'url' => '/sejourner/restaurants#cafes-bars',
                                'post_id' => '804',
                                'title' => 'Cafés & bars',
                            ],
                        ],
                        'title' => 'Où manger ?',
                        'url' => '/sejourner/restaurants',
                        'post_id' => '801',
                        'columns' => '1cols',
                    ],
                    [
                        'type' => 'buttons',
                        'position' => 'grid-col-start-1 grid-col-end-6 grid-row-start-9 grid-row-end-13',
                        'items' => [
                            [
                                'url' => '/sejourner/toutes-les-infos-pratiques',
                                'post_id' => '816',
                                'title' => 'Toutes les infos pratiques',
                                'style' => 'primary',
                            ],
                            [
                                'url' => '/sejourner/toutes-les-infos-pratiques/nous-contacter',
                                'post_id' => '824',
                                'title' => 'Nous contacter',
                                'style' => 'secondary',
                            ],
                        ],
                        'columns' => '1cols',
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
                'label' => 'Dahomey Discovery sur Facebook (nouvel onglet)',
            ],
            [
                'icon' => 'wicon-011-instagram',
                'url' => 'https://www.instagram.com/dahomeydiscovery/',
                'label' => 'Dahomey Discovery sur Instagram (nouvel onglet)',
            ],
        ],
        'company' => 'Dahomey Discovery',
        'address' => 'Rond-point Akpakpa, Ciné Concorde
Cotonou — Bénin',
        'phone' => '+229 01 67 11 02 20',
        'email' => 'reservations@dahomeydiscovery.com',
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
                'title' => 'À propos de Dahomey Discovery',
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
