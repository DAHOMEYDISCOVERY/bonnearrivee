{{-- À propos — présentation du portail Bonne Arrivée et de ses partenaires.
     Refonte novembre 2026 (préfixe apo) : héros photo, mission en trois volets illustrés,
     écosystème en cartes de marque, coordonnées en carte de visite. Textes d'origine conservés. --}}
@extends('layouts.app')

@section('bodyClass', 'page menus-v2 prod gabarit-basic_page benin-hotels-page')
@section('mainAttributes')role="main" class="content-type-page basic_page" id="main-content"@endsection

@section('headGlobals')@verbatim<script>window.globals={"ancestors":{"chapter1":"À propos","chapter2":null,"chapter3":null},"context":"website","current_lang":"fr","current_locale":"fr","current_locale_region":"fr_FR","env":"prod","languages":["fr","nl"],"page_type":"basic_page","post_id":9002,"post_title":"À propos","post_type":"page","site_key":"benin","tags":{"places":[],"seasons":[],"themes":[],"targets":[]},"woody_options_pages":{"search_url":2043,"weather_url":0},"woody_timezone":"Europe/Paris","search":{"api":{"uri":"","args":{"size":15,"threshold":0.5}}},"sheet_bordereaux_with_dates":[],"short_date_bordereaux":["FMA","ASC"],"tmaps":{"url_api":"","url_api_lazy":""},"hawwwai":{"sheet_options":{"nearby_distance_max":5000}}}</script>@endverbatim@endsection
@section('headSchema')@verbatim<script type="application/ld+json">{"@context":"https://schema.org","@type":"AboutPage","url":"https://www.bonnearrivee.bj/a-propos","name":"À propos — Bonne Arrivée","description":"Bonne Arrivée, Portail du Tourisme et de l’Attractivité au Bénin : un site d’information, et les partenaires de son écosystème."}</script>@endverbatim@endsection

@section('headImageOg')@verbatim<meta property="og:image" content="/assets/img/cotonou-1920x960.webp"/>@endverbatim@endsection

@section('content')
@verbatim
<style>.content-wrapper>.emerv-hero:has(+.apo) .emerv-hero__overlay{background:linear-gradient(180deg,rgba(6,24,17,.45) 0%,rgba(6,24,17,.35) 45%,rgba(6,24,17,.7) 100%)!important}.content-wrapper>.emerv-hero:has(+.apo) .emerv-hero__lead p{font-size:clamp(1.02rem,1.5vw,1.2rem);color:#fff;max-width:62ch;margin:0 auto}.apo{--vert:#00844d;--vert2:#0b3d2c;--or:#fcd116;--rouge:#e8112d;--ink:#17241d;--soft:#4d5a53;--sable:#f6f2e9;background:#fff;color:var(--ink)}.apo-in{max-width:1180px;margin:0 auto;padding:0 clamp(1.25rem,4vw,2.5rem)}.apo h2{margin:0 0 1rem;font-size:clamp(1.9rem,3.6vw,2.8rem);line-height:1.08;letter-spacing:-.015em;color:var(--vert2)}.apo p{font-size:1.05rem;line-height:1.8;color:var(--soft)}.apo-k{display:block;margin-bottom:.8rem;font-size:.74rem;font-weight:700;letter-spacing:.24em;text-transform:uppercase;color:var(--vert)}.apo-sec{padding:clamp(3.5rem,8vw,6rem) 0}.apo-split{display:grid;grid-template-columns:1.05fr .95fr;gap:clamp(2rem,5vw,4.5rem);align-items:center}.apo-photo{position:relative;border-radius:28px;overflow:hidden;aspect-ratio:4/5;box-shadow:0 30px 60px -36px rgba(11,61,44,.55)}.apo-photo img{width:100%;height:100%;object-fit:cover}.apo-photo figcaption{position:absolute;left:1rem;bottom:1rem;padding:.4rem .75rem;border-radius:99px;background:rgba(255,255,255,.92);font-size:.8rem;font-weight:600;color:var(--vert2)}.apo-mission{background:var(--sable)}.apo-head{max-width:720px;margin-bottom:clamp(2rem,4vw,3rem)}.apo-trio{display:grid;grid-template-columns:repeat(3,1fr);gap:1.2rem;margin:0;padding:0;list-style:none}.apo-trio li{margin:0;border-radius:24px;overflow:hidden;background:#fff;box-shadow:0 1px 0 rgba(16,40,28,.05),0 22px 44px -30px rgba(16,40,28,.45)}.apo-trio img{width:100%;aspect-ratio:4/3;object-fit:cover}.apo-trio div{padding:1.3rem 1.4rem 1.5rem;font-size:1rem;line-height:1.7;color:var(--soft);text-align:justify;hyphens:auto}.apo-trio strong{display:block;margin-bottom:.35rem;font-size:1.35rem;color:var(--vert2);text-align:left}.apo-trio strong::after{content:none}.apo-pas{display:grid;grid-template-columns:auto 1fr;gap:1.2rem;align-items:center;margin-top:clamp(1.8rem,4vw,2.6rem);padding:1.4rem 1.6rem;border-radius:22px;background:var(--vert2);color:#fff}.apo-pas b{display:grid;place-items:center;width:3rem;height:3rem;border-radius:50%;background:var(--or);color:var(--vert2);font-size:1.4rem}.apo-pas p{margin:0;color:rgba(255,255,255,.88)}.apo-pas p strong{color:#fff}.apo-eco-g{display:grid;grid-template-columns:repeat(3,1fr);gap:1.2rem;margin:0 0 1.4rem;padding:0;list-style:none}.apo-eco-g li{margin:0}.apo-eco-g a{display:flex;flex-direction:column;height:100%;padding:1.6rem;border-radius:24px;background:#fff;color:var(--ink)!important;text-decoration:none!important;box-shadow:inset 0 0 0 1px rgba(16,40,28,.08),0 22px 44px -32px rgba(16,40,28,.5);transition:transform .3s,box-shadow .3s}.apo-eco-g a:hover{transform:translateY(-4px);box-shadow:inset 0 0 0 1px var(--c),0 28px 50px -30px rgba(16,40,28,.55)}.apo-eco-g .apo-logo{display:grid;place-items:center;width:4.25rem;height:4.25rem;margin-bottom:1.2rem;border-radius:18px;background:#fff;box-shadow:inset 0 0 0 1px rgba(16,40,28,.1)}.apo-eco-g .apo-logo img{width:78%;height:78%;object-fit:contain}.apo-eco-g strong{display:flex;align-items:center;gap:.5rem;font-size:1.25rem;color:var(--vert2)}.apo-eco-g strong i{width:.6rem;height:.6rem;border-radius:50%;background:var(--c)}.apo-eco-g span.apo-d{margin-top:.6rem;font-size:.97rem;line-height:1.65;color:var(--soft)}.apo-eco-g span.apo-go{margin-top:auto;padding-top:1.2rem;font-size:.9rem;font-weight:700;color:var(--vert)}.apo-visite{position:relative;isolation:isolate;padding:clamp(4rem,9vw,7rem) 0;color:#fff}.apo-visite::before{content:"";position:absolute;inset:0;z-index:-1;background:linear-gradient(90deg,rgba(11,61,44,.95) 0%,rgba(11,61,44,.8) 45%,rgba(11,61,44,.25) 100%),url(/assets/img/zemidjan-1200x600.webp) center/cover}.apo-visite h2{color:#fff}.apo-carte{max-width:520px;padding:clamp(1.6rem,3vw,2.2rem);border-radius:26px;background:rgba(255,255,255,.1);backdrop-filter:blur(6px);box-shadow:inset 0 0 0 1px rgba(255,255,255,.22)}.apo-carte p{margin:0;font-size:1.08rem;line-height:2;color:#fff}.apo-carte a{color:var(--or)!important;font-weight:600}@media (max-width:1000px){.apo-split{grid-template-columns:1fr}.apo-trio,.apo-eco-g{grid-template-columns:1fr}.apo-photo{aspect-ratio:16/11}}</style>

<div class="content-wrapper no-terms">

    <div class="emerv-hero">
        <img class="emerv-hero__img" src="/assets/img/cotonou-1920x960.webp" srcset="/assets/img/cotonou-640x320.webp 640w, /assets/img/cotonou-1200x600.webp 1200w, /assets/img/cotonou-1920x960.webp 1920w" sizes="100vw" alt="Cotonou vue du ciel, autour de la place de l'Étoile rouge" fetchpriority="high">
        <div class="emerv-hero__overlay">
            <nav class="emerv-hero__breadcrumb" aria-label="Fil d'ariane">
                <ol>
                    <li><a href="/">Accueil</a></li>
                    <li aria-hidden="true">/</li>
                    <li aria-current="page">À propos</li>
                </ol>
            </nav>
            <div class="emerv-hero__center">
                <p class="emerv-hero__eyebrow">Portail du Tourisme et de l'Attractivité</p>
                <h1 class="emerv-hero__title page-main-title">Qui sommes-nous ?</h1>
                <div class="emerv-hero__lead page-main-description">
                    <p><strong>Bonne Arrivée</strong> est le Portail du Tourisme et de l'Attractivité au Bénin. C'est un site d'information : il fait connaître le pays, ses destinations, sa culture et ses infos pratiques, et oriente les visiteurs vers les professionnels de son écosystème.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="apo">
        <section class="apo-sec">
            <div class="apo-in apo-split">
                <div>
                    <span class="apo-k">Notre histoire</span>
                    <h2>Un portail né du terroir</h2>
                    <p>Bonne Arrivée est porté par <strong>Mme Zoulfati HASSAN DERBY</strong>, avec une conviction simple : le Bénin mérite d'être raconté par les Béninois eux-mêmes. Depuis Cotonou, la rédaction du portail fait découvrir un Bénin qui va au-delà de la carte postale — vodun vécu, palais royaux d'Abomey, village lacustre de Ganvié, parc de la Pendjari, plages de Grand-Popo — avec des informations vérifiées sur le terrain.</p>
                </div>
                <figure class="apo-photo"><img src="/assets/img/artisanat-640x854.webp" alt="Une vannière au travail, artisanat béninois" loading="lazy" decoding="async" width="640" height="854"><figcaption>Le Bénin raconté de l'intérieur</figcaption></figure>
            </div>
        </section>

        <section class="apo-sec apo-mission">
            <div class="apo-in">
                <div class="apo-head"><span class="apo-k">Notre mission</span><h2>Ce que fait le portail</h2></div>
                <ul class="apo-trio">
                    <li><img src="/assets/img/ganvie-640x480.webp" alt="Ganvié, la cité lacustre" loading="lazy" decoding="async"><div><strong>Informer</strong> — destinations, patrimoine, traditions, nature, agenda des fêtes et infos pratiques (visa, santé, argent, sécurité).</div></li>
                    <li><img src="/assets/img/hotel-640x480.webp" alt="Une chambre d'hôtel au Bénin" loading="lazy" decoding="async"><div><strong>Orienter</strong> — vers les professionnels de l'écosystème : agences réceptives, hébergeurs, restaurateurs, transporteurs, guides.</div></li>
                    <li><img src="/assets/img/wikimedia/lever-de-soleil-sur-l-esplanade-de-l-amazone-a-cotonou.webp" alt="Lever de soleil sur l'esplanade de l'Amazone, à Cotonou" loading="lazy" decoding="async"><div><strong>Valoriser</strong> — l'attractivité du Bénin auprès des visiteurs, des médias et des investisseurs.</div></li>
                </ul>
                <div class="apo-pas"><b aria-hidden="true">!</b><p><strong>Bonne Arrivée n'est pas une agence et ne vend aucune prestation</strong> : ni circuit, ni hébergement, ni transport, ni assurance. Tout ce qui est pratique (réservations, séjours, services) se fait directement auprès des partenaires de l'écosystème, qui en sont seuls responsables.</p></div>
            </div>
        </section>

        <section class="apo-sec">
            <div class="apo-in">
                <div class="apo-head"><span class="apo-k">Nos partenaires</span><h2>L'écosystème</h2><p>Les services pratiques sont assurés par les partenaires de l'écosystème :</p></div>
                <ul class="apo-eco-g">
                    <li><a href="https://dahomeydiscovery.com" target="_blank" rel="noopener noreferrer" style="--c:#00844d"><span class="apo-logo"><img src="/assets/img/partenaires/ba-logo-dahomey-discovery.webp" alt="" loading="lazy"></span><strong><i></i>Dahomey Discovery</strong><span class="apo-d">agence réceptive au Bénin : circuits sur mesure, hébergements, transferts et immersions culturelles.</span><span class="apo-go">dahomeydiscovery.com →</span></a></li>
                    <li><a href="https://www.petit-train-cotonou.com" target="_blank" rel="noopener noreferrer" style="--c:#e8112d"><span class="apo-logo"><img src="/assets/img/partenaires/ba-logo-petit-train-cotonou.webp" alt="" loading="lazy"></span><strong><i></i>Le Petit Train de Cotonou</strong><span class="apo-d">trois circuits commentés (15 min, 30 min, 1 h) pour découvrir la capitale économique du Bénin en famille, en groupe ou en privatisation.</span><span class="apo-go">petit-train-cotonou.com →</span></a></li>
                    <li><a href="https://www.wanilo.studio" target="_blank" rel="noopener noreferrer" style="--c:#fcd116"><span class="apo-logo"><img src="/assets/img/partenaires/ba-logo-wanilo-studio.webp" alt="" loading="lazy"></span><strong><i></i>Wanilo Studio</strong><span class="apo-d">studio audiovisuel béninois basé à Cotonou : production vidéo, podcast, photo, décors professionnels et équipe technique.</span><span class="apo-go">wanilo.studio →</span></a></li>
                </ul>
                <p><strong>Bonne Arrivée</strong> les fait connaître ; chaque réservation se fait directement auprès d'eux.</p>
            </div>
        </section>

        <section class="apo-visite">
            <div class="apo-in">
                <span class="apo-k" style="color:#fcd116">À Cotonou</span>
                <h2>Nous rencontrer</h2>
                <div class="apo-carte">
                    <p>
                        Rond-point Akpakpa, Ciné Concorde<br>
                        Cotonou — Bénin<br>
                        Tél. : <a href="tel:+2290167110220">+229 01 67 11 02 20</a><br>
                        WhatsApp : <a href="https://wa.me/2290154292929" target="_blank" rel="noopener noreferrer">+229 01 54 29 29 29</a><br>
                        E-mail : <a href="mailto:contact@bonnearrivee.bj">contact@bonnearrivee.bj</a>
                    </p>
                </div>
            </div>
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
