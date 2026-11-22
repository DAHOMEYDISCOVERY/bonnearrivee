{{-- Écosystème « Bonne Arrivée » — bouton flottant rond, en bas à droite (même bouton que sur
     petit-train-cotonou.com). Un appui déplie les trois sites de l'écosystème en cartes empilées ;
     un second appui, Échap ou un clic ailleurs les referme. Une bulle d'invite présente tour à tour
     un message général puis chaque marque avec son logo.
     Touche locale : le bouton est cerclé des trois couleurs du drapeau béninois, et chaque carte
     porte la pastille de couleur de sa marque.
     Logos servis depuis public/assets/img/partenaires/ ; à défaut, depuis le site de la marque.
     Rendu en position fixe, visible sur toute la page ; il s'arrête au-dessus du repère « BÉNIN »
     de la carte du pied de page. --}}
@php
    $ecoLinks = [
        [
            'href'  => 'https://dahomeydiscovery.com',
            'brand' => '#00844d',
            'title' => 'Dahomey Discovery',
            'sub'   => 'L’agence partenaire : hébergements, véhicules, circuits et transferts partout au Bénin.',
            'logo'  => '/assets/img/partenaires/ba-logo-dahomey-discovery.webp',
            'logo2' => 'https://g4sw3krx8jwjylss.public.blob.vercel-storage.com/dahomey-discovery-1.png',
        ],
        [
            'href'  => 'https://www.wanilo.studio',
            'brand' => '#fcd116',
            'title' => 'Wanilo Studio',
            'sub'   => 'Studio podcast, vidéo et photo à Cotonou. Matériel professionnel et équipe dédiée.',
            'logo'  => '/assets/img/partenaires/ba-logo-wanilo-studio.webp',
            'logo2' => 'https://g4sw3krx8jwjylss.public.blob.vercel-storage.com/wanilo-studio-1.png',
        ],
        [
            'href'  => 'https://www.petit-train-cotonou.com',
            'brand' => '#e8112d',
            'title' => 'Le Petit Train de Cotonou',
            'sub'   => 'La visite guidée de Cotonou, de la place de l’Amazone à la route des Pêches.',
            'logo'  => '/assets/img/partenaires/ba-logo-petit-train-cotonou.webp',
            'logo2' => 'https://www.petit-train-cotonou.com/icons/client-180.png',
        ],
    ];
@endphp

<div class="ba-eco" data-ba-eco>
    <nav class="ba-eco__nav" id="ba-eco-liste" aria-label="Notre écosystème" hidden>
        <ul>
            @foreach($ecoLinks as $i => $l)
                <li style="--d: {{ (count($ecoLinks) - 1 - $i) * 60 }}ms; --brand: {{ $l['brand'] }};">
                    <a href="{{ $l['href'] }}" target="_blank" rel="noopener noreferrer">
                        <span class="ba-eco__logo"><img src="{{ $l['logo'] }}" data-secours="{{ $l['logo2'] }}" alt="" loading="lazy" decoding="async" onerror="if(this.dataset.secours){this.src=this.dataset.secours;this.removeAttribute('data-secours')}else{this.parentNode.classList.add('is-vide');this.remove()}"></span>
                        <span class="ba-eco__txt"><span class="ba-eco__nom"><i aria-hidden="true"></i>{{ $l['title'] }}</span><span class="ba-eco__sub">{{ $l['sub'] }}</span></span>
                        <svg class="ba-eco__go" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        <span class="ba-eco__sr"> (nouvel onglet)</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </nav>
    <div class="ba-eco__pied">
        <span class="ba-eco__bulle" aria-hidden="true"><i></i><img alt="" hidden><span class="ba-eco__bulle-t">Besoin d’autres services ?</span></span>
        <button type="button" class="ba-eco__btn" aria-expanded="false" aria-controls="ba-eco-liste" aria-label="Voir nos sociétés">
            <svg class="ba-eco__ic ba-eco__ic--ouvrir" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2C6.486 2 2 5.94 2 10.75c0 2.64 1.36 5.01 3.5 6.6V22l3.9-2.14c.83.2 1.71.3 2.6.3 5.514 0 10-3.94 10-8.75S17.514 2 12 2zm1 11.75l-2.5-2.67-4.88 2.67 5.38-5.75 2.56 2.67 4.82-2.67-5.38 5.75z"></path></svg>
            <svg class="ba-eco__ic ba-eco__ic--fermer" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18"></path></svg>
        </button>
    </div>
</div>

<style>
.ba-ecolaunch{display:none!important}
.ba-eco{position:fixed;right:1.25rem;bottom:calc(1.25rem + env(safe-area-inset-bottom,0px));z-index:900;display:flex;flex-direction:column;align-items:flex-end;gap:.75rem;font-family:inherit}
@media (min-width:768px){.ba-eco{right:1.75rem;bottom:calc(1.75rem + env(safe-area-inset-bottom,0px))}}
.ba-eco__nav[hidden]{display:none}
.ba-eco__nav ul{display:flex;flex-direction:column;align-items:flex-end;gap:.625rem;margin:0;padding:0;list-style:none}
.ba-eco__nav li{margin:0;animation:baEcoPile .34s cubic-bezier(.2,.9,.3,1.15) both;animation-delay:var(--d,0ms)}
@keyframes baEcoPile{from{opacity:0;transform:translateY(14px) scale(.96)}to{opacity:1;transform:none}}
.ba-eco__nav a{display:flex;align-items:center;gap:.875rem;width:min(80vw,300px);padding:.75rem 1rem;border-radius:16px;background:#fff;color:#1d2a23!important;text-decoration:none!important;box-shadow:0 0 0 1px rgba(16,40,28,.08),0 18px 40px -18px rgba(16,40,28,.45);transition:transform .25s cubic-bezier(.2,.7,.3,1)}
.ba-eco__nav a:hover,.ba-eco__nav a:focus-visible{transform:translateX(-4px)}
.ba-eco__nav a:focus-visible{outline:2px solid #00844d;outline-offset:2px}
.ba-eco__logo{display:grid;place-items:center;flex:0 0 2.75rem;width:2.75rem;height:2.75rem;border-radius:12px;overflow:hidden;background:#fff;box-shadow:inset 0 0 0 1px rgba(16,40,28,.1)}
.ba-eco__logo img{width:100%;height:100%;object-fit:contain;padding:.35rem}
.ba-eco__logo.is-vide{background:var(--brand)}
.ba-eco__txt{display:grid;gap:.15rem;min-width:0}
.ba-eco__nom{display:flex;align-items:center;gap:.45rem;font-size:.95rem;font-weight:700;line-height:1.25;color:#1d2a23}
.ba-eco__nom i{flex:0 0 auto;width:.5rem;height:.5rem;border-radius:50%;background:var(--brand);box-shadow:0 0 0 2px rgba(0,0,0,.04)}
.ba-eco__sub{display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;font-size:.76rem;line-height:1.4;color:#5d6b64}
.ba-eco__go{flex:0 0 auto;width:.9rem;height:.9rem;margin-left:auto;color:#8a968f}
.ba-eco__sr{position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0 0 0 0);white-space:nowrap}
.ba-eco__pied{position:relative}
.ba-eco__btn{position:relative;display:grid;place-items:center;width:3.5rem;height:3.5rem;padding:0;border:0;border-radius:50%;cursor:pointer;color:#fff;background:#00844d;box-shadow:0 14px 30px -10px rgba(0,132,77,.75);transition:transform .2s,background .2s}
.ba-eco__btn::before{content:"";position:absolute;inset:-4px;border-radius:50%;background:conic-gradient(from -90deg,#00844d 0 33.3%,#fcd116 0 66.6%,#e8112d 0);-webkit-mask:radial-gradient(farthest-side,transparent calc(100% - 3px),#000 calc(100% - 3px));mask:radial-gradient(farthest-side,transparent calc(100% - 3px),#000 calc(100% - 3px));pointer-events:none}
.ba-eco__btn:hover{background:#006b3e}
.ba-eco__btn:active{transform:scale(.95)}
.ba-eco__btn:focus-visible{outline:2px solid #1d2a23;outline-offset:6px}
.ba-eco__ic{width:1.5rem;height:1.5rem}
.ba-eco__ic--fermer,.ba-eco.is-ouvert .ba-eco__ic--ouvrir{display:none}
.ba-eco.is-ouvert .ba-eco__ic--fermer{display:block}
.ba-eco__bulle{position:absolute;right:calc(100% + 14px);top:50%;display:flex;align-items:center;gap:.5rem;max-width:calc(100vw - 7rem);padding:.5rem .875rem .5rem .625rem;border-radius:12px;background:#fff;font-size:.86rem;font-weight:700;color:#1d2a23;white-space:nowrap;box-shadow:0 0 0 1px rgba(16,40,28,.08),0 14px 30px -14px rgba(16,40,28,.45);opacity:0;visibility:hidden;transform:translate(8px,-50%);transition:opacity .3s,transform .3s,visibility .3s;pointer-events:none}
.ba-eco__bulle img{flex:0 0 auto;width:1.5rem;height:1.5rem;border-radius:6px;object-fit:contain}
.ba-eco__bulle img[hidden],.ba-eco__bulle img:not([hidden])+.ba-eco__bulle-t:not(:empty)~i{display:none}
.ba-eco__bulle-t{overflow:hidden;text-overflow:ellipsis}
.ba-eco__bulle i{flex:0 0 auto;width:.5rem;height:.5rem;border-radius:50%;background:#e8112d}
.ba-eco__bulle::after{content:"";position:absolute;right:-6px;top:50%;width:.75rem;height:.75rem;background:#fff;transform:translateY(-50%) rotate(45deg);box-shadow:1px -1px 0 rgba(16,40,28,.08)}
.ba-eco.is-bulle .ba-eco__bulle{opacity:1;visibility:visible;transform:translate(0,-50%)}
@media (prefers-reduced-motion:reduce){.ba-eco__nav li{animation:none}.ba-eco__btn,.ba-eco__nav a,.ba-eco__bulle{transition:none}}
@media print{.ba-eco{display:none}}
</style>

<script>
/* Ouverture / fermeture du bouton, petite bulle d'invite une fois par visite, et arrêt du bouton
   au-dessus du repère « BÉNIN » de la carte du pied de page (SVG world-benin, viewBox 598.8 x 510.2). */
(function(){
  var VB_W = 598.8, VB_H = 510.2, MARK_Y = 332, GAP = 12;
  function init(){
    var el = document.querySelector('[data-ba-eco]');
    if (!el) return;
    try { if (el.parentNode !== document.body) document.body.appendChild(el); } catch (e) {}
    var btn = el.querySelector('.ba-eco__btn'), nav = el.querySelector('.ba-eco__nav');

    function ouvrir(oui){
      el.classList.toggle('is-ouvert', oui);
      el.classList.remove('is-bulle');
      nav.hidden = !oui;
      btn.setAttribute('aria-expanded', oui ? 'true' : 'false');
      btn.setAttribute('aria-label', oui ? 'Fermer' : 'Voir nos sociétés');
    }
    btn.addEventListener('click', function(){ ouvrir(btn.getAttribute('aria-expanded') !== 'true'); });
    document.addEventListener('keydown', function(e){ if (e.key === 'Escape' && el.classList.contains('is-ouvert')) { ouvrir(false); btn.focus(); } });
    document.addEventListener('click', function(e){ if (el.classList.contains('is-ouvert') && !el.contains(e.target)) ouvrir(false); });

    /* Bulle d'invite, comme sur petit-train-cotonou.com : un message général, puis chaque marque
       à tour de rôle avec son logo. Elle tourne tant que la liste est fermée et l'onglet visible. */
    var bulle = el.querySelector('.ba-eco__bulle'), bImg = bulle.querySelector('img'), bTxt = bulle.querySelector('.ba-eco__bulle-t'), bPoint = bulle.querySelector('i');
    var msgs = [{ t: 'Besoin d’autres services ?' }];
    Array.prototype.forEach.call(nav.querySelectorAll('a'), function (a) {
        var nom = a.querySelector('.ba-eco__nom').textContent.trim(), im = a.querySelector('.ba-eco__logo img');
        msgs.push({ t: 'Découvrez ' + nom, img: im ? im.getAttribute('src') : '' });
    });
    var n = 0, minuteur = null;
    function suivant() {
        if (el.classList.contains('is-ouvert') || document.hidden) { minuteur = setTimeout(suivant, 3000); return; }
        var m = msgs[n++ % msgs.length];
        bTxt.textContent = m.t;
        if (m.img) { bImg.src = m.img; bImg.hidden = false; bPoint.style.display = 'none'; } else { bImg.hidden = true; bPoint.style.display = ''; }
        el.classList.add('is-bulle');
        minuteur = setTimeout(function () { el.classList.remove('is-bulle'); minuteur = setTimeout(suivant, 3000); }, 4500);
    }
    if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) minuteur = setTimeout(suivant, 3000);

    var img = document.querySelector('.site-footer .world-map-footer') || document.querySelector('.world-map-footer');
    var fallback = document.querySelector('.site-footer .map-block') || document.querySelector('footer.site-footer') || document.querySelector('footer');
    if (!img && !fallback) return;
    var ticking = false;
    function stopLineY(){
      if (img) {
        var r = img.getBoundingClientRect();
        if (r.height > 0 && r.width > 0) {
          var scale = Math.min(r.width / VB_W, r.height / VB_H);
          return r.top + (r.height - VB_H * scale) / 2 + MARK_Y * scale;
        }
      }
      return fallback.getBoundingClientRect().top;
    }
    function apply(){
      ticking = false;
      var bottomPx = parseFloat(getComputedStyle(el).bottom) || 20;
      var push = Math.max(0, (window.innerHeight - bottomPx) - (stopLineY() - GAP));
      el.style.transform = push ? 'translateY(-' + push + 'px)' : '';
    }
    function onChange(){ if (!ticking){ ticking = true; requestAnimationFrame(apply); } }
    window.addEventListener('scroll', onChange, { passive: true });
    window.addEventListener('resize', onChange, { passive: true });
    window.addEventListener('load', onChange);
    if (img) img.addEventListener('load', onChange);
    setTimeout(apply, 600);
    apply();
  }
  if (document.readyState !== 'loading') { init(); } else { document.addEventListener('DOMContentLoaded', init); }
})();
</script>
