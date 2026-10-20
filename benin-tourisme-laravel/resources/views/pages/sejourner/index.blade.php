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
@endsection
