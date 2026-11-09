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
/* Le lanceur reste flottant puis « s'accroche » juste au-dessus du widget Bénin
   (bloc carte) du pied de page : il atteint le footer mais ne le recouvre pas. */
(function(){
  function init(){
    var el = document.querySelector('.ba-ecolaunch');
    if (!el) return;
    var target = document.querySelector('.site-footer .map-block')
              || document.querySelector('.map-block')
              || document.querySelector('footer.site-footer')
              || document.querySelector('footer');
    if (!target) return;

    var GAP = 16, ticking = false;
    function update(){
      ticking = false;
      var bottomPx = parseFloat(getComputedStyle(el).bottom) || 24;
      var launcherBottomY = window.innerHeight - bottomPx;   // bas du lanceur (px depuis le haut)
      var limitY = target.getBoundingClientRect().top - GAP; // ne pas dépasser le widget
      var push = Math.max(0, launcherBottomY - limitY);
      el.style.transform = 'translateY(-' + push + 'px)';
    }
    function onScroll(){ if (!ticking){ ticking = true; requestAnimationFrame(update); } }
    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onScroll, { passive: true });
    update();
  }
  if (document.readyState !== 'loading') { init(); } else { document.addEventListener('DOMContentLoaded', init); }
})();
</script>
