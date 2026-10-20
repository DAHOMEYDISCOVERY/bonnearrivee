{{-- Hôtels au Bénin — refonte 2026 : hero image, chapô, 3 blocs thématiques
     rédactionnels, aggregator Dahomey Discovery, cross-liens vers les autres
     types d'hébergement, écosystème. --}}
@extends('layouts.app')

@section('bodyClass', 'page menus-v2 prod gabarit-basic_page benin-hotels-page')
@section('mainAttributes')role="main" class="content-type-page basic_page" id="main-content"@endsection

@section('headGlobals')@verbatim<script>window.globals={"ancestors":{"chapter1":"Organisez","chapter2":"Hébergements","chapter3":"Hôtels"},"context":"website","current_lang":"fr","current_locale":"fr","current_locale_region":"fr_FR","env":"prod","languages":["fr","nl"],"page_type":"basic_page","post_id":798,"post_title":"Hôtels","post_type":"page","site_key":"benin","tags":{"places":[],"seasons":[],"themes":[],"targets":[]},"woody_options_pages":{"search_url":2043,"weather_url":0},"woody_timezone":"Europe/Paris","search":{"api":{"uri":"","args":{"size":15,"threshold":0.5}}},"sheet_bordereaux_with_dates":[],"short_date_bordereaux":["FMA","ASC"],"tmaps":{"url_api":"","url_api_lazy":""},"hawwwai":{"sheet_options":{"nearby_distance_max":5000}}}</script>@endverbatim@endsection
@section('headSchema')@verbatim<script type="application/ld+json">{"@context":"https://schema.org","@type":"CollectionPage","name":"Hôtels au Bénin | Bonne Arrivée","url":"https://www.bonnearrivee.bj/sejourner/hebergements/hotels","description":"Notre sélection d'hôtels au Bénin — Cotonou, Ouidah, Grand-Popo, Abomey, Natitingou. Réservez avec Dahomey Discovery."}</script>@endverbatim@endsection
@section('headImageOg')@verbatim<meta property="og:image" content="/assets/img/hotel-1920x960.webp"/>@endverbatim@endsection

@section('content')
@verbatim
<div class="content-wrapper no-terms">

    <div class="emerv-hero">
        <img class="emerv-hero__img"
             src="/assets/img/hotel-1920x960.webp"
             srcset="/assets/img/hotel-640x320.webp 640w, /assets/img/hotel-1200x600.webp 1200w, /assets/img/hotel-1920x960.webp 1920w"
             sizes="100vw"
             alt="Hôtel au Bénin"
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
                    <li aria-current="page">Hôtels</li>
                </ol>
            </nav>
            <div class="emerv-hero__center">
                <p class="emerv-hero__eyebrow">Séjourner · Hébergements</p>
                <h1 class="emerv-hero__title">Hôtels au Bénin</h1>
                <p class="emerv-hero__lead">Des enseignes internationales de Cotonou aux hôtels de charme de Grand-Popo, Ouidah et Abomey. Réservez avec l'agence réceptive Dahomey Discovery.</p>
            </div>
        </div>
    </div>

    <section class="benin-page-chapo">
        <p>L'hôtellerie classée se trouve surtout à <strong>Cotonou</strong>, et à une échelle plus modeste à <strong>Porto-Novo</strong>, <strong>Parakou</strong>, <strong>Ouidah</strong> et <strong>Grand-Popo</strong>. Hors des grandes villes, attendez-vous à des coupures de courant et à des paiements en espèces.</p>
    </section>

    <section class="benin-page-blocks" aria-label="Repères pour choisir son hôtel">
        <article class="benin-page-block">
            <span class="benin-page-block__num">01</span>
            <h2 class="benin-page-block__title">Cotonou : l'offre internationale</h2>
            <p>L'hôtellerie 4 et 5 étoiles se concentre à <strong>Cotonou</strong>, en particulier dans les quartiers de <strong>Cadjèhoun</strong>, <strong>Haie Vive</strong> et le long du <strong>boulevard de la Marina</strong>. On y trouve les enseignes internationales — Novotel, Golden Tulip, Azalaï, Marina — ainsi que plusieurs hôtels indépendants comparables (Bénin Marina Hôtel, Livingstone).</p>
            <p>Comptez, en 2025, entre <strong>70 000 et 180 000 F&nbsp;CFA la nuit</strong> (~105-275&nbsp;€) selon la catégorie, petit déjeuner inclus. Toutes ces adresses disposent de wifi, climatisation, restaurant et souvent piscine ; la sécurité est assurée sur site.</p>
        </article>

        <article class="benin-page-block benin-page-block--highlight">
            <span class="benin-page-block__num">02</span>
            <h2 class="benin-page-block__title">En dehors de Cotonou</h2>
            <p>L'hôtellerie de charme se trouve à <strong>Grand-Popo</strong> (Villa Karo, Awalé Plage, Bel Azur), à <strong>Ouidah</strong> (Casa del Papa, Kanhoun), à <strong>Porto-Novo</strong> et à <strong>Abomey</strong>. Ce sont souvent des établissements à taille humaine — 10 à 30 chambres — installés dans des maisons coloniales rénovées ou des lodges les pieds dans le sable.</p>
            <p>Au nord, à <strong>Natitingou</strong> et près de la <strong>Pendjari</strong>, les <strong>campements</strong> et <strong>écolodges</strong> remplacent l'hôtel classique : bungalows, cases somba modernisées, ou tentes sur plateforme pour les safaris. Le confort y est plus rustique mais l'expérience unique.</p>
        </article>

        <article class="benin-page-block">
            <span class="benin-page-block__num">03</span>
            <h2 class="benin-page-block__title">Ce qu'il faut savoir avant de réserver</h2>
            <p>Les <strong>coupures d'électricité</strong> restent fréquentes hors des grandes villes : les hôtels sérieux disposent d'un groupe électrogène ; vérifiez sa présence avant réservation. Le <strong>wifi</strong> est correct à Cotonou, plus aléatoire ailleurs — prévoyez une carte SIM locale (MTN ou Moov Africa).</p>
            <p>En saison des pluies (juin-octobre), demandez une chambre bien ventilée et systématiquement équipée d'une <strong>moustiquaire imprégnée</strong>. Les paiements en carte bancaire sont acceptés dans les grands hôtels ; ailleurs, prévoyez du liquide (F&nbsp;CFA), retiré aux distributeurs BOA, Ecobank ou UBA.</p>
        </article>
    </section>
@endverbatim

    <x-aggregator.accommodations-block
        title="Notre sélection d'hôtels au Bénin"
        pretitle="Réserver avec Dahomey Discovery"
        :filters="['accommodation_type' => 'hotel']"
        :limit="6"
        ctaHref="https://dahomeydiscovery.com/type-hebergement/hotel/"
        ctaLabel="Voir tous les hôtels sur dahomeydiscovery.com" />

@verbatim
    <section class="benin-page-related">
        <div class="benin-page-related__head">
            <p class="benin-page-related__eyebrow">Compléter votre séjour</p>
            <h2 class="benin-page-related__title">Nos autres hébergements</h2>
        </div>
        <div class="benin-page-related__grid">
            <a class="benin-page-related__card" href="/sejourner/hebergements/gites-et-lodges">
                <img src="/assets/img/mangrove-640x480.webp" alt="Gîtes, lodges et écolodges au Bénin" loading="lazy" decoding="async">
                <div class="benin-page-related__card-inner">
                    <h3>Gîtes, lodges et écolodges</h3>
                    <p>Immersion nature, du Mono à la Pendjari.</p>
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
