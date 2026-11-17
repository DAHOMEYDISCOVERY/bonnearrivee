/*
 * Galerie de la page d'accueil (#bonnearrivee) : zoom au défilement.
 *
 * La galerie reste telle quelle. Quand on arrive dessus et qu'on continue à
 * défiler, la galerie reste à l'écran et sa première photo s'agrandit jusqu'à
 * remplir tout l'écran, seule (les autres s'effacent). En défilant encore, on
 * arrive sur le pied de page.
 *
 * La section est maintenue à l'écran en JS : position:sticky ne fonctionne
 * pas ici (débordement coupé par un bloc parent). Styles posés sur les
 * éléments (une balise <style> ajoutée après coup est retirée par le site).
 * Désactivé si l'internaute demande à réduire les animations.
 */
(function () {
    'use strict';
    var wall = document.querySelector('.woody-component-socialwall');
    if (!wall || wall.getAttribute('data-ba-zoom')) return;
    if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
    var section = wall.closest('section');
    var tuile = wall.querySelector('.media-zoom-button');
    if (!section || !tuile) return;
    wall.setAttribute('data-ba-zoom', '1');

    // Ce qui s'efface pendant le zoom : les autres photos et le titre.
    var tuileCell = tuile.closest('[role="listitem"]') || tuile;
    var autres = Array.prototype.filter.call(
        wall.querySelectorAll('[role="listitem"], .socialwall-titles'),
        function (el) { return el !== tuileCell && !el.contains(tuileCell) && !tuileCell.contains(el); }
    );

    var cadre = document.createElement('div');
    cadre.className = 'ba-zoomgal';
    section.parentNode.insertBefore(cadre, section);
    cadre.appendChild(section);
    cadre.style.cssText = 'position:relative;overflow-y:clip;';
    section.style.willChange = 'transform';
    tuile.style.position = 'relative';
    tuile.style.zIndex = '30';
    tuile.style.transformOrigin = '50% 50%';
    // Pas de will-change ici : il fige l'image à sa petite taille pendant
    // l'agrandissement (flou). Sans lui, le navigateur la redessine nette.

    // Version haute définition de la photo, affichée par-dessus la vignette
    // dès que le zoom commence (la vignette ne fait que 714 px).
    var HD = { 'ba-vannerie': '/assets/img/artisanat-1920x1440.webp' };
    // Le site charge les photos tardivement : la vignette n'a sa vraie adresse
    // qu'une fois chargée. On prépare donc la version HD au premier défilement.
    var hd = null, hdPret = false;
    function prepHD() {
        if (hdPret) return;
        var vignette = tuile.querySelector('img');
        if (!vignette) { hdPret = true; return; }
        var adr = (vignette.getAttribute('src') || '') + ' ' + (vignette.getAttribute('srcset') || '') + ' ' + (vignette.getAttribute('data-src') || '');
        var nom = (adr.match(/\/(ba-[a-z0-9-]+?)-\d+x\d+\.webp/) || [])[1];
        if (!nom) return; // pas encore chargée : on réessaiera
        hdPret = true;
        if (!HD[nom]) return;
        hd = document.createElement('img');
        hd.alt = '';
        hd.setAttribute('aria-hidden', 'true');
        hd.decoding = 'async';
        hd.style.cssText = 'position:absolute;top:0;left:0;width:100%;height:100%;max-width:none;object-fit:cover;pointer-events:none;z-index:2;opacity:0;transition:opacity .25s;';
        hd.onload = function () { demande(); };
        var hote = vignette.closest('figure') || tuile;
        hote.style.position = 'relative';
        hote.appendChild(hd);
        hd.src = HD[nom];
    }

    var m = null; // mesures
    function mesurer() {
        section.style.transform = '';
        tuile.style.transform = '';
        autres.forEach(function (el) { el.style.opacity = ''; });
        var vw = document.documentElement.clientWidth, vh = window.innerHeight;
        var rs = section.getBoundingClientRect(), rt = tuile.getBoundingClientRect();
        var secH = rs.height, tTop = rt.top - rs.top;
        // Position (haut de la section dans l'écran) où le zoom commence.
        var debut;
        if (secH <= vh) {
            debut = vh - secH;                     // toute la galerie visible
        } else {                                   // galerie plus haute que l'écran
            debut = Math.min(0, Math.max(vh - secH, (vh - rt.height) / 2 - tTop));
        }
        var course = Math.round(vh * 1.2);         // distance de défilement du zoom
        // Partie basse de la galerie jamais vue (effacée) : coupée, pour que le
        // pied de page suive directement la photo en plein écran.
        var cache = Math.max(0, secH - (vh - debut));
        cadre.style.height = (secH - cache + course) + 'px';
        var x0 = rt.left, y0 = debut + tTop;
        m = {
            secH: secH,
            debut: debut, course: course,
            dx: vw / 2 - (x0 + rt.width / 2),
            dy: vh / 2 - (y0 + rt.height / 2),
            s: Math.max(vw / rt.width, vh / rt.height) * 1.01
        };
    }

    var attente = false;
    function maj() {
        attente = false;
        if (!m) return;
        // Les photos se chargent au fil du défilement : si la hauteur de la
        // galerie a changé, on remesure avant d'animer.
        if (Math.abs(section.offsetHeight - m.secH) > 1) mesurer();
        var haut = cadre.getBoundingClientRect().top;
        var decale = Math.min(m.course, Math.max(0, m.debut - haut));
        var p = decale / m.course;
        section.style.transform = decale ? 'translate3d(0,' + decale.toFixed(1) + 'px,0)' : '';
        tuile.style.transform = p ? 'translate3d(' + (m.dx * p).toFixed(1) + 'px,' + (m.dy * p).toFixed(1) + 'px,0) scale(' + (1 + (m.s - 1) * p).toFixed(4) + ')' : '';
        if (haut < window.innerHeight * 2) prepHD(); // préchargement à l'approche
        if (hd) {
            hd.style.opacity = p > 0 && hd.complete && hd.naturalWidth ? '1' : '0';
        }
        var o = Math.max(0, 1 - p * 2.5);
        autres.forEach(function (el) { el.style.opacity = p ? o.toFixed(3) : ''; });
    }
    function demande() {
        if (!attente) { attente = true; window.requestAnimationFrame(maj); }
    }
    var tRes;
    window.addEventListener('scroll', demande, { passive: true });
    window.addEventListener('resize', function () {
        clearTimeout(tRes);
        tRes = setTimeout(function () { mesurer(); maj(); }, 150);
    });
    window.addEventListener('load', function () { mesurer(); maj(); });
    // La galerie change de hauteur au chargement de ses photos : on remesure.
    if (window.ResizeObserver) {
        var hPrec = 0;
        new ResizeObserver(function () {
            var hNow = section.offsetHeight;
            if (Math.abs(hNow - hPrec) < 2) return;
            hPrec = hNow;
            clearTimeout(tRes);
            tRes = setTimeout(function () { mesurer(); maj(); }, 100);
        }).observe(section);
    }
    mesurer();
    maj();
})();
