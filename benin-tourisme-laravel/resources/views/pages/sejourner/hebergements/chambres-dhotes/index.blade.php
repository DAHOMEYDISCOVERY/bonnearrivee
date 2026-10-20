{{-- Chambres et maisons d'hôtes au Bénin — refonte 2026, gabarit identique
     à la page Hôtels : hero image, chapô, 3 blocs thématiques, aggregator
     Dahomey Discovery, cross-liens, écosystème. --}}
@extends('layouts.app')

@section('bodyClass', 'page menus-v2 prod gabarit-basic_page benin-hotels-page')
@section('mainAttributes')role="main" class="content-type-page basic_page" id="main-content"@endsection

@section('headGlobals')@verbatim<script>window.globals={"ancestors":{"chapter1":"Organisez","chapter2":"Hébergements","chapter3":"Chambres et maisons d'hôtes"},"context":"website","current_lang":"fr","current_locale":"fr","current_locale_region":"fr_FR","env":"prod","languages":["fr","nl"],"page_type":"basic_page","post_id":800,"post_title":"Chambres et maisons d'hôtes","post_type":"page","site_key":"benin","tags":{"places":[],"seasons":[],"themes":[],"targets":[]},"woody_options_pages":{"search_url":2043,"weather_url":0},"woody_timezone":"Europe/Paris","search":{"api":{"uri":"","args":{"size":15,"threshold":0.5}}},"sheet_bordereaux_with_dates":[],"short_date_bordereaux":["FMA","ASC"],"tmaps":{"url_api":"","url_api_lazy":""},"hawwwai":{"sheet_options":{"nearby_distance_max":5000}}}</script>@endverbatim@endsection
@section('headSchema')@verbatim<script type="application/ld+json">{"@context":"https://schema.org","@type":"CollectionPage","name":"Chambres et maisons d'hôtes au Bénin | Bonne Arrivée","url":"https://www.bonnearrivee.bj/sejourner/hebergements/chambres-dhotes","description":"Chambres chez l'habitant, guest houses et villas au Bénin — Cotonou, Ouidah, Grand-Popo, Abomey, Porto-Novo. Réservez avec Dahomey Discovery."}</script>@endverbatim@endsection
@section('headImageOg')@verbatim<meta property="og:image" content="/assets/img/cotonou-1920x960.webp"/>@endverbatim@endsection

@section('content')
@verbatim
<div class="content-wrapper no-terms">

    <div class="emerv-hero">
        <img class="emerv-hero__img"
             src="/assets/img/cotonou-1920x960.webp"
             srcset="/assets/img/cotonou-640x320.webp 640w, /assets/img/cotonou-1200x600.webp 1200w, /assets/img/cotonou-1920x960.webp 1920w"
             sizes="100vw"
             alt="Maison d'hôtes au Bénin"
             fetchpriority="high">
        <div class="emerv-hero__overlay">
            <nav class="emerv-hero__breadcrumb" aria-label="Fil d'ariane">
                <ol>
                    <li><a href="/">Accueil</a></li>
                    <li aria-hidden="true">/</li>
                    <li><a href="/sejourner">Organisez</a></li>
                    <li aria-hidden="true">/</li>
                    <li><a href="/sejourner/hebergements">Hébergements</a></li>
                    <li aria-hidden="true">/</li>
                    <li aria-current="page">Chambres et maisons d'hôtes</li>
                </ol>
            </nav>
            <div class="emerv-hero__center">
                <p class="emerv-hero__eyebrow">Séjourner · Hébergements</p>
                <h1 class="emerv-hero__title">Chambres et maisons d'hôtes</h1>
                <p class="emerv-hero__lead">Une chambre chez l'habitant, une cour partagée, un petit déjeuner sur la terrasse. Souvent la formule la plus juste au Bénin, parfois la seule disponible.</p>
            </div>
        </div>
    </div>

    <section class="benin-page-chapo">
        <p>Une chambre chez l'habitant, une cour partagée, un petit déjeuner sur la terrasse. C'est souvent la formule la plus juste au Bénin, et parfois la seule disponible.</p>
    </section>

    <section class="benin-page-blocks" aria-label="Repères pour choisir une chambre d'hôtes">
        <article class="benin-page-block">
            <span class="benin-page-block__num">01</span>
            <h2 class="benin-page-block__title">Ce que recouvre la formule</h2>
            <p>Au Bénin, l'appellation couvre des réalités très différentes : la maison familiale qui loue deux chambres, la villa reconvertie tenue par un couple, la résidence de standing avec cuisine équipée. Il n'existe pas de label unifié qui permette de trancher avant d'arriver.</p>
            <p>Ce que ces adresses ont en commun : peu de chambres, un accueil direct par les propriétaires, et une capacité à vous orienter que n'a aucun hôtel. C'est souvent chez eux qu'on obtient le nom d'un guide fiable, l'heure réelle d'un marché ou la date d'une sortie de masque.</p>
        </article>

        <article class="benin-page-block benin-page-block--highlight">
            <span class="benin-page-block__num">02</span>
            <h2 class="benin-page-block__title">Où en trouver</h2>
            <p>L'offre se concentre sur l'axe <strong>Cotonou, Ouidah, Grand-Popo</strong>, puis à <strong>Abomey</strong> et <strong>Porto-Novo</strong>. Dans le nord, elle se raréfie fortement : à <strong>Natitingou</strong> ou <strong>Tanguiéta</strong>, les adresses se comptent sur les doigts d'une main et se remplissent en haute saison.</p>
            <p>Beaucoup ne figurent sur aucune plateforme de réservation internationale. Passez par un opérateur local ou par l'office de tourisme du lieu.</p>
        </article>

        <article class="benin-page-block">
            <span class="benin-page-block__num">03</span>
            <h2 class="benin-page-block__title">Ce à quoi s'attendre</h2>
            <p>Hors des grandes villes : <strong>coupures de courant</strong>, eau chaude non garantie, wifi variable, paiement en espèces. Ce ne sont pas des défauts d'exploitation, c'est le réseau du pays.</p>
            <p>Demandez avant de réserver s'il y a un <strong>groupe électrogène</strong>, une <strong>moustiquaire imprégnée</strong> sur chaque lit, et si le petit déjeuner est inclus. Ces trois questions règlent la plupart des mauvaises surprises.</p>
        </article>
    </section>
@endverbatim

    <x-aggregator.accommodations-block
        title="Chambres et guest houses de notre réseau"
        pretitle="Réserver avec Dahomey Discovery"
        :filters="['accommodation_type' => 'guest-house']"
        :limit="6"
        ctaHref="https://dahomeydiscovery.com/type-hebergement/guest-house/"
        ctaLabel="Voir toutes les guest houses sur dahomeydiscovery.com" />

@verbatim
    <section class="benin-page-related">
        <div class="benin-page-related__head">
            <p class="benin-page-related__eyebrow">Compléter votre séjour</p>
            <h2 class="benin-page-related__title">Nos autres hébergements</h2>
        </div>
        <div class="benin-page-related__grid">
            <a class="benin-page-related__card" href="/sejourner/hebergements/hotels">
                <img src="/assets/img/hotel-640x480.webp" alt="Hôtels au Bénin" loading="lazy" decoding="async">
                <div class="benin-page-related__card-inner">
                    <h3>Hôtels au Bénin</h3>
                    <p>Enseignes internationales à Cotonou, adresses de charme sur la côte.</p>
                    <span class="benin-page-related__arrow" aria-hidden="true">Découvrir →</span>
                </div>
            </a>
            <a class="benin-page-related__card" href="/sejourner/hebergements/gites-et-lodges">
                <img src="/assets/img/mangrove-640x480.webp" alt="Gîtes, lodges et écolodges au Bénin" loading="lazy" decoding="async">
                <div class="benin-page-related__card-inner">
                    <h3>Gîtes, lodges et écolodges</h3>
                    <p>Immersion nature, du Mono à la Pendjari.</p>
                    <span class="benin-page-related__arrow" aria-hidden="true">Découvrir →</span>
                </div>
            </a>
        </div>
    </section>
</div>
@endverbatim

<x-aggregator.ecosysteme-block />
@endsection
