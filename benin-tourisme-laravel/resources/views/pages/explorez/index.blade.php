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

    <section class="exz" aria-label="Le Bénin du sud au nord">
        <style>
        .exz{--sud:#0f6e7a;--centre:#2f7d3b;--nord:#c0782b;--ink:#13241d;--soft:#4f5d56;--fond:#f7f2e8;max-width:1220px;margin:clamp(2.5rem,6vw,4.5rem) auto;padding:0 clamp(1.15rem,4vw,2.8rem);font-family:Montserrat,Helvetica,Arial,sans-serif;color:var(--ink)}
        .exz *{box-sizing:border-box}
        .exz-k{display:block;margin:0 0 .7rem;font-size:.74rem;font-weight:800;letter-spacing:.24em;text-transform:uppercase;color:var(--nord)}
        .exz-h{margin:0 0 1.6rem!important;font-size:clamp(1.8rem,3.8vw,2.8rem)!important;line-height:1.08!important;font-weight:800!important;text-transform:none!important;color:var(--ink)!important;max-width:820px}
        .exz-route{position:relative;display:grid;grid-template-columns:repeat(3,1fr);border-radius:28px;overflow:hidden;box-shadow:0 34px 70px -50px rgba(19,36,29,.75)}
        .exz-zone{position:relative;padding:clamp(1.3rem,3vw,2rem) clamp(1.1rem,2.4vw,1.7rem) clamp(1.5rem,3vw,2.2rem);color:#fff;min-height:250px;display:flex;flex-direction:column}
        .exz-zone--sud{background:linear-gradient(160deg,#127f8c,#0b5560)}
        .exz-zone--centre{background:linear-gradient(160deg,#3a8f45,#22612c)}
        .exz-zone--nord{background:linear-gradient(160deg,#d08a3a,#9c5a1d)}
        .exz-zone b{display:block;font-size:.72rem;letter-spacing:.22em;text-transform:uppercase;opacity:.85}
        .exz-zone strong{display:block;margin:.35rem 0 .5rem;font-size:clamp(1.35rem,2.4vw,1.75rem);line-height:1.1}
        .exz-zone p{margin:0 0 1.1rem!important;font-size:.95rem!important;line-height:1.55!important;color:rgba(255,255,255,.88)!important}
        .exz-zone ul{list-style:none;margin:auto 0 0!important;padding:0!important;display:flex;flex-wrap:wrap;gap:.45rem}
        .exz-zone li{margin:0!important}
        .exz-zone a{display:inline-flex;align-items:center;gap:.4rem;padding:.48rem .9rem;border-radius:999px;background:rgba(255,255,255,.16);color:#fff!important;font-size:.86rem;font-weight:700;text-decoration:none!important;transition:background .25s,color .25s}
        .exz-zone a:hover,.exz-zone a:focus-visible{background:#fff;color:var(--ink)!important}
        .exz-zone svg.exz-ic{position:absolute;right:1.1rem;top:1.1rem;width:52px;height:52px;opacity:.28}
        .exz-axe{display:flex;align-items:center;gap:.8rem;margin:1.1rem .4rem 0;font-size:.8rem;font-weight:700;color:var(--soft)}
        .exz-axe i{flex:1;height:6px;border-radius:99px;background:linear-gradient(90deg,var(--sud),var(--centre),var(--nord))}
        .exz-chiffres{display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-top:clamp(1.6rem,3vw,2.4rem)}
        .exz-chiffres div{padding:1.15rem 1.2rem;border-radius:20px;background:var(--fond)}
        .exz-chiffres em{display:block;font-style:normal;font-size:clamp(1.7rem,3vw,2.3rem);font-weight:800;line-height:1;color:var(--ink)}
        .exz-chiffres span{display:block;margin-top:.45rem;font-size:.86rem;line-height:1.45;color:var(--soft)}
        .exz-rv{opacity:0;transform:translateY(18px);transition:opacity .7s ease,transform .7s cubic-bezier(.2,.8,.2,1);transition-delay:var(--dl,0ms)}
        .exz-rv.is-in{opacity:1;transform:none}
        @media (max-width:900px){.exz-route{grid-template-columns:1fr}.exz-zone{min-height:0}.exz-axe{display:none}.exz-chiffres{grid-template-columns:1fr 1fr}}
        @media (prefers-reduced-motion:reduce){.exz-rv{opacity:1;transform:none;transition:none}}
        /* Cartes des cinq régions : numéro, arche, survol */
        main .benin-regions__grid{counter-reset:exzr}
        main .benin-regions__card{counter-increment:exzr;position:relative;padding:0 0 1.1rem;border-radius:26px;background:#fff;box-shadow:0 22px 44px -36px rgba(19,36,29,.65);text-decoration:none!important;transition:transform .35s cubic-bezier(.2,.8,.2,1),box-shadow .35s}
        main .benin-regions__card img{border-radius:140px 140px 18px 18px!important;aspect-ratio:4/5;object-fit:cover;width:100%;transition:transform .6s}
        main .benin-regions__card::before{content:counter(exzr,decimal-leading-zero);position:absolute;z-index:2;top:.8rem;left:50%;transform:translateX(-50%);display:grid;place-items:center;width:2.4rem;height:2.4rem;border-radius:50%;background:#f8d55a;color:#13241d;font:800 .8rem Montserrat,Helvetica,Arial,sans-serif;box-shadow:0 6px 16px -6px rgba(0,0,0,.45)}
        main .benin-regions__card:hover,main .benin-regions__card:focus-visible{transform:translateY(-6px);box-shadow:0 34px 60px -38px rgba(19,36,29,.8)}
        main .benin-regions__card h3,main .benin-regions__card p{padding:0 .7rem}
        @media (max-width:640px){main .benin-regions__card img{aspect-ratio:4/3;border-radius:22px 22px 14px 14px!important}}
        </style>
        <span class="exz-k">Du golfe de Guinée au fleuve Niger</span>
        <h2 class="exz-h">Trois Bénin sur un même axe</h2>
        <div class="exz-route">
            <div class="exz-zone exz-zone--sud exz-rv">
                <svg class="exz-ic" viewBox="0 0 48 48" aria-hidden="true"><path d="M4 30c5-4 9-4 14 0s9 4 14 0 9-4 12-1M4 38c5-4 9-4 14 0s9 4 14 0 9-4 12-1" fill="none" stroke="#fff" stroke-width="3" stroke-linecap="round"/><circle cx="34" cy="14" r="6" fill="#fff"/></svg>
                <b>Le sud</b><strong>Lagunes et océan</strong>
                <p>Cordon de sable, lacs et villages sur pilotis, mémoire de la traite à Ouidah.</p>
                <ul><li><a href="/explorez/les-cinq-benin/la-cote-memorielle">Côte mémorielle →</a></li><li><a href="/explorez/les-cinq-benin/le-littoral-atlantique">Littoral atlantique →</a></li></ul>
            </div>
            <div class="exz-zone exz-zone--centre exz-rv" style="--dl:110ms">
                <svg class="exz-ic" viewBox="0 0 48 48" aria-hidden="true"><path d="M2 40 14 20l8 10 8-16 16 26z" fill="#fff"/></svg>
                <b>Le centre</b><strong>Royaumes et collines</strong>
                <p>Palais d'Abomey, Porto-Novo la capitale, puis les collines sacrées de Dassa.</p>
                <ul><li><a href="/explorez/les-cinq-benin/le-plateau-royal">Plateau royal →</a></li><li><a href="/explorez/les-cinq-benin/le-centre-mystique">Centre mystique →</a></li></ul>
            </div>
            <div class="exz-zone exz-zone--nord exz-rv" style="--dl:220ms">
                <svg class="exz-ic" viewBox="0 0 48 48" aria-hidden="true"><circle cx="24" cy="24" r="8" fill="#fff"/><path d="M24 4v6M24 38v6M4 24h6M38 24h6M10 10l4 4M34 34l4 4M10 38l4-4M34 14l4-4" stroke="#fff" stroke-width="3" stroke-linecap="round"/></svg>
                <b>Le nord</b><strong>Savane et Atacora</strong>
                <p>Tata somba, chutes et parcs nationaux de la Pendjari et du W.</p>
                <ul><li><a href="/explorez/les-cinq-benin/le-grand-nord">Le grand Nord →</a></li><li><a href="/explorez/naturellement-sauvage">Nature &amp; parcs →</a></li></ul>
            </div>
        </div>
        <div class="exz-axe" aria-hidden="true"><span>Cotonou</span><i></i><span>Malanville</span></div>
        <div class="exz-chiffres">
            <div class="exz-rv"><em>≈ 700 km</em><span>du nord au sud</span></div>
            <div class="exz-rv" style="--dl:80ms"><em>≈ 125 km</em><span>de côte sur l'Atlantique</span></div>
            <div class="exz-rv" style="--dl:160ms"><em>5</em><span>régions à parcourir, du littoral à l'Atacora</span></div>
            <div class="exz-rv" style="--dl:240ms"><em>2</em><span>parcs nationaux, Pendjari et W, au patrimoine mondial</span></div>
        </div>
        <script>
        (function(){var el=document.querySelectorAll('.exz-rv');if(!('IntersectionObserver' in window)){el.forEach(function(e){e.classList.add('is-in')});return}var io=new IntersectionObserver(function(en){en.forEach(function(e){if(e.isIntersecting){e.target.classList.add('is-in');io.unobserve(e.target)}})},{threshold:.15});el.forEach(function(e){io.observe(e)})})();
        </script>
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
                <img src="/assets/img/explorez-plateau-royal-2026-11-06-640x480.webp" alt="Le plateau royal">
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
            <img src="/assets/img/explorez-terre-de-traditions-2026-11-06-1200x900.webp" alt="Terre de traditions">
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
        pretitle="Avec notre partenaire Dahomey Discovery"
        title="Circuits proposés par notre agence partenaire"
        description="Itinéraires clés en main conçus par notre partenaire Dahomey Discovery — véhicule, chauffeur, guide local et hébergement inclus."
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
