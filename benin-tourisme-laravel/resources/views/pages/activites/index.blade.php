{{-- Vivez le Bénin — refonte 2026 : hero, chapô, mosaïque 4 rubriques activités,
     3 blocs éditoriaux (quand, comment, avec qui), circuits Dahomey Discovery,
     CTA la vie s'émerveille, écosystème. --}}
@extends('layouts.app')

@section('bodyClass', 'page menus-v2 prod gabarit-basic_page benin-hotels-page')
@section('mainAttributes')role="main" class="content-type-page basic_page" id="main-content"@endsection

@section('headGlobals')@verbatim<script>window.globals={"ancestors":{"chapter1":"Vivez","chapter2":null,"chapter3":null},"context":"website","current_lang":"fr","current_locale":"fr","current_locale_region":"fr_FR","env":"prod","languages":["fr","nl"],"page_type":"basic_page","post_id":764,"post_title":"Vivez","post_type":"page","site_key":"benin","tags":{"places":[],"seasons":[],"themes":[],"targets":[]},"woody_options_pages":{"search_url":2043,"weather_url":0},"woody_timezone":"Europe/Paris","search":{"api":{"uri":"","args":{"size":15,"threshold":0.5}}},"sheet_bordereaux_with_dates":[],"short_date_bordereaux":["FMA","ASC"],"tmaps":{"url_api":"","url_api_lazy":""},"hawwwai":{"sheet_options":{"nearby_distance_max":5000}}}</script>@endverbatim@endsection
@section('headSchema')@verbatim<script type="application/ld+json">{"@context":"https://schema.org","@type":"CollectionPage","name":"Vivez le Bénin | Bonne Arrivée","url":"https://www.bonnearrivee.bj/activites","description":"Safari, pirogue, randonnée, mémoire : les grandes façons de vivre le Bénin en immersion."}</script>@endverbatim@endsection
@section('headImageOg')@verbatim<meta property="og:image" content="/assets/img/pendjari-elephant-1920x1440.webp"/>@endverbatim@endsection

@section('content')
@verbatim
<div class="content-wrapper no-terms">

    <div class="emerv-hero">
        <img class="emerv-hero__img"
             src="/assets/img/pendjari-elephant-1920x1440.webp"
             srcset="/assets/img/pendjari-elephant-640x480.webp 640w, /assets/img/pendjari-elephant-1200x900.webp 1200w, /assets/img/pendjari-elephant-1920x1440.webp 1920w"
             sizes="100vw"
             alt="Éléphants dans le parc national de la Pendjari"
             fetchpriority="high">
        <div class="emerv-hero__overlay">
            <nav class="emerv-hero__breadcrumb" aria-label="Fil d'ariane">
                <ol>
                    <li><a href="/">Accueil</a></li>
                    <li aria-hidden="true">/</li>
                    <li aria-current="page">Vivez</li>
                </ol>
            </nav>
            <div class="emerv-hero__center">
                <p class="emerv-hero__eyebrow">Vivre · Pratiquer · Rencontrer</p>
                <h1 class="emerv-hero__title">Vivez le Bénin</h1>
                <p class="emerv-hero__lead">Safari dans la savane du nord, pirogue sur les lacs du sud, marche mémorielle à Ouidah, randonnée dans l'Atacora. Choisissez la façon qui vous ressemble.</p>
            </div>
        </div>
    </div>

    <section class="benin-page-chapo">
        <p><strong>Safari</strong> dans la savane du nord, <strong>pirogue</strong> sur les lacs du sud, <strong>marche mémorielle</strong> à Ouidah, <strong>randonnée</strong> dans l'Atacora. Le Bénin ne se visite pas d'une seule façon — les saisons ne sont pas les mêmes selon ce que vous venez faire.</p>
    </section>

    <section class="benin-littoral" aria-label="Les grandes façons de vivre le Bénin">
        <div class="benin-littoral__head">
            <p class="benin-littoral__eyebrow">Quatre façons de vivre le pays</p>
            <h2 class="benin-littoral__title">Choisir votre expérience</h2>
            <p class="benin-littoral__lead">Chaque rubrique regroupe les activités concrètes que nos guides organisent — dates conseillées, saisons, opérateurs locaux, ce qu'il faut prévoir.</p>
        </div>
        <div class="benin-littoral__grid">

            <a class="benin-littoral__card benin-littoral__card--wide" href="/activites/sur-les-traces-de-lhistoire">
                <img src="/assets/img/ouidah-porte-1200x600.webp" alt="Porte du Non-Retour, Ouidah" loading="lazy" decoding="async">
                <div class="benin-littoral__card-inner">
                    <span class="benin-littoral__cat">Mémoire</span>
                    <h3>Sur les traces de l'Histoire</h3>
                    <p>Ouidah, Abomey, Porto-Novo, Allada. Route de l'Esclave, palais royaux, forêt sacrée — trois siècles à comprendre.</p>
                </div>
            </a>

            <a class="benin-littoral__card" href="/activites/safari-et-randonnee">
                <img src="/assets/img/pendjari-savane-640x480.webp" alt="Safari dans la Pendjari" loading="lazy" decoding="async">
                <div class="benin-littoral__card-inner">
                    <span class="benin-littoral__cat">Sur terre</span>
                    <h3>Safari &amp; randonnée</h3>
                    <p>Pendjari, Parc W, chaîne de l'Atacora. Éléphants, buffles, lions et tata sombas. Saison sèche uniquement.</p>
                </div>
            </a>

            <a class="benin-littoral__card" href="/activites/sejour-sur-le-lac">
                <img src="/assets/img/pirogue-640x480.webp" alt="Pirogue sur le lac Nokoué" loading="lazy" decoding="async">
                <div class="benin-littoral__card-inner">
                    <span class="benin-littoral__cat">Sur l'eau</span>
                    <h3>Séjour sur le lac</h3>
                    <p>Ganvié, lac Nokoué, lac Ahémé, Bouche du Roy. Pirogue, pêche traditionnelle, coucher de soleil.</p>
                </div>
            </a>

            <a class="benin-littoral__card benin-littoral__card--wide" href="/activites/les-operateurs-de-safari">
                <img src="/assets/img/pendjari-elephant-1200x900.webp" alt="Opérateurs de safari au Bénin" loading="lazy" decoding="async">
                <div class="benin-littoral__card-inner">
                    <span class="benin-littoral__cat">Guides &amp; agences</span>
                    <h3>Les opérateurs de safari</h3>
                    <p>Notre sélection d'opérateurs locaux pour la Pendjari et le parc W : accompagnement, transport 4×4, hébergement en campement.</p>
                </div>
            </a>

        </div>
    </section>

    <section class="benin-page-blocks" aria-label="Repères pour organiser son séjour actif">
        <article class="benin-page-block">
            <span class="benin-page-block__num">01</span>
            <h2 class="benin-page-block__title">Quand venir vivre chaque expérience</h2>
            <p>Le <strong>safari dans la Pendjari</strong> ne se pratique que pendant la <strong>saison sèche</strong> (décembre à avril), quand la végétation basse laisse voir les animaux et que les pistes sont carrossables. Hors saison, le parc ferme.</p>
            <p>Les <strong>sorties en pirogue</strong> sur les lacs Nokoué et Ahémé se font toute l'année, avec une préférence pour les mois <strong>secs</strong> (novembre-février). Les <strong>randonnées dans l'Atacora</strong> demandent la même fenêtre, pour éviter les chemins boueux et la chaleur humide.</p>
            <p>La <strong>marche mémorielle à Ouidah</strong> et la visite d'<strong>Abomey</strong> se font toute l'année sans contrainte météo particulière.</p>
        </article>

        <article class="benin-page-block benin-page-block--highlight">
            <span class="benin-page-block__num">02</span>
            <h2 class="benin-page-block__title">Combien de jours prévoir</h2>
            <p><strong>Cotonou &amp; Ganvié :</strong> 2 jours suffisent pour ressentir la capitale économique et faire la sortie en pirogue à Ganvié.</p>
            <p><strong>Route mémorielle sud :</strong> 3 à 4 jours pour Ouidah, Grand-Popo, Abomey et Porto-Novo — l'essentiel des palais et lieux de mémoire.</p>
            <p><strong>Pendjari &amp; Atacora :</strong> 4 à 5 jours minimum depuis Cotonou (route + safari + Tata sombas + chutes de Tanougou).</p>
            <p><strong>Circuit complet :</strong> 10 à 14 jours pour tout enchaîner du sud au nord sans se presser.</p>
        </article>

        <article class="benin-page-block">
            <span class="benin-page-block__num">03</span>
            <h2 class="benin-page-block__title">Y aller avec un guide</h2>
            <p>Un guide francophone local n'est pas un luxe au Bénin — c'est ce qui transforme une visite touristique en <strong>rencontre</strong>. Les palais d'Abomey, la Route de l'Esclave, une cérémonie vodun, un village tata somba : sans médiation, on passe à côté du sens.</p>
            <p>Notre équipe <strong><a href="https://dahomeydiscovery.com" target="_blank" rel="noopener noreferrer">Dahomey Discovery</a></strong> conçoit des circuits sur mesure avec chauffeur, guide et hébergement inclus. Écrivez-nous depuis la <a href="/sejourner/toutes-les-infos-pratiques/nous-contacter">page contact</a>.</p>
        </article>
    </section>
@endverbatim

    <x-aggregator.circuits-block
        pretitle="Réserver avec Dahomey Discovery"
        title="Circuits proposés par notre agence réceptive"
        description="Itinéraires clés en main — véhicule, chauffeur, guide local et hébergement inclus."
        :filters="[]"
        :limit="6" />

@verbatim
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
            <img src="/assets/img/pendjari-elephant-640x480.webp" alt="Pendjari">
            <img src="/assets/img/ouidah-porte-640x480.webp" alt="Ouidah">
            <img src="/assets/img/ganvie-640x480.webp" alt="Ganvié">
            <img src="/assets/img/tata-somba-640x480.webp" alt="Tata somba">
        </div>
    </section>
</div>
@endverbatim

<x-aggregator.ecosysteme-block />
@endsection
