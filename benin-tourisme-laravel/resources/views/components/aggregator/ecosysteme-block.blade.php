{{-- Écosystème « Bonne Arrivée » — lanceur flottant : trois boutons empilés à
     droite (vert / jaune / rouge, couleurs du Bénin) qui ouvrent les sites des
     trois marques. Remplace l'ancienne section « Un portail, trois marques ».
     Rendu en position fixe, visible sur toute la page. --}}
@php
    $ecoLinks = [
        [
            'href'  => 'https://dahomeydiscovery.com',
            'brand' => '#00844d',   // vert
            'ink'   => '#ffffff',
            'title' => 'Dahomey Discovery',
            'sub'   => 'Agence réceptive',
            'icon'  => 'compass',
        ],
        [
            'href'  => 'https://www.wanilo.studio',
            'brand' => '#fcd116',   // jaune
            'ink'   => '#1a1405',
            'title' => 'Wanilo Studio',
            'sub'   => 'Studio créatif',
            'icon'  => 'camera',
        ],
        [
            'href'  => 'https://www.petit-train-cotonou.com',
            'brand' => '#e8112d',   // rouge
            'ink'   => '#ffffff',
            'title' => 'Le Petit Train de Cotonou',
            'sub'   => 'Loisirs urbains',
            'icon'  => 'train',
        ],
    ];

    $ecoIcons = [
        'compass' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"/></svg>',
        'camera'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>',
        'train'   => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="3" width="16" height="14" rx="3"/><path d="M4 11h16M9 3v8m6-8v8"/><circle cx="8.5" cy="14" r="1"/><circle cx="15.5" cy="14" r="1"/><path d="M6.5 21l2-3m9 3-2-3"/></svg>',
    ];
@endphp

<nav class="ba-ecolaunch" aria-label="Notre écosystème">
    @foreach($ecoLinks as $l)
        <a class="ba-ecolaunch__btn"
           href="{{ $l['href'] }}"
           target="_blank"
           rel="noopener noreferrer"
           style="--brand: {{ $l['brand'] }}; --ink: {{ $l['ink'] }};"
           aria-label="{{ $l['title'] }} — {{ $l['sub'] }} (nouvel onglet)">
            <span class="ba-ecolaunch__ic" aria-hidden="true">{!! $ecoIcons[$l['icon']] !!}</span>
            <span class="ba-ecolaunch__label">{{ $l['title'] }}<span class="ba-ecolaunch__sub">{{ $l['sub'] }}</span></span>
            <span class="ba-ecolaunch__go" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M9 7h8v8"/></svg></span>
        </a>
    @endforeach
</nav>

<script>
/* Desktop : le lanceur flotte et s'arrête juste au-dessus du repère « BÉNIN »
   (intégré au SVG world-benin, viewBox 598.8 x 510.2, marqueur ~y=345 ; on tient
   compte du cadrage xMidYMid meet).
   Mobile : il ne flotte pas par-dessus le contenu — il est ancré tout en bas de
   la page (position absolute) pour ne jamais recouvrir les liens du footer. */
(function(){
  var VB_W = 598.8, VB_H = 510.2, MARK_Y = 332, GAP = 12, MOBILE = 820;
  function init(){
    var el = document.querySelector('.ba-ecolaunch');
    if (!el) return;
    // Rattaché au <body> : positionnement fiable (pas de parent en overflow/transform).
    try { if (el.parentNode !== document.body) document.body.appendChild(el); } catch (e) {}
    var img = document.querySelector('.site-footer .world-map-footer') || document.querySelector('.world-map-footer');
    var fallback = document.querySelector('.site-footer .map-block') || document.querySelector('footer.site-footer') || document.querySelector('footer');
    if (!img && !fallback) return;

    var ticking = false;
    function isMobile(){ return window.matchMedia('(max-width:' + MOBILE + 'px)').matches; }
    function stopLineY(){
      if (img) {
        var r = img.getBoundingClientRect();
        if (r.height > 0 && r.width > 0) {
          var scale = Math.min(r.width / VB_W, r.height / VB_H);
          var offY = (r.height - VB_H * scale) / 2;
          return r.top + offY + MARK_Y * scale;
        }
      }
      return fallback.getBoundingClientRect().top;
    }
    function apply(){
      ticking = false;
      if (isMobile()) {
        // Ancré au bas du footer (pas de flottement par-dessus les liens, pas de
        // dépassement sous le footer).
        var fEl = document.querySelector('footer.site-footer') || document.querySelector('.site-footer') || fallback;
        var footerBottomDoc = fEl.getBoundingClientRect().bottom + window.pageYOffset;
        el.style.position = 'absolute';
        el.style.bottom = 'auto';
        el.style.transform = 'none';
        el.style.top = Math.round(footerBottomDoc - el.offsetHeight - 18) + 'px';
      } else {
        el.style.position = '';
        el.style.top = '';
        el.style.bottom = '';
        var bottomPx = parseFloat(getComputedStyle(el).bottom) || 24;
        var push = Math.max(0, (window.innerHeight - bottomPx) - (stopLineY() - GAP));
        el.style.transform = 'translateY(-' + push + 'px)';
      }
    }
    function onChange(){ if (!ticking){ ticking = true; requestAnimationFrame(apply); } }
    window.addEventListener('scroll', onChange, { passive: true });
    window.addEventListener('resize', onChange, { passive: true });
    window.addEventListener('load', onChange);
    if (img) img.addEventListener('load', onChange);
    setTimeout(apply, 600); // recalage après chargement des images du footer
    apply();
  }
  if (document.readyState !== 'loading') { init(); } else { document.addEventListener('DOMContentLoaded', init); }
})();
</script>
