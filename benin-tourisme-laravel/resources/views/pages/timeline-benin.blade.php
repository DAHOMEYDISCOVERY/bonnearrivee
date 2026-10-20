{{-- Timeline historique du Benin — de la fondation des royaumes a aujourd'hui.
     Contenu editorial 100% original, base sur les faits publics documentes. --}}
@extends('layouts.app')

@section('bodyClass', 'page menus-v2 prod gabarit-basic_page benin-timeline-page')
@section('mainAttributes')role="main" class="content-type-page basic_page" id="main-content"@endsection

@php
    $eras = [
        [
            'periode' => 'XVIe – XVIIe siècle',
            'couleur' => '#a2582a',
            'titre' => 'Naissance des royaumes',
            'events' => [
                ['date' => 'v. 1600', 'titre' => 'Fondation d\'Allada',       'texte' => 'Les Aja quittent Tado (Togo actuel) et fondent le royaume d\'Allada, matrice culturelle du peuple fon.'],
                ['date' => 'v. 1620', 'titre' => 'Naissance du royaume du Dahomey', 'texte' => 'Do-Aklin, prince aja, s\'installe sur le plateau d\'Abomey. Son petit-fils Houegbadja pose les bases de ce qui deviendra l\'un des royaumes les plus puissants de la région.'],
                ['date' => '1670',    'titre' => 'Ouidah, port négrier',      'texte' => 'La ville portuaire de Xwéda devient plaque tournante de la traite atlantique. Européens (Portugais, Français, Anglais) y installent leurs forts.'],
            ],
        ],
        [
            'periode' => 'XVIIIe siècle',
            'couleur' => '#e8112d',
            'titre' => 'L\'apogée du Dahomey',
            'events' => [
                ['date' => '1724', 'titre' => 'Le roi Agadja prend Allada',   'texte' => 'Le Dahomey conquiert son royaume d\'origine, puis Ouidah en 1727. L\'unification territoriale du sud est en marche.'],
                ['date' => '1740', 'titre' => 'Le corps des Amazones',        'texte' => 'Sous Tegbessou, l\'armée s\'agrandit avec le célèbre régiment féminin des Agojie, les « Amazones du Dahomey » — soldates redoutées, elles combattront jusqu\'en 1894.'],
                ['date' => '1789', 'titre' => 'Règne de Kpengla, puis Agonglo', 'texte' => 'Le royaume atteint sa puissance maximale. Palais royaux d\'Abomey enrichis de bas-reliefs, tissus appliqués, sabres cérémoniels.'],
            ],
        ],
        [
            'periode' => 'XIXe siècle',
            'couleur' => '#fcd116',
            'titre' => 'Résistance et conquête',
            'events' => [
                ['date' => '1818', 'titre' => 'Ghezo monte sur le trône',    'texte' => 'Ghezo refuse le tribut aux Yoruba. Le Dahomey retrouve son indépendance politique et devient une puissance régionale respectée.'],
                ['date' => '1851', 'titre' => 'Fin (officielle) de la traite négrière', 'texte' => 'Sous pression internationale, le Dahomey signe un traité avec la France. La traite continue clandestinement quelques années.'],
                ['date' => '1892', 'titre' => 'Guerre franco-dahoméenne',    'texte' => 'Béhanzin, dernier roi indépendant, oppose deux ans de résistance aux troupes du colonel Dodds. Il est déposé et exilé en Martinique.'],
                ['date' => '1894', 'titre' => 'Colonie française du Dahomey', 'texte' => 'Le Dahomey et ses dépendances deviennent une colonie française, intégrée à l\'Afrique Occidentale Française (AOF) en 1904.'],
            ],
        ],
        [
            'periode' => 'XXe siècle',
            'couleur' => '#00844d',
            'titre' => 'Vers l\'indépendance et la République',
            'events' => [
                ['date' => '1958', 'titre' => 'République autonome',         'texte' => 'Le Dahomey devient une république autonome au sein de la Communauté française. Hubert Maga prend la tête du gouvernement.'],
                ['date' => '1960', 'titre' => 'Indépendance',                'texte' => 'Le 1er août 1960, la République du Dahomey accède à l\'indépendance. Hubert Maga en devient le premier président.'],
                ['date' => '1972', 'titre' => 'Prise du pouvoir par Kérékou', 'texte' => 'Le commandant Mathieu Kérékou renverse le régime civil. Il proclame le marxisme-léninisme en 1974.'],
                ['date' => '1975', 'titre' => 'Renommage : République Populaire du Bénin', 'texte' => 'Le pays prend le nom actuel de Bénin, en référence à l\'ancien royaume du Bénin (nord du Nigeria). Le drapeau rouge remplace le tricolore vert-jaune-rouge.'],
                ['date' => '1990', 'titre' => 'Conférence nationale',        'texte' => 'Face à la crise économique, Kérékou convoque une conférence nationale historique — première d\'Afrique. Adoption du multipartisme. Le drapeau vert-jaune-rouge est restauré.'],
                ['date' => '1991', 'titre' => 'Alternance démocratique',     'texte' => 'Nicéphore Soglo bat Kérékou à la présidentielle. Le Bénin devient un modèle de transition démocratique en Afrique de l\'Ouest.'],
            ],
        ],
        [
            'periode' => 'XXIe siècle',
            'couleur' => '#0b3d2c',
            'titre' => 'Le Bénin contemporain',
            'events' => [
                ['date' => '1997', 'titre' => 'Reconnaissance du vodun',     'texte' => 'Le vodun est officiellement reconnu comme religion nationale. Le 10 janvier devient férié, journée nationale du vodun.'],
                ['date' => '2016', 'titre' => 'Élection de Patrice Talon',   'texte' => 'Homme d\'affaires, Patrice Talon accède à la présidence avec un programme de modernisation économique et touristique.'],
                ['date' => '2019', 'titre' => 'Programme d\'action gouvernemental', 'texte' => 'Le tourisme devient priorité nationale : réhabilitation des palais royaux, création du Musée international de la mémoire de l\'esclavage, marketing pays "Révélons le Bénin".'],
                ['date' => '2021', 'titre' => 'Restitution de 26 œuvres',    'texte' => 'La France restitue au Bénin 26 œuvres du trésor royal d\'Abomey pillées en 1892. Exposition événement à Cotonou.'],
                ['date' => '2022', 'titre' => 'Premiers Vodun Days',         'texte' => 'Lancement des Vodun Days de Ouidah, festival international qui met en scène le vodun contemporain. Trois éditions depuis, événement phare de janvier.'],
                ['date' => '2026', 'titre' => 'Le Bénin s\'ouvre',           'texte' => 'e-visa en 48 h, réhabilitation totale des sites majeurs, nouveaux hôtels et opérateurs, portails éditoriaux comme Bonne Arrivée. Une nouvelle décennie touristique s\'ouvre.'],
            ],
        ],
    ];
@endphp

@section('content')
<div class="content-wrapper no-terms">
    <section class="ba-page-hero" aria-label="Timeline historique du Bénin">
        <img src="/assets/img/abomey-palais-1920x960.webp" alt="" loading="eager" fetchpriority="high">
        <div class="ba-page-hero__overlay">
            <nav class="ba-page-hero__breadcrumb" aria-label="Fil d'ariane">
                <ol>
                    <li><a href="/">Accueil</a></li>
                    <li aria-hidden="true">/</li>
                    <li><a href="/explorez">Découvrez</a></li>
                    <li aria-hidden="true">/</li>
                    <li aria-current="page">Timeline historique</li>
                </ol>
            </nav>
            <div class="ba-page-hero__center">
                <p class="ba-page-hero__eyebrow">Quatre siècles d'histoire</p>
                <h1 class="ba-page-hero__title">Une brève histoire du Bénin</h1>
                <p class="ba-page-hero__lead">Des royaumes fon d'Abomey à la République moderne — trente-cinq dates pour comprendre le pays où vous êtes sur le point de mettre les pieds.</p>
            </div>
        </div>
    </section>

    <section class="ba-timeline">
        @foreach($eras as $ei => $era)
            <div class="ba-timeline__era" style="--era-color: {{ $era['couleur'] }}">
                <header class="ba-timeline__era-head">
                    <span class="ba-timeline__era-num">{{ str_pad((string)($ei+1), 2, '0', STR_PAD_LEFT) }}</span>
                    <div>
                        <p class="ba-timeline__era-periode">{{ $era['periode'] }}</p>
                        <h2 class="ba-timeline__era-titre">{{ $era['titre'] }}</h2>
                    </div>
                </header>
                <ol class="ba-timeline__list">
                    @foreach($era['events'] as $i => $evt)
                        <li class="ba-timeline__item" style="--i: {{ $i }}">
                            <div class="ba-timeline__marker" aria-hidden="true">
                                <span class="ba-timeline__dot"></span>
                            </div>
                            <div class="ba-timeline__content">
                                <span class="ba-timeline__date">{{ $evt['date'] }}</span>
                                <h3 class="ba-timeline__titre">{{ $evt['titre'] }}</h3>
                                <p class="ba-timeline__texte">{{ $evt['texte'] }}</p>
                            </div>
                        </li>
                    @endforeach
                </ol>
            </div>
        @endforeach
    </section>

    <section class="ba-cta-simple">
        <p>Envie de voir de vos yeux les palais d'Abomey, Ouidah, la Pendjari ?</p>
        <a class="ba-cta-simple__btn" href="/explorez">
            Explorer les régions du Bénin <span aria-hidden="true">→</span>
        </a>
    </section>
</div>
<x-aggregator.ecosysteme-block />
@endsection
