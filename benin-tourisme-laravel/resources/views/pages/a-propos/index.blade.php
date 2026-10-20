{{-- À propos — présentation de Dahomey Discovery et du portail Bonne Arrivée. --}}
@extends('layouts.app')

@section('bodyClass', 'page menus-v2 prod gabarit-basic_page')
@section('mainAttributes')role="main" class="content-type-page basic_page" id="main-content"@endsection

@section('headGlobals')@verbatim<script>window.globals={"ancestors":{"chapter1":"À propos","chapter2":null,"chapter3":null},"context":"website","current_lang":"fr","current_locale":"fr","current_locale_region":"fr_FR","env":"prod","languages":["fr","nl"],"page_type":"basic_page","post_id":9002,"post_title":"À propos","post_type":"page","site_key":"benin","tags":{"places":[],"seasons":[],"themes":[],"targets":[]},"woody_options_pages":{"search_url":2043,"weather_url":0},"woody_timezone":"Europe/Paris","search":{"api":{"uri":"","args":{"size":15,"threshold":0.5}}},"sheet_bordereaux_with_dates":[],"short_date_bordereaux":["FMA","ASC"],"tmaps":{"url_api":"","url_api_lazy":""},"hawwwai":{"sheet_options":{"nearby_distance_max":5000}}}</script>@endverbatim@endsection
@section('headSchema')@verbatim<script type="application/ld+json">{"@context":"https://schema.org","@type":"AboutPage","url":"https://www.bonnearrivee.bj/a-propos","name":"À propos — Bonne Arrivée","description":"Dahomey Discovery, agence réceptive au Bénin, éditeur du portail Bonne Arrivée."}</script>@endverbatim@endsection

@section('content')
@verbatim
<div class="content-wrapper no-terms">

    <header class="page-teaser" id="the-page-teaser">
        <div class="woody-component woody-component-teaser trip-info-banner tpl_01 padd-bottom-md">
            <div class="woody-component-breadcrumb breadcrumb-wrapper padd-all-sm">
                <ol vocab="https://schema.org/" typeof="BreadcrumbList" class="flex-container align-middle wrap grid-container">
                    <li property="itemListElement" typeof="ListItem"><a property="item" typeof="WebPage" href="/"><span property="name">Accueil</span></a><meta property="position" content="1"></li>
                    <span class="wicon wicon-034-fleche-suivant"></span>
                    <li property="itemListElement" typeof="ListItem"><span property="item" typeof="WebPage"><span property="name">À propos</span></span><meta property="position" content="2"></li>
                </ol>
            </div>
            <div class="grid-container isRel">
                <div class="grid-x">
                    <div class="cell texts-wrapper has-title has-desc">
                        <div class="page-main-titles">
                            <div class="flex-container align-middle">
                                <h1 class="page-main-title">Qui sommes-nous ?</h1>
                            </div>
                        </div>
                        <div class="page-main-description-wrapper">
                            <div class="page-main-description">
                                <p>Bonne Arrivée est le portail éditorial de <strong>Dahomey Discovery</strong>, agence réceptive au Bénin. Nous accompagnons chaque année des voyageurs venus d'Europe, d'Afrique et d'Amérique à la découverte du berceau du vodun.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="page-section">
        <section class="section padd-top-md padd-bottom-md">
            <article class="section-layout-wrapper grid-container">
                <div class="grid-x align-center">
                    <div class="cell large-10">

                        <div class="woody-component woody-component-custom-text tpl_01 no-sessions">
                            <div class="woody-component-text">

                                <h2>L'histoire d'une agence née du terroir</h2>
                                <p>Dahomey Discovery a été fondée en <strong>2018</strong> par <strong>Mme Zoulfati HASSAN DERBY</strong>, avec une conviction simple : le Bénin mérite d'être raconté et accompagné par les Béninois eux-mêmes. Depuis Cotonou, notre équipe conçoit des séjours qui vont au-delà de la carte postale — vodun vécu, palais royaux d'Abomey, village lacustre de Ganvié, parc de la Pendjari, plages de Grand-Popo — avec des guides locaux qui parlent la langue de chaque région.</p>

                                <h2>Ce que fait notre agence</h2>
                                <ul>
                                    <li><strong>Circuits sur mesure</strong> — itinéraires accompagnés de 3 à 15 jours, avec chauffeur, guide et hébergement inclus.</li>
                                    <li><strong>Réservation d'hébergements</strong> — hôtels, écolodges, guest houses, villas et appartements contrôlés par notre équipe.</li>
                                    <li><strong>Transferts</strong> — accueil aéroport, navettes VTC, chauffeur privé, 4×4 pour la Pendjari.</li>
                                    <li><strong>Immersions culturelles</strong> — cérémonies vodun, ateliers d'artisans, rencontres avec les rois d'Abomey.</li>
                                    <li><strong>DD Care</strong> — notre assurance voyage dédiée, trois formules adaptées aux circuits au Bénin.</li>
                                </ul>

                                <h2>Notre écosystème</h2>
                                <p>Autour de Dahomey Discovery gravitent deux autres marques que nous portons également :</p>
                                <ul>
                                    <li><a href="https://www.petit-train-cotonou.com" target="_blank" rel="noopener noreferrer"><strong>Le Petit Train de Cotonou</strong></a> — trois circuits commentés (15 min, 30 min, 1 h) pour découvrir la capitale économique du Bénin en famille, en groupe ou en privatisation.</li>
                                    <li><a href="https://www.wanilo.studio" target="_blank" rel="noopener noreferrer"><strong>Wanilo Studio</strong></a> — studio audiovisuel béninois basé à Cotonou : production vidéo, podcast, photo, décors professionnels et équipe technique.</li>
                                </ul>
                                <p><strong>Bonne Arrivée</strong> réunit ces trois marques dans un portail unique de découverte du Bénin.</p>

                                <h2>Nous rencontrer</h2>
                                <p>
                                    Rond-point Akpakpa, Ciné Concorde<br>
                                    Cotonou — Bénin<br>
                                    Tél. : <a href="tel:+2290167110220">+229 01 67 11 02 20</a><br>
                                    WhatsApp : <a href="https://wa.me/2290154292929" target="_blank" rel="noopener noreferrer">+229 01 54 29 29 29</a><br>
                                    E-mail : <a href="mailto:reservations@dahomeydiscovery.com">reservations@dahomeydiscovery.com</a>
                                </p>

                            </div>
                        </div>

                    </div>
                </div>
            </article>
        </section>
    </div>

</div>
@endverbatim
{{-- BEGIN ecosysteme --}}
<x-aggregator.ecosysteme-block />
{{-- END ecosysteme --}}
@verbatim
@endverbatim
@endsection
