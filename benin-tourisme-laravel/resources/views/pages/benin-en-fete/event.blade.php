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
    // Cartes « À combiner avec » : même photo d'en-tête que la fiche visée.
    foreach ((is_file(resource_path('data/benin-events-identite.php')) ? require resource_path('data/benin-events-identite.php') : []) as $__slug => $__id) {
        if (isset($allEvents[$__slug], $__id['hero'])) { $allEvents[$__slug]['hero'] = $__id['hero']; }
    }

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
<div class="content-wrapper no-terms event-page event-page--{{ $slug }}" style="--event-color: {{ $event['accent'] ?? $event['themeCouleur'] }}">

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

    {{-- SECTIONS : ordre et titres propres à chaque événement (benin-events-identite.php) ;
         sans réglage, l'ordre d'origine. Section « signature » : contenu propre à l'événement. --}}
    @php
        $__ordre = $event['ordre'] ?? ['stats', 'blocks', 'ambiance', 'lieux', 'tempsForts', 'programme', 'tips', 'combine', 'histoire', 'autour', 'surPlace', 'faq'];
        if (! empty($event['signature']) && ! in_array('signature', $__ordre, true)) { array_unshift($__ordre, 'signature'); }
    @endphp
    @foreach ($__ordre as $__section)
        @includeIf('pages.benin-en-fete.sections.' . $__section, ['t' => $event['titres'][$__section] ?? []])
    @endforeach

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
