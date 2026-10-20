{{-- Gîtes, lodges et écolodges au Bénin — refonte 2026, gabarit identique
     aux autres pages hébergement : hero image, chapô, 3 blocs thématiques,
     aggregator Dahomey Discovery, cross-liens, écosystème. --}}
@extends('layouts.app')

@section('bodyClass', 'page menus-v2 prod gabarit-basic_page benin-hotels-page')
@section('mainAttributes')role="main" class="content-type-page basic_page" id="main-content"@endsection

@section('headGlobals')@verbatim<script>window.globals={"ancestors":{"chapter1":"Organisez","chapter2":"Hébergements","chapter3":"Gîtes et lodges"},"context":"website","current_lang":"fr","current_locale":"fr","current_locale_region":"fr_FR","env":"prod","languages":["fr","nl"],"page_type":"basic_page","post_id":799,"post_title":"Gîtes et lodges","post_type":"page","site_key":"benin","tags":{"places":[],"seasons":[],"themes":[],"targets":[]},"woody_options_pages":{"search_url":2043,"weather_url":0},"woody_timezone":"Europe/Paris","search":{"api":{"uri":"","args":{"size":15,"threshold":0.5}}},"sheet_bordereaux_with_dates":[],"short_date_bordereaux":["FMA","ASC"],"tmaps":{"url_api":"","url_api_lazy":""},"hawwwai":{"sheet_options":{"nearby_distance_max":5000}}}</script>@endverbatim@endsection
@section('headSchema')@verbatim<script type="application/ld+json">{"@context":"https://schema.org","@type":"CollectionPage","name":"Gîtes, lodges et écolodges au Bénin | Bonne Arrivée","url":"https://www.bonnearrivee.bj/sejourner/hebergements/gites-et-lodges","description":"Écolodges, campements de brousse et gîtes communautaires au Bénin — Pendjari, Atacora, lac Ahémé. Réservez avec Dahomey Discovery."}</script>@endverbatim@endsection
@section('headImageOg')@verbatim<meta property="og:image" content="/assets/img/pendjari-savane-1920x960.webp"/>@endverbatim@endsection

@section('content')
@verbatim
<div class="content-wrapper no-terms">

    <div class="emerv-hero">
        <img class="emerv-hero__img"
             src="/assets/img/pendjari-savane-1920x960.webp"
             srcset="/assets/img/pendjari-savane-640x320.webp 640w, /assets/img/pendjari-savane-1200x600.webp 1200w, /assets/img/pendjari-savane-1920x960.webp 1920w"
             sizes="100vw"
             alt="Gîte de brousse dans le nord Bénin"
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
                    <li aria-current="page">Gîtes et lodges</li>
                </ol>
            </nav>
            <div class="emerv-hero__center">
                <p class="emerv-hero__eyebrow">Séjourner · Hébergements</p>
                <h1 class="emerv-hero__title">Gîtes, lodges et écolodges</h1>
                <p class="emerv-hero__lead">Autour des parcs et sur les lacs, l'hébergement prend une autre forme : campements, gîtes villageois, lodges de brousse. On y dort plus près du site qu'on est venu voir.</p>
            </div>
        </div>
    </div>

    <section class="benin-page-chapo">
        <p>Autour des parcs et sur les lacs, l'hébergement prend une autre forme : <strong>campements</strong>, <strong>gîtes villageois</strong>, <strong>lodges de brousse</strong>. On y dort plus près du site qu'on est venu voir.</p>
    </section>

    <section class="benin-page-blocks" aria-label="Repères pour choisir un gîte ou un lodge">
        <article class="benin-page-block">
            <span class="benin-page-block__num">01</span>
            <h2 class="benin-page-block__title">Les campements de brousse</h2>
            <p>Autour des aires protégées du nord, l'hébergement est saisonnier et lié à l'ouverture des parcs. Les campements sont sobres : chambre simple, ventilateur ou climatisation sur groupe, repas pris en commun. Leur intérêt est la <strong>position</strong>, à l'entrée du parc ou à l'intérieur, ce qui permet de sortir aux heures où les animaux bougent.</p>
            <p>L'accès aux parcs du nord dépend de la <strong>situation sécuritaire</strong>, qui a fortement évolué. Vérifiez auprès du gestionnaire du parc et lisez la page Sécurité avant de réserver quoi que ce soit.</p>
        </article>

        <article class="benin-page-block benin-page-block--highlight">
            <span class="benin-page-block__num">02</span>
            <h2 class="benin-page-block__title">Les gîtes communautaires</h2>
            <p>Ce sont les adresses les plus intéressantes du pays. Gérés par des <strong>associations villageoises</strong>, ils reversent directement une part au village. À <strong>Koussoukoingou</strong>, dans l'Atacora, on dort dans un écolodge ou sur la terrasse d'une tata. À <strong>Possotomé</strong>, sur le lac Ahémé, un gîte et un camping en bord d'eau.</p>
            <p>On n'y va pas pour le confort mais pour ce qu'on comprend en y dormant. Une tata betammaribe s'explique mieux depuis sa terrasse que depuis un cartel.</p>
        </article>

        <article class="benin-page-block">
            <span class="benin-page-block__num">03</span>
            <h2 class="benin-page-block__title">Réserver</h2>
            <p>Ces structures passent rarement par les plateformes internationales. On les contacte via l'association gestionnaire ou un opérateur local, souvent par téléphone. Prévoyez du <strong>liquide</strong> : le paiement par carte n'existe pas.</p>
            <p>Réservez tôt pour la <strong>saison sèche</strong>, de novembre à février, et pour toute la période des <strong>Vodun Days</strong>.</p>
        </article>
    </section>
@endverbatim

    <x-aggregator.accommodations-block
        title="Villas et lodges indépendants de notre réseau"
        pretitle="Réserver avec Dahomey Discovery"
        :filters="['accommodation_type' => 'villa']"
        :limit="6"
        ctaHref="https://dahomeydiscovery.com/type-hebergement/villa/"
        ctaLabel="Voir toutes les villas sur dahomeydiscovery.com" />

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
            <a class="benin-page-related__card" href="/sejourner/hebergements/chambres-dhotes">
                <img src="/assets/img/vodun-640x480.webp" alt="Chambres et maisons d'hôtes au Bénin" loading="lazy" decoding="async">
                <div class="benin-page-related__card-inner">
                    <h3>Chambres et maisons d'hôtes</h3>
                    <p>L'accueil au plus près des habitants, en ville comme en village.</p>
                    <span class="benin-page-related__arrow" aria-hidden="true">Découvrir →</span>
                </div>
            </a>
        </div>
    </section>
</div>
@endverbatim

<x-aggregator.ecosysteme-block />
@endsection
