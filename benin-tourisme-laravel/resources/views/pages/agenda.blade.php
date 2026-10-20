{{-- Agenda mois par mois : evenements culturels et festivals du Benin
     tries chronologiquement sur l'annee. Contenu editorial 100% original. --}}
@extends('layouts.app')

@section('bodyClass', 'page menus-v2 prod gabarit-basic_page benin-agenda-page')
@section('mainAttributes')role="main" class="content-type-page basic_page" id="main-content"@endsection

@php
    $mois = [
        ['nom' => 'Janvier',   'temperature' => '25–32 °C · sec',   'accents' => [
            ['type' => 'FESTIVAL', 'titre' => 'Vodun Days',            'lieu' => 'Ouidah',       'dates' => '9 → 10 janv.', 'texte' => 'Le grand rendez-vous international du vodun. Défilés, danses masquées, forums, allumage de la flamme.', 'lien' => '/benin-en-fete/vodun-days', 'couleur' => '#e8112d'],
            ['type' => 'FÊTE',     'titre' => 'Journée du vodun',      'lieu' => 'Tout le pays', 'dates' => '10 janv.',     'texte' => 'Férié national depuis 1997. Cérémonies familiales, ouvertures de temples, chants et libations.', 'lien' => null,                     'couleur' => '#00844d'],
        ]],
        ['nom' => 'Février',   'temperature' => '25–33 °C · sec / harmattan',  'accents' => [
            ['type' => 'CULTURE',  'titre' => 'Saison culturelle Porto-Novo', 'lieu' => 'Porto-Novo', 'dates' => 'tout le mois', 'texte' => 'Expositions au musée Honmé, concerts au conservatoire, promenades guidées dans la ville afro-brésilienne.', 'lien' => null, 'couleur' => '#00844d'],
        ]],
        ['nom' => 'Mars',      'temperature' => '26–35 °C · sec, chaud',       'accents' => [
            ['type' => 'ARTISANAT','titre' => 'Foires artisanales'           , 'lieu' => 'Cotonou · Abomey', 'dates' => 'week-ends',    'texte' => 'Marché de tissages, poteries, sculptures. Idéal pour ramener des objets authentiques.', 'lien' => null, 'couleur' => '#a2582a'],
        ]],
        ['nom' => 'Avril',     'temperature' => '26–34 °C · premières pluies', 'accents' => [
            ['type' => 'NATURE',   'titre' => 'Fin de la saison safari',      'lieu' => 'Pendjari · Parc W', 'dates' => 'avant mi-avril','texte' => 'Dernières semaines pour observer la faune massée aux points d\'eau avant l\'arrivée des pluies.', 'lien' => '/explorez/naturellement-sauvage/le-parc-de-la-pendjari', 'couleur' => '#00844d'],
        ]],
        ['nom' => 'Mai',       'temperature' => '25–32 °C · saison des pluies','accents' => [
            ['type' => 'PATRIMOINE','titre' => 'Journée internationale des musées', 'lieu' => 'Abomey · Ouidah · Porto-Novo', 'dates' => '18 mai', 'texte' => 'Musées ouverts gratuitement. Visites guidées spéciales aux Palais royaux, au Musée d\'histoire de Ouidah.', 'lien' => null, 'couleur' => '#a2582a'],
        ]],
        ['nom' => 'Juin',      'temperature' => '24–29 °C · pluies fréquentes','accents' => [
            ['type' => 'CULTURE',  'titre' => 'Ganvié Beach Festival',        'lieu' => 'Ganvié · lac Nokoué', 'dates' => 'mi-juin',    'texte' => 'Musique, danse, régates de pirogues sur le lac. Un festival né de la cité lacustre elle-même.', 'lien' => null, 'couleur' => '#4f7fbe'],
        ]],
        ['nom' => 'Juillet',   'temperature' => '24–28 °C · pluies',           'accents' => [
            ['type' => 'FESTIVAL', 'titre' => 'JISTNA',                        'lieu' => 'Cotonou',       'dates' => '15 → 25 juillet', 'texte' => 'Les Journées internationales du sport, du tourisme et de la nature — parade, ateliers, sport ouvert, plage.', 'lien' => '/benin-en-fete/jistna', 'couleur' => '#0080b8'],
        ]],
        ['nom' => 'Août',      'temperature' => '25–30 °C · pluies',           'accents' => [
            ['type' => 'HISTOIRE', 'titre' => 'Fête nationale',                'lieu' => 'Tout le pays',   'dates' => '1er août',        'texte' => 'Indépendance du Bénin (1960). Défilés militaires à Cotonou, cérémonies dans les préfectures.', 'lien' => null, 'couleur' => '#e8112d'],
            ['type' => 'FESTIVAL', 'titre' => 'Festival International des Masques', 'lieu' => 'Porto-Novo', 'dates' => 'weekend',        'texte' => 'Gèlèdè, zangbéto, egungun réunis sur deux journées. Les grandes sociétés masquées de la sous-région à Porto-Novo.', 'lien' => '/benin-en-fete/festival-des-masques', 'couleur' => '#a2582a'],
        ]],
        ['nom' => 'Septembre', 'temperature' => '25–32 °C · pluies déclinent', 'accents' => [
            ['type' => 'NATURE',   'titre' => 'Réouverture safaris',          'lieu' => 'Pendjari · Parc W', 'dates' => 'fin septembre','texte' => 'Les parcs rouvrent aux visiteurs. Faune concentrée près des points d\'eau, végétation encore luxuriante.', 'lien' => '/explorez/naturellement-sauvage', 'couleur' => '#00844d'],
        ]],
        ['nom' => 'Octobre',   'temperature' => '25–33 °C · sec revient',      'accents' => [
            ['type' => 'FESTIVAL', 'titre' => 'Nuit des rois',                 'lieu' => 'Abomey',        'dates' => 'variable',        'texte' => 'Cérémonie annuelle aux Palais royaux, hommage aux 12 rois du Dahomey.', 'lien' => null, 'couleur' => '#fcd116'],
        ]],
        ['nom' => 'Novembre',  'temperature' => '26–34 °C · sec',              'accents' => [
            ['type' => 'FESTIVAL', 'titre' => 'La Gaani',                      'lieu' => 'Nikki',         'dates' => '2 → 5 nov.',      'texte' => 'La grande fête royale bariba. Défilé de cavaliers, épreuves équestres, transe des zima, retour de l\'histoire de Sunon Sero.', 'lien' => '/benin-en-fete/la-gaani', 'couleur' => '#c05b1a'],
        ]],
        ['nom' => 'Décembre',  'temperature' => '24–32 °C · sec, harmattan',   'accents' => [
            ['type' => 'FESTIVAL', 'titre' => 'Weloveya',                      'lieu' => 'Fidjrossè · Cotonou', 'dates' => '27 → 28 déc.', 'texte' => 'Deux nuits de festival sur la plage. Scène internationale + scène béninoise émergente, food-court, ambiance carnaval de fin d\'année.', 'lien' => '/benin-en-fete/weloveya', 'couleur' => '#e8112d'],
            ['type' => 'FÊTE',     'titre' => 'Fêtes de fin d\'année',         'lieu' => 'Tout le pays', 'dates' => '25 déc. · 31 déc.', 'texte' => 'Traditions catholiques + fêtes traditionnelles. Les Béninois de la diaspora rentrent — hôtels pleins.', 'lien' => null, 'couleur' => '#00844d'],
        ]],
    ];
@endphp

@section('content')
<div class="content-wrapper no-terms">
    <section class="ba-page-hero" aria-label="Agenda annuel du Bénin">
        <img src="/assets/img/danse-1920x960.webp" alt="" loading="eager" fetchpriority="high">
        <div class="ba-page-hero__overlay">
            <nav class="ba-page-hero__breadcrumb" aria-label="Fil d'ariane">
                <ol>
                    <li><a href="/">Accueil</a></li>
                    <li aria-hidden="true">/</li>
                    <li><a href="/benin-en-fete">Bénin en Fête</a></li>
                    <li aria-hidden="true">/</li>
                    <li aria-current="page">Agenda annuel</li>
                </ol>
            </nav>
            <div class="ba-page-hero__center">
                <p class="ba-page-hero__eyebrow">Douze mois de rendez-vous</p>
                <h1 class="ba-page-hero__title">L'agenda du Bénin, mois par mois</h1>
                <p class="ba-page-hero__lead">Festivals, fêtes coutumières, temps forts culturels et naturels : tout ce qui se passe au Bénin sur douze mois, avec la météo de chaque période.</p>
            </div>
        </div>
    </section>

    <section class="ba-agenda">
        @foreach($mois as $mi => $m)
            <article class="ba-agenda__month" data-mois="{{ $mi + 1 }}">
                <header class="ba-agenda__month-head">
                    <span class="ba-agenda__month-num">{{ str_pad((string)($mi+1), 2, '0', STR_PAD_LEFT) }}</span>
                    <div>
                        <h2 class="ba-agenda__month-nom">{{ $m['nom'] }}</h2>
                        <p class="ba-agenda__month-meteo">{{ $m['temperature'] }}</p>
                    </div>
                </header>
                <div class="ba-agenda__events">
                    @foreach($m['accents'] as $e)
                        <div class="ba-agenda__event" style="--e-color: {{ $e['couleur'] }}">
                            <span class="ba-agenda__event-type">{{ $e['type'] }}</span>
                            <h3 class="ba-agenda__event-titre">
                                @if($e['lien'])
                                    <a href="{{ $e['lien'] }}">{{ $e['titre'] }}</a>
                                @else
                                    {{ $e['titre'] }}
                                @endif
                            </h3>
                            <p class="ba-agenda__event-meta">
                                <span class="ba-agenda__event-dates">{{ $e['dates'] }}</span>
                                <span class="ba-agenda__event-sep" aria-hidden="true">·</span>
                                <span class="ba-agenda__event-lieu">{{ $e['lieu'] }}</span>
                            </p>
                            <p class="ba-agenda__event-texte">{{ $e['texte'] }}</p>
                        </div>
                    @endforeach
                </div>
            </article>
        @endforeach
    </section>

    <section class="ba-cta-simple">
        <p>Envie de caler un séjour sur un événement précis ?</p>
        <a class="ba-cta-simple__btn" href="/benin-en-fete">
            Voir les 5 grands festivals <span aria-hidden="true">→</span>
        </a>
    </section>
</div>
<x-aggregator.ecosysteme-block />
@endsection
