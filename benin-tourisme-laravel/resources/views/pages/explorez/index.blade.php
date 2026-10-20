{{-- Découvrez le Bénin — refonte 2026 : hero image + chapô + grande section
     dédiée au littoral (5 escales) + 4 thématiques transversales (5 régions,
     nature, traditions, mémoire) + aggregator circuits Dahomey Discovery +
     lien vers « La vie s'émerveille » + écosystème. --}}
@extends('layouts.app')

@section('bodyClass', 'page menus-v2 prod gabarit-basic_page benin-hotels-page')
@section('mainAttributes')role="main" class="content-type-page basic_page" id="main-content"@endsection

@section('headGlobals')@verbatim<script>window.globals={"ancestors":{"chapter1":"Découvrez","chapter2":null,"chapter3":null},"context":"website","current_lang":"fr","current_locale":"fr","current_locale_region":"fr_FR","env":"prod","languages":["fr","nl"],"page_type":"basic_page","post_id":682,"post_title":"Découvrez","post_type":"page","site_key":"benin","tags":{"places":[],"seasons":[],"themes":[],"targets":[]},"woody_options_pages":{"search_url":2043,"weather_url":0},"woody_timezone":"Europe/Paris","search":{"api":{"uri":"","args":{"size":15,"threshold":0.5}}},"sheet_bordereaux_with_dates":[],"short_date_bordereaux":["FMA","ASC"],"tmaps":{"url_api":"","url_api_lazy":""},"hawwwai":{"sheet_options":{"nearby_distance_max":5000}}}</script>@endverbatim@endsection
@section('headSchema')@verbatim<script type="application/ld+json">{"@context":"https://schema.org","@type":"CollectionPage","name":"Découvrez le Bénin | Bonne Arrivée","url":"https://www.bonnearrivee.bj/explorez","description":"Le littoral, les cinq régions, la nature sauvage, la terre de traditions. Un pays étroit et long qui change trois fois de visage entre le golfe de Guinée et les collines de l'Atacora."}</script>@endverbatim@endsection
@section('headImageOg')@verbatim<meta property="og:image" content="/assets/img/pirogue-1920x960.webp"/>@endverbatim@endsection

@section('content')
@verbatim
<div class="content-wrapper no-terms">

    <div class="emerv-hero">
        <img class="emerv-hero__img"
             src="/assets/img/pirogue-1920x960.webp"
             srcset="/assets/img/pirogue-640x320.webp 640w, /assets/img/pirogue-1200x600.webp 1200w, /assets/img/pirogue-1920x960.webp 1920w"
             sizes="100vw"
             alt="Pirogue sur le lac Nokoué"
             fetchpriority="high">
        <div class="emerv-hero__overlay">
            <nav class="emerv-hero__breadcrumb" aria-label="Fil d'ariane">
                <ol>
                    <li><a href="/">Accueil</a></li>
                    <li aria-hidden="true">/</li>
                    <li aria-current="page">Découvrez</li>
                </ol>
            </nav>
            <div class="emerv-hero__center">
                <p class="emerv-hero__eyebrow">Explorer · Comprendre · Choisir</p>
                <h1 class="emerv-hero__title">Découvrez le Bénin</h1>
                <p class="emerv-hero__lead">Sept cents kilomètres du nord au sud, trois visages en chemin : la côte et les lagunes, les royaumes du plateau, la savane et l'Atacora.</p>
            </div>
        </div>
    </div>

    <section class="benin-page-chapo">
        <p>Le <strong>Bénin</strong> tient sur 700 kilomètres du nord au sud, et change trois fois de visage. Les <strong>lagunes et le cordon littoral</strong> au sud, les <strong>collines et les royaumes</strong> au centre, la <strong>savane et les montagnes de l'Atacora</strong> au nord. Choisissez par où commencer.</p>
    </section>

    <section class="benin-littoral" aria-label="Le littoral béninois">
        <div class="benin-littoral__head">
            <p class="benin-littoral__eyebrow">Zoom sur le littoral</p>
            <h2 class="benin-littoral__title">Cent vingt-cinq kilomètres de côte, deux grands lacs, une lagune continue</h2>
            <p class="benin-littoral__lead">Le sud du Bénin est un pays d'eau. On s'y déplace en pirogue autant qu'en voiture, les villages naissent au bord des rives, et la mémoire de la traite négrière habite chaque plage. Voici cinq escales pour comprendre le littoral.</p>
        </div>
        <div class="benin-littoral__grid">

            <a class="benin-littoral__card benin-littoral__card--wide" href="/explorez/le-littoral-et-les-lacs/grand-popo">
                <img src="/assets/img/plage-grand-popo-1200x675.webp" alt="La plage de Grand-Popo" loading="lazy" decoding="async">
                <div class="benin-littoral__card-inner">
                    <span class="benin-littoral__cat">Plage & lagune</span>
                    <h3>Grand-Popo</h3>
                    <p>La côte des mystères — plage de sable fin bordée de cocotiers, lagune du Mono en arrière, empreinte afro-brésilienne du XIXᵉ siècle.</p>
                </div>
            </a>

            <a class="benin-littoral__card" href="/explorez/le-littoral-et-les-lacs/ganvie">
                <img src="/assets/img/ganvie-640x480.webp" alt="La cité lacustre de Ganvié" loading="lazy" decoding="async">
                <div class="benin-littoral__card-inner">
                    <span class="benin-littoral__cat">Cité lacustre</span>
                    <h3>Ganvié</h3>
                    <p>La Venise d'Afrique — un village entier sur pilotis au milieu du lac Nokoué, on s'y déplace en pirogue depuis l'embarcadère de Calavi.</p>
                </div>
            </a>

            <a class="benin-littoral__card" href="/explorez/le-littoral-et-les-lacs/la-bouche-du-roy">
                <img src="/assets/img/mangrove-640x480.webp" alt="La Bouche du Roy" loading="lazy" decoding="async">
                <div class="benin-littoral__card-inner">
                    <span class="benin-littoral__cat">Nature protégée</span>
                    <h3>La Bouche du Roy</h3>
                    <p>L'embouchure du fleuve Mono et ses bancs de sable, la mangrove, les tortues marines qui viennent pondre en saison.</p>
                </div>
            </a>

            <a class="benin-littoral__card" href="/explorez/le-littoral-et-les-lacs/le-lac-nokoue">
                <img src="/assets/img/pirogue-640x480.webp" alt="Le lac Nokoué" loading="lazy" decoding="async">
                <div class="benin-littoral__card-inner">
                    <span class="benin-littoral__cat">Grand lac</span>
                    <h3>Le lac Nokoué</h3>
                    <p>Le plus grand lac du pays, ses acadja de branchages, ses pêcheurs Tofinou, et Cotonou juste à côté.</p>
                </div>
            </a>

            <a class="benin-littoral__card benin-littoral__card--wide" href="/explorez/le-littoral-et-les-lacs/la-mangrove">
                <img src="/assets/img/mangrove-1200x600.webp" alt="La mangrove du fleuve Mono" loading="lazy" decoding="async">
                <div class="benin-littoral__card-inner">
                    <span class="benin-littoral__cat">Écosystème</span>
                    <h3>La mangrove</h3>
                    <p>Rempart contre l'océan et nurserie du golfe de Guinée — sortie en pirogue à l'aube, entre les racines qui plongent dans l'eau salée.</p>
                </div>
            </a>

        </div>

        <div class="benin-littoral__footer">
            <a class="benin-littoral__more" href="/explorez/le-littoral-et-les-lacs">
                Voir toute la rubrique Littoral & lacs <span aria-hidden="true">→</span>
            </a>
        </div>
    </section>

    <section class="benin-regions" aria-label="Les cinq visages du Bénin">
        <div class="benin-page-related__head">
            <p class="benin-page-related__eyebrow">Cinq visages</p>
            <h2 class="benin-page-related__title">Choisir son point cardinal</h2>
        </div>
        <div class="benin-regions__grid">
            <a class="benin-regions__card" href="/explorez/les-cinq-benin/la-cote-memorielle">
                <img src="/assets/img/ouidah-porte-640x480.webp" alt="La côte mémorielle">
                <h3>La côte mémorielle</h3>
                <p>Ouidah, Grand-Popo, Route de l'Esclave</p>
            </a>
            <a class="benin-regions__card" href="/explorez/les-cinq-benin/le-littoral-atlantique">
                <img src="/assets/img/cotonou-640x480.webp" alt="Le littoral atlantique">
                <h3>Le littoral atlantique</h3>
                <p>Cotonou, Ganvié, Fidjrossè</p>
            </a>
            <a class="benin-regions__card" href="/explorez/les-cinq-benin/le-plateau-royal">
                <img src="/assets/img/abomey-palais-640x480.webp" alt="Le plateau royal">
                <h3>Le plateau royal</h3>
                <p>Abomey, Porto-Novo, Kétou</p>
            </a>
            <a class="benin-regions__card" href="/explorez/les-cinq-benin/le-centre-mystique">
                <img src="/assets/img/dassa-collines-640x480.webp" alt="Le centre mystique">
                <h3>Le centre mystique</h3>
                <p>Dassa-Zoumè, Savalou, Savé</p>
            </a>
            <a class="benin-regions__card" href="/explorez/les-cinq-benin/le-grand-nord">
                <img src="/assets/img/atacora-640x480.webp" alt="Le grand Nord">
                <h3>Le grand Nord</h3>
                <p>Natitingou, Pendjari, Atacora</p>
            </a>
        </div>
    </section>

    <section class="benin-themes" aria-label="Thématiques du Bénin">
        <a class="benin-themes__card benin-themes__card--nature" href="/explorez/naturellement-sauvage">
            <img src="/assets/img/pendjari-elephant-1200x900.webp" alt="Naturellement sauvage">
            <div>
                <span>Nature & parcs</span>
                <h2>Naturellement sauvage</h2>
                <p>Deux parcs nationaux UNESCO, la chaîne de l'Atacora, les chutes de Tanougou, la mangrove du Mono. La nature béninoise, moins connue mais dense.</p>
                <em>Découvrir la rubrique →</em>
            </div>
        </a>
        <a class="benin-themes__card benin-themes__card--traditions" href="/explorez/terre-de-traditions">
            <img src="/assets/img/vodun-1200x900.webp" alt="Terre de traditions">
            <div>
                <span>Culture & vodun</span>
                <h2>Terre de traditions</h2>
                <p>Le berceau du vodun, les royaumes du Danxomè, les masques gèlèdè et zangbéto. Rien n'y est un musée — ce sont des pratiques vivantes.</p>
                <em>Découvrir la rubrique →</em>
            </div>
        </a>
    </section>
@endverbatim

    <x-aggregator.circuits-block
        pretitle="Réserver avec Dahomey Discovery"
        title="Circuits proposés par notre agence réceptive"
        description="Itinéraires clés en main conçus par Dahomey Discovery — véhicule, chauffeur, guide local et hébergement inclus."
        :filters="[]"
        :limit="6" />

@verbatim
    <section class="benin-emerv-cta">
        <div>
            <p class="benin-emerv-cta__eyebrow">Sélection éditoriale</p>
            <h2>La vie s'émerveille au Bénin</h2>
            <p>Vingt-neuf lieux en une seule vue — plages, palais royaux, cités lacustres, cascades, cités vodun. Filtrez par ce qui vous parle.</p>
            <a href="/explorez/la-vie-semerveille-au-benin">
                Voir la sélection complète <span aria-hidden="true">→</span>
            </a>
        </div>
        <div class="benin-emerv-cta__images">
            <img src="/assets/img/chutes-tanougou-640x480.webp" alt="Les chutes de Tanougou">
            <img src="/assets/img/abomey-palais-640x480.webp" alt="Les Palais d'Abomey">
            <img src="/assets/img/ganvie-640x480.webp" alt="Ganvié">
            <img src="/assets/img/tata-somba-640x480.webp" alt="Tata somba">
        </div>
    </section>
</div>
@endverbatim

<x-aggregator.ecosysteme-block />
@endsection
