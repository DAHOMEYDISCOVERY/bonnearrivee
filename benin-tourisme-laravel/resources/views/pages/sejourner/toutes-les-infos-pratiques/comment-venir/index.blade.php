{{-- Comment venir au Bénin — refonte 2026 dans l'esprit de la page Mayotte
     équivalente : hero + compagnies aériennes + arrivée à Cotonou + autres
     portes d'entrée + tuiles infos pratiques + écosystème. --}}
@extends('layouts.app')

@section('bodyClass', 'page menus-v2 prod gabarit-basic_page benin-hotels-page')
@section('mainAttributes')role="main" class="content-type-page basic_page" id="main-content"@endsection

@section('headGlobals')@verbatim<script>window.globals={"ancestors":{"chapter1":"Organisez","chapter2":"Toutes les infos pratiques","chapter3":"Comment venir"},"context":"website","current_lang":"fr","current_locale":"fr","current_locale_region":"fr_FR","env":"prod","languages":["fr","nl"],"page_type":"basic_page","post_id":812,"post_title":"Comment venir","post_type":"page","site_key":"benin","tags":{"places":[],"seasons":[],"themes":[],"targets":[]},"woody_options_pages":{"search_url":2043,"weather_url":0},"woody_timezone":"Europe/Paris","search":{"api":{"uri":"","args":{"size":15,"threshold":0.5}}},"sheet_bordereaux_with_dates":[],"short_date_bordereaux":["FMA","ASC"],"tmaps":{"url_api":"","url_api_lazy":""},"hawwwai":{"sheet_options":{"nearby_distance_max":5000}}}</script>@endverbatim@endsection
@section('headSchema')@verbatim<script type="application/ld+json">{"@context":"https://schema.org","@type":"WebPage","name":"Comment venir au Bénin | Bonne Arrivée","url":"https://www.bonnearrivee.bj/sejourner/toutes-les-infos-pratiques/comment-venir","description":"Les compagnies aériennes qui desservent l'aéroport de Cotonou, les frontières terrestres et l'arrivée en pratique."}</script>@endverbatim@endsection
@section('headImageOg')@verbatim<meta property="og:image" content="/assets/img/aeroport-1920x960.webp"/>@endverbatim@endsection
@section('styles')@verbatim<link rel='stylesheet' id='benin-carte-css' href='/assets/benin-carte.css?v=d067e2a2' media='all' />@endverbatim@endsection
@section('scripts')@verbatim<script src='/assets/benin-carte.js?v=48eb117a'></script>@endverbatim@endsection

@section('content')
@verbatim
<div class="content-wrapper no-terms">

    <div class="emerv-hero">
        <img class="emerv-hero__img"
             src="/assets/img/aeroport-1920x960.webp"
             srcset="/assets/img/aeroport-640x320.webp 640w, /assets/img/aeroport-1200x600.webp 1200w, /assets/img/aeroport-1920x960.webp 1920w"
             sizes="100vw"
             alt="Aéroport international de Cotonou Cadjèhoun"
             fetchpriority="high">
        <div class="emerv-hero__overlay">
            <nav class="emerv-hero__breadcrumb" aria-label="Fil d'ariane">
                <ol>
                    <li><a href="/">Accueil</a></li>
                    <li aria-hidden="true">/</li>
                    <li><a href="/sejourner">Organisez</a></li>
                    <li aria-hidden="true">/</li>
                    <li><a href="/sejourner/toutes-les-infos-pratiques">Infos pratiques</a></li>
                    <li aria-hidden="true">/</li>
                    <li aria-current="page">Comment venir</li>
                </ol>
            </nav>
            <div class="emerv-hero__center">
                <p class="emerv-hero__eyebrow">Aéroport COO · Cadjèhoun</p>
                <h1 class="emerv-hero__title">Comment venir au Bénin</h1>
                <p class="emerv-hero__lead">Comment rejoindre Cotonou depuis l'Europe, l'Afrique et le reste du monde ? Et comment sortir de l'aéroport à l'arrivée ?</p>
            </div>
        </div>
    </div>

    <section class="benin-page-chapo">
        <p>Un <strong>aéroport international</strong> à Cotonou, quatre <strong>frontières terrestres</strong> ouvertes, un <strong>port de commerce</strong>. Voici les portes d'entrée du pays et ce qu'elles impliquent.</p>
    </section>
@endverbatim

    {{-- Cartographie interactive : Monde → Afrique de l'Ouest → Golfe de Guinée → 12 départements --}}
    <section class="benin-cv-map" aria-label="Situer le Bénin">
        <div class="benin-cv-map__head">
            <p class="benin-cv-map__eyebrow">Situer le Bénin</p>
            <h2 class="benin-cv-map__title">Le Bénin en quatre échelles</h2>
            <p class="benin-cv-map__lead">Du monde entier au niveau des départements : quatre cartes pour saisir la position du Bénin en Afrique de l'Ouest et repérer d'où l'on vient et où l'on va.</p>
        </div>
        <x-carte-du-benin />
    </section>
@verbatim

    <div class="benin-airlines-title">
        <p class="benin-airlines__eyebrow">Lignes régulières · Aéroport de Cotonou (COO)</p>
        <h2>Les compagnies aériennes</h2>
        <p>Cotonou est desservie depuis l'Europe en vol direct (Paris, Bruxelles) et depuis toute l'Afrique par les grands hubs (Addis, Nairobi, Casablanca, Istanbul, Lomé, Dakar, Abidjan).</p>
    </div>

    <div class="page-section page-section-0" id="pageSection-airlines">
        <section class="section padd-top-md padd-bottom-md marg-top-none marg-bottom-none">
            <article class="section-layout-wrapper grid-container grid-default">
                <div class="grid_3col_tpl_01 grid-x medium-up-3 align-center align-middle grid-padding-x grid-padding-y">
@endverbatim
                    @php
                        // Photo dediee par compagnie : depose le fichier sous
                        //   /public/assets/img/airlines/<slug>.jpg
                        // Si le fichier n'existe pas, on affiche le SVG generique
                        // (silhouette d'avion sur ciel bleu) qui evite tout logo.
                        $airlinesDir = public_path('assets/img/airlines');
                        $pick = function (string $slug) use ($airlinesDir) {
                            foreach (['jpg', 'jpeg', 'webp', 'png'] as $ext) {
                                if (is_file("$airlinesDir/$slug.$ext")) {
                                    return "/assets/img/airlines/$slug.$ext";
                                }
                            }
                            return '/assets/img/airlines/_placeholder.svg';
                        };
                        $airlines = [
                            ['name' => 'Air France',         'route' => 'Paris CDG · direct',    'img' => $pick('air-france'),         'url' => 'https://www.airfrance.fr'],
                            ['name' => 'Brussels Airlines',  'route' => 'Bruxelles · direct',    'img' => $pick('brussels-airlines'),  'url' => 'https://www.brusselsairlines.com'],
                            ['name' => 'Turkish Airlines',   'route' => 'Istanbul',              'img' => $pick('turkish-airlines'),   'url' => 'https://www.turkishairlines.com'],
                            ['name' => 'Ethiopian Airlines', 'route' => 'Addis-Abeba',           'img' => $pick('ethiopian-airlines'), 'url' => 'https://www.ethiopianairlines.com'],
                            ['name' => 'Kenya Airways',      'route' => 'Nairobi',               'img' => $pick('kenya-airways'),      'url' => 'https://www.kenya-airways.com'],
                            ['name' => 'Royal Air Maroc',    'route' => 'Casablanca',            'img' => $pick('royal-air-maroc'),    'url' => 'https://www.royalairmaroc.com'],
                            ['name' => 'Amazon Airlines',    'route' => 'Cotonou · nouvelle',    'img' => $pick('amazon-airlines'),    'url' => 'https://flyamazoneair.bj/'],
                            ['name' => 'ASKY Airlines',      'route' => 'Lomé',                  'img' => $pick('asky-airlines'),      'url' => 'https://flyasky.com'],
                            ['name' => 'Air Sénégal',        'route' => 'Dakar',                 'img' => $pick('air-senegal'),        'url' => 'https://www.airsenegal.com'],
                            ['name' => 'Air Côte d’Ivoire',  'route' => 'Abidjan',               'img' => $pick('air-cote-divoire'),   'url' => 'https://www.aircotedivoire.com'],
                        ];
                    @endphp
                    @foreach($airlines as $a)
                        <div class="cell small-down-order-9">
                            <div class="woody-component woody-component-cta tpl_01 border-secondary bg-primary bg-gradient bg-filigrane isRel padd-all-md no-sessions benin-airline-cta" data-equalizer="data-equalizer">
                                <img class="benin-airline-cta__bg" src="{{ $a['img'] }}" alt="" aria-hidden="true" loading="lazy" decoding="async">
                                <div class="benin-airline-cta__inner">
                                    <div class="generic-block-header marg-bottom-sm">
                                        <div class="generic-block-titles flex-container flex-dir-column align-center text-left">
                                            <div class="generic-block-texts has-pretitle">
                                                <span class="generic-block-pretitle block-span">{{ $a['name'] }}</span>
                                                <span class="benin-airline-cta__route">{{ $a['route'] }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="grid-x grid-padding-x">
                                        <div class="cell medium-8 cta-desc flex-container flex-dir-column"></div>
                                        <div class="cell medium-4 cta-actions flex-container flex-dir-column align-center">
                                            <ul class="list-unstyled flex-container align-right align-middle wrap grid-gap-sm">
                                                <li class="list-item">
                                                    <div class="woody-component-button padd-top-sm">
                                                        <a class="woody-component-button button primary small has-icon" href="{{ $a['url'] }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $a['name'] }} (nouvel onglet)">
                                                            <span class="wicon wicon-042-lien"></span>
                                                            <span class="button-label" data-label="{{ $a['name'] }}">{{ $a['name'] }}</span>
                                                            <span class="button-arrow"></span>
                                                        </a>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
@verbatim
                </div>
            </article>
        </section>
    </div>

    <section class="benin-page-blocks" aria-label="Arrivée et autres portes d'entrée">
        <article class="benin-page-block">
            <span class="benin-page-block__num">01</span>
            <h2 class="benin-page-block__title">À l'arrivée à Cadjèhoun</h2>
            <p>À l'aérogare, on présente le <strong>passeport</strong>, l'<strong>e-visa</strong> imprimé, le <strong>certificat de vaccination contre la fièvre jaune</strong> et le billet de retour. Le détail des formalités figure sur la <a href="/sejourner/toutes-les-infos-pratiques/formalites-et-visa">page qui leur est consacrée</a>.</p>
            <p>L'aéroport est situé dans le quartier de <strong>Cadjèhoun</strong>, à environ <strong>4 km du centre de Cotonou</strong> — 15 à 30 minutes selon le trafic. Prévoyez un accueil : taxi officiel, VTC (Yango, Gozem), transfert privé Dahomey Discovery ou navette de votre hôtel.</p>
        </article>

    </section>

    <section class="benin-gateway" aria-label="Autres portes d'entrée au Bénin">
        <div class="benin-gateway__head">
            <p class="benin-gateway__eyebrow">Alternatives à l'aérien</p>
            <h2 class="benin-gateway__title">Les autres portes d'entrée</h2>
        </div>
        <div class="benin-gateway__grid">

            <article class="benin-gateway__card">
                <div class="benin-gateway__media">
                    <img src="/assets/img/gateway/route-benin.jpg" alt="Route nationale inter-État au Bénin" loading="lazy" decoding="async">
                    <span class="benin-gateway__badge">Frontières terrestres</span>
                </div>
                <div class="benin-gateway__body">
                    <span class="benin-gateway__num">02</span>
                    <h3>Par la route</h3>
                    <p>Quatre frontières terrestres ouvertes, deux vraiment praticables pour les voyageurs — le Togo à l'ouest, le Nigeria à l'est.</p>
                    <ul class="benin-gateway__list">
                        <li><strong>Hillacondji</strong> (Togo, route côtière depuis Lomé) — la plus simple, route bonne, transporteurs réguliers</li>
                        <li><strong>Kraké</strong> (Nigeria, vers Lagos) — très fréquentée, attente parfois longue</li>
                        <li><strong>Malanville</strong> (Niger) et postes Burkina — <em>zones actuellement déconseillées</em></li>
                    </ul>
                    <div class="benin-gateway__ctas">
                        <a class="benin-gateway__cta benin-gateway__cta--primary" href="/sejourner/toutes-les-infos-pratiques/securite">
                            Consulter la page Sécurité <span aria-hidden="true">→</span>
                        </a>
                        <a class="benin-gateway__cta benin-gateway__cta--ghost" href="/sejourner/toutes-les-infos-pratiques/se-deplacer">
                            Se déplacer au Bénin
                        </a>
                    </div>
                </div>
            </article>

            <article class="benin-gateway__card">
                <div class="benin-gateway__media">
                    <img src="/assets/img/gateway/port-cotonou.jpg" alt="Navires au Port Autonome de Cotonou" loading="lazy" decoding="async">
                    <span class="benin-gateway__badge">Port de commerce</span>
                </div>
                <div class="benin-gateway__body">
                    <span class="benin-gateway__num">03</span>
                    <h3>Par la mer</h3>
                    <p>Le <strong>Port Autonome de Cotonou</strong> est un port de commerce, l'un des plus actifs du golfe de Guinée. Ce n'est pas une porte d'entrée touristique régulière, mais quelques croisières font escale.</p>
                    <ul class="benin-gateway__list">
                        <li><strong>Trafic marchandises</strong> — hub logistique de l'Afrique de l'Ouest</li>
                        <li><strong>Escales de croisières</strong> — ponctuelles, pas de ligne régulière</li>
                        <li><strong>Sortie du port</strong> — passage par la douane, transfert VTC vers le centre-ville</li>
                    </ul>
                    <div class="benin-gateway__ctas">
                        <a class="benin-gateway__cta benin-gateway__cta--primary" href="https://pac.bj" target="_blank" rel="noopener noreferrer">
                            Port Autonome de Cotonou <span aria-hidden="true">↗</span>
                        </a>
                        <a class="benin-gateway__cta benin-gateway__cta--ghost" href="/sejourner/toutes-les-infos-pratiques/formalites-et-visa">
                            Formalités à l'arrivée
                        </a>
                    </div>
                </div>
            </article>

        </div>
    </section>

    <section class="benin-related-tiles" aria-label="Plus d'infos pratiques">
        <div class="benin-related-tiles__head">
            <p class="benin-related-tiles__eyebrow">Compléter votre préparation</p>
            <h2 class="benin-related-tiles__title">Plus d'infos pratiques</h2>
            <p class="benin-related-tiles__lead">Tout ce qu'il faut savoir avant de faire vos valises : formalités, santé, argent, sécurité et logistique sur place — six pages pour arriver serein au Bénin.</p>
        </div>
        <div class="benin-related-tiles__grid">
            <a class="benin-related-tiles__card" href="/sejourner/toutes-les-infos-pratiques/formalites-et-visa" style="--i: 0">
                <div class="benin-related-tiles__media">
                    <img src="/assets/img/aeroport-640x480.webp" alt="">
                    <span class="benin-related-tiles__scrim" aria-hidden="true"></span>
                </div>
                <div class="benin-related-tiles__body">
                    <span class="benin-related-tiles__eyebrow-mini">Étape 01</span>
                    <h3>Formalités &amp; visa</h3>
                    <p>Passeport, e-visa, certificat de fièvre jaune : le check-list complet des documents à préparer.</p>
                    <span class="benin-related-tiles__arrow" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                    </span>
                </div>
            </a>
            <a class="benin-related-tiles__card" href="/sejourner/toutes-les-infos-pratiques/sante-et-vaccins" style="--i: 1">
                <div class="benin-related-tiles__media">
                    <img src="/assets/img/marche-640x480.webp" alt="">
                    <span class="benin-related-tiles__scrim" aria-hidden="true"></span>
                </div>
                <div class="benin-related-tiles__body">
                    <span class="benin-related-tiles__eyebrow-mini">Étape 02</span>
                    <h3>Santé &amp; vaccins</h3>
                    <p>Vaccins obligatoires et recommandés, prévention du paludisme, trousse à médicaments à emporter.</p>
                    <span class="benin-related-tiles__arrow" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                    </span>
                </div>
            </a>
            <a class="benin-related-tiles__card" href="/sejourner/toutes-les-infos-pratiques/avant-de-partir" style="--i: 2">
                <div class="benin-related-tiles__media">
                    <img src="/assets/img/paysage-640x480.webp" alt="">
                    <span class="benin-related-tiles__scrim" aria-hidden="true"></span>
                </div>
                <div class="benin-related-tiles__body">
                    <span class="benin-related-tiles__eyebrow-mini">Étape 03</span>
                    <h3>Avant de partir</h3>
                    <p>Meilleure saison, valise, adaptateurs, applications, langues : les basiques à préparer une semaine avant.</p>
                    <span class="benin-related-tiles__arrow" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                    </span>
                </div>
            </a>
            <a class="benin-related-tiles__card" href="/sejourner/toutes-les-infos-pratiques/se-deplacer" style="--i: 3">
                <div class="benin-related-tiles__media">
                    <img src="/assets/img/zemidjan-640x480.webp" alt="">
                    <span class="benin-related-tiles__scrim" aria-hidden="true"></span>
                </div>
                <div class="benin-related-tiles__body">
                    <span class="benin-related-tiles__eyebrow-mini">Étape 04</span>
                    <h3>Se déplacer</h3>
                    <p>VTC, taxis, location de voiture, moto-taxi : comment bouger dans Cotonou et rejoindre le reste du pays.</p>
                    <span class="benin-related-tiles__arrow" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                    </span>
                </div>
            </a>
            <a class="benin-related-tiles__card" href="/sejourner/toutes-les-infos-pratiques/securite" style="--i: 4">
                <div class="benin-related-tiles__media">
                    <img src="/assets/img/gateway/securite-benin.jpg" alt="">
                    <span class="benin-related-tiles__scrim" aria-hidden="true"></span>
                </div>
                <div class="benin-related-tiles__body">
                    <span class="benin-related-tiles__eyebrow-mini">Étape 05</span>
                    <h3>Sécurité</h3>
                    <p>Zones sûres, zones déconseillées, comportement en ville et en brousse : les vraies consignes du terrain.</p>
                    <span class="benin-related-tiles__arrow" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                    </span>
                </div>
            </a>
            <a class="benin-related-tiles__card" href="/sejourner/toutes-les-infos-pratiques/nous-contacter" style="--i: 5">
                <div class="benin-related-tiles__media">
                    <img src="/assets/img/peuple-640x480.webp" alt="">
                    <span class="benin-related-tiles__scrim" aria-hidden="true"></span>
                </div>
                <div class="benin-related-tiles__body">
                    <span class="benin-related-tiles__eyebrow-mini">Étape 06</span>
                    <h3>Nous contacter</h3>
                    <p>Une question qui n'a pas trouvé sa réponse ? L'équipe Dahomey Discovery répond en 24 h ouvrées.</p>
                    <span class="benin-related-tiles__arrow" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                    </span>
                </div>
            </a>
        </div>
    </section>
</div>
@endverbatim

<x-aggregator.ecosysteme-block />
@endsection
