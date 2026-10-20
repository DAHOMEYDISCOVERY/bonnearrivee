{{-- Fiche détaillée d'un événement de Bénin en Fête.
     Structure partagée pour Vodun Days / Weloveya / Festival des Masques / JISTNA / La Gaani. --}}
@extends('layouts.app')

@section('bodyClass', 'page menus-v2 prod gabarit-basic_page benin-event-page')
@section('mainAttributes')role="main" class="content-type-page basic_page" id="main-content"@endsection

@php
    $globals = [
        'ancestors' => ['chapter1' => 'Bénin en Fête', 'chapter2' => $event['title'], 'chapter3' => null],
        'context' => 'website',
        'current_lang' => 'fr',
        'current_locale' => 'fr',
        'current_locale_region' => 'fr_FR',
        'env' => 'prod',
        'languages' => ['fr', 'nl'],
        'page_type' => 'basic_page',
        'post_id' => 9100,
        'post_title' => $event['title'],
        'post_type' => 'page',
        'site_key' => 'benin',
        'tags' => ['places' => [], 'seasons' => [], 'themes' => [], 'targets' => []],
        'woody_options_pages' => ['search_url' => 2043, 'weather_url' => 0],
        'woody_timezone' => 'Europe/Paris',
        'search' => ['api' => ['uri' => '', 'args' => ['size' => 15, 'threshold' => 0.5]]],
        'sheet_bordereaux_with_dates' => [],
        'short_date_bordereaux' => ['FMA', 'ASC'],
        'tmaps' => ['url_api' => '', 'url_api_lazy' => ''],
        'hawwwai' => ['sheet_options' => ['nearby_distance_max' => 5000]],
    ];
    $globalsJson = json_encode($globals, JSON_UNESCAPED_UNICODE);

    $schema = [
        'context'     => 'https://schema.org',
        'type'        => 'Event',
        'name'        => $event['title'],
        'description' => $event['description'],
        'location'    => ['type' => 'Place', 'name' => $event['lieu']],
        'startDate'   => $event['dates'],
        'image'       => $event['hero'],
        'url'         => "https://www.bonnearrivee.bj/benin-en-fete/{$slug}",
    ];
    $schemaJson = str_replace(
        ['"context"', '"type"'],
        ['"@context"', '"@type"'],
        json_encode($schema, JSON_UNESCAPED_UNICODE)
    );

    $allEvents = require resource_path('data/benin-events.php');

    // Mapping label -> SVG icone (stroke, 24x24, style Lucide/Feather).
    // Remplace les emojis dans surPlace et tips par une iconographie coherente.
    $eventIcons = [
        'Manger'        => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 2v7a2 2 0 0 0 2 2h1V2M8 2v20M17 2c-2 0-3 2-3 4v5h3v11"/></svg>',
        'Sécurité'      => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l8 3v6c0 5-3.5 9.3-8 11-4.5-1.7-8-6-8-11V5l8-3z"/><path d="M9 12l2 2 4-4"/></svg>',
        'Accessibilité' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="4" r="1.5"/><path d="M10.5 8h3l1.5 4h3M9 12l-1 6M15 12l2 5h3"/></svg>',
        'Paiement'      => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="6" width="20" height="12" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/><line x1="6" y1="15" x2="10" y2="15"/></svg>',
        'Tenue'         => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M8 3l4 3 4-3 4 2-3 4h-2v13H9V9H7L4 5z"/></svg>',
        'Photo'         => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>',
        'Météo'         => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4"/><line x1="12" y1="2" x2="12" y2="4"/><line x1="12" y1="20" x2="12" y2="22"/><line x1="2" y1="12" x2="4" y2="12"/><line x1="20" y1="12" x2="22" y2="12"/><line x1="4.9" y1="4.9" x2="6.3" y2="6.3"/><line x1="17.7" y1="17.7" x2="19.1" y2="19.1"/><line x1="4.9" y1="19.1" x2="6.3" y2="17.7"/><line x1="17.7" y1="6.3" x2="19.1" y2="4.9"/></svg>',
        'Transport'     => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M5 17h14M6 17l-1-5 3-6h10l3 6-1 5M7 17v3M17 17v3"/><circle cx="7.5" cy="14.5" r="1"/><circle cx="16.5" cy="14.5" r="1"/></svg>',
        'Nuit'          => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8z"/></svg>',
        'Argent'        => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="3"/><path d="M6 12h.01M18 12h.01"/></svg>',
        'Retour'        => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12h18M13 6l6 6-6 6"/></svg>',
        'Billets'       => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2 9a3 3 0 0 1 3-3h14a3 3 0 0 1 3 3v2a2 2 0 0 0 0 4v2a3 3 0 0 1-3 3H5a3 3 0 0 1-3-3v-2a2 2 0 0 0 0-4z"/><line x1="12" y1="6" x2="12" y2="18" stroke-dasharray="2 3"/></svg>',
        'Chaussures'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 15v3a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-1c0-2-2-2-4-4-2-2-2-4-4-4-2 0-2 2-4 2-2 0-4 2-4 4z"/></svg>',
    ];
    // Icone fallback (cercle neutre) si un label n'est pas dans le mapping.
    $eventIconFallback = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="3" fill="currentColor"/></svg>';
@endphp

@section('headGlobals'){!! '<script>window.globals=' . $globalsJson . ';</script>' !!}@endsection
@section('headSchema'){!! '<script type="application/ld+json">' . $schemaJson . '</script>' !!}@endsection
@section('headImageOg'){!! '<meta property="og:image" content="' . e($event['hero']) . '"/>' !!}@endsection

@section('content')
<div class="content-wrapper no-terms">

    {{-- HERO --}}
    <div class="event-hero" style="--event-color: {{ $event['themeCouleur'] }}">
        <img class="event-hero__img" src="{{ $event['hero'] }}" alt="{{ $event['title'] }}"
             loading="eager" decoding="async" referrerpolicy="no-referrer">
        <div class="event-hero__overlay">
            <nav class="event-hero__breadcrumb" aria-label="Fil d'ariane">
                <ol>
                    <li><a href="/">Accueil</a></li>
                    <li aria-hidden="true">/</li>
                    <li><a href="/benin-en-fete">Bénin en Fête</a></li>
                    <li aria-hidden="true">/</li>
                    <li aria-current="page">{{ $event['title'] }}</li>
                </ol>
            </nav>
            <div class="event-hero__center">
                <p class="event-hero__eyebrow">{{ $event['subtitle'] }}</p>
                <h1 class="event-hero__title">{{ $event['title'] }}</h1>
                <p class="event-hero__lead">{{ $event['description'] }}</p>
            </div>
        </div>
    </div>

    {{-- INFO BAR --}}
    <section class="event-facts event-facts--2col" data-anim-reveal>
        <div class="event-facts__item">
            <span class="event-facts__label">Où</span>
            <span class="event-facts__value">{{ $event['lieu'] }}</span>
        </div>
        <div class="event-facts__sep" aria-hidden="true"></div>
        <div class="event-facts__item">
            <span class="event-facts__label">Quand</span>
            <span class="event-facts__value">{{ $event['dates'] }}</span>
        </div>
    </section>

    {{-- CHAPÔ --}}
    <section class="event-chapo" data-anim-reveal>
        <p>{{ $event['chapo'] }}</p>
    </section>

    {{-- STATS EN CHIFFRES --}}
    @if(!empty($event['stats']))
    <section class="event-stats" data-anim-reveal aria-label="En chiffres">
        @foreach($event['stats'] as $i => $s)
            <div class="event-stat" style="--i: {{ $i }}">
                <span class="event-stat__nb">{{ $s['nb'] }}</span>
                <span class="event-stat__label">{{ $s['label'] }}</span>
            </div>
        @endforeach
    </section>
    @endif

    {{-- BLOCS ÉDITORIAUX --}}
    <section class="event-blocks">
        @foreach($event['blocks'] as $i => $b)
            <article class="event-block" data-anim-reveal style="--i: {{ $i }}">
                <span class="event-block__num">{{ str_pad((string)($i+1), 2, '0', STR_PAD_LEFT) }}</span>
                <div class="event-block__body">
                    <h2>{{ $b['titre'] }}</h2>
                    <p>{{ $b['texte'] }}</p>
                </div>
            </article>
        @endforeach
    </section>

    {{-- AMBIANCE --}}
    @if(!empty($event['ambiance']))
    <section class="event-ambiance" data-anim-reveal aria-label="L'ambiance">
        <div class="event-ambiance__grid">
            <div class="event-ambiance__body">
                <p class="event-section-eyebrow">L'ambiance</p>
                <h2>{{ $event['ambiance']['titre'] }}</h2>
                <p class="event-ambiance__lead">{{ $event['ambiance']['texte'] }}</p>
                <ul class="event-ambiance__points">
                    @foreach($event['ambiance']['points'] as $p)
                        <li>{{ $p }}</li>
                    @endforeach
                </ul>
            </div>
            <div class="event-ambiance__gallery">
                @foreach($event['ambiance']['photos'] as $i => $ph)
                    <img src="{{ $ph }}" alt="" loading="lazy" decoding="async" referrerpolicy="no-referrer" style="--i: {{ $i }}">
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- LIEUX EMBLÉMATIQUES --}}
    @if(!empty($event['lieux']))
    <section class="event-lieux" data-anim-reveal aria-label="Les lieux emblématiques">
        <div class="event-section-head">
            <p class="event-section-eyebrow">Les scènes</p>
            <h2 class="event-section-title">Les lieux emblématiques</h2>
            <p class="event-section-lead">Chaque événement respire dans des lieux précis. Les connaître, c'est déjà commencer à comprendre le déroulé.</p>
        </div>
        <div class="event-lieux__grid">
            @foreach($event['lieux'] as $i => $l)
                <article class="event-lieu" data-anim-reveal style="--i: {{ $i }}">
                    <div class="event-lieu__media">
                        <img src="{{ $l['photo'] }}" alt="{{ $l['nom'] }}" loading="lazy" decoding="async" referrerpolicy="no-referrer">
                    </div>
                    <div class="event-lieu__body">
                        <h3>{{ $l['nom'] }}</h3>
                        <p>{{ $l['desc'] }}</p>
                    </div>
                </article>
            @endforeach
        </div>
    </section>
    @endif

    {{-- TEMPS FORTS (vagues de programmation) --}}
    @if(!empty($event['tempsForts']))
    <section class="event-vagues" data-anim-reveal aria-label="Les temps forts">
        <div class="event-section-head">
            <p class="event-section-eyebrow">Les vagues de programmation</p>
            <h2 class="event-section-title">Les temps forts</h2>
            <p class="event-section-lead">Chaque édition suit un fil narratif similaire — voici les cinq vagues qui structurent l'expérience.</p>
        </div>
        <div class="event-vagues__list">
            @foreach($event['tempsForts'] as $i => $v)
                <article class="event-vague" data-anim-reveal style="--i: {{ $i }}">
                    <span class="event-vague__index">{{ str_pad((string)($i+1), 2, '0', STR_PAD_LEFT) }}</span>
                    <div class="event-vague__body">
                        <span class="event-vague__sous">{{ $v['sous'] }}</span>
                        <h3>{{ $v['titre'] }}</h3>
                        <p>{{ $v['texte'] }}</p>
                    </div>
                </article>
            @endforeach
        </div>
    </section>
    @endif

    {{-- PROGRAMME TIMELINE --}}
    @if(!empty($event['programme']))
    <section class="event-programme" id="programme" data-anim-reveal aria-label="Programme type">
        <div class="event-section-head">
            <p class="event-section-eyebrow">Le déroulé</p>
            <h2 class="event-section-title">Programme type</h2>
            <p class="event-section-lead">Un aperçu de ce qui vous attend jour après jour. Le programme officiel varie chaque année — vérifiez la date exacte sur le site du festival.</p>
        </div>
        <ol class="event-timeline">
            @foreach($event['programme'] as $i => $p)
                <li class="event-timeline__item" data-anim-reveal style="--i: {{ $i }}">
                    <div class="event-timeline__marker" aria-hidden="true">
                        <span class="event-timeline__dot"></span>
                    </div>
                    <div class="event-timeline__body">
                        <div class="event-timeline__meta">
                            <span class="event-timeline__day">{{ $p['jour'] }}</span>
                            <span class="event-timeline__lieu"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 1 1 16 0z"/><circle cx="12" cy="10" r="3"/></svg>{{ $p['lieu'] }}</span>
                        </div>
                        <h3 class="event-timeline__title">{{ $p['titre'] }}</h3>
                        <p class="event-timeline__desc">{{ $p['texte'] }}</p>
                    </div>
                </li>
            @endforeach
        </ol>
    </section>
    @endif

    {{-- BON À SAVOIR / TIPS --}}
    @if(!empty($event['tips']))
    <section class="event-tips" data-anim-reveal aria-label="Bon à savoir">
        <div class="event-section-head">
            <p class="event-section-eyebrow">Bon à savoir</p>
            <h2 class="event-section-title">Les conseils de nos guides</h2>
            <p class="event-section-lead">Ces règles évitent les faux pas et rendent le séjour plus fluide. Notre équipe les rappelle systématiquement à chaque groupe.</p>
        </div>
        <div class="event-tips__grid">
            @foreach($event['tips'] as $i => $t)
                <div class="event-tips__card" data-anim-reveal style="--i: {{ $i }}">
                    <span class="event-tips__ic" aria-hidden="true">{!! $eventIcons[$t['label']] ?? $eventIconFallback !!}</span>
                    <div>
                        <span class="event-tips__label">{{ $t['label'] }}</span>
                        <p class="event-tips__text">{{ $t['texte'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- COMBINER AVEC (cross-liens) --}}
    @if(!empty($event['combinerAvec']))
    <section class="event-combine" data-anim-reveal aria-label="Combiner avec d'autres événements">
        <div class="event-section-head">
            <p class="event-section-eyebrow">Prolonger le voyage</p>
            <h2 class="event-section-title">À combiner avec</h2>
            <p class="event-section-lead">Ces rendez-vous s'inscrivent dans la même saison ou la même thématique. Ils gagnent à être vécus dans le même voyage.</p>
        </div>
        <div class="event-combine__grid">
            @foreach($event['combinerAvec'] as $i => $c)
                @php $target = $allEvents[$c['slug']] ?? null; @endphp
                @if($target)
                    <a class="event-combine__card" href="/benin-en-fete/{{ $c['slug'] }}" data-anim-reveal style="--i: {{ $i }}; --event-color: {{ $target['themeCouleur'] }}">
                        <img src="{{ $target['hero'] }}" alt="{{ $c['titre'] }}" loading="lazy" decoding="async" referrerpolicy="no-referrer">
                        <div class="event-combine__body">
                            <span class="event-combine__lieu">{{ $target['lieu'] }} · {{ $target['dates'] }}</span>
                            <h3>{{ $c['titre'] }}</h3>
                            <p>{{ $c['note'] }}</p>
                            <span class="event-combine__arrow" aria-hidden="true">Découvrir →</span>
                        </div>
                    </a>
                @endif
            @endforeach
        </div>
    </section>
    @endif

    {{-- HISTOIRE --}}
    @if(!empty($event['histoire']))
    <section class="event-histoire" data-anim-reveal aria-label="Comment est né l'événement">
        <div class="event-histoire__grid">
            <aside class="event-histoire__aside">
                <p class="event-section-eyebrow">L'origine</p>
                <p class="event-histoire__annee">{{ $event['dates'] }}</p>
                <p class="event-histoire__lieu">{{ $event['lieu'] }}</p>
            </aside>
            <div class="event-histoire__body">
                <h2>{{ $event['histoire']['titre'] }}</h2>
                @foreach($event['histoire']['paragraphs'] as $p)
                    <p>{{ $p }}</p>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- AUTOUR DE L'ÉVÉNEMENT --}}
    @if(!empty($event['autour']))
    <section class="event-autour" data-anim-reveal aria-label="Autour de l'événement">
        <div class="event-section-head">
            <p class="event-section-eyebrow">À faire à côté</p>
            <h2 class="event-section-title">{{ $event['autour']['titre'] }}</h2>
            <p class="event-section-lead">Étirez votre séjour d'une journée ou deux — voici les visites qui prolongent l'expérience.</p>
        </div>
        <div class="event-autour__grid">
            @foreach($event['autour']['points'] as $i => $a)
                <article class="event-autour__card" data-anim-reveal style="--i: {{ $i }}">
                    <span class="event-autour__num" aria-hidden="true">{{ str_pad((string)($i+1), 2, '0', STR_PAD_LEFT) }}</span>
                    <div>
                        <h3>{{ $a['t'] }}</h3>
                        <p>{{ $a['d'] }}</p>
                    </div>
                </article>
            @endforeach
        </div>
    </section>
    @endif

    {{-- SUR PLACE --}}
    @if(!empty($event['surPlace']))
    <section class="event-surplace" data-anim-reveal aria-label="Sur place">
        <div class="event-section-head">
            <p class="event-section-eyebrow">Repères pratiques</p>
            <h2 class="event-section-title">{{ $event['surPlace']['titre'] }}</h2>
        </div>
        <div class="event-surplace__grid">
            @foreach($event['surPlace']['points'] as $i => $s)
                <div class="event-surplace__item" data-anim-reveal style="--i: {{ $i }}">
                    <span class="event-surplace__ic" aria-hidden="true">{!! $eventIcons[$s['label']] ?? $eventIconFallback !!}</span>
                    <div>
                        <span class="event-surplace__label">{{ $s['label'] }}</span>
                        <p class="event-surplace__text">{{ $s['texte'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- FAQ --}}
    @if(!empty($event['faq']))
    <section class="event-faq" data-anim-reveal aria-label="Questions fréquentes">
        <div class="event-section-head">
            <p class="event-section-eyebrow">Vous nous demandez souvent</p>
            <h2 class="event-section-title">Les questions fréquentes</h2>
        </div>
        <div class="event-faq__list">
            @foreach($event['faq'] as $i => $qa)
                <details class="event-faq__item" data-anim-reveal style="--i: {{ $i }}">
                    <summary>
                        <span>{{ $qa['q'] }}</span>
                        <span class="event-faq__chevron" aria-hidden="true">+</span>
                    </summary>
                    <p>{{ $qa['r'] }}</p>
                </details>
            @endforeach
        </div>
    </section>
    @endif

    {{-- CTA FINALE --}}
    <section class="event-cta" data-anim-reveal>
        <div>
            <p class="event-cta__eyebrow">Composer votre séjour</p>
            <h2>Vivre {{ $event['title'] }} avec Dahomey Discovery</h2>
            <p>Notre équipe organise chaque année des circuits sur mesure pendant l'événement — accueil aéroport, hébergement, transport, guide francophone, accès aux moments-clés du programme.</p>
            <div class="event-cta__buttons">
                <a class="event-cta__btn event-cta__btn--primary" href="https://dahomeydiscovery.com" target="_blank" rel="noopener noreferrer">
                    Nous confier votre séjour <span aria-hidden="true">↗</span>
                </a>
                <a class="event-cta__btn event-cta__btn--ghost" href="/benin-en-fete">
                    Voir tous les événements
                </a>
            </div>
        </div>
    </section>

</div>

{{-- Écosystème --}}
<x-aggregator.ecosysteme-block />

{{-- Reveal-on-scroll + compteur anime des statistiques (leger, vanilla) --}}
<script>
(function(){
  // 1) Reveal-on-scroll
  if (!('IntersectionObserver' in window)) {
    document.querySelectorAll('[data-anim-reveal]').forEach(function(el){ el.classList.add('is-revealed'); });
  } else {
    var io = new IntersectionObserver(function(entries){
      entries.forEach(function(e){
        if (e.isIntersecting) {
          e.target.classList.add('is-revealed');
          io.unobserve(e.target);
        }
      });
    }, { threshold: 0.12, rootMargin: '0px 0px -50px 0px' });
    document.querySelectorAll('[data-anim-reveal]').forEach(function(el){ io.observe(el); });
    // Failsafe si l'IO ne fire pas
    setTimeout(function(){
      document.querySelectorAll('[data-anim-reveal]:not(.is-revealed)').forEach(function(el){
        el.classList.add('is-revealed');
      });
    }, 1600);
  }

  // 2) Compteur anime des statistiques
  function animateCounter(el){
    if (el.dataset.animated === '1') return;
    var raw = el.textContent.trim();
    // Extrait prefix non-numerique, nombre (peut contenir espaces), suffix
    var m = raw.match(/^(\D*)([\d][\d\s ]*)(.*)$/);
    if (!m) return;
    var prefix = m[1];
    var target = parseInt(m[2].replace(/[\s ]/g, ''), 10);
    var suffix = m[3];
    if (isNaN(target)) return;
    el.dataset.animated = '1';
    el.dataset.finalText = raw;
    var duration = 1400;
    var start = performance.now();
    function fmt(v){ return v.toLocaleString('fr-FR').replace(/,/g, ' '); }
    function tick(now){
      var t = Math.min((now - start) / duration, 1);
      var eased = 1 - Math.pow(1 - t, 3);
      var value = Math.round(target * eased);
      el.textContent = prefix + fmt(value) + suffix;
      if (t < 1) {
        requestAnimationFrame(tick);
      } else {
        el.textContent = raw; // valeur finale exacte
      }
    }
    requestAnimationFrame(tick);
  }

  var statsSection = document.querySelector('.event-stats');
  if (statsSection) {
    if ('IntersectionObserver' in window) {
      var statsIO = new IntersectionObserver(function(entries){
        entries.forEach(function(e){
          if (e.isIntersecting) {
            e.target.querySelectorAll('.event-stat__nb').forEach(animateCounter);
            statsIO.unobserve(e.target);
          }
        });
      }, { threshold: 0.4 });
      statsIO.observe(statsSection);
    } else {
      statsSection.querySelectorAll('.event-stat__nb').forEach(animateCounter);
    }
  }
})();
</script>
@endsection
