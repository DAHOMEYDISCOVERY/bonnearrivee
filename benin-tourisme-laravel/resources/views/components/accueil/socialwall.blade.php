{{-- Galerie #bonnearrivee de la page d'accueil (section avant le pied de page).
     Disposition d'origine de Bonne Arrivée (deux grandes tuiles, titre dans la
     grille), l'artisane au centre. Au défilement, la galerie reste à l'écran :
     l'artisane grandit jusqu'à remplir l'écran pendant que les autres photos
     glissent vers elle et s'effacent ; un titre apparaît en fin de zoom.
     Photos de Bonne Arrivée uniquement, servies par le site, sans aucun lien.
     L'ancien mur Instagram est conservé dans socialwall-instagram-ancien.blade.php
     (non affiché). Ce fichier n'est plus généré par tools/convert.py. --}}
@php
    // [image, texte alternatif, légende, classe de placement]
    $bag = [
        ['/assets/img/ba-papillon-640x640.webp', 'Danseuse en costume de papillon aux couleurs vives', 'Costumes de fête', 'p1'],
        ['/assets/img/ba-danse-640x640.webp', 'Danseuse en jupe de raphia', 'Danses traditionnelles', 'p2'],
        ['/assets/img/ba-elephants-640x640.webp', 'Éléphants au point d’eau', 'Parc de la Pendjari', 'p3'],
        ['/assets/img/ba-cavalier-640x640.webp', 'Cavalier en tenue d’apparat', 'Cavaliers du Nord', 'p4'],
        ['/assets/img/ba-portrait-714x714.webp', 'Portrait au visage peint, une bougie sur la tête', 'Cérémonie', 'p5'],
        ['/assets/img/artisanat-1200x1200.webp', 'Une artisane et sa fille tressent des paniers', 'Vannerie', 'centre'],
        ['/assets/img/ba-zangbeto-640x640.webp', 'Sortie de zangbeto, gardien de la nuit', 'Zangbeto', 'p6'],
        ['/assets/img/ba-tata-somba-640x640.webp', 'Tata somba sous les arbres', 'Tata somba', 'p7'],
        ['/assets/img/ba-statues-640x640.webp', 'Statues de bronze sur une place', 'Mémoire', 'p8'],
        ['/assets/img/ba-rocher-640x640.webp', 'Rocher au coucher du soleil', 'Collines', 'p9'],
    ];
@endphp
<div class="page-section page-section-5" id="pageSection-5">
<section data-uuid="ea19f00a-7532-4494-b021-59e424cbdc80" class="section marg-top-none marg-bottom-none is-full-width bag" aria-labelledby="bag-titre">
  <div class="bag__piste" data-bag>
    <div class="bag__scene">
      <div class="bag__grille">
        <div class="bag__titre-cell">
          <p class="bag__surtitre">Le Bénin en images</p>
          <h2 class="bag__titre" id="bag-titre">#bonnearrivee</h2>
          <span class="bag__filet" aria-hidden="true"><i></i><i></i><i></i></span>
        </div>
        @foreach ($bag as [$src, $alt, $leg, $pos])
          <figure class="bag__tuile bag__tuile--{{ $pos }}{{ $pos === 'centre' ? ' is-centre' : '' }}">
            <img src="{{ $src }}" alt="{{ $alt }}" loading="{{ $pos === 'centre' ? 'eager' : 'lazy' }}" decoding="async">
            @if ($pos === 'centre')<img class="bag__hd" src="" data-src="/assets/img/artisanat-1920x1080.webp" alt="" aria-hidden="true" decoding="async">@endif
            <figcaption>{{ $leg }}</figcaption>
          </figure>
        @endforeach
      </div>
      <div class="bag__final" aria-hidden="true">
        <p class="bag__final-sur">#bonnearrivee</p>
        <p class="bag__final-titre">Bonne arrivée<br><em>au Bénin</em></p>
        <p class="bag__final-sous">Des savoir-faire, des visages, une terre à vivre.</p>
      </div>
      <div class="bag__progres" aria-hidden="true"><span></span></div>
    </div>
  </div>
</section>
</div>

@verbatim
<style>
/* ===== Galerie #bonnearrivee — styles scopés .bag ===== */
.bag{--bag-gap:18px;--bag-r:22px;background:linear-gradient(180deg,#fff 0%,#f7f6f1 55%,#fff 100%);padding:0;overflow:visible}
.bag__piste{position:relative;overflow:hidden;overflow:clip}
.bag__piste.is-zoom .bag__tuile figcaption{opacity:0!important;transform:translateY(6px)!important}
.bag__piste.is-zoom .bag__tuile:hover img{transform:none}
.bag__scene{position:relative;padding:72px 0;will-change:transform}
.bag__grille{display:grid;grid-template-columns:repeat(6,1fr);gap:var(--bag-gap);max-width:1400px;margin:0 auto;padding:0 24px}
.bag__tuile{position:relative;margin:0;aspect-ratio:1/1;border-radius:var(--bag-r);overflow:hidden;background:#e9ece6;box-shadow:0 1px 2px rgba(20,32,26,.06),0 18px 40px -26px rgba(11,61,44,.45);transform-origin:50% 50%;will-change:transform,opacity;isolation:isolate}
.bag__tuile img{position:absolute;inset:0;width:100%;height:100%;max-width:none;object-fit:cover;transition:transform .9s cubic-bezier(.2,.7,.2,1)}
.bag__tuile .bag__hd{opacity:0;transition:opacity .4s ease}
.bag__tuile figcaption{position:absolute;left:12px;bottom:12px;z-index:2;padding:7px 12px;border-radius:999px;font-size:.74rem;font-weight:600;letter-spacing:.02em;color:#fff;background:rgba(12,20,16,.42);backdrop-filter:blur(10px) saturate(140%);-webkit-backdrop-filter:blur(10px) saturate(140%);box-shadow:inset 0 0 0 1px rgba(255,255,255,.18);opacity:0;transform:translateY(6px);transition:opacity .35s ease,transform .35s ease;pointer-events:none}
.bag__tuile:hover img{transform:scale(1.06)}
.bag__tuile:hover figcaption{opacity:1;transform:none}
.bag__tuile::after{content:"";position:absolute;inset:0;border-radius:inherit;box-shadow:inset 0 0 0 1px rgba(255,255,255,.12);pointer-events:none}
/* Placement (6 colonnes x 3 rangées) — l'artisane au centre */
.bag__titre-cell{grid-column:3/5;grid-row:1/2;display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;will-change:transform,opacity}
.bag__tuile--p1{grid-column:1/2;grid-row:1/2}
.bag__tuile--p2{grid-column:2/3;grid-row:1/2}
.bag__tuile--p3{grid-column:5/6;grid-row:1/2}
.bag__tuile--p4{grid-column:6/7;grid-row:1/2}
.bag__tuile--p5{grid-column:1/3;grid-row:2/4;aspect-ratio:auto}
.bag__tuile--centre{grid-column:3/5;grid-row:2/4;z-index:5;aspect-ratio:auto}
.bag__tuile--p6{grid-column:5/6;grid-row:2/3}
.bag__tuile--p7{grid-column:6/7;grid-row:2/3}
.bag__tuile--p8{grid-column:5/6;grid-row:3/4}
.bag__tuile--p9{grid-column:6/7;grid-row:3/4}
.bag__surtitre{margin:0 0 10px;font-size:.72rem;font-weight:700;letter-spacing:.28em;text-transform:uppercase;color:var(--benin-green,#00a05d)}
.bag__titre{margin:0;font-family:"Playfair Display",Georgia,serif;font-weight:500;font-style:italic;font-size:clamp(2rem,3.6vw,3.6rem);line-height:1;letter-spacing:-.01em;color:#14201a}
.bag__filet{display:flex;gap:4px;margin-top:16px}
.bag__filet i{display:block;width:26px;height:3px;border-radius:2px;background:var(--benin-green,#00a05d)}
.bag__filet i:nth-child(2){background:var(--benin-yellow,#fcd116)}
.bag__filet i:nth-child(3){background:var(--benin-red,#e8112d)}
/* Titre final, sur la photo plein écran */
.bag__final{position:absolute;left:0;right:0;z-index:20;display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;color:#fff;padding:0 24px;opacity:0;pointer-events:none;background:radial-gradient(60% 55% at 50% 50%,rgba(6,14,10,.48),rgba(6,14,10,0) 70%)}
.bag__final-sur{margin:0 0 14px;font-size:.78rem;font-weight:700;letter-spacing:.3em;text-transform:uppercase;color:var(--benin-yellow,#fcd116)}
.bag__final-titre{margin:0;font-family:"Playfair Display",Georgia,serif;font-weight:500;font-size:clamp(2.6rem,7vw,6.4rem);line-height:.98;letter-spacing:-.015em;text-shadow:0 10px 40px rgba(0,0,0,.35)}
.bag__final-titre em{font-style:italic;color:#fff}
.bag__final-sous{margin:18px 0 0;font-size:clamp(.95rem,1.3vw,1.15rem);color:rgba(255,255,255,.88)}
.bag__progres{position:absolute;left:50%;z-index:21;width:min(220px,40vw);height:3px;border-radius:3px;background:rgba(20,32,26,.12);overflow:hidden;transform:translateX(-50%);opacity:0}
.bag__progres span{display:block;height:100%;width:100%;transform-origin:0 50%;transform:scaleX(0);background:linear-gradient(90deg,var(--benin-green,#00a05d) 0 33.3%,var(--benin-yellow,#fcd116) 33.3% 66.6%,var(--benin-red,#e8112d) 66.6%)}
/* Tablette et téléphone : même disposition, resserrée (4 colonnes) */
@media (max-width:900px){
  .bag{--bag-gap:8px;--bag-r:14px}
  .bag__scene{padding:48px 0}
  .bag__grille{grid-template-columns:repeat(4,1fr);padding:0 16px}
  .bag__titre-cell{grid-column:1/5;grid-row:1/2;padding:6px 0 10px}
  .bag__tuile--p1{grid-column:1/2;grid-row:2/3}
  .bag__tuile--p2{grid-column:2/3;grid-row:2/3}
  .bag__tuile--p3{grid-column:3/4;grid-row:2/3}
  .bag__tuile--p4{grid-column:4/5;grid-row:2/3}
  .bag__tuile--p6{grid-column:1/2;grid-row:3/4}
  .bag__tuile--p7{grid-column:1/2;grid-row:4/5}
  .bag__tuile--centre{grid-column:2/4;grid-row:3/5}
  .bag__tuile--p8{grid-column:4/5;grid-row:3/4}
  .bag__tuile--p9{grid-column:4/5;grid-row:4/5}
  .bag__tuile--p5{grid-column:1/5;grid-row:5/6;aspect-ratio:auto}
  .bag__tuile--p5{aspect-ratio:2/1}
  .bag__tuile figcaption{display:none}
}
@media (prefers-reduced-motion:reduce){.bag *{transition:none!important}}
</style>
<script>
/* Galerie #bonnearrivee : la galerie reste à l'écran pendant que l'artisane
   grandit jusqu'au plein écran ; les autres photos glissent vers elle en
   grandissant et s'effacent (même transition que le Petit Train de Cotonou).
   La scène est maintenue à l'écran en JS : position:sticky ne fonctionne pas
   sur cette page. Désactivé si l'internaute demande moins d'animations. */
(function () {
  'use strict';
  var piste = document.querySelector('[data-bag]');
  if (!piste) return;
  var scene = piste.querySelector('.bag__scene');
  var centre = scene.querySelector('.bag__tuile.is-centre');
  var autres = Array.prototype.slice.call(scene.querySelectorAll('.bag__tuile:not(.is-centre), .bag__titre-cell'));
  var hd = centre && centre.querySelector('.bag__hd');
  var fin = scene.querySelector('.bag__final');
  var barre = scene.querySelector('.bag__progres');
  var barreIn = barre && barre.querySelector('span');
  if (!centre) return;
  if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

  var m = null, attente = false;
  function raz() {
    scene.style.transform = '';
    centre.style.transform = ''; centre.style.borderRadius = '';
    autres.forEach(function (el) { el.style.transform = ''; el.style.opacity = ''; });
  }
  function mesurer() {
    raz();
    piste.style.height = '';
    var vw = document.documentElement.clientWidth, vh = window.innerHeight;
    var rs = scene.getBoundingClientRect();
    var H = rs.height;
    var course = Math.round(vh * 1.8);
    // Moment où la galerie se fige : centrée à l'écran (ou calée en haut si plus haute).
    var debut = H <= vh ? (vh - H) / 2 : Math.min(0, (vh - H) / 2);
    // La zone de la galerie couvre tout l'écran jusqu'à la fin du zoom : la photo
    // agrandie y reste contenue et ne passe jamais par-dessus le pied de page.
    piste.style.height = (course + Math.max(H, vh - debut)) + 'px';
    var rc = centre.getBoundingClientRect();
    var cx = rc.left + rc.width / 2, cy = rc.top - rs.top + rc.height / 2; // dans la scène
    var cible = function (el) {
      var r = el.getBoundingClientRect();
      return { dx: cx - (r.left + r.width / 2), dy: cy - (r.top - rs.top + r.height / 2), s: rc.width / Math.max(1, r.width) };
    };
    m = {
      vw: vw, vh: vh, H: H, debut: debut, course: course,
      // la photo centrale vient au centre de l'écran et le couvre entièrement
      cdx: vw / 2 - cx, cdy: (vh / 2 - debut) - cy,
      cs: Math.max(vw / rc.width, vh / rc.height) * 1.02,
      autres: autres.map(cible)
    };
    if (fin) { fin.style.top = (-debut) + 'px'; fin.style.height = vh + 'px'; }
    if (barre) { barre.style.top = (vh - debut - 34) + 'px'; }
  }
  function maj() {
    attente = false;
    if (!m) return;
    if (Math.abs(scene.getBoundingClientRect().height - m.H) > 2 && !scene.style.transform) { mesurer(); }
    var haut = piste.getBoundingClientRect().top;
    var decale = Math.min(m.course, Math.max(0, m.debut - haut));
    var p = decale / m.course;
    piste.classList.toggle('is-zoom', p > 0);
    scene.style.transform = decale ? 'translate3d(0,' + decale.toFixed(1) + 'px,0)' : '';
    // Les autres photos : glissent vers l'artisane et s'effacent (60 % premiers du parcours).
    var q = Math.min(1, p / 0.6), qe = 1 - Math.pow(1 - q, 2);
    autres.forEach(function (el, i) {
      var t = m.autres[i];
      if (!p) { el.style.transform = ''; el.style.opacity = ''; return; }
      el.style.transform = 'translate3d(' + (t.dx * qe).toFixed(1) + 'px,' + (t.dy * qe).toFixed(1) + 'px,0) scale(' + (1 + (t.s - 1) * qe).toFixed(4) + ')';
      el.style.opacity = Math.max(0, 1 - q * 1.15).toFixed(3);
    });
    // L'artisane : accélère jusqu'au plein écran.
    var e = p * p * p;
    centre.style.transform = p ? 'translate3d(' + (m.cdx * e).toFixed(1) + 'px,' + (m.cdy * e).toFixed(1) + 'px,0) scale(' + (1 + (m.cs - 1) * e).toFixed(4) + ')' : '';
    centre.style.borderRadius = p ? (22 * (1 - e) / (1 + (m.cs - 1) * e)).toFixed(2) + 'px' : '';
    if (hd) {
      if (!hd.getAttribute('src') && haut < m.vh * 2.5) hd.setAttribute('src', hd.getAttribute('data-src'));
      hd.style.opacity = (e > 0.04 && hd.complete && hd.naturalWidth) ? '1' : '0';
    }
    if (fin) {
      var f = Math.min(1, Math.max(0, (p - 0.82) / 0.14));
      fin.style.opacity = f.toFixed(3);
      fin.style.transform = 'translate3d(0,' + ((1 - f) * 24).toFixed(1) + 'px,0)';
    }
    if (barre) {
      barre.style.opacity = (p > 0 && p < 1) ? '1' : '0';
      barreIn.style.transform = 'scaleX(' + p.toFixed(4) + ')';
    }
  }
  function demande() { if (!attente) { attente = true; window.requestAnimationFrame(maj); } }
  var tRes;
  window.addEventListener('scroll', demande, { passive: true });
  window.addEventListener('resize', function () { clearTimeout(tRes); tRes = setTimeout(function () { mesurer(); maj(); }, 150); });
  window.addEventListener('load', function () { mesurer(); maj(); });
  if (hd) hd.addEventListener('load', demande);
  mesurer(); maj();
})();
</script>
@endverbatim

                    </div>
