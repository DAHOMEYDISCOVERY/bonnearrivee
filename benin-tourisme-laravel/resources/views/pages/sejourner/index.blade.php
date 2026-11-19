{{-- Organisez votre séjour — refonte 2026 : hero, chapô, 3 grandes cards de
     choix (Hébergements / Restauration / Infos pratiques), aggregator
     Dahomey Discovery, 3 blocs éditoriaux, CTA la vie s'émerveille, écosystème. --}}
@extends('layouts.app')

@section('bodyClass', 'page menus-v2 prod gabarit-basic_page benin-hotels-page')
@section('mainAttributes')role="main" class="content-type-page basic_page" id="main-content"@endsection

@section('headGlobals')@verbatim<script>window.globals={"ancestors":{"chapter1":"Organisez","chapter2":null,"chapter3":null},"context":"website","current_lang":"fr","current_locale":"fr","current_locale_region":"fr_FR","env":"prod","languages":["fr","nl"],"page_type":"basic_page","post_id":796,"post_title":"Organisez","post_type":"page","site_key":"benin","tags":{"places":[],"seasons":[],"themes":[],"targets":[]},"woody_options_pages":{"search_url":2043,"weather_url":0},"woody_timezone":"Europe/Paris","search":{"api":{"uri":"","args":{"size":15,"threshold":0.5}}},"sheet_bordereaux_with_dates":[],"short_date_bordereaux":["FMA","ASC"],"tmaps":{"url_api":"","url_api_lazy":""},"hawwwai":{"sheet_options":{"nearby_distance_max":5000}}}</script>@endverbatim@endsection
@section('headSchema')@verbatim<script type="application/ld+json">{"@context":"https://schema.org","@type":"CollectionPage","name":"Organisez votre séjour | Bonne Arrivée","url":"https://www.bonnearrivee.bj/sejourner","description":"Hébergements, restauration, infos pratiques — tout ce qu'il faut régler avant de partir au Bénin."}</script>@endverbatim@endsection
@section('headImageOg')@verbatim<meta property="og:image" content="/assets/img/hotel-1920x960.webp"/>@endverbatim@endsection

@section('content')
@verbatim
<div class="content-wrapper no-terms">

    <div class="emerv-hero">
        <img class="emerv-hero__img"
             src="/assets/img/hotel-1920x960.webp"
             srcset="/assets/img/hotel-640x320.webp 640w, /assets/img/hotel-1200x600.webp 1200w, /assets/img/hotel-1920x960.webp 1920w"
             sizes="100vw"
             alt="Séjour au Bénin"
             fetchpriority="high">
        <div class="emerv-hero__overlay">
            <nav class="emerv-hero__breadcrumb" aria-label="Fil d'ariane">
                <ol>
                    <li><a href="/">Accueil</a></li>
                    <li aria-hidden="true">/</li>
                    <li aria-current="page">Organisez</li>
                </ol>
            </nav>
            <div class="emerv-hero__center">
                <p class="emerv-hero__eyebrow">Préparer · Réserver · Partir</p>
                <h1 class="emerv-hero__title">Organisez votre séjour</h1>
                <p class="emerv-hero__lead">Où dormir, où manger, comment circuler, quand venir, quelles formalités. Tout ce qu'il faut régler avant de partir — et savoir une fois sur place.</p>
            </div>
        </div>
    </div>

    <section class="benin-page-chapo">
        <p><strong>Hôtels d'affaires</strong> à Cotonou, <strong>maisons d'hôtes</strong> sur la côte, <strong>écolodges</strong> près des parcs, nuit en <strong>tata</strong> dans l'Atacora. L'offre existe mais se concentre au sud — dans le nord, on réserve à l'avance.</p>
    </section>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Caveat:wght@500;700&family=Fraunces:opsz,wght@9..144,600;9..144,700&display=swap"><script>document.documentElement.classList.add("op-carnet")</script><style>html.op-carnet{--bao-vert:#2b2118;--bao-vert2:#b8442c;--bao-or:#b8442c;--bao-or2:#e8c27a;--bao-sable:#f3e9d6;--bao-serif:"Fraunces",Georgia,serif}</style><style>.op *{box-sizing:border-box} .op h2,.op h3{text-transform:none!important;letter-spacing:0!important} .op h2::before,.op h2::after{display:none!important} .op-rv{opacity:0;transform:translateY(24px);transition:opacity .9s ease var(--d,0ms),transform .9s cubic-bezier(.2,.8,.2,1) var(--d,0ms)} .op-rv.on{opacity:1;transform:none} .op a{text-decoration:none!important} @media (prefers-reduced-motion:reduce){.op-rv{opacity:1;transform:none;transition:none}}</style><style>.cr{position:relative;background:#efe3cc url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='120' height='120'%3E%3Cfilter id='n'%3E%3CfeTurbulence baseFrequency='.9' numOctaves='2'/%3E%3CfeColorMatrix values='0 0 0 0 .4 0 0 0 0 .3 0 0 0 0 .2 0 0 0 .06 0'/%3E%3C/filter%3E%3Crect width='120' height='120' filter='url(%23n)'/%3E%3C/svg%3E");padding:clamp(4rem,8vw,7rem) 1.25rem;color:#2b2118;overflow:hidden} .cr-in{max-width:1180px;margin:0 auto} .cr-head{display:grid;grid-template-columns:1.1fr .9fr;gap:3rem;align-items:end;margin-bottom:3.5rem} .cr-head h2{font-family:"Fraunces",serif!important;font-size:clamp(2.6rem,5.5vw,4.6rem)!important;line-height:.98!important;color:#2b2118!important;margin:0!important} .cr-head h2 em{font-family:"Caveat",cursive;font-style:normal;color:#b8442c;font-size:1.1em} .cr-note{font-family:"Caveat",cursive;font-size:1.65rem;line-height:1.25;color:#3d3024;transform:rotate(-1.5deg);border-left:3px solid #b8442c;padding-left:1.2rem} .cr-route{position:relative;display:grid;grid-template-columns:repeat(5,1fr);gap:1.2rem;align-items:start} .cr-route::before{content:"";position:absolute;left:4%;right:4%;top:118px;border-top:3px dashed #b8442c;opacity:.55} .cr-stop{position:relative;display:block;color:#2b2118!important;transform:rotate(var(--r));transition:transform .5s cubic-bezier(.2,.8,.2,1)} .cr-stop:nth-child(even){margin-top:4.5rem} .cr-stop:hover{transform:rotate(0) translateY(-8px) scale(1.03);z-index:2} .cr-stop figure{margin:0;background:#fff;padding:10px 10px 0;box-shadow:0 22px 40px -22px rgba(43,33,24,.6)} .cr-stop img{width:100%;aspect-ratio:1;object-fit:cover;display:block} .cr-stop figcaption{font-family:"Caveat",cursive;font-size:1.45rem;line-height:1.1;text-align:center;padding:.55rem .2rem .7rem;color:#2b2118} .cr-pin{position:absolute;left:50%;top:-12px;width:70px;height:24px;margin-left:-35px;background:rgba(232,194,122,.75);transform:rotate(-4deg);z-index:1} .cr-stop p{margin:.8rem .3rem 0;font-size:.86rem;line-height:1.45} .cr-stop p b{display:block;font-size:.66rem;letter-spacing:.2em;text-transform:uppercase;color:#b8442c} .cr-nord{position:absolute;right:1.25rem;top:-2.4rem;font-family:"Caveat",cursive;font-size:1.4rem;color:#b8442c} .cr-tabs{display:grid;grid-template-columns:repeat(4,1fr);gap:0;margin-top:5rem;border-radius:4px;overflow:hidden;box-shadow:0 30px 60px -40px rgba(43,33,24,.7)} .cr-tab{position:relative;display:flex;flex-direction:column;gap:.35rem;padding:2rem 1.5rem 1.8rem;background:var(--c);color:#fff!important;min-height:210px;transition:filter .3s} .cr-tab:hover{filter:brightness(1.1)} .cr-tab span{font-family:"Caveat",cursive;font-size:2.4rem;line-height:1;opacity:.75} .cr-tab b{font-family:"Fraunces",serif;font-size:1.6rem;line-height:1.1;margin-top:auto} .cr-tab i{font-style:normal;font-size:.85rem;opacity:.85} .cr-tab::after{content:"→";position:absolute;right:1.3rem;top:1.6rem;font-size:1.3rem} @media (max-width:980px){.cr-head{grid-template-columns:1fr}.cr-route{grid-template-columns:repeat(2,1fr)}.cr-route::before{display:none}.cr-stop:nth-child(even){margin-top:2rem}.cr-tabs{grid-template-columns:1fr 1fr}} @media (max-width:520px){.cr-route{grid-template-columns:1fr 1fr;gap:.8rem}.cr-tabs{grid-template-columns:1fr}.cr-tab{min-height:150px}}</style><section class="op cr"><div class="cr-in"><div class="cr-head op-rv"><h2>Du golfe de Guinée <em>jusqu’aux</em> collines du Nord</h2><p class="cr-note">Cinq étapes pour traverser le Bénin du sud au nord. Prévoyez 10 à 14 jours pour tout voir sans courir.</p></div><div class="cr-route" style="position:relative"><span class="cr-nord">vers le nord →</span><a class="cr-stop op-rv" style="--d:0ms;--r:-3deg" href="/sejourner/hebergements/hotels"><span class="cr-pin"></span><figure><img src="/assets/img/cotonou-640x480.webp" alt="Cotonou" loading="lazy" decoding="async"><figcaption>Cotonou</figcaption></figure><p><b>Étape 1</b>Arrivée, marchés, premières adresses</p></a><a class="cr-stop op-rv" style="--d:120ms;--r:2.5deg" href="/explorez/le-littoral-et-les-lacs"><span class="cr-pin"></span><figure><img src="/assets/img/plage-grand-popo-640x480.webp" alt="Ouidah · Grand-Popo" loading="lazy" decoding="async"><figcaption>Ouidah · Grand-Popo</figcaption></figure><p><b>Étape 2</b>La mémoire et l’océan</p></a><a class="cr-stop op-rv" style="--d:240ms;--r:-2deg" href="/activites/sejour-sur-le-lac"><span class="cr-pin"></span><figure><img src="/assets/img/ganvie-640x480.webp" alt="Ganvié · lac Ahémé" loading="lazy" decoding="async"><figcaption>Ganvié · lac Ahémé</figcaption></figure><p><b>Étape 3</b>Une nuit sur l’eau</p></a><a class="cr-stop op-rv" style="--d:360ms;--r:3deg" href="/explorez/terre-de-traditions/les-palais-royaux-dabomey"><span class="cr-pin"></span><figure><img src="/assets/img/abomey-palais-640x480.webp" alt="Abomey · Dassa" loading="lazy" decoding="async"><figcaption>Abomey · Dassa</figcaption></figure><p><b>Étape 4</b>Rois et collines</p></a><a class="cr-stop op-rv" style="--d:480ms;--r:-2.5deg" href="/sejourner/hebergements/gites-et-lodges"><span class="cr-pin"></span><figure><img src="/assets/img/tata-somba-640x480.webp" alt="Natitingou · Pendjari" loading="lazy" decoding="async"><figcaption>Natitingou · Pendjari</figcaption></figure><p><b>Étape 5</b>Tata somba et safari</p></a></div><div class="cr-tabs"><a class="cr-tab op-rv" style="--c:#e8b13a;--d:0ms" href="/sejourner/toutes-les-infos-pratiques/quand-partir"><span>01</span><b>Quand partir</b><i>Saisons, pluies, grandes fêtes</i></a><a class="cr-tab op-rv" style="--c:#b8442c;--d:90ms" href="/sejourner/toutes-les-infos-pratiques/formalites-et-visa"><span>02</span><b>Formalités</b><i>e-Visa, passeport, fièvre jaune</i></a><a class="cr-tab op-rv" style="--c:#2f6b4f;--d:180ms" href="/sejourner/hebergements"><span>03</span><b>Où dormir</b><i>Hôtels, chambres d’hôtes, lodges</i></a><a class="cr-tab op-rv" style="--c:#2e4a7d;--d:270ms" href="/sejourner/toutes-les-infos-pratiques/se-deplacer"><span>04</span><b>Se déplacer</b><i>Zémidjan, taxis, chauffeur</i></a></div></div></section><script>(function(){function go(){var e=document.querySelectorAll(".op-rv:not(.on)");if(!("IntersectionObserver" in window)){e.forEach(function(x){x.classList.add("on")});return}var o=new IntersectionObserver(function(n){n.forEach(function(x){if(x.isIntersecting){x.target.classList.add("on");o.unobserve(x.target)}})},{threshold:.1});e.forEach(function(x){o.observe(x)})}if(document.readyState==="loading")document.addEventListener("DOMContentLoaded",go);else go()})();</script>


    <div class="page-section">
        <section class="section padd-top-md padd-bottom-md">
            <article class="section-layout-wrapper grid-container">
                <div class="benin-heb-choice">
                    <a class="benin-heb-choice__card" href="/sejourner/hebergements">
                        <img class="benin-heb-choice__img" src="/assets/img/hotel-640x480.webp" alt="Hébergements au Bénin" loading="lazy" decoding="async">
                        <span class="benin-heb-choice__source">3 rubriques</span>
                        <div class="benin-heb-choice__body">
                            <h2>Hébergements</h2>
                            <p>Hôtels, gîtes et lodges, chambres d'hôtes. Notre <strong>sélection</strong> pour chaque type de séjour et chaque budget.</p>
                            <span class="benin-heb-choice__cta">
                                Découvrir <span aria-hidden="true">→</span>
                            </span>
                        </div>
                    </a>

                    <a class="benin-heb-choice__card" href="/sejourner/restauration">
                        <img class="benin-heb-choice__img" src="/assets/img/marche-640x480.webp" alt="Restauration au Bénin" loading="lazy" decoding="async">
                        <span class="benin-heb-choice__source">Cuisine du terroir</span>
                        <div class="benin-heb-choice__body">
                            <h2>Restauration</h2>
                            <p>Restaurants de Cotonou, maquis, traiteurs de mariages. Où <strong>bien manger</strong> le vrai Bénin — plats emblématiques et adresses de confiance.</p>
                            <span class="benin-heb-choice__cta">
                                Découvrir <span aria-hidden="true">→</span>
                            </span>
                        </div>
                    </a>

                    <a class="benin-heb-choice__card" href="/sejourner/toutes-les-infos-pratiques">
                        <img class="benin-heb-choice__img" src="/assets/img/paysage-640x480.webp" alt="Infos pratiques Bénin" loading="lazy" decoding="async">
                        <span class="benin-heb-choice__source">14 fiches</span>
                        <div class="benin-heb-choice__body">
                            <h2>Infos pratiques</h2>
                            <p>Comment venir, formalités, santé, monnaie, sécurité, quand partir. Toutes les <strong>réponses</strong> avant votre voyage.</p>
                            <span class="benin-heb-choice__cta">
                                Découvrir <span aria-hidden="true">→</span>
                            </span>
                        </div>
                    </a>
                </div>
            </article>
        </section>
    </div>
@endverbatim

@verbatim<style>.crs{background:#2b2118;color:#f3e9d6;padding:clamp(3.5rem,7vw,5.5rem) 1.25rem} .crs-in{max-width:1080px;margin:0 auto;display:grid;grid-template-columns:auto 1fr;gap:3rem;align-items:center} .crs-stamp{width:190px;height:190px;border-radius:50%;border:3px solid #e8c27a;display:grid;place-items:center;text-align:center;color:#e8c27a;transform:rotate(-12deg);font-family:"Fraunces",serif;font-size:1rem;line-height:1.2;letter-spacing:.12em;text-transform:uppercase;box-shadow:inset 0 0 0 8px #2b2118,inset 0 0 0 10px #e8c27a} .crs-stamp b{display:block;font-family:"Caveat",cursive;text-transform:none;letter-spacing:0;font-size:2rem} .crs h2{font-family:"Fraunces",serif!important;font-size:clamp(2rem,3.6vw,2.9rem)!important;color:#fff!important;margin:0 0 .8rem!important;text-align:left!important} .crs p{margin:0 0 1.5rem;color:rgba(243,233,214,.82);line-height:1.7} .crs a{display:inline-block;margin:0 .6rem .6rem 0;padding:.85rem 1.4rem;border-radius:4px;font-weight:700;font-size:.9rem} .crs a.a1{background:#e8c27a;color:#2b2118!important}.crs a.a2{border:1.5px solid rgba(243,233,214,.6);color:#f3e9d6!important} @media (max-width:700px){.crs-in{grid-template-columns:1fr}.crs-stamp{width:150px;height:150px}}</style><section class="op crs"><div class="crs-in op-rv"><div class="crs-stamp"><div>Itinéraire<b>sur mesure</b>Bénin</div></div><div><h2>Votre carnet, écrit avec vous</h2><p>Dites-nous vos dates et vos envies : l’agence réceptive qui édite Bonne Arrivée trace votre itinéraire, réserve les étapes et vous accueille à Cotonou.</p><a class="a1" href="https://dahomeydiscovery.com" target="_blank" rel="noopener">Demander un itinéraire →</a><a class="a2" href="https://wa.me/2290154292929?text=Bonjour%2C%20je%20pr%C3%A9pare%20un%20s%C3%A9jour%20au%20B%C3%A9nin%20(via%20Bonne%20Arriv%C3%A9e)." target="_blank" rel="noopener">WhatsApp</a></div></div></section>@endverbatim
    <x-aggregator.accommodations-block
        title="Notre sélection d'hébergements"
        pretitle="Réserver avec Dahomey Discovery"
        :limit="6"
        ctaHref="https://dahomeydiscovery.com/hebergements/"
        ctaLabel="Voir toute la sélection sur dahomeydiscovery.com" />

@verbatim
    <section class="benin-page-blocks" aria-label="Repères pour préparer son séjour">
        <article class="benin-page-block">
            <span class="benin-page-block__num">01</span>
            <h2 class="benin-page-block__title">Quand partir</h2>
            <p>Le Bénin se visite <strong>toute l'année</strong>, mais la meilleure fenêtre reste la <strong>saison sèche</strong> (novembre à mars). Les températures sont supportables, la végétation basse laisse voir les animaux dans les parcs, les pistes sont carrossables, et les grandes fêtes (Vodun Days le 10 janvier, La Gaani en avril) tombent dans ce créneau.</p>
            <p>De juin à octobre, la saison des pluies transforme les pistes du nord en boue — la Pendjari est fermée. Le sud reste accessible mais les averses sont fréquentes.</p>
        </article>

        <article class="benin-page-block benin-page-block--highlight">
            <span class="benin-page-block__num">02</span>
            <h2 class="benin-page-block__title">Combien de temps prévoir</h2>
            <p>Pour un <strong>premier voyage complet</strong>, comptez <strong>10 à 14 jours</strong> pour enchaîner sud, centre et Pendjari sans se presser. Un séjour <strong>court de 4-5 jours</strong> permet de couvrir Cotonou, Ganvié, Ouidah et Abomey.</p>
            <p>La <strong>route est un facteur clé</strong> : Cotonou-Natitingou fait 620 km sur une bonne journée. Prévoyez des étapes intermédiaires (Dassa, Parakou) plutôt qu'un trajet d'une traite.</p>
        </article>

        <article class="benin-page-block">
            <span class="benin-page-block__num">03</span>
            <h2 class="benin-page-block__title">Budget indicatif</h2>
            <p><strong>Hébergement :</strong> 40 000 F CFA (~60 €) en guest house, 80-180 000 F CFA (~120-275 €) en hôtel 3-4 étoiles à Cotonou, petit-déjeuner inclus.</p>
            <p><strong>Repas :</strong> 3 à 10 € dans un maquis, 15 à 30 € dans un restaurant. Les fruits, jus locaux et rue sont très abordables.</p>
            <p><strong>Guide + véhicule + chauffeur :</strong> à partir de <strong>100 € par jour</strong> en tout inclus pour 2 personnes chez Dahomey Discovery. Le confort d'un pro qui parle la région ne se remplace pas.</p>
        </article>
    </section>

    <section class="benin-emerv-cta">
        <div>
            <p class="benin-emerv-cta__eyebrow">Sélection éditoriale</p>
            <h2>La vie s'émerveille au Bénin</h2>
            <p>Vingt-neuf lieux en une seule vue — plages, palais royaux, cités lacustres, cascades, cités vodun. Filtrez par ce qui vous parle et composez votre itinéraire.</p>
            <a href="/explorez/la-vie-semerveille-au-benin">
                Voir la sélection complète <span aria-hidden="true">→</span>
            </a>
        </div>
        <div class="benin-emerv-cta__images">
            <img src="/assets/img/hotel-640x480.webp" alt="Hébergements">
            <img src="/assets/img/marche-640x480.webp" alt="Cuisine béninoise">
            <img src="/assets/img/ganvie-640x480.webp" alt="Ganvié">
            <img src="/assets/img/aeroport-640x480.webp" alt="Comment venir">
        </div>
    </section>
</div>
@endverbatim

<x-aggregator.ecosysteme-block />
@verbatim<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&display=swap"><link rel="stylesheet" href="/assets/ba-org.css?v=20261119d"><script src="/assets/ba-org.js?v=20261119d" defer></script>@endverbatim
@endsection
