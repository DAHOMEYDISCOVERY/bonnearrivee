/* Bonne Arrivée — mise en forme des longs textes saisis dans l'admin.
   Le texte reste celui de l'admin : ce script le lit à l'affichage et le présente
   en sections (variantes par page). Sans JS, ou si le texte est court, rien ne change. */
(function () {
  'use strict';

  var D = document;
  function esc(s) { return String(s).replace(/[&<>"]/g, function (c) { return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[c]; }); }
  function norm(s) { return String(s).replace(/\u00a0/g, ' ').replace(/[ \t\r\f\v]+/g, ' ').trim(); }
  function fix(s) { return norm(s).replace(/([a-zà-ÿ0-9\)»])\.(?=[A-ZÀ-Ý])/g, '$1. '); }
  function low(s) { return String(s).toLowerCase().replace(/[\u2019']/g, "'"); }
  function cap(s) { s = norm(s); return s.charAt(0).toUpperCase() + s.slice(1); }
  function phrases(t) { return (fix(t).match(/[^.!?]+(?:[.!?]+(?=\s|$)|$)/g) || [t]).map(norm).filter(Boolean); }
  function reEsc(s) { return s.replace(/[.*+?^${}()|[\]\\]/g, '\\$&').replace(/[\u2019']/g, "[\u2019']"); }
  function marquer(h, mots) {
    (mots || []).forEach(function (m) { h = h.replace(new RegExp('(' + reEsc(esc(m)) + ')', 'i'), '<mark>$1</mark>'); });
    return h;
  }
  function trouve(t, mots) { var l = low(t); return (mots || []).filter(function (m) { return l.indexOf(low(m)) > -1; }); }

  /* Lecture du bloc : lignes, intertitres (gras en début de ligne ou ligne courte sans point). */
  function lignes(el) {
    var out = [], cur = '';
    function flush() { var t = fix(cur); if (t) out.push({ t: t }); cur = ''; }
    (function walk(n) {
      n.childNodes.forEach(function (c) {
        if (c.nodeType === 3) { cur += c.nodeValue.replace(/\n/g, ' '); return; }
        if (c.nodeType !== 1) return;
        var tag = c.tagName;
        if (tag === 'BR') { flush(); return; }
        if ((tag === 'B' || tag === 'STRONG') && norm(cur) === '' && norm(c.textContent).length > 3) {
          flush(); out.push({ t: fix(c.textContent), h: 1 }); return;
        }
        if (tag === 'SPAN' && norm(cur) === '' && c.querySelector('b,strong') && norm(c.textContent) === norm(c.querySelector('b,strong').textContent)) {
          flush(); out.push({ t: fix(c.textContent), h: 1 }); return;
        }
        walk(c);
      });
    })(el);
    flush();
    return out;
  }
  function blocs(el) {
    var L = lignes(el), aGras = L.some(function (l) { return l.h; }), res = [], b = null;
    L.forEach(function (l, i) {
      var court = !aGras && l.t.length <= 110 && !/[.!?;:,]$/.test(l.t) && l.t.split(' ').length <= 16 && i < L.length - 1;
      if (l.h || court) { b = { titre: l.t.replace(/\s*:\s*$/, ''), p: [] }; res.push(b); return; }
      if (!b) { b = { titre: '', p: [] }; res.push(b); }
      b.p.push(l.t);
    });
    return res.filter(function (x) { return x.p.length; });
  }
  function item(p) { var m = p.match(/^([^:]{3,70}?)\s?:\s+(.+)$/); return m ? { label: norm(m[1]), texte: norm(m[2]) } : null; }

  /* ---------- Variantes ---------- */
  var V = {};

  // 1. Parcours vertical (régions du nord au sud)
  V.parcours = function (bs, c) {
    var intro = bs[0], reg = bs.slice(1);
    if (reg.length < 2) return '';
    var full = bs.map(function (b) { return b.p.join(' '); }).join(' ');
    var km = (full.match(/(\d[\d\s\u00a0]*)\s*kilom[eè]tres/) || [])[1];
    var cles = c.regions || [];
    function cle(t) { for (var i = 0; i < cles.length; i++) if (low(t).indexOf(low(cles[i].k)) > -1) return cles[i]; return {}; }
    var h = '<div class="bar-in"><header class="bar-intro bar-rv"><span class="bar-kick">' + esc(c.kick || 'Le Bénin du nord au sud') + '</span><h2>' + esc(intro.titre || c.titre || '') + '</h2>' +
      '<p class="bar-lead">' + esc(intro.p.join(' ')) + '</p>' + (km ? '<div class="bar-km"><b data-c="' + km.replace(/\D/g, '') + '">' + esc(norm(km)) + '</b><span>kilomètres, du golfe de Guinée à l\u2019Atacora</span></div>' : '') + '</header>';
    h += '<div class="bar-route"><ol class="bar-rail" aria-hidden="true">' + reg.map(function (b, i) { var k = cle(b.titre); return '<li data-i="' + i + '"><i></i><span>' + esc(k.court || b.titre.split(',')[0]) + '</span></li>'; }).join('') + '</ol><div class="bar-etapes">';
    reg.forEach(function (b, i) {
      var k = cle(b.titre), ph = phrases(b.p.join(' ')), lead = ph.shift() || '';
      var titre = b.titre, m = titre.match(/^(.*?),\s*(.+)$/);
      h += '<article class="bar-etape bar-rv" data-i="' + i + '" style="--c:' + (k.c || '#c8862b') + '">' +
        (k.img ? '<figure><img src="' + esc(k.img) + '" alt="' + esc(k.alt || titre) + '" loading="lazy" decoding="async"><figcaption>' + esc(k.alt || '') + '</figcaption></figure>' : '') +
        '<div class="bar-etape-t"><span class="bar-kick">0' + (i + 1) + ' · ' + esc(k.court || '') + '</span><h3>' + (m ? esc(m[1]) + '<small>' + esc(m[2]) + '</small>' : esc(titre)) + '</h3>' +
        '<p class="bar-lead">' + esc(lead) + '</p>' + (ph.length ? '<p>' + esc(ph.join(' ')) + '</p>' : '') + '</div></article>';
    });
    return h + '</div></div></div>';
  };

  // 2. Manifeste (un seul long paragraphe : fondements + rôles aujourd'hui)
  V.manifeste = function (bs, c) {
    var t = fix(bs.map(function (b) { return (b.titre ? b.titre + ' ' : '') + b.p.join(' '); }).join(' '));
    var cut = t.search(/Au-del[àa]/), a = cut > 0 ? t.slice(0, cut) : t, b = cut > 0 ? t.slice(cut) : '';
    var ph = phrases(a), lead = ph[0] || a;
    var accroche = lead.split(/\s[—-]\s/)[0];
    var h = '<div class="bar-in"><div class="bar-mani-tete bar-rv"><span class="bar-kick">' + esc(c.kick || 'Ce qui fonde les traditions') + '</span><p class="bar-grand">' + esc(accroche.replace(/[,;]\s*$/, '')) + '</p></div>';
    h += '<div class="bar-piliers">' + (c.piliers || []).map(function (p, i) {
      var f = trouve(a, p.kw); if (!f.length) return '';
      return '<article class="bar-pilier bar-rv" style="--d:' + (i * 90) + 'ms">' + (p.img ? '<img src="' + esc(p.img) + '" alt="" loading="lazy" decoding="async">' : '') + '<div><b>' + esc(p.t) + '</b><span>' + f.map(esc).join(' · ') + '</span></div></article>';
    }).join('') + '</div>';
    h += '<details class="bar-texte bar-rv"><summary>Lire le texte complet</summary><p>' + marquer(esc(a), [].concat.apply([], (c.piliers || []).map(function (p) { return p.kw; }))) + '</p></details>';
    if (b) {
      var parts = b.split(/,?\s(?:et\s)?en\s(?=[a-zéèêà]+ant\b)/), tete = parts.shift();
      if (parts.length >= 2) {
        h += '<div class="bar-roles"><h3 class="bar-rv">' + esc(cap(tete.replace(/[,:]\s*$/, ''))) + '</h3><ol>' + parts.map(function (r, i) {
          return '<li class="bar-rv" style="--d:' + (i * 80) + 'ms"><span>0' + (i + 1) + '</span><p>' + esc(cap(r.replace(/[.,;]\s*$/, ''))) + '</p></li>';
        }).join('') + '</ol></div>';
      } else h += '<p class="bar-rv">' + esc(b) + '</p>';
    }
    return h + '</div>';
  };

  // 3. Chiffres (texte riche en données : mur de chiffres + menaces)
  V.chiffres = function (bs, c) {
    var t = fix(bs.map(function (b) { return b.p.join(' '); }).join(' ')), ps = [];
    bs.forEach(function (b) { b.p.forEach(function (p) { ps.push(p); }); });
    var stats = (c.stats || []).map(function (s) { var m = t.match(new RegExp(s.re, 'i')); return m ? { v: s.v || norm(m[1]), l: s.l, k: s.k || '' } : null; }).filter(Boolean);
    var men = (t.match(/menaces[^—–-]*[—–-]\s*([^—–]+)[—–]/i) || [])[1];
    var h = '<div class="bar-in"><div class="bar-chif-tete bar-rv"><span class="bar-kick">' + esc(c.kick || 'En chiffres') + '</span><h2>' + esc(c.titre || '') + '</h2><p class="bar-lead">' + esc((ps[0] || '').split(/\s[—–]\s/)[0].replace(/[,;]\s*$/, '')) + '.</p></div>';
    if (stats.length) h += '<div class="bar-stats">' + stats.map(function (s, i) {
      var n = s.v.replace(/[^\d]/g, ''), simple = /^\d[\d\s]*$/.test(s.v);
      return '<div class="bar-stat bar-rv' + (s.v.length > 7 ? ' bar-stat-l' : '') + '" style="--d:' + (i * 70) + 'ms">' + (s.k ? '<i class="bar-ic bar-ic-' + s.k + '"></i>' : '') + '<b' + (simple ? ' data-c="' + n + '"' : '') + '>' + esc(s.v) + '</b><span>' + esc(s.l) + '</span></div>';
    }).join('') + '</div>';
    if (men) h += '<div class="bar-menaces bar-rv"><b>Sous pression</b>' + men.split(/,\s|\set\s/).map(function (m) { return '<span>' + esc(cap(m.replace(/\(.*?\)/g, ''))) + '</span>'; }).join('') + '</div>';
    h += '<details class="bar-texte bar-rv"><summary>Lire le texte complet</summary>' + ps.map(function (p) { return '<p>' + esc(p) + '</p>'; }).join('') + '</details>';
    return h + '</div>';
  };

  // 4. Fiches (intertitres + éléments en cartes)
  V.fiches = function (bs, c) {
    var kws = c.kw || [], n = 0;
    function k(t) { for (var i = 0; i < kws.length; i++) if (low(t).indexOf(low(kws[i].k)) > -1) return kws[i]; return null; }
    var h = '<div class="bar-in">';
    if (c.sauter) bs = bs.slice(c.sauter);
    if (c.exclure) { var rx = new RegExp(c.exclure, 'i'); bs = bs.filter(function (b) { return !rx.test(b.titre); }); }
    bs.forEach(function (b, bi) {
      var intro = [], cartes = [];
      b.p.forEach(function (p) {
        var it = item(p), kk = k(p);
        if (it) cartes.push({ t: it.label, x: it.texte, kk: k(it.label) || kk });
        else if (c.mode !== 'items' && kk && p.length > 60 && (intro.length || cartes.length || bi > 0 || b.titre)) cartes.push({ t: kk.t || kk.k, x: p, kk: kk });
        else intro.push(p);
      });
      if (!b.titre && bi === 0 && !cartes.length) { h += '<div class="bar-fiche-ouv bar-rv"><p class="bar-grand">' + esc(intro.join(' ')) + '</p></div>'; return; }
      n++;
      h += '<section class="bar-fiche' + (n % 2 === 0 ? ' bar-inv' : '') + '"><div class="bar-fiche-tete bar-rv"><span class="bar-num">0' + n + '</span><h3>' + esc(b.titre) + '</h3>' + intro.map(function (p) { return '<p>' + esc(p) + '</p>'; }).join('') + '</div>';
      if (cartes.length) h += '<div class="bar-cartes bar-c' + Math.min(cartes.length, 4) + '">' + cartes.map(function (x, i) {
        var img = x.kk && x.kk.img, ic = x.kk && x.kk.ic, ph = phrases(x.x), lead = ph.shift();
        return '<article class="bar-carte bar-rv' + (img ? ' a-img' : '') + '" style="--d:' + (i * 80) + 'ms">' + (img ? '<figure><img src="' + esc(img) + '" alt="' + esc(x.t) + '" loading="lazy" decoding="async"></figure>' : (ic ? '<i class="bar-ic bar-ic-' + ic + '"></i>' : '')) +
          '<div><h4>' + esc(cap(x.t)) + '</h4><p class="bar-lead">' + esc(lead || '') + '</p>' + (ph.length ? '<p>' + esc(ph.join(' ')) + '</p>' : '') + '</div></article>';
      }).join('') + '</div>';
      h += '</section>';
    });
    if (c.renvoi) { var r = c.renvoi; h += '<a class="bar-renvoi bar-rv" href="' + esc(r.lien) + '">' + (r.img ? '<img src="' + esc(r.img) + '" alt="" loading="lazy" decoding="async">' : '') + '<span><small>' + esc(r.kick || '') + '</small><b>' + esc(r.titre) + '</b><em>' + esc(r.texte) + '</em><i>' + esc(r.label || 'Découvrir') + ' →</i></span></a>'; }
    return h + '</div>';
  };

  // 5. Lacs (fiches comparées avec surfaces)
  V.lacs = function (bs, c) {
    var L = bs.filter(function (b) { return /lac/i.test(b.titre); });
    if (L.length < 2) return '';
    var data = L.map(function (b) {
      var t = b.p.join(' '), km = 0, lib = '';
      var m = t.match(/(\d+(?:[,.]\d+)?)\s*kilom[eè]tres carr[ée]s/i), ha = t.match(/(\d[\d\s]*)\s*hectares/i);
      if (m) { km = parseFloat(m[1].replace(',', '.')); lib = m[1] + ' km²'; } else if (ha) { km = parseInt(ha[1].replace(/\s/g, ''), 10) / 100; lib = norm(ha[1]) + ' ha'; }
      var cfg = (c.lacs || []).filter(function (x) { return low(b.titre).indexOf(low(x.k)) > -1; })[0] || {};
      return { b: b, km: km, lib: lib, cfg: cfg };
    });
    var max = Math.max.apply(null, data.map(function (d) { return d.km || 0; })) || 1;
    var h = '<div class="bar-in"><div class="bar-lacs-tete bar-rv"><span class="bar-kick">' + esc(c.kick || 'Trois lacs, trois ambiances') + '</span><h2>' + esc(c.titre || '') + '</h2></div><div class="bar-lacs-g">';
    data.forEach(function (d, i) {
      var ph = phrases(d.b.p.join(' ')), lead = ph.shift(), m = d.b.titre.match(/^(.*?)(?:\s(?:et|,|entre)\s)(.+)$/);
      h += '<article class="bar-lac bar-rv" style="--d:' + (i * 120) + 'ms;--c:' + (d.cfg.c || '#1f8a9a') + '">' + (d.cfg.img ? '<figure><img src="' + esc(d.cfg.img) + '" alt="' + esc(d.cfg.alt || d.b.titre) + '" loading="lazy" decoding="async">' + (d.lib ? '<span class="bar-surf">' + esc(d.lib) + '</span>' : '') + '</figure>' : '') +
        '<div class="bar-lac-t"><span class="bar-kick">' + esc(d.cfg.lieu || '') + '</span><h3>' + esc(m ? m[1].replace(/,\s*$/, '') : d.b.titre) + '</h3>' + (m ? '<p class="bar-sous">' + esc(cap(m[2])) + '</p>' : '') +
        (d.km ? '<div class="bar-jauge" aria-hidden="true"><i style="--w:' + Math.max(8, Math.round(d.km / max * 100)) + '%"></i></div>' : '') +
        '<p class="bar-lead">' + esc(lead || '') + '</p>' + (d.cfg.tags ? '<div class="bar-tags">' + trouve(d.b.p.join(' '), d.cfg.tags).map(function (x) { return '<span>' + esc(cap(x)) + '</span>'; }).join('') + '</div>' : '') +
        (ph.length ? '<details><summary>En savoir plus</summary><p>' + esc(ph.join(' ')) + '</p></details>' : '') + '</div></article>';
    });
    return h + '</div></div>';
  };

  // 6. Atouts (texte promotionnel condensé en tuiles)
  V.atouts = function (bs, c) {
    var t = fix(bs.map(function (b) { return (b.titre ? b.titre + ' ' : '') + b.p.join(' '); }).join(' '));
    var lead = (t.split(/\s[—–]\s|,\s(?=s['\u2019]appuyant)/)[0] || '').replace(/[,;]\s*$/, '');
    var stats = (c.stats || []).map(function (s) { var m = t.match(new RegExp(s.re, 'i')); return m ? { v: norm(m[1]), l: s.l } : null; }).filter(Boolean);
    var h = '<div class="bar-atouts"><p class="bar-lead">' + esc(lead) + '.</p>';
    if (stats.length) h += '<div class="bar-atouts-g">' + stats.map(function (s) { return '<div><b>' + esc(s.v) + '</b><span>' + esc(s.l) + '</span></div>'; }).join('') + '</div>';
    var pts = trouve(t, c.kw || []);
    if (pts.length) h += '<div class="bar-tags">' + pts.map(function (x) { return '<span>' + esc(cap(x)) + '</span>'; }).join('') + '</div>';
    if (c.note) h += '<p class="bar-note">' + c.note + '</p>';
    h += '<details><summary>Lire le texte complet</summary>' + bs.map(function (b) { return b.p.map(function (p) { return '<p>' + esc(p) + '</p>'; }).join(''); }).join('') + '</details></div>';
    return h;
  };

  // 7. Triangle (trois villes reliées sur une carte)
  V.triangle = function (bs, c) {
    var cfgs = c.villes || [];
    function cv(t) { for (var i = 0; i < cfgs.length; i++) if (low(t).indexOf(low(cfgs[i].k)) > -1) return cfgs[i]; return null; }
    var L = bs.filter(function (b) { return cv(b.titre); });
    if (L.length < 2) return '';
    var pts = L.map(function (b) { return cv(b.titre); });
    var svg = '<svg viewBox="0 0 300 300" aria-hidden="true"><defs><linearGradient id="bar-tg" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#d9a441"/><stop offset="1" stop-color="#b4552d"/></linearGradient></defs>' +
      '<path class="bar-tri-cote" d="M10 262 C 80 255, 150 250, 290 238" fill="none" stroke="rgba(255,255,255,.25)" stroke-width="2" stroke-dasharray="4 6"/>' +
      '<polygon class="bar-tri-l" points="' + pts.map(function (p) { return p.x + ',' + p.y; }).join(' ') + '" fill="rgba(217,164,65,.08)" stroke="url(#bar-tg)" stroke-width="2.5" stroke-linejoin="round"/>' +
      (c.ref ? '<circle cx="' + c.ref.x + '" cy="' + c.ref.y + '" r="4" fill="rgba(255,255,255,.6)"/><text x="' + (c.ref.x + 8) + '" y="' + (c.ref.y + 4) + '" class="bar-tri-ref">' + esc(c.ref.t) + '</text>' : '') +
      pts.map(function (p, i) { return '<g class="bar-tri-pt" data-i="' + i + '"><circle cx="' + p.x + '" cy="' + p.y + '" r="15" class="bar-tri-halo"/><circle cx="' + p.x + '" cy="' + p.y + '" r="7" class="bar-tri-dot"/><text x="' + (p.x + (p.lx || 0)) + '" y="' + (p.y + (p.ly || -22)) + '" text-anchor="middle">' + esc(p.k) + '</text></g>'; }).join('') +
      '<text x="150" y="285" text-anchor="middle" class="bar-tri-ref">Golfe de Guinée</text></svg>';
    var h = '<div class="bar-in"><header class="bar-tri-tete bar-rv"><span class="bar-kick">' + esc(c.kick || 'Le triangle historique') + '</span><h2>' + esc(c.titre || '') + '</h2>' + (c.chapo ? '<p class="bar-lead">' + esc(c.chapo) + '</p>' : '') + '</header><div class="bar-tri"><div class="bar-tri-carte">' + svg + '</div><div class="bar-tri-villes">';
    L.forEach(function (b, i) {
      var k = cv(b.titre), t = b.p.join(' '), ph = phrases(t), lead = ph.shift() || '', m = b.titre.match(/^([^,]+),\s*(.+)$/);
      var mons = trouve(t, k.monuments || []);
      h += '<article class="bar-ville bar-rv" data-i="' + i + '" style="--c:' + (k.c || '#d9a441') + '">' +
        '<figure><img src="' + esc(k.img) + '" alt="' + esc(k.alt || k.k) + '" loading="lazy" decoding="async"><span class="bar-ville-n">0' + (i + 1) + '</span></figure>' +
        '<div class="bar-ville-t"><span class="bar-kick">' + esc(k.role || '') + '</span><h3>' + esc(m ? m[1] : b.titre) + '</h3>' + (m ? '<p class="bar-sous">' + esc(cap(m[2])) + '</p>' : '') +
        '<p class="bar-lead">' + esc(lead) + '</p>' + (mons.length ? '<ul class="bar-mons">' + mons.map(function (x) { return '<li>' + esc(x) + '</li>'; }).join('') + '</ul>' : '') +
        (ph.length ? '<details><summary>Lire la suite</summary><p>' + esc(ph.join(' ')) + '</p></details>' : '') + (k.lien ? '<a class="bar-ville-lien" href="' + esc(k.lien) + '">' + esc(k.lienTxt || 'Découvrir') + ' →</a>' : '') + '</div></article>';
    });
    return h + '</div></div></div>';
  };

  /* ---------- Moteur ---------- */
  function animer(root) {
    D.documentElement.classList.add('bar-js');
    var io = 'IntersectionObserver' in window;
    root.querySelectorAll('.bar-rv').forEach(function (e) {
      if (!io) { e.classList.add('is-in'); return; }
      var o = new IntersectionObserver(function (en) {
        en.forEach(function (x) {
          if (!x.isIntersecting) return;
          x.target.classList.add('is-in'); o.disconnect();
          x.target.querySelectorAll('[data-c]').forEach(function (b) {
            var f = +b.getAttribute('data-c'), t0 = null, fmt = function (v) { return String(v).replace(/\B(?=(\d{3})+(?!\d))/g, '\u202f'); };
            if (!f) return;
            (function st(ts) { if (!t0) t0 = ts; var k = Math.min(1, (ts - t0) / 1500); b.textContent = fmt(Math.round(f * (1 - Math.pow(1 - k, 3)))); if (k < 1) requestAnimationFrame(st); })(performance.now());
          });
        });
      }, { threshold: 0.15 });
      o.observe(e);
    });
    var tri = root.querySelectorAll('.bar-tri-pt');
    if (tri.length && io) {
      var o3 = new IntersectionObserver(function (en) { en.forEach(function (x) { if (x.isIntersecting) tri.forEach(function (g) { g.classList.toggle('on', g.getAttribute('data-i') === x.target.getAttribute('data-i')); }); }); }, { rootMargin: '-40% 0px -50% 0px' });
      root.querySelectorAll('.bar-ville').forEach(function (e) { o3.observe(e); });
    }
    var rail = root.querySelector('.bar-rail');
    if (rail && io) {
      var lis = rail.querySelectorAll('li');
      var o2 = new IntersectionObserver(function (en) {
        en.forEach(function (x) { if (x.isIntersecting) { lis.forEach(function (l) { l.classList.toggle('on', l.getAttribute('data-i') === x.target.getAttribute('data-i')); }); } });
      }, { rootMargin: '-45% 0px -45% 0px' });
      root.querySelectorAll('.bar-etape').forEach(function (e) { o2.observe(e); });
    }
  }

  function un(c) {
    var src = [].slice.call(D.querySelectorAll(c.src)).filter(function (e) { return !e.closest('.bar'); })
      .sort(function (a, b) { return b.textContent.length - a.textContent.length; })[0];
    if (!src || norm(src.textContent).length < (c.min || 600)) return;
    var bs = blocs(src); if (!bs.length || !V[c.v]) return;
    var html; try { html = V[c.v](bs, c); } catch (e) { return; }
    if (!html) return;
    var cache = c.hide ? (src.closest(c.hide) || src) : src;
    var el = D.createElement(c.inline ? 'div' : 'div');
    el.className = (c.inline ? '' : 'page-section ') + 'bar bar-' + c.v + (c.theme ? ' bar-t-' + c.theme : '');
    el.innerHTML = html;
    if (c.at === 'avant-main') { var m = D.querySelector('main .page-section, main section'); (m && m.parentNode ? m.parentNode.insertBefore(el, m) : cache.parentNode.insertBefore(el, cache)); }
    else cache.parentNode.insertBefore(el, cache.nextSibling);
    cache.classList.add('bar-cache');
    animer(el);
  }

  function run() {
    D.querySelectorAll('script.ba-recit-cfg').forEach(function (s) {
      var cfg; try { cfg = JSON.parse(s.textContent); } catch (e) { return; }
      (Array.isArray(cfg) ? cfg : [cfg]).forEach(function (c) { try { un(c); } catch (e) { /* on laisse le texte tel quel */ } });
    });
  }
  window.baRecit = { run: run, blocs: blocs };
  if (D.readyState === 'loading') D.addEventListener('DOMContentLoaded', run); else run();
})();
