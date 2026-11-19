/* Bonne Arrivée — couche premium « Organisez » (/sejourner).
   Réorganise à l'affichage des blocs existants (textes de l'admin conservés :
   les nœuds sont déplacés, jamais réécrits). */
(function () {
  'use strict';
  function $$(s, r) { return Array.prototype.slice.call((r || document).querySelectorAll(s)); }
  function txt(e) { return e ? e.textContent.replace(/\s+/g, ' ').trim() : ''; }
  function el(t, c, h) { var e = document.createElement(t); if (c) e.className = c; if (h != null) e.innerHTML = h; return e; }
  var IMG = '/assets/img/';
  var TT = '.generic-block-texts h2,.generic-block-texts h3,.generic-block-title';
  var TUILES = {
    'guides-et-brochures': 'tata-somba-640x480.webp',
    'les-liens-utiles': 'frontiere-640x480.webp',
    'comment-venir': 'aeroport-640x480.webp',
    'vie-pratique': 'marche-640x480.webp',
    'avant-de-partir': 'cotonou-640x480.webp',
    'nous-contacter': 'pirogue-640x480.webp',
    'services-en-pratique': 'zemidjan-640x480.webp',
    'argent-et-paiements': 'franc-cfa-640x480.webp',
    'sante-et-vaccins': 'sante-640x480.webp',
    'formalites-et-visa': 'frontiere-640x480.webp',
    'quand-partir': 'sunset-640x480.webp',
    'se-deplacer': 'gare-routiere-640x480.webp',
    'securite': 'cotonou-640x480.webp'
  };

  function run() {
    var main = document.querySelector('main');
    if (!main || document.body.classList.contains('bao')) return;
    document.body.classList.add('bao');

    /* 1. Sections de texte titrées consécutives -> étapes éditoriales */
    var secs = $$('.page-section', main).filter(function (s) { return s.parentElement && !s.closest('.bao-steps'); });
    function estTexte(s) {
      var ct = s.querySelector('.woody-component-custom-text.tpl_01');
      if (!ct || s.querySelector('img,figure,.benin-pratique,form,iframe,.card')) return false;
      return !!txt(s.querySelector(TT)) && !!s.querySelector('.woody-component-text');
    }
    function estIntro(s) {
      var ct = s.querySelector('.woody-component-custom-text.tpl_01');
      return ct && !ct.classList.contains('benin-chapo') && !s.querySelector('img,figure,.card,form') && !txt(s.querySelector(TT)) && txt(s).length > 30 && txt(s).length < 600;
    }
    var runs = [], cur = [];
    secs.forEach(function (s) {
      if (estTexte(s) && (!cur.length || cur[cur.length - 1].nextElementSibling === s)) cur.push(s);
      else { if (cur.length >= 2) runs.push(cur); cur = estTexte(s) ? [s] : []; }
    });
    if (cur.length >= 2) runs.push(cur);
    runs.forEach(function (r, ri) {
      var avant = r[0].previousElementSibling, intro = null;
      if (avant && avant.classList.contains('page-section') && estIntro(avant)) intro = avant;
      var hero = document.querySelector('.hero-title,.emerv-hero__title');
      var wrap = el('section', 'bao-steps');
      var inn = el('div', 'bao-in'); wrap.appendChild(inn);
      var aside = el('aside', 'bao-rv');
      aside.appendChild(el('span', 'bao-k', 'L’essentiel'));
      aside.appendChild(el('h2', '', hero ? txt(hero) : 'En bref'));
      if (intro) { var ip = intro.querySelector('.woody-component-text'); if (ip) { var lead = el('div', 'bao-lead'); lead.appendChild(ip); aside.appendChild(lead); ip.querySelectorAll('p').forEach(function (p) { p.classList.add('bao-lead'); }); } intro.style.display = 'none'; }
      var nav = el('ol', 'bao-nav'); aside.appendChild(nav);
      var list = el('div', 'bao-list');
      r.forEach(function (s, i) {
        var id = 'bao-' + ri + '-' + i, n = (i + 1 < 10 ? '0' : '') + (i + 1);
        var art = el('article', 'bao-step bao-rv'); art.id = id; art.style.setProperty('--d', (i * 80) + 'ms');
        art.appendChild(el('span', 'bao-step-n', n));
        var t = s.querySelector(TT), b = s.querySelector('.woody-component-text');
        art.appendChild(t); art.appendChild(b);
        list.appendChild(art);
        var li = el('li'); var a = el('a', '', '<b>' + n + '</b><span></span>'); a.href = '#' + id; a.lastChild.textContent = txt(t); li.appendChild(a); nav.appendChild(li);
        s.style.display = 'none';
      });
      inn.appendChild(aside); inn.appendChild(list);
      r[0].parentNode.insertBefore(wrap, r[0]);
      if ('IntersectionObserver' in window) {
        var links = $$('a', nav);
        var io = new IntersectionObserver(function (es) {
          es.forEach(function (e) { if (e.isIntersecting) { links.forEach(function (l) { l.classList.toggle('on', l.getAttribute('href') === '#' + e.target.id); }); } });
        }, { rootMargin: '-40% 0px -55% 0px' });
        $$('.bao-step', list).forEach(function (x) { io.observe(x); });
      }
    });

    /* 2. Galeries de 3 photos sans titre */
    $$('.page-section', main).forEach(function (s) {
      if (s.closest('.bao-steps')) return;
      var figs = s.querySelectorAll('figure');
      if (figs.length >= 3 && !s.querySelector('.card,form') && !txt(s.querySelector(TT))) s.classList.add('bao-galerie');
    });

    /* 3. Encadré « En pratique » */
    $$('.benin-pratique', main).forEach(function (b) { var s = b.closest('.page-section'); if (s) s.classList.add('bao-pratique'); });

    /* 4. « À lire aussi » : cartes basiques avec image */
    $$('.page-section', main).forEach(function (s) {
      var cards = s.querySelectorAll('.card.basicCard');
      if (cards.length && s.querySelector('.card.basicCard img')) s.classList.add('bao-liens');
    });

    /* 5. Tuiles des infos pratiques (cartes sans image) */
    $$('.woody_list_grid', main).forEach(function (g) {
      var cards = g.querySelectorAll('.card.has-not-card-image');
      if (cards.length < 3) return;
      g.classList.add('bao-tuiles');
      Array.prototype.forEach.call(cards, function (c, i) {
        var a = c.querySelector('a.card-link'); if (!a) return;
        var slug = (a.getAttribute('href') || '').replace(/\/$/, '').split('/').pop();
        var im = TUILES[slug];
        if (im) { var img = el('img', 'bao-tuile-img'); img.src = IMG + im; img.alt = ''; img.loading = 'lazy'; img.decoding = 'async'; a.insertBefore(img, a.firstChild); }
        a.insertBefore(el('span', 'bao-tuile-n', (i + 1 < 10 ? '0' : '') + (i + 1)), a.firstChild);
        var cell = c.closest('.cell'); if (cell) { cell.classList.add('bao-rv'); cell.style.setProperty('--d', (i * 60) + 'ms'); }
      });
    });

    /* 6. Duo de textes (index infos pratiques) */
    var duos = $$('.woody-component-custom-text.tpl_02', main).filter(function (b) { return b.querySelector(':scope h2.section-title'); });
    if (duos.length >= 2 && duos[0].parentNode === duos[1].parentNode) {
      var box = el('div', 'bao-duo');
      duos[0].parentNode.insertBefore(box, duos[0]);
      duos.forEach(function (d) { if (d.parentNode === box.parentNode) { box.appendChild(d); d.classList.add('bao-rv'); } });
    }

    /* 7. Blocs numérotés (hébergements) */
    $$('.benin-page-block', main).forEach(function (b) {
      var n = b.querySelector('.benin-page-block__num'), t = b.querySelector('.benin-page-block__title');
      if (n && t && !b.querySelector('.bao-bh')) { var h = el('div', 'bao-bh'); b.insertBefore(h, b.firstChild); h.appendChild(n); h.appendChild(t); }
      b.classList.add('bao-rv');
    });

    /* Apparitions */
    var px = $$('.boi-cta>img');
    if (px.length && !(window.matchMedia && matchMedia('(prefers-reduced-motion: reduce)').matches)) {
      var tick = false;
      var upd = function () { tick = false; var h = window.innerHeight; px.forEach(function (im) { var r = im.parentNode.getBoundingClientRect(); if (r.bottom < 0 || r.top > h) return; var p = (r.top + r.height / 2 - h / 2) / h; im.style.transform = 'translate3d(0,' + (p * -8).toFixed(2) + '%,0)'; }); };
      window.addEventListener('scroll', function () { if (!tick) { tick = true; requestAnimationFrame(upd); } }, { passive: true }); upd();
    }
    var rv = $$('.bao-rv,.boi-rv');
    if (!('IntersectionObserver' in window)) { rv.forEach(function (x) { x.classList.add('is-in'); }); return; }
    var o = new IntersectionObserver(function (es) { es.forEach(function (e) { if (e.isIntersecting) { e.target.classList.add('is-in'); o.unobserve(e.target); } }); }, { threshold: .08 });
    rv.forEach(function (x) { o.observe(x); });
  }
  window.baOrg = { run: run };
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', run); else run();
})();
