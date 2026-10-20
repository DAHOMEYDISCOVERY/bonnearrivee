{{-- « La vie s'émerveille au Bénin » — page listing des 29 lieux
     référencés dans la carte À DÉCOUVRIR de la home (benin-atlas.js).
     Mêmes catégories, filtres fonctionnels côté client, mosaïque adaptative. --}}
@extends('layouts.app')

@section('bodyClass', 'page menus-v2 prod gabarit-basic_page benin-emerv-page')
@section('mainAttributes')role="main" class="content-type-page basic_page" id="main-content"@endsection

@section('headGlobals')@verbatim<script>window.globals={"ancestors":{"chapter1":"Explorez","chapter2":"La vie s'émerveille au Bénin","chapter3":null},"context":"website","current_lang":"fr","current_locale":"fr","current_locale_region":"fr_FR","env":"prod","languages":["fr","nl"],"page_type":"basic_page","post_id":9010,"post_title":"La vie s'émerveille au Bénin","post_type":"page","site_key":"benin","tags":{"places":[],"seasons":[],"themes":[],"targets":[]},"woody_options_pages":{"search_url":2043,"weather_url":0},"woody_timezone":"Europe/Paris","search":{"api":{"uri":"","args":{"size":15,"threshold":0.5}}},"sheet_bordereaux_with_dates":[],"short_date_bordereaux":["FMA","ASC"],"tmaps":{"url_api":"","url_api_lazy":""},"hawwwai":{"sheet_options":{"nearby_distance_max":5000}}}</script>@endverbatim@endsection
@section('headSchema')@verbatim<script type="application/ld+json">{"@context":"https://schema.org","@type":"CollectionPage","name":"La vie s'émerveille au Bénin — Bonne Arrivée","url":"https://www.bonnearrivee.bj/explorez/la-vie-semerveille-au-benin","description":"Les 29 lieux du Bénin référencés sur la carte de découverte : littoral, patrimoine royal, parcs, cités royales, portes d'entrée."}</script>@endverbatim@endsection

@section('content')
@php
    // Catégories (identiques à celles de benin-atlas.js)
    $cats = [
        'plage'   => 'Plages & lagunes',
        'culture' => 'Culture & histoire',
        'nature'  => 'Nature & parcs',
        'ville'   => 'Villes & marchés',
        'arrivee' => 'Arriver au pays',
    ];

    // Fallback image par catégorie
    $catFallback = [
        'plage'   => 'mangrove-640x480.webp',
        'culture' => 'vodun-640x480.webp',
        'nature'  => 'atacora-640x480.webp',
        'ville'   => 'cotonou-640x480.webp',
        'arrivee' => 'paysage-640x480.webp',
    ];

    // Images spécifiques quand elles existent, par id de lieu
    $imgById = [
        'grand-popo' => 'plage-grand-popo-640x480.webp',
        'ouidah'     => 'ouidah-porte-640x480.webp',
        'cotonou'    => 'cotonou-640x480.webp',
        'ganvie'     => 'ganvie-640x480.webp',
        'porto-novo' => 'porto-novo-640x480.webp',
        'aheme'      => 'lac-aheme-640x480.webp',
        'abomey'     => 'abomey-palais-640x480.webp',
        'dassa'      => 'dassa-collines-640x480.webp',
        'boukoumbe'  => 'tata-somba-640x480.webp',
        'natitingou' => 'tata-somba-640x480.webp',
        'tanougou'   => 'chutes-tanougou-640x480.webp',
        'kota'       => 'chutes-tanougou-640x480.webp',
        'pendjari'   => 'pendjari-elephant-640x480.webp',
        'parc-w'     => 'pendjari-elephant-640x480.webp',
    ];

    // Les 29 sites de l'atlas (id, nom, catégorie, département)
    $sites = [
        ['grand-popo', 'Grand-Popo', 'plage',   'Mono'],
        ['ouidah',     'Ouidah',     'culture', 'Atlantique'],
        ['cotonou',    'Cotonou',    'ville',   'Littoral'],
        ['ganvie',     'Ganvié',     'plage',   'Atlantique'],
        ['porto-novo', 'Porto-Novo', 'culture', 'Ouémé'],
        ['aheme',      'Lac Ahémé',  'plage',   'Mono'],
        ['ketou',      'Kétou',      'culture', 'Plateau'],
        ['lama',       'Forêt de la Lama', 'nature', 'Zou'],
        ['abomey',     'Abomey',     'culture', 'Zou'],
        ['dassa',      'Dassa-Zoumè','culture', 'Collines'],
        ['parakou',    'Parakou',    'ville',   'Borgou'],
        ['nikki',      'Nikki',      'culture', 'Borgou'],
        ['djougou',    'Djougou',    'ville',   'Donga'],
        ['kota',       'Chutes de Kota',      'nature',  'Atacora'],
        ['boukoumbe',  'Boukoumbé',  'culture', 'Atacora'],
        ['natitingou', 'Natitingou', 'ville',   'Atacora'],
        ['tanougou',   'Chutes de Tanougou',  'nature',  'Atacora'],
        ['pendjari',   'Parc de la Pendjari', 'nature',  'Atacora'],
        ['parc-w',     'Parc W du Bénin',     'nature',  'Alibori'],
        ['malanville', 'Malanville', 'ville',   'Alibori'],
        ['allada',     'Allada',     'culture', 'Atlantique'],
        ['dogbo',      'Dogbo',      'culture', 'Couffo'],
        ['savalou',    'Savalou',    'culture', 'Collines'],
        ['save',       'Savé',       'nature',  'Collines'],
        ['tchaourou',  'Tchaourou',  'ville',   'Borgou'],
        ['taneka',     'Tanéka-Béri','culture', 'Donga'],
        ['tanguieta',  'Tanguiéta',  'ville',   'Atacora'],
        ['aeroport',   'Aéroport de Cotonou',    'arrivee', 'Littoral'],
        ['port',       'Port autonome de Cotonou','arrivee','Littoral'],
    ];
@endphp
@verbatim
<div class="content-wrapper no-terms">

    <div class="emerv-hero">
        <img class="emerv-hero__img"
             src="/assets/img/plage-grand-popo-1920x1080.webp"
             srcset="/assets/img/plage-grand-popo-640x360.webp 640w, /assets/img/plage-grand-popo-1200x675.webp 1200w, /assets/img/plage-grand-popo-1920x1080.webp 1920w"
             sizes="100vw"
             alt="Le littoral béninois vu de Grand-Popo"
             fetchpriority="high">
        <div class="emerv-hero__overlay">
            <nav class="emerv-hero__breadcrumb" aria-label="Fil d'ariane">
                <ol>
                    <li><a href="/">Accueil</a></li>
                    <li aria-hidden="true">/</li>
                    <li><a href="/explorez">Découvrez</a></li>
                    <li aria-hidden="true">/</li>
                    <li aria-current="page">La vie s'émerveille au Bénin</li>
                </ol>
            </nav>
            <div class="emerv-hero__center">
                <p class="emerv-hero__eyebrow">À découvrir · 29 lieux du Bénin</p>
                <h1 class="emerv-hero__title">La vie s'émerveille au Bénin</h1>
                <p class="emerv-hero__lead">Les vingt-neuf escales référencées sur notre carte de découverte, réunies en une seule vue.</p>
            </div>
        </div>
        <div class="emerv-hero__credit">© Kulttuurinavigaattori / CC BY-SA 4.0</div>
    </div>

    <header class="emerv-teaser">
        <ul class="emerv-share" aria-label="Partager cette page">
            <li><a class="emerv-share__btn emerv-share__btn--fb" href="https://facebook.com/sharer/sharer.php?u=https%3A%2F%2Fwww.bonnearrivee.bj%2Fexplorez%2Fla-vie-semerveille-au-benin" target="_blank" rel="noopener noreferrer" title="Partager sur Facebook" aria-label="Partager sur Facebook"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M18.77 7.46H14.5v-1.9c0-.9.6-1.1 1-1.1h3V.5h-4.33C10.24.5 9.5 3.44 9.5 5.32v2.15h-3v4h3v12h5v-12h3.85l.42-4z"/></svg></a></li>
            <li><a class="emerv-share__btn emerv-share__btn--x" href="https://twitter.com/intent/tweet/?url=https%3A%2F%2Fwww.bonnearrivee.bj%2Fexplorez%2Fla-vie-semerveille-au-benin" target="_blank" rel="noopener noreferrer" title="Partager sur X" aria-label="Partager sur X"><svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M1.05227 2L9.33002 13.2342L1 22.368H2.87475L10.1677 14.3712L16.0601 22.368H22.44L13.6965 10.5019L21.45 2H19.5753L12.8589 9.36491L7.43213 2H1.05227ZM3.80924 3.40166H6.74016L19.6826 20.9661H16.7517L3.80924 3.40166Z"/></svg></a></li>
            <li><a class="emerv-share__btn emerv-share__btn--in" href="https://www.linkedin.com/shareArticle?mini=true&url=https%3A%2F%2Fwww.bonnearrivee.bj%2Fexplorez%2Fla-vie-semerveille-au-benin" target="_blank" rel="noopener noreferrer" title="Partager sur LinkedIn" aria-label="Partager sur LinkedIn"><svg viewBox="0 0 50 50" xmlns="http://www.w3.org/2000/svg"><g transform="translate(3.995 0)"><path d="m.725887 14.017622h8.687782v27.946652h-8.687782z"/><path d="m5.06977823 10.1995707c-2.03833886.0011559-3.87655884-1.22597292-4.65713137-3.10893154-.78057254-1.88295862-.34969502-4.05073223 1.09162844-5.4920557 1.44132347-1.44132346 3.60909708-1.87220098 5.4920557-1.09162844 1.88295861.78057254 3.1100874 2.61879251 3.1089316 4.65713137.0007587 1.33572593-.52952038 2.61696197-1.47402137 3.561463-.94450103.944501-2.22573707 1.47478011-3.561463 1.47402131z"/><path d="m41.9499857 41.9642748h-8.6820667v-13.5918068c0-3.2407714-.0571564-7.4103353-4.5125027-7.4103353-4.5210762 0-5.2098115 3.5351272-5.2098115 7.1788517v13.8232904h-8.6849245v-27.9466524h8.3305544v3.8209096h.1200286c1.1431292-2.197666 4.0009523-4.5125027 8.2162415-4.5125027 8.7963796 0 10.4196231 5.784234 10.4196231 13.3088823z"/></g></svg></a></li>
            <li><a class="emerv-share__btn emerv-share__btn--mail" href="mailto:?body=%3Chttps%3A%2F%2Fwww.bonnearrivee.bj%2Fexplorez%2Fla-vie-semerveille-au-benin%3E" title="Partager par e-mail" aria-label="Partager par e-mail"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M22 4H2C.9 4 0 4.9 0 6v12c0 1.1.9 2 2 2h20c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zM7.25 14.43l-3.5 2c-.08.05-.17.07-.25.07-.17 0-.34-.1-.43-.25-.14-.24-.06-.55.18-.68l3.5-2c.24-.14.55-.06.68.18.14.24.06.55-.18.68zm4.75.07c-.1 0-.2-.03-.27-.08l-8.5-5.5c-.23-.15-.3-.46-.15-.7.15-.22.46-.3.7-.14L12 13.4l8.23-5.32c.23-.15.54-.08.7.15.14.23.07.54-.16.7l-8.5 5.5c-.08.04-.17.07-.27.07zm8.93 1.75c-.1.16-.26.25-.43.25-.08 0-.17-.02-.25-.07l-3.5-2c-.24-.13-.32-.44-.18-.68s.44-.32.68-.18l3.5 2c.24.13.32.44.18.68z"/></svg></a></li>
        </ul>
    </header>

    <div class="emerv-filters">
        <div class="emerv-filters__bar">
            <span class="emerv-filters__label">Filtrer par thème :</span>
            <span class="emerv-filters__count" id="emerv-count">29 lieux</span>
        </div>
        <div class="emerv-chips" role="group" aria-label="Filtres par catégorie">
@endverbatim
            @foreach($cats as $key => $label)
                <button type="button" class="emerv-chip" data-filter="{{ $key }}" aria-pressed="false">
                    <span class="emerv-chip__dot emerv-chip__dot--{{ $key }}" aria-hidden="true"></span>
                    <span>{{ $label }}</span>
                </button>
            @endforeach
@verbatim
            <button type="button" class="emerv-chip emerv-chip--reset" id="emerv-reset">Tout voir</button>
        </div>
    </div>

    <section class="emerv-grid" aria-label="Sélection d'escales à découvrir">
@endverbatim
        @php
            // Ces lieux prennent une tuile portrait (span 2 rows) — répartis
            // pour créer une mosaïque asymétrique à la Mayotte.
            $tallIds = ['ouidah', 'abomey', 'pendjari', 'ganvie', 'grand-popo', 'natitingou'];
        @endphp
        @foreach($sites as [$id, $name, $cat, $dept])
            @php
                $img = $imgById[$id] ?? $catFallback[$cat];
                $tall = in_array($id, $tallIds, true);
            @endphp
            <a class="emerv-tile @if($tall) emerv-tile--tall @endif" data-cat="{{ $cat }}" href="/explorez#{{ $id }}">
                <img class="emerv-tile__img" src="/assets/img/{{ $img }}" alt="{{ $name }}" loading="lazy" decoding="async">
                <div class="emerv-tile__caption">
                    <h2 class="emerv-tile__title">{{ $name }}</h2>
                    <p class="emerv-tile__sub">{{ $dept }}</p>
                </div>
            </a>
        @endforeach
@verbatim
        <p class="emerv-empty" id="emerv-empty" hidden>Aucun lieu ne correspond à ces filtres. <a href="#" id="emerv-empty-reset">Réinitialiser</a>.</p>
    </section>

</div>

<script>
(function(){
  var chips = document.querySelectorAll('.emerv-chip[data-filter]');
  var tiles = document.querySelectorAll('.emerv-tile');
  var reset = document.getElementById('emerv-reset');
  var count = document.getElementById('emerv-count');
  var empty = document.getElementById('emerv-empty');
  var emptyReset = document.getElementById('emerv-empty-reset');
  var active = new Set();

  function apply() {
    var showAll = active.size === 0;
    var visible = 0;
    tiles.forEach(function(t){
      var cat = t.getAttribute('data-cat');
      var show = showAll || active.has(cat);
      t.style.display = show ? '' : 'none';
      if (show) visible++;
    });
    count.textContent = visible + ' ' + (visible > 1 ? 'lieux' : 'lieu');
    empty.hidden = visible > 0;
  }

  chips.forEach(function(c){
    c.addEventListener('click', function(){
      var f = c.getAttribute('data-filter');
      if (active.has(f)) { active.delete(f); c.setAttribute('aria-pressed', 'false'); }
      else               { active.add(f);    c.setAttribute('aria-pressed', 'true');  }
      apply();
    });
  });

  function doReset(e){
    if (e) e.preventDefault();
    active.clear();
    chips.forEach(function(c){ c.setAttribute('aria-pressed', 'false'); });
    apply();
  }
  reset.addEventListener('click', doReset);
  emptyReset.addEventListener('click', doReset);
})();
</script>
@endverbatim
@endsection
