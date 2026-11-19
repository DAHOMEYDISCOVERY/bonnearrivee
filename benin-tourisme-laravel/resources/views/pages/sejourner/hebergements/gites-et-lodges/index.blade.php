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
             src="/assets/img/hebergement-gites-1920x960.webp"
             srcset="/assets/img/hebergement-gites-640x320.webp 640w, /assets/img/hebergement-gites-1200x600.webp 1200w, /assets/img/hebergement-gites-1920x960.webp 1920w"
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
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Zilla+Slab:wght@500;700&family=Caveat:wght@600&display=swap"><script>document.documentElement.classList.add("op-brousse")</script><style>html.op-brousse{--bao-vert:#26402e;--bao-vert2:#4c6b3c;--bao-or:#c8a165;--bao-or2:#e6cc94;--bao-sable:#efe6d2;--bao-serif:"Zilla Slab",Georgia,serif}</style><style>.op *{box-sizing:border-box} .op h2,.op h3{text-transform:none!important;letter-spacing:0!important} .op h2::before,.op h2::after{display:none!important} .op-rv{opacity:0;transform:translateY(24px);transition:opacity .9s ease var(--d,0ms),transform .9s cubic-bezier(.2,.8,.2,1) var(--d,0ms)} .op-rv.on{opacity:1;transform:none} .op a{text-decoration:none!important} @media (prefers-reduced-motion:reduce){.op-rv{opacity:1;transform:none;transition:none}}</style><style>.br{background:#26402e url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='300' height='300' fill='none' stroke='%23e6cc94' stroke-opacity='.09'%3E%3Cpath d='M0 150c60-40 120 40 180 0s90-50 120-20M0 190c60-40 120 40 180 0s90-50 120-20M0 110c60-40 120 40 180 0s90-50 120-20M0 230c60-40 120 40 180 0s90-50 120-20M0 70c60-40 120 40 180 0s90-50 120-20'/%3E%3C/svg%3E");color:#efe6d2;padding:clamp(4rem,8vw,6.5rem) 1.25rem} .br-in{max-width:1180px;margin:0 auto} .br h2{font-family:"Zilla Slab",serif!important;font-size:clamp(2.4rem,5vw,4rem)!important;line-height:1!important;color:#fff!important;margin:0 0 .6rem!important;text-align:left!important} .br-hand{font-family:"Caveat",cursive;font-size:1.6rem;color:#e6cc94;margin:0 0 3rem} .br-sun{list-style:none;margin:0;padding:0;display:grid;grid-template-columns:repeat(6,1fr);gap:1rem;position:relative} .br-sun::before{content:"";position:absolute;left:4%;right:4%;top:-60px;height:120px;border:2px dashed rgba(230,204,148,.35);border-bottom:0;border-radius:50% 50% 0 0/100% 100% 0 0} .br-sun li{margin:0;padding-top:1.4rem;border-top:2px solid #c8a165;position:relative} .br-sun li::before{content:"";position:absolute;top:-9px;left:0;width:16px;height:16px;border-radius:50%;background:#e6cc94;box-shadow:0 0 0 5px rgba(230,204,148,.2)} .br-sun time{font-family:"Zilla Slab",serif;font-size:1.8rem;color:#e6cc94;display:block} .br-sun b{display:block;font-size:1rem;margin:.3rem 0} .br-sun span{font-size:.85rem;line-height:1.5;color:rgba(239,230,210,.75)} @media (max-width:900px){.br-sun{grid-template-columns:repeat(3,1fr);row-gap:2.4rem}.br-sun::before{display:none}} @media (max-width:520px){.br-sun{grid-template-columns:1fr 1fr}}</style><section class="op br"><div class="br-in"><h2 class="op-rv">Une journée au lodge</h2><p class="br-hand op-rv">aux portes de la Pendjari, en saison sèche…</p><ol class="br-sun"><li class="op-rv" style="--d:0ms;--t:0"><time>5 h 30</time><b>Réveil</b><span>Café chaud, la savane encore bleue.</span></li><li class="op-rv" style="--d:90ms;--t:1"><time>6 h</time><b>Sortie en 4×4</b><span>Les animaux sont actifs à la fraîche.</span></li><li class="op-rv" style="--d:180ms;--t:2"><time>10 h</time><b>Retour au camp</b><span>Brunch et récit des observations.</span></li><li class="op-rv" style="--d:270ms;--t:3"><time>13 h</time><b>Sieste</b><span>La chaleur impose son rythme.</span></li><li class="op-rv" style="--d:360ms;--t:4"><time>16 h</time><b>Deuxième sortie</b><span>Points d’eau et lumière dorée.</span></li><li class="op-rv" style="--d:450ms;--t:5"><time>19 h</time><b>Dîner au feu</b><span>Les bruits de la nuit en fond.</span></li></ol></div></section><script>(function(){var e=document.querySelectorAll(".op-rv");if(!("IntersectionObserver" in window)){e.forEach(function(x){x.classList.add("on")});return}var o=new IntersectionObserver(function(n){n.forEach(function(x){if(x.isIntersecting){x.target.classList.add("on");o.unobserve(x.target)}})},{threshold:.1});e.forEach(function(x){o.observe(x)})})();</script>


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

@verbatim<style>.brf{background:#efe6d2;padding:clamp(4rem,8vw,6rem) 1.25rem} .brf-in{max-width:1180px;margin:0 auto} .brf h2{font-family:"Zilla Slab",serif!important;font-size:clamp(2.2rem,4vw,3.2rem)!important;color:#26402e!important;margin:0 0 2.4rem!important;text-align:left!important} .brf-g{display:grid;grid-template-columns:1fr 1fr;gap:1.6rem} .br-f{display:grid;grid-template-columns:42% 1fr;background:#fffaf0;border:1px solid #d6c49c;box-shadow:6px 6px 0 #c8a165;transform:rotate(-.4deg)} .br-f:nth-child(even){transform:rotate(.5deg)} .br-f figure{margin:0;padding:10px;background:#fffaf0}.br-f img{width:100%;height:100%;min-height:220px;object-fit:cover;display:block;filter:sepia(.15)} .br-f>div{padding:1.4rem 1.4rem 1.2rem 0} .br-f small{font-family:"Caveat",cursive;font-size:1.3rem;color:#b07a3a} .br-f h3{font-family:"Zilla Slab",serif!important;font-size:1.6rem!important;color:#26402e!important;margin:.1rem 0 .8rem!important} .br-f dl{margin:0;display:grid;gap:.5rem} .br-f dt{font-size:.66rem;font-weight:800;letter-spacing:.16em;text-transform:uppercase;color:#4c6b3c} .br-f dd{margin:.1rem 0 0;font-size:.9rem;line-height:1.5;color:#3d3426} .brf-c{margin-top:3rem;padding:1.8rem 2rem;background:#26402e;color:#efe6d2;display:flex;align-items:center;gap:1rem;flex-wrap:wrap} .brf-c p{margin:0 auto 0 0;font-family:"Zilla Slab",serif;font-size:1.5rem} .brf-c a{padding:.85rem 1.4rem;font-weight:700;font-size:.9rem;border-radius:3px} .brf-c .a1{background:#e6cc94;color:#26402e!important}.brf-c .a2{border:1px solid rgba(239,230,210,.5);color:#efe6d2!important} @media (max-width:900px){.brf-g{grid-template-columns:1fr}}@media (max-width:520px){.br-f{grid-template-columns:1fr}.br-f>div{padding:0 1.2rem 1.2rem}}</style><section class="op brf"><div class="brf-in"><h2 class="op-rv">Fiches de terrain</h2><div class="brf-g"><article class="br-f op-rv" style="--d:0ms"><figure><img src="/assets/img/grand-nord-hero-1200x600.webp" alt="Pendjari · W" loading="lazy" decoding="async"></figure><div><small>Fiche 01</small><h3>Pendjari · W</h3><dl><dt>Milieu</dt><dd>Savane, galeries forestières</dd><dt>On y voit</dt><dd>Éléphants, buffles, antilopes, hippopotames ; lions avec de la chance.</dd><dt>Meilleure saison</dt><dd>Fin de saison sèche (février-avril).</dd></dl></div></article><article class="br-f op-rv" style="--d:100ms"><figure><img src="/assets/img/lac-aheme-640x480.webp" alt="Lac Ahémé" loading="lazy" decoding="async"></figure><div><small>Fiche 02</small><h3>Lac Ahémé</h3><dl><dt>Milieu</dt><dd>Lac, villages de pêcheurs</dd><dt>On y voit</dt><dd>Pêche traditionnelle, sources thermales de Possotomè.</dd><dt>Meilleure saison</dt><dd>Toute l’année, idéal de novembre à mars.</dd></dl></div></article><article class="br-f op-rv" style="--d:200ms"><figure><img src="/assets/img/mangrove-640x480.webp" alt="Mono · Bouche du Roy" loading="lazy" decoding="async"></figure><div><small>Fiche 03</small><h3>Mono · Bouche du Roy</h3><dl><dt>Milieu</dt><dd>Mangrove, lagune, océan</dd><dt>On y voit</dt><dd>Oiseaux d’eau, pirogues, villages de pêcheurs.</dd><dt>Meilleure saison</dt><dd>Saison sèche, pour les sorties en pirogue.</dd></dl></div></article><article class="br-f op-rv" style="--d:300ms"><figure><img src="/assets/img/tata-somba-640x480.webp" alt="Atacora" loading="lazy" decoding="async"></figure><div><small>Fiche 04</small><h3>Atacora</h3><dl><dt>Milieu</dt><dd>Montagnes, villages bètammaribè</dd><dt>On y voit</dt><dd>Tata somba, chutes de Kota et de Tanougou.</dd><dt>Meilleure saison</dt><dd>D’octobre à décembre pour les chutes.</dd></dl></div></article></div><div class="brf-c op-rv"><p>Lodge, 4×4, guide agréé, entrées du parc : on s’occupe de tout.</p><a class="a1" href="https://dahomeydiscovery.com" target="_blank" rel="noopener">Organiser mon safari</a><a class="a2" href="https://wa.me/2290154292929?text=Bonjour%2C%20je%20pr%C3%A9pare%20un%20s%C3%A9jour%20au%20B%C3%A9nin%20(via%20Bonne%20Arriv%C3%A9e)." target="_blank" rel="noopener">WhatsApp</a></div></div></section>@endverbatim
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
@verbatim<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&display=swap"><link rel="stylesheet" href="/assets/ba-org.css?v=20261119c"><script src="/assets/ba-org.js?v=20261119c" defer></script>@endverbatim
@endsection
