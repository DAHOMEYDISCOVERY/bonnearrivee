{{-- Utilitaires globaux du portail :
     - WhatsApp flottant (Dahomey Discovery)
     - Bouton "Retour en haut"
     - Progress bar de lecture (visible sur les pages editoriales longues)
     - Skip link renforce pour accessibilite clavier
     Toutes les interactions vanilla JS, pas de dependance. --}}

{{-- Skip link (positionne en haut du DOM, visible au focus clavier) --}}
<a class="ba-skip" href="#main-content">Aller au contenu principal</a>

{{-- Bouton flottant "Parametrer les cookies" (equivalent CMP Woody eskalad,
     position: left, revoke_label: "Parametrer les cookies") --}}
<button type="button" class="ba-consent-btn" aria-haspopup="dialog" aria-controls="ba-consent-modal" aria-label="Paramétrer les cookies">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <path d="M12 2a10 10 0 1 0 10 10c0-.46-.03-.92-.09-1.36a3 3 0 0 1-4.24-4.24A10 10 0 0 0 12 2z"/>
        <circle cx="8.5" cy="10.5" r="1.2" fill="currentColor"/>
        <circle cx="15.5" cy="14.5" r="1.2" fill="currentColor"/>
        <circle cx="11" cy="16" r="1.1" fill="currentColor"/>
    </svg>
    <span class="ba-consent-btn__label">Paramétrer les cookies</span>
</button>

{{-- Modale de consentement (cachee par defaut, ouverte par le bouton) --}}
<div class="ba-consent-modal" id="ba-consent-modal" role="dialog" aria-modal="true" aria-labelledby="ba-consent-title" hidden>
    <div class="ba-consent-modal__backdrop" data-close></div>
    <div class="ba-consent-modal__panel" role="document">
        <button type="button" class="ba-consent-modal__close" data-close aria-label="Fermer">&times;</button>
        <h2 id="ba-consent-title" class="ba-consent-modal__title">Vos préférences de confidentialité</h2>
        <p class="ba-consent-modal__lead">Bonne Arrivée utilise des cookies pour améliorer votre expérience, mesurer la fréquentation du site et vous proposer des contenus adaptés. Vous pouvez accepter, refuser ou personnaliser à tout moment.</p>

        <div class="ba-consent-cats">
            <div class="ba-consent-cat">
                <div class="ba-consent-cat__head">
                    <div>
                        <h3 class="ba-consent-cat__titre">Cookies essentiels</h3>
                        <p class="ba-consent-cat__desc">Nécessaires au fonctionnement du site (navigation, langue). Toujours actifs.</p>
                    </div>
                    <span class="ba-consent-cat__lock" aria-label="Toujours actif">Toujours actif</span>
                </div>
            </div>
            <div class="ba-consent-cat">
                <div class="ba-consent-cat__head">
                    <div>
                        <h3 class="ba-consent-cat__titre">Mesure d'audience</h3>
                        <p class="ba-consent-cat__desc">Statistiques anonymes de fréquentation pour améliorer les pages les plus consultées.</p>
                    </div>
                    <label class="ba-consent-toggle">
                        <input type="checkbox" data-cat="analytics">
                        <span class="ba-consent-toggle__ui"></span>
                    </label>
                </div>
            </div>
            <div class="ba-consent-cat">
                <div class="ba-consent-cat__head">
                    <div>
                        <h3 class="ba-consent-cat__titre">Contenus tiers</h3>
                        <p class="ba-consent-cat__desc">Vidéos, cartes, publications réseaux sociaux intégrées depuis des services externes.</p>
                    </div>
                    <label class="ba-consent-toggle">
                        <input type="checkbox" data-cat="embeds">
                        <span class="ba-consent-toggle__ui"></span>
                    </label>
                </div>
            </div>
        </div>

        <div class="ba-consent-modal__actions">
            <button type="button" class="ba-consent-modal__btn ba-consent-modal__btn--ghost" data-action="reject">Tout refuser</button>
            <button type="button" class="ba-consent-modal__btn ba-consent-modal__btn--ghost" data-action="save">Enregistrer mes choix</button>
            <button type="button" class="ba-consent-modal__btn ba-consent-modal__btn--primary" data-action="accept">Tout accepter</button>
        </div>
        <p class="ba-consent-modal__legal">
            Vos préférences sont conservées 12 mois sur cet appareil. Consultez notre <a href="/mentions-legales">politique de confidentialité</a>.
        </p>
    </div>
</div>

<script>
(function () {
    // --- Fallback tabs Woody (au cas ou Foundation ne s'initialise pas)
    // Bascule .is-active sur .tabs-title et .tabs-panel en cliquant sur .tab-link
    document.querySelectorAll('.woody-component-tabs .tab-link').forEach(function (link) {
        link.addEventListener('click', function (e) {
            var href = link.getAttribute('href') || '';
            if (!href.startsWith('#')) return;
            e.preventDefault();
            var panelId = href.slice(1);
            var wrapper = link.closest('.woody-component-tabs');
            if (!wrapper) return;
            wrapper.querySelectorAll('.tabs-title').forEach(function (t) { t.classList.remove('is-active'); });
            wrapper.querySelectorAll('.tab-link').forEach(function (l) { l.setAttribute('aria-selected', 'false'); });
            wrapper.querySelectorAll('.tabs-panel').forEach(function (p) { p.classList.remove('is-active'); });
            var parentTitle = link.closest('.tabs-title');
            if (parentTitle) parentTitle.classList.add('is-active');
            link.setAttribute('aria-selected', 'true');
            try {
                var panel = wrapper.querySelector('#' + CSS.escape(panelId));
                if (panel) panel.classList.add('is-active');
            } catch (err) {}
        });
    });

// --- Modale de consentement cookies -----------------------------------
    var consentBtn = document.querySelector('.ba-consent-btn');
    var consentModal = document.getElementById('ba-consent-modal');
    if (consentBtn && consentModal) {
        var STORAGE = 'ba-consent-v1';
        var toggles = consentModal.querySelectorAll('input[type="checkbox"][data-cat]');
        function loadPrefs() {
            try { return JSON.parse(localStorage.getItem(STORAGE) || '{}'); } catch (e) { return {}; }
        }
        function savePrefs(prefs) {
            try { localStorage.setItem(STORAGE, JSON.stringify(prefs)); } catch (e) {}
        }
        function applyPrefs(prefs) {
            toggles.forEach(function (t) {
                t.checked = !!prefs[t.dataset.cat];
            });
        }
        function open() {
            applyPrefs(loadPrefs());
            consentModal.hidden = false;
            document.body.style.overflow = 'hidden';
            var closeBtn = consentModal.querySelector('.ba-consent-modal__close');
            if (closeBtn) closeBtn.focus();
        }
        function close() {
            consentModal.hidden = true;
            document.body.style.overflow = '';
            consentBtn.focus();
        }
        consentBtn.addEventListener('click', open);
        consentModal.querySelectorAll('[data-close]').forEach(function (el) {
            el.addEventListener('click', close);
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && !consentModal.hidden) close();
        });
        consentModal.querySelectorAll('[data-action]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var action = btn.dataset.action;
                var prefs = {};
                if (action === 'accept') {
                    toggles.forEach(function (t) { prefs[t.dataset.cat] = true; });
                } else if (action === 'reject') {
                    toggles.forEach(function (t) { prefs[t.dataset.cat] = false; });
                } else if (action === 'save') {
                    toggles.forEach(function (t) { prefs[t.dataset.cat] = t.checked; });
                }
                savePrefs(prefs);
                applyPrefs(prefs);
                close();
            });
        });
    }
})();
</script>

{{-- Fallback d'ouverture du menu burger (fiabilise en prod)
     Woody attache son listener au terme de son JS module defere : si
     l'utilisateur clique avant que ce listener soit en place (race
     condition observee en prod sur reseau lent), le clic est perdu.
     On attache ici un fallback en capture : si Woody a bien gere, on
     ne fait rien ; sinon on toggle manuellement body.menu-is-open. --}}
<script>
(function () {
    document.addEventListener('click', function (e) {
        var btn = e.target && e.target.closest
            ? e.target.closest('.menu-icon, [data-toggle="main-menu"]')
            : null;
        if (!btn) return;
        var wasOpen = document.body.classList.contains('menu-is-open');
        // 60ms suffit pour que Woody ait fait son toggle s'il est pret.
        // Si rien n'a change dans ce delai, on toggle nous-memes.
        window.setTimeout(function () {
            var isOpen = document.body.classList.contains('menu-is-open');
            if (isOpen === wasOpen) {
                document.body.classList.toggle('menu-is-open');
                btn.classList.toggle('open');
            }
        }, 60);
    }, true);
})();
</script>

{{-- Header hide/show au scroll (comme desktop Woody natif).
     Au scroll vers le bas : on cache le header en ajoutant .ba-nav-hidden
     (CSS applique translateY(-100%) + opacity 0 avec transition fluide).
     Au scroll vers le haut, en haut de page ou menu ouvert : on retire. --}}
<script>
(function () {
    var mobileNav  = document.querySelector('.woody-component-mobilenavs');
    var desktopNav = document.querySelector('.woody-component-headnavs');
    if (!mobileNav && !desktopNav) return;

    var lastY     = window.pageYOffset || document.documentElement.scrollTop;
    var ticking   = false;
    var threshold = 10;
    var topZone   = 24;

    function update() {
        ticking = false;
        var y        = window.pageYOffset || document.documentElement.scrollTop;
        var delta    = y - lastY;
        var scrollDn = delta > threshold;
        var scrollUp = delta < -threshold;
        var atTop    = y <= topZone;
        var menuOpen = document.body.classList.contains('menu-is-open');

        [mobileNav, desktopNav].forEach(function (nav) {
            if (!nav) return;
            if (menuOpen || atTop || scrollUp) {
                nav.classList.remove('ba-nav-hidden');
            } else if (scrollDn) {
                nav.classList.add('ba-nav-hidden');
            }
        });

        if (Math.abs(delta) > threshold) lastY = y;
    }

    window.addEventListener('scroll', function () {
        if (!ticking) {
            window.requestAnimationFrame(update);
            ticking = true;
        }
    }, { passive: true });
})();
</script>

{{-- Prefetch au survol : des que l'utilisateur survole un lien interne (ou
     commence a toucher sur mobile), on precharge le HTML en arriere-plan.
     Quand il clique, la page s'ouvre instantanement (contenu deja en cache
     navigateur). Logique: 65 ms de delai avant prefetch (evite les survols
     accidentels), 1 prefetch max par URL, ignore les externes/anchors. --}}
<script>
(function () {
    if (!('IntersectionObserver' in window) || !('fetch' in window)) return;
    var prefetched = new Set();
    var host = window.location.host;
    var timer = null;

    function shouldPrefetch(a) {
        if (!a || !a.href) return false;
        if (a.hasAttribute('download') || a.target === '_blank') return false;
        try {
            var u = new URL(a.href);
            if (u.host !== host) return false;
            if (u.pathname === window.location.pathname) return false;
            if (u.hash && u.pathname === window.location.pathname) return false;
            // Only HTML pages (skip assets)
            if (/\.(png|jpe?g|webp|gif|svg|ico|css|js|mjs|pdf|zip|mp4|woff2?)$/i.test(u.pathname)) return false;
            return true;
        } catch (e) { return false; }
    }

    function doPrefetch(href) {
        if (prefetched.has(href)) return;
        prefetched.add(href);
        var link = document.createElement('link');
        link.rel = 'prefetch';
        link.href = href;
        link.as = 'document';
        document.head.appendChild(link);
    }

    document.addEventListener('mouseover', function (e) {
        var a = e.target.closest && e.target.closest('a[href]');
        if (!shouldPrefetch(a)) return;
        clearTimeout(timer);
        timer = setTimeout(function () { doPrefetch(a.href); }, 65);
    }, { passive: true });

    document.addEventListener('mouseout', function () { clearTimeout(timer); }, { passive: true });

    // Touchstart : prefetch immédiat sur mobile (avant le clic)
    document.addEventListener('touchstart', function (e) {
        var a = e.target.closest && e.target.closest('a[href]');
        if (shouldPrefetch(a)) doPrefetch(a.href);
    }, { passive: true, capture: true });
})();
</script>

{{-- Recherche : la loupe du header ouvre l'overlay plein ecran
     (partials/overlays.blade.php). L'input est live : debounce 180ms
     puis fetch /recherche/suggest, rendu de cartes dans la grille
     .search-results-container. Enter envoie sur /recherche?q=... --}}
<script>
(function () {
    var overlay = document.querySelector('[data-ba-search-overlay]');
    if (!overlay) return;
    var openBtn  = document.querySelector('[data-ba-search-open]');
    var closeBtn = overlay.querySelector('[data-ba-search-close]');
    var input    = overlay.querySelector('[data-ba-search-input]');
    var clearBtn = overlay.querySelector('[data-ba-search-clear]');
    var results  = overlay.querySelector('[data-ba-search-results]');
    var empty    = overlay.querySelector('[data-ba-search-empty]');
    var hint     = overlay.querySelector('[data-ba-search-hint]');
    if (!openBtn || !input || !results) return;

    var timer = null;
    var currentCtrl = null;

    function open() {
        overlay.hidden = false;
        document.body.classList.add('ba-search-open');
        setTimeout(function () { input.focus(); }, 20);
    }
    function close() {
        overlay.hidden = true;
        document.body.classList.remove('ba-search-open');
        input.value = '';
        results.innerHTML = '';
        if (empty) empty.hidden = true;
        if (hint) hint.hidden = false;
    }
    // Fonction globale appelee par onclick inline sur le bouton X.
    // Garanti de marcher meme si un autre script ou un pointer-events
    // casse la delegation habituelle.
    window.baSearchClose = close;
    window.baSearchOpen  = open;

    openBtn.addEventListener('click', function (e) { e.preventDefault(); open(); });
    // Delegation globale en complement.
    document.addEventListener('click', function (e) {
        if (overlay.hidden) return;
        var t = e.target;
        if (t && t.closest && t.closest('[data-ba-search-close]')) {
            e.preventDefault();
            close();
            return;
        }
        if (t === overlay) { close(); }
    });
    if (clearBtn) clearBtn.addEventListener('click', function () {
        input.value = '';
        results.innerHTML = '';
        if (empty) empty.hidden = true;
        if (hint) hint.hidden = false;
        input.focus();
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && !overlay.hidden) { close(); openBtn.focus(); }
    });

    function escapeHtml(s) {
        return String(s == null ? '' : s)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;').replace(/'/g, '&#039;');
    }

    function card(it) {
        return '<div class="cell">'
             +   '<a class="ba-search-card" href="' + escapeHtml(it.url) + '">'
             +     (it.badge ? '<span class="ba-search-card__badge">' + escapeHtml(it.badge) + '</span>' : '')
             +     '<span class="ba-search-card__title">' + escapeHtml(it.title) + '</span>'
             +     (it.subtitle ? '<span class="ba-search-card__sub">' + escapeHtml(it.subtitle) + '</span>' : '')
             +     (it.excerpt  ? '<p class="ba-search-card__excerpt">' + escapeHtml(it.excerpt) + '</p>' : '')
             +   '</a>'
             + '</div>';
    }

    function render(data) {
        if (hint) hint.hidden = true;
        var items = data.results || [];
        if (!items.length) {
            results.innerHTML = '';
            if (empty) empty.hidden = false;
            return;
        }
        if (empty) empty.hidden = true;
        var html = items.map(card).join('');
        if (data.moreUrl) {
            html += '<div class="cell small-12 ba-search-more-wrap">'
                 +    '<a class="ba-search-more" href="' + escapeHtml(data.moreUrl) + '">Voir tous les résultats sur la page recherche →</a>'
                 +  '</div>';
        }
        results.innerHTML = html;
    }

    function fetchResults(q) {
        if (currentCtrl) currentCtrl.abort();
        currentCtrl = new AbortController();
        fetch('/recherche/suggest?q=' + encodeURIComponent(q), {
            headers: { 'Accept': 'application/json' },
            signal: currentCtrl.signal,
        })
        .then(function (r) { return r.ok ? r.json() : null; })
        .then(function (data) { if (data) render(data); })
        .catch(function () {});
    }

    input.addEventListener('input', function () {
        clearTimeout(timer);
        var q = input.value.trim();
        if (q.length < 2) {
            results.innerHTML = '';
            if (empty) empty.hidden = true;
            if (hint) hint.hidden = false;
            return;
        }
        if (hint) hint.hidden = true;
        if (empty) empty.hidden = true;
        results.innerHTML = '<div class="cell small-12"><div class="ba-search-loading">Recherche…</div></div>';
        timer = setTimeout(function () { fetchResults(q); }, 180);
    });
})();
</script>

{{-- Héros plein écran des pages intérieures : les pages déclarent un bandeau
     très allongé (format 4:1, ex. -1920x480) alors que le héros occupe tout
     l'écran ; la photo était fortement agrandie et recadrée (têtes coupées,
     image floue). On charge la même photo dans un format plus haut (2:1, ou à
     défaut 16:9) quand il existe ; sinon le bandeau d'origine reste affiché.
     Une photo remplacée au back-office (/storage/…) n'est jamais touchée. --}}
<script>
(function () {
    'use strict';
    var BANDEAU = /^(.*\/assets\/img\/.+)-(\d+)x(\d+)\.(webp|jpe?g|png)$/i;
    function plusHaut(img) {
        var m = (img.getAttribute('src') || '').match(BANDEAU);
        if (!m || Number(m[2]) < Number(m[3]) * 3) return;
        var besoin = Math.min((window.innerWidth || 1366) * (window.devicePixelRatio || 1), 1920);
        var l = besoin > 1200 ? 1920 : (besoin > 640 ? 1200 : 640);
        var formats = [[l, l / 2], [l, Math.round(l * 9 / 16)]];
        if (l !== 1920) formats.push([1920, 960], [1920, 1080]);
        var i = 0;
        (function essai() {
            if (i >= formats.length) return;
            var f = formats[i++], url = m[1] + '-' + f[0] + 'x' + f[1] + '.' + m[4];
            var test = new Image();
            test.onload = function () { img.removeAttribute('srcset'); img.removeAttribute('sizes'); img.src = url; };
            test.onerror = essai;
            test.src = url;
        })();
    }
    document.querySelectorAll('.hero-bg img').forEach(plusHaut);
})();
</script>

<script src="/assets/benin-zoom-galerie.js?v=1" defer></script>

{{-- Réglages d'affichage communs à toutes les pages (novembre 2026) :
     1. Liens vers les réseaux sociaux et boutons de partage masqués pour le moment.
        Le balisage reste en place : retirer la règle suffit à les remettre.
     2. Crédits photo superposés aux images (« © … / CC BY-SA … ») masqués.
        Les attributions complètes restent publiées sur /credits-photos.
     3. Le fil d'Ariane ne se pose jamais sur la photo d'un héros : il passe dans
        une barre blanche juste sous celui-ci (le nœud est déplacé, pas réécrit). --}}
<style>
.social-networks,.shares-wrapper,.sharing-links,.emerv-share{display:none!important}
.imageObject-caption,.emerv-hero__credit,.ltr-credit,.ptn-credit,.abm-credit,.ba-credit-masque{display:none!important}
.emerv-hero .emerv-hero__breadcrumb,.event-hero .event-hero__breadcrumb,.ba-page-hero .ba-page-hero__breadcrumb{visibility:hidden}
.ba-crumbbar{background:#fff;border-bottom:1px solid rgba(0,0,0,.08);padding:.85rem 0;position:relative;z-index:2}
.ba-crumbbar>nav{position:static!important;inset:auto!important;transform:none!important;visibility:visible!important;margin:0 auto!important;max-width:1180px;padding:0 1.25rem!important;background:none!important}
.ba-crumbbar ol{display:flex;flex-wrap:wrap;justify-content:center;align-items:center;gap:.4rem;margin:0;padding:0;list-style:none;font-size:.82rem;color:#5d6b64!important}
.ba-crumbbar li{margin:0}
.ba-crumbbar a{color:#3d4a43!important;text-decoration:none!important;border-bottom-color:transparent!important}
.ba-crumbbar a:hover{color:#00844d!important}
.ba-crumbbar [aria-current]{color:#00844d!important}
.ba-crumbbar [aria-hidden="true"]{color:#9aa59f!important}
/* 4. Plus de liserés de couleur posés en haut ou à gauche des blocs.
      L'accent passe dans un fond teinté, un contour fin tout autour et une ombre douce. */
.bg-white::before,[class*="bg-"] .bg-white::before,
.card.basicCard .card-section::before,.card.splitCard .card-section::before,
.benin-newsletter::before,.benin-related-tiles__card::after,.ba-eco-card__body::before,.wd-pricing__inner::before,
.bao .bao-pratique .woody-component-custom-text.tpl_02::before,.bao-step::before,
.tdt-traditions .tdt-cta::before,.ba-dir__card::before{display:none!important}
main .woody-component-custom-text.bg-white{border-radius:22px;box-shadow:0 1px 0 rgba(16,40,28,.04),0 18px 40px -26px rgba(16,40,28,.35);outline:1px solid rgba(16,40,28,.06);outline-offset:-1px}
main .card.basicCard,main .card.splitCard{border-radius:18px;overflow:hidden;box-shadow:0 1px 0 rgba(16,40,28,.05),0 16px 36px -24px rgba(16,40,28,.38);transition:transform .35s cubic-bezier(.2,.7,.3,1),box-shadow .35s}
main .card.basicCard:hover,main .card.splitCard:hover{transform:translateY(-4px);box-shadow:0 1px 0 rgba(16,40,28,.05),0 26px 46px -24px rgba(16,40,28,.45)}
.benin-related-tiles__card{border-radius:18px}
.bao-step{padding:clamp(1.8rem,3.5vw,2.8rem) clamp(1.6rem,4vw,3.2rem)!important}
.bao-nav{border-left:0!important}
.bao-nav a{border-left:0!important;margin-left:0!important;padding:.6rem .9rem!important;border-radius:14px;transition:background .3s,color .3s}
.bao-nav a.on,.bao-nav a:hover{background:rgba(255,255,255,.75);box-shadow:0 10px 24px -18px rgba(13,59,44,.5)}
/* Récits mis en forme (ba-recit) et pages d'exploration : mêmes principes, pas de liseré. */
.bar-carte,.bar-t-savane .bar-carte,.bar-t-terre .bar-carte{border:0!important;box-shadow:0 1px 0 rgba(16,40,28,.05),0 22px 46px -30px rgba(16,40,28,.45)!important;border-radius:24px!important}
.bar-carte:hover{box-shadow:0 1px 0 rgba(16,40,28,.05),0 34px 60px -30px rgba(16,40,28,.5)!important}
.bar-carte>div{padding:1.5rem 1.6rem 1.6rem!important}
.bar-etape,.bar-parcours .bar-etape{border:0!important;box-shadow:inset 0 0 0 1px color-mix(in srgb,var(--c,#00844d) 22%,transparent),0 30px 60px -40px rgba(58,31,10,.5)!important}
.bar-note,.bar-atouts .bar-note{border-left:0!important;padding:1rem 1.2rem!important;border-radius:16px!important;background:rgba(200,60,30,.07)!important;box-shadow:inset 0 0 0 1px rgba(200,69,43,.18)}
.ltr-lead{border-left:0!important;padding-left:0!important}
.br-sun li{border-top:0!important}
/* 6. Plus de petits traits décoratifs (devant les surtitres, sous les titres, autour des citations).
      Les surtitres restent en capitales espacées, sans ornement, comme sur les grands sites éditoriaux. */
.card.overlayedCard .card-titles .card-texts::after,.card.overlayedCard .card-titles .card-town::after,
.hero-titles>[class*="-texts"]::after,.hero-titles>[class*="-texts"]>[class*="-description"]::before,
.section-titles>[class*="-texts"]::after,.section-titles>[class*="-texts"]>[class*="-description"]::before,
.focus-header .focus-block-titles>[class*="-texts"]::after,.focus-header .focus-block-titles>[class*="-texts"]>[class*="-description"]::before,
.woody-component-custom-text .generic-block-header .generic-block-titles>[class*="-texts"]::after,
.woody-component-landswpr .landswpr-titles>[class*="-texts"]::after,.page-main-titles::after,
.benin-littoral__eyebrow::before,.benin-littoral__eyebrow::after,.benin-newsletter__eyebrow::after,
.benin-airlines__eyebrow::before,.benin-airlines__eyebrow::after,.benin-gateway__eyebrow::before,.benin-gateway__eyebrow::after,
.ba-intro__title::after,.bao .benin-page-chapo::after,.bao-k::before,
.bar .bar-kick::before,.slc-tete span::before,.trc .trc-k::before,.rte .rte-cit::before,.ltr .ltr-kick::before,
.tdt-traditions .tdt-pre::before,.tdt-traditions .tdt-txt::before,.vdn .vdn-orn::before,.vdn .vdn-orn::after,.vdn .vdn-kicker::before,
.tlq .tlq-k::before,.bb-l::before{display:none!important;content:none!important}
.bag__filet,.tdt-bar{display:none!important}
/* 5. Textes courants des cartes et encadrés : justifiés, avec césure, et jamais collés au bord. */
.bao-step .woody-component-text p,.bao-step>p,
main .woody-component-custom-text .woody-component-text p,
main .benin-page-block p,.bf-lead,.bf-faq details p,
.vd-dieu__txt p,.vd-piliers p,.vd-station p,.vd-orig__txt p,.wl-bloc p,.wl-track p,.wl-hist p,
.bar-carte p,.bar-etape p,.bar-note,.fm-salle p,.fm-hist p,.jn-fiche p,.jn-page p,.jn-hist p,.ga-chron__txt p,.ga-mot dd,.ga-bloc p{text-align:justify;hyphens:auto;-webkit-hyphens:auto}
.gnv .gnv-arret,.abm .abm-k,.rs-tier,.ptn .ptn-j,.ba-timeline__era,.ba-agenda__event{border:0!important}
.gnv .gnv-arret{background:color-mix(in srgb,var(--turq,#16a3a0) 7%,#fff)!important;box-shadow:inset 0 0 0 1px color-mix(in srgb,var(--turq,#16a3a0) 22%,transparent),0 14px 30px -24px rgba(4,38,45,.5)!important}
.abm .abm-k{background:color-mix(in srgb,var(--sang,#8b1e24) 6%,#fff)!important;box-shadow:inset 0 0 0 1px color-mix(in srgb,var(--sang,#8b1e24) 20%,transparent),0 14px 30px -24px rgba(74,15,18,.5)!important}
.rs-tier{background:color-mix(in srgb,var(--c,#00844d) 9%,var(--rs-paper,#f7f6f1))!important;box-shadow:inset 0 0 0 1px color-mix(in srgb,var(--c,#00844d) 22%,transparent)}
.ptn .ptn-j{box-shadow:inset 0 0 0 1px color-mix(in srgb,var(--c,var(--turq,#16a3a0)) 25%,transparent)}
.ba-timeline__era{box-shadow:inset 0 0 0 1px color-mix(in srgb,var(--era-color,#00844d) 22%,transparent),0 18px 40px -28px rgba(11,61,44,.35)!important;border-radius:22px!important}
.ba-agenda__event{background:color-mix(in srgb,var(--e-color,#00844d) 8%,#fff)!important;box-shadow:inset 0 0 0 1px color-mix(in srgb,var(--e-color,#00844d) 20%,transparent);border-radius:16px!important}
.tdt-traditions .tdt-intro{border-left:0!important;padding-left:0!important}
.cr-note{border-left:0!important;padding-left:0!important}
.ba-safety{border-left-width:1px!important}
</style>
<script>
(function () {
    'use strict';
    var CREDITS = location.pathname.indexOf('/credits-photos') === 0;
    var MOTIF = /©|\bCC[ -]?(BY|0)\b|Wikimedia|licences? libres?/i;

    function filDAriane() {
        var navs = document.querySelectorAll('.emerv-hero__breadcrumb,.event-hero__breadcrumb,main nav[aria-label*="riane" i],main [class*="crumb"]');
        Array.prototype.forEach.call(navs, function (nav) {
            if (nav.closest('.ba-crumbbar,.bao-crumbbar,.rs-crumbbar,.woody-component-breadcrumb') || nav.parentElement.closest('[class*="crumb"]')) return;
            var hero = nav.closest('.emerv-hero') || nav.closest('.event-hero') || nav.closest('.woody-component-hero');
            for (var p = nav.parentElement; !hero && p && p.tagName !== 'MAIN' && p !== document.body; p = p.parentElement) {
                if (/(^|\s)[a-z0-9-]*hero(\s|$)/.test(p.className)) hero = p;
            }
            if (!hero) return;
            var bar = document.createElement('div');
            bar.className = 'ba-crumbbar bao-crumbbar';
            hero.parentNode.insertBefore(bar, hero.nextSibling);
            bar.appendChild(nav);
        });
    }

    function credits() {
        if (CREDITS) return;
        var els = document.querySelectorAll('main figcaption,main small,main p,main span,main div');
        Array.prototype.forEach.call(els, function (e) {
            if (e.children.length > 3 || e.closest('.ba-credit-masque,.benin-sources,footer,.site-footer-container')) return;
            var t = e.textContent;
            if (t.length > 220 || !MOTIF.test(t) || e.querySelector('a[href*="credits-photos"]')) return;
            /* Seulement les mentions posées sur ou sous une image, pas les textes de la page. */
            if (e.tagName !== 'FIGCAPTION' && !e.closest('figure') && !/cr(e|é)dit|caption|legende|légende|source/i.test(e.className + ' ' + t.slice(0, 12))) return;
            e.classList.add('ba-credit-masque');
        });
    }

    function lancer() { filDAriane(); credits(); }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', lancer); else lancer();
})();
</script>

{{-- Navigation plus rapide : la page visée est préchargée dès que le pointeur s'attarde sur
     un lien interne (ou au premier appui sur mobile). Rien n'est chargé sans intention de clic. --}}
<script type="speculationrules">
{"prefetch":[{"source":"document","where":{"and":[{"href_matches":"/*"},{"not":{"href_matches":"/admin*"}},{"not":{"href_matches":"/livewire*"}},{"not":{"href_matches":"/*\\?*"}},{"not":{"href_matches":"/*.pdf"}},{"not":{"selector_matches":"[download],[target=_blank],[rel~=nofollow]"}}]},"eagerness":"moderate"}]}
</script>
<script>
(function () {
    'use strict';
    if (HTMLScriptElement.supports && HTMLScriptElement.supports('speculationrules')) return;
    var vus = {}, n = 0;
    function precharger(e) {
        var a = e.target.closest && e.target.closest('a[href]');
        if (!a || n > 20 || a.target === '_blank' || a.hasAttribute('download')) return;
        var u = a.href;
        if (a.origin !== location.origin || u.indexOf('?') > -1 || /\/admin|\.pdf$|#/.test(u) || u === location.href || vus[u]) return;
        vus[u] = 1; n++;
        var l = document.createElement('link'); l.rel = 'prefetch'; l.href = u; document.head.appendChild(l);
    }
    document.addEventListener('mouseover', function (e) { clearTimeout(precharger.t); precharger.t = setTimeout(function () { precharger(e); }, 120); }, { passive: true });
    document.addEventListener('touchstart', precharger, { passive: true });
})();
</script>

{{-- Mobile (novembre 2026) :
     1. Le héros d'en-tête couvre tout l'écran, titre posé en bas sur un dégradé, sans invite de défilement.
     2. Le fil d'Ariane n'est plus abrégé en « [...] » : il garde tous ses niveaux, sur une seule ligne
        qui défile au doigt et s'ouvre calée sur la page en cours.
     3. Les extraits coupés au milieu d'une phrase (« … pachydermes de... ») s'arrêtent à la dernière phrase complète. --}}
<style>
@media (max-width:767.98px){
html:not(#ba) body:not(#ba) main>.content-wrapper>.woody-component-hero.has-bgimg:not(.hero-split):not(.hero-blog-header),html:not(#ba) body:not(#ba) main .emerv-hero{position:relative!important;height:100vh!important;height:100svh!important;min-height:560px!important;max-height:none!important;overflow:hidden!important}
html:not(#ba) body:not(#ba) main>.content-wrapper>.woody-component-hero.has-bgimg:not(.hero-split):not(.hero-blog-header) .hero-bg,html:not(#ba) body:not(#ba) main>.content-wrapper>.woody-component-hero.has-bgimg:not(.hero-split):not(.hero-blog-header) .hero-bg figure,html:not(#ba) body:not(#ba) main>.content-wrapper>.woody-component-hero.has-bgimg:not(.hero-split):not(.hero-blog-header) .backgroundMedia{position:absolute!important;inset:0!important;height:100%!important;width:100%!important;margin:0!important}
html:not(#ba) body:not(#ba) main>.content-wrapper>.woody-component-hero.has-bgimg:not(.hero-split):not(.hero-blog-header) .hero-bg img,html:not(#ba) body:not(#ba) main .emerv-hero .emerv-hero__img{position:absolute!important;inset:0!important;width:100%!important;height:100%!important;max-width:none!important;object-fit:cover!important;transform:none!important}
html:not(#ba) body:not(#ba) main>.content-wrapper>.woody-component-hero.has-bgimg:not(.hero-split):not(.hero-blog-header)::after,html:not(#ba) body:not(#ba) main .emerv-hero::after{content:"";position:absolute;inset:0;z-index:1;pointer-events:none;background:linear-gradient(180deg,rgba(0,0,0,.42) 0%,rgba(0,0,0,0) 26%,rgba(0,0,0,0) 42%,rgba(0,0,0,.72) 100%)}
html:not(#ba) body:not(#ba) main>.content-wrapper>.woody-component-hero.has-bgimg:not(.hero-split):not(.hero-blog-header) .fullcenterbox{position:absolute!important;inset:0!important;top:0!important;bottom:0!important;height:100%!important;transform:none!important;z-index:2!important;display:flex!important;flex-direction:column!important;justify-content:flex-end!important;padding:0 1.4rem calc(5.2rem + env(safe-area-inset-bottom,0px))!important}
html:not(#ba) body:not(#ba) main .emerv-hero .emerv-hero__overlay{position:absolute!important;inset:0!important;z-index:2!important;display:flex!important;flex-direction:column!important;justify-content:flex-end!important;align-items:center!important;background:none!important;padding:0 1.4rem calc(5.2rem + env(safe-area-inset-bottom,0px))!important}
html:not(#ba) body:not(#ba) main>.content-wrapper>.woody-component-hero.has-bgimg:not(.hero-split):not(.hero-blog-header) .hero-title,html:not(#ba) body:not(#ba) main .emerv-hero .emerv-hero__title{font-size:clamp(2.2rem,10vw,3rem)!important;line-height:1.04!important;text-shadow:0 2px 24px rgba(0,0,0,.35)}
html:not(#ba) body:not(#ba) main .emerv-hero .emerv-hero__center{flex:0 0 auto!important;height:auto!important;min-height:0!important;justify-content:flex-end!important;padding:0!important}
html:not(#ba) body:not(#ba) main .emerv-hero .emerv-hero__lead{font-size:1.02rem!important;line-height:1.55!important}
html.ba-hero-vu .ba-ecolaunch{opacity:0!important;visibility:hidden!important;pointer-events:none!important}
.ba-ecolaunch{transition:opacity .35s ease,visibility .35s}
html:not(#ba) body:not(#ba) main .home-slider .woody-component-landswpr,html:not(#ba) body:not(#ba) main .home-slider .woody-landing-swiper,html:not(#ba) body:not(#ba) main .home-slider .swiper-wrapper,html:not(#ba) body:not(#ba) main .home-slider .swiper-slide,html:not(#ba) body:not(#ba) main .home-slider .landswprMediaWrapper{height:100vh!important;height:100svh!important;min-height:560px!important;max-height:none!important}
html:not(#ba) body:not(#ba) main .home-slider .landswprMediaWrapper img{width:100%!important;height:100%!important;max-width:none!important;object-fit:cover!important}
.al-in{grid-template-columns:minmax(0,1fr)!important}.al-k{gap:.8rem!important;padding:1rem 1.1rem!important}.al-k b{font-size:2.3rem!important;min-width:0!important}.al-k i{width:44px!important;height:44px!important}
.woody-component-breadcrumb ol,.ba-crumbbar ol{flex-wrap:nowrap!important;justify-content:flex-start!important;overflow-x:auto;overscroll-behavior-x:contain;scrollbar-width:none;white-space:nowrap;-webkit-mask-image:linear-gradient(90deg,transparent 0,#000 1rem,#000 calc(100% - 1rem),transparent 100%);mask-image:linear-gradient(90deg,transparent 0,#000 1rem,#000 calc(100% - 1rem),transparent 100%);padding:0 1rem!important}
.woody-component-breadcrumb ol::-webkit-scrollbar,.ba-crumbbar ol::-webkit-scrollbar{display:none}
.woody-component-breadcrumb li,.ba-crumbbar li{flex:0 0 auto;white-space:nowrap}
}
.woody-component-breadcrumb li[style*="none"],.woody-component-breadcrumb .breadcrumb-icon-separator[style*="none"]{display:inline-flex!important}
</style>
<script>
(function () {
    'use strict';
    /* Le thème remplace, sous 768 px, les niveaux intermédiaires du fil d'Ariane par « [...] ».
       Les intitulés d'origine sont notés ici, avant son passage, puis remis en place. */
    var noms = [];
    var fil = document.querySelector('.woody-component-breadcrumb');
    if (fil) Array.prototype.forEach.call(fil.querySelectorAll('[property="name"]'), function (s) { noms.push([s, s.textContent]); });

    function remettre() {
        noms.forEach(function (n) { if (/^\s*\[(\.\.\.|…)\]\s*$/.test(n[0].textContent)) n[0].textContent = n[1]; });
        if (window.innerWidth < 768) {
            Array.prototype.forEach.call(document.querySelectorAll('.woody-component-breadcrumb ol,.ba-crumbbar ol'), function (ol) { ol.scrollLeft = ol.scrollWidth; });
        }
    }

    function extraits() {
        var els = document.querySelectorAll('main .card p,main .card-description,main [class*="card"] [class*="desc"],main .card-texts p');
        Array.prototype.forEach.call(els, function (e) {
            if (e.children.length) return;
            var t = e.textContent.replace(/\s+$/, '');
            if (!/(\.\.\.|…)$/.test(t)) return;
            if (/[^.]\.{4}$/.test(t)) { e.textContent = t.replace(/\.+$/, '.'); return; }
            var base = t.replace(/(\.{3,}|…)+$/, '').replace(/[\s,;:]+$/, '');
            var i = Math.max(base.lastIndexOf('. '), base.lastIndexOf('! '), base.lastIndexOf('? '));
            e.textContent = i > 30 ? base.slice(0, i + 1) : base + '…';
        });
    }

    /* Sous le héros plein écran, les boutons partenaires flottants attendent qu'on ait commencé à défiler. */
    function heros() {
        var h = document.querySelector('main>.content-wrapper>.woody-component-hero.has-bgimg,main .emerv-hero,main .home-slider');
        if (!h || !('IntersectionObserver' in window)) return;
        var mq = window.matchMedia('(max-width: 767.98px)');
        /* En plein écran vertical, une photo en 2:1 est trop étirée : on prend le même cliché en format carré ou portrait s'il existe. */
        var img = h.querySelector('.hero-bg img,.emerv-hero__img');  /* le carrousel d'accueil garde ses propres images */
        var m = img && mq.matches && (img.currentSrc || img.src).match(/^(.*\/assets\/img\/.+?)-\d+x\d+\.(webp|jpe?g|png)$/i);
        if (m) {
            var f = ['1200x1200', '1920x1440', '1200x900', '640x854'], i = 0;
            (function essai() {
                if (i >= f.length) return;
                var u = m[1] + '-' + f[i++] + '.' + m[2], x = new Image();
                x.onload = function () { img.removeAttribute('srcset'); img.removeAttribute('sizes'); img.src = u; };
                x.onerror = essai; x.src = u;
            })();
        }
        new IntersectionObserver(function (es) {
            document.documentElement.classList.toggle('ba-hero-vu', mq.matches && es[0].intersectionRatio > 0.45);
        }, { threshold: [0, 0.45, 0.6, 1] }).observe(h);
    }

    function lancer() { remettre(); extraits(); heros(); }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', lancer); else lancer();
    window.addEventListener('load', remettre);
})();
</script>

{{-- En-tête photo des pages sans héros (contact, mentions légales, plan du site…) : la photo couvre
     l'en-tête, titre et chapô en blanc dessus ; le fil d'Ariane passe dans la barre blanche dessous. --}}
<style>
.ba-teaser-hero{position:relative;isolation:isolate;display:flex;flex-direction:column;justify-content:flex-end;min-height:clamp(440px,64vh,660px);padding:calc(var(--ba-header-h,96px) + 2.5rem) 0 clamp(2.5rem,5vw,4rem);overflow:hidden;background:#0b3d2c}
.ba-teaser-hero>.ba-teaser-hero__img{position:absolute;inset:0;z-index:-2;width:100%;height:100%;max-width:none;object-fit:cover}
.ba-teaser-hero::before{content:"";position:absolute;inset:0;z-index:-1;background:linear-gradient(180deg,rgba(6,24,17,.55) 0%,rgba(6,24,17,.25) 38%,rgba(6,24,17,.78) 100%)}
.ba-teaser-hero .woody-component-teaser{background:none!important;padding-bottom:0!important}
.ba-teaser-hero .bgImageObject{display:none!important}
.ba-teaser-hero .texts-wrapper{text-align:center!important;margin:0 auto!important;max-width:860px}
.ba-teaser-hero .page-main-titles .flex-container{justify-content:center}
.ba-teaser-hero .page-main-title{color:#fff!important;font-size:clamp(2.3rem,5vw,4rem)!important;line-height:1.05!important;text-shadow:0 2px 24px rgba(0,0,0,.35)}
.ba-teaser-hero .page-main-description,.ba-teaser-hero .page-main-description p{color:rgba(255,255,255,.92)!important;font-size:clamp(1rem,1.4vw,1.18rem)!important;line-height:1.6!important}
.ba-teaser-hero .page-main-description strong{color:#fff!important}
.ba-teaser-hero .page-main-description a{color:#fcd116!important}
.ba-teaser-crumb .woody-component-breadcrumb{background:#fff;border-bottom:1px solid rgba(0,0,0,.08)}
.ba-teaser-crumb .woody-component-breadcrumb ol{justify-content:center}
@media (max-width:767.98px){.ba-teaser-hero{min-height:100vh;min-height:100svh;padding-bottom:calc(3rem + env(safe-area-inset-bottom,0px))}.ba-teaser-hero .page-main-title{font-size:clamp(2.2rem,10vw,3rem)!important}}

/* Cartes superposées avec bouton « Lire la suite » : bouton compact, centré, dans la carte */
html:not(#ba) body:not(#ba) main .overlayedCard .card-section.has-button{justify-content:flex-end!important;align-items:center!important;gap:.55rem;padding:1rem 1rem 1.1rem!important;box-sizing:border-box!important}
html:not(#ba) body:not(#ba) main .overlayedCard .card-section.has-button .card-titles{width:100%}
html:not(#ba) body:not(#ba) main .overlayedCard .card-section.has-button .card-description{display:-webkit-box!important;-webkit-box-orient:vertical!important;-webkit-line-clamp:3;overflow:hidden!important}
html:not(#ba) body:not(#ba) main .overlayedCard .card-section.has-button .card-button{position:relative!important;inset:auto!important;flex:0 0 auto!important;width:auto!important;max-width:calc(100% - .5rem)!important;align-self:center!important;margin:0!important;padding:.5rem 2.4rem .5rem 1.3rem!important;min-height:0!important;border-radius:999px!important;white-space:nowrap!important;font-size:.9rem!important;line-height:1.3!important;transform:none!important}
@media (hover:none){html:not(#ba) body:not(#ba) main .overlayedCard .card-section.has-button .card-button{opacity:1!important;visibility:visible!important}}
@media (max-width:1199.98px){html:not(#ba) body:not(#ba) main .overlayedCard .card-section.has-button .card-description{-webkit-line-clamp:2}}
@media (max-width:767.98px){
/* Héros sur mobile : texte centré au milieu de l'écran */
html:not(#ba) body:not(#ba) main>.content-wrapper>.woody-component-hero.has-bgimg:not(.hero-split):not(.hero-blog-header) .fullcenterbox{justify-content:center!important;text-align:center!important;padding:calc(var(--ba-header-h,84px) + .5rem) 1.4rem 2.5rem!important}
html:not(#ba) body:not(#ba) main>.content-wrapper>.woody-component-hero.has-bgimg:not(.hero-split):not(.hero-blog-header) .hero-titles,html:not(#ba) body:not(#ba) main>.content-wrapper>.woody-component-hero.has-bgimg:not(.hero-split):not(.hero-blog-header) .hero-texts{align-items:center!important;text-align:center!important}
html:not(#ba) body:not(#ba) main .emerv-hero .emerv-hero__overlay{justify-content:center!important;padding:calc(var(--ba-header-h,84px) + .5rem) 1.4rem 2.5rem!important}
html:not(#ba) body:not(#ba) main .emerv-hero .emerv-hero__center{justify-content:center!important;text-align:center!important}
html:not(#ba) body:not(#ba) main>.content-wrapper>.woody-component-hero.has-bgimg:not(.hero-split):not(.hero-blog-header)::after,html:not(#ba) body:not(#ba) main .emerv-hero::after{background:linear-gradient(180deg,rgba(0,0,0,.5) 0%,rgba(0,0,0,.28) 28%,rgba(0,0,0,.42) 50%,rgba(0,0,0,.28) 72%,rgba(0,0,0,.5) 100%)!important}
html:not(#ba) body:not(#ba) main .ba-teaser-hero{justify-content:center!important;padding-bottom:2.5rem!important}
html:not(#ba) body:not(#ba) main .ofr-hero{align-items:center!important}
html:not(#ba) body:not(#ba) main .ofr-hero .section-header-wrapper,html:not(#ba) body:not(#ba) main .ofr-hero .section-header{text-align:center!important}
html:not(#ba) body:not(#ba) main .bf :is(.vd-hero,.wl-hero,.fm-hero,.jn-hero,.ga-hero) :is(h1,h2,p,span,small,em,strong){text-align:center!important}
html:not(#ba) body:not(#ba) main .bf .vd-hero__txt{justify-content:center!important;align-items:center!important}
html:not(#ba) body:not(#ba) main .bf :is(.wl-hero__grid,.fm-hero__grid,.jn-hero__grid){align-items:center!important;justify-items:center!important}
html:not(#ba) body:not(#ba) main .bf .ga-hero{align-items:center!important}
html:not(#ba) body:not(#ba) main .bf :is(.vd-hero,.wl-hero,.fm-hero,.jn-hero,.ga-hero) :is(div,ul,p,nav){justify-content:center}
}
/* Introductions longues (souvent rédigées dans l'admin) : lecture confortable, repliées au-delà de quelques lignes */
html:not(#ba) body:not(#ba) main .page-main-description.ba-intro-long{max-width:780px!important;margin-left:auto!important;margin-right:auto!important;text-align:left!important}
html:not(#ba) body:not(#ba) main .ba-intro-long .ba-intro-body{position:relative;overflow:hidden;max-height:11.2em;transition:max-height .6s cubic-bezier(.2,.8,.2,1)}
html:not(#ba) body:not(#ba) main .ba-intro-long .ba-intro-body p,html:not(#ba) body:not(#ba) main .ba-intro-long .ba-intro-body{text-align:left!important;line-height:1.85!important}
html:not(#ba) body:not(#ba) main .ba-intro-long .ba-intro-body::after{content:"";position:absolute;left:0;right:0;bottom:0;height:5.5em;background:linear-gradient(180deg,rgba(255,255,255,0),var(--ba-intro-bg,#fff) 88%);pointer-events:none;transition:opacity .4s}
html:not(#ba) body:not(#ba) main .ba-intro-long.is-open .ba-intro-body{max-height:none}
html:not(#ba) body:not(#ba) main .ba-intro-long.is-open .ba-intro-body::after{opacity:0}
html:not(#ba) body:not(#ba) main .ba-intro-long .ba-intro-body>p:first-child::first-letter,html:not(#ba) body:not(#ba) main .ba-intro-long .ba-intro-body>p:first-child>span:first-child::first-letter{float:left;font-size:3.6em;line-height:.82;margin:.06em .14em 0 0;font-weight:800;color:#00844d}
html:not(#ba) body:not(#ba) main .ba-intro-more{display:flex;align-items:center;gap:.55rem;margin:1.1rem auto 0;padding:.62rem 1.35rem;border:0;border-radius:999px;background:#00844d;color:#fff;font:700 .92rem/1 Montserrat,Helvetica,Arial,sans-serif;cursor:pointer;box-shadow:0 10px 24px -14px rgba(0,80,45,.8);transition:transform .25s,background .25s}
html:not(#ba) body:not(#ba) main .ba-intro-more:hover{transform:translateY(-2px);background:#006b3f}
html:not(#ba) body:not(#ba) main .ba-intro-more svg{transition:transform .35s}
html:not(#ba) body:not(#ba) main .ba-intro-more:hover svg{transform:translateX(3px)}
html:not(#ba) body:not(#ba) main .ba-intro-long .ba-intro-body{cursor:pointer}
/* Fenêtre de lecture des longues introductions */
html.ba-read-open,html.ba-read-open body{overflow:hidden!important}
dialog.ba-read{position:fixed;inset:0;width:100%;max-width:none;height:100%;max-height:none;margin:0;padding:clamp(.75rem,3vw,2rem);border:0;background:transparent;display:none;place-items:center;z-index:2147483000}
dialog.ba-read[open]{display:grid;animation:baReadIn .28s ease-out}
dialog.ba-read::backdrop{background:rgba(8,26,20,.62);backdrop-filter:blur(4px);-webkit-backdrop-filter:blur(4px)}
.ba-read__box{display:flex;flex-direction:column;width:min(760px,100%);max-height:min(86vh,900px);max-height:min(86dvh,900px);border-radius:24px;background:#fffdf8;box-shadow:0 40px 90px -30px rgba(0,0,0,.55);overflow:hidden;font-family:Montserrat,Helvetica,Arial,sans-serif}
.ba-read__head{position:relative;padding:1.4rem 4.2rem 1.1rem 1.6rem;background:#00844d;color:#fff}
.ba-read__k{display:block;font-size:.7rem;font-weight:800;letter-spacing:.22em;text-transform:uppercase;color:#f8d55a}
.ba-read__t{margin:.35rem 0 0!important;font-size:clamp(1.2rem,2.6vw,1.6rem)!important;font-weight:800;line-height:1.2!important;color:#fff!important}
.ba-read__x{position:absolute;top:1rem;right:1rem;display:grid;place-items:center;width:2.6rem;height:2.6rem;padding:0;border:0;border-radius:50%;background:rgba(255,255,255,.16);color:#fff;cursor:pointer;transition:background .2s,transform .2s}
.ba-read__x:hover,.ba-read__x:focus-visible{background:#fff;color:#00844d;transform:rotate(90deg);outline:none}
.ba-read__body{flex:1 1 auto;overflow:auto;overscroll-behavior:contain;-webkit-overflow-scrolling:touch;padding:1.5rem 1.6rem}
.ba-read__text,.ba-read__text p{color:#2b3530!important;font-size:1.02rem!important;line-height:1.85!important;text-align:left!important}
.ba-read__text p{margin:0 0 1em!important}.ba-read__text p:last-child{margin-bottom:0!important}
.ba-read__text>p:first-child::first-letter{float:left;margin:.08em .12em 0 0;font-size:3.2em;line-height:.85;font-weight:800;color:#00844d}
.ba-read__text a{color:#00844d!important;text-decoration:underline}
.ba-read__foot{display:flex;justify-content:flex-end;padding:.9rem 1.6rem;background:#f4efe4}
.ba-read__close{padding:.65rem 1.5rem;border:0;border-radius:999px;background:#00844d;color:#fff;font:700 .92rem Montserrat,Helvetica,Arial,sans-serif;cursor:pointer}
.ba-read__close:hover{background:#006b3f}
@keyframes baReadIn{from{opacity:0;transform:translateY(14px) scale(.98)}to{opacity:1;transform:none}}
@media (max-width:767.98px){dialog.ba-read{padding:0;place-items:end center}.ba-read__box{width:100%;max-height:92vh;max-height:92dvh;border-radius:22px 22px 0 0}.ba-read__head{padding:1.2rem 4rem 1rem 1.2rem}.ba-read__body{padding:1.2rem}.ba-read__foot{padding:.8rem 1.2rem calc(.8rem + env(safe-area-inset-bottom))}.ba-read__close{width:100%}}
@media (prefers-reduced-motion:reduce){dialog.ba-read[open]{animation:none}}
/* Accessibilité (PageSpeed) : vert plus soutenu là où le vert clair #00a05d manquait de contraste */
html body .section-pretitle{color:#00754a!important}
html body .site-footer .bg-primary,html body .infos-block.bg-primary,html body .buttons-block.bg-primary{background-color:#00754a!important}
/* Zones tactiles du pied de page assez grandes (24 px minimum) */
html body .infos-block a[href^="tel:"],html body .infos-block a[href^="mailto:"]{display:inline-block;min-height:24px;line-height:24px;padding:2px 0}
@media (max-width:767.98px){html:not(#ba) body:not(#ba) main .ba-intro-long .ba-intro-body{max-height:9.3em}}
</style>
<script>
(function () {
    /* Pas dans un aperçu de l'admin : le texte doit y rester tel quel. */
    if (window.self !== window.top) return;
    function fond(el) {
        while (el && el !== document.documentElement) {
            var c = getComputedStyle(el).backgroundColor;
            if (c && c !== 'transparent' && !/rgba\(\d+,\s*\d+,\s*\d+,\s*0\)/.test(c)) return c;
            el = el.parentElement;
        }
        return '#fff';
    }
    function init() {
        document.querySelectorAll('main .page-main-description').forEach(function (d) {
            if (d.closest('.ba-teaser-hero') || d.classList.contains('ba-intro-long')) return;
            if (d.textContent.replace(/\s+/g, ' ').trim().length < 650) return;
            var body = document.createElement('div');
            body.className = 'ba-intro-body';
            while (d.firstChild) body.appendChild(d.firstChild);
            d.appendChild(body);
            d.classList.add('ba-intro-long');
            d.style.setProperty('--ba-intro-bg', fond(d));
            var b = document.createElement('button');
            b.type = 'button';
            b.className = 'ba-intro-more';
            b.setAttribute('aria-haspopup', 'dialog');
            b.innerHTML = '<span>Lire la suite</span><svg width="14" height="14" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"/></svg>';
            d.insertAdjacentElement('afterend', b);
            b.addEventListener('click', function () { ouvrir(body, b); });
            /* Le texte tronqué lui-même ouvre aussi la fenêtre. */
            body.addEventListener('click', function (e) { if (!e.target.closest('a')) ouvrir(body, b); });
        });
    }
    /* Fenêtre de lecture : tout le texte, une croix pour fermer, Échap et clic à l'extérieur aussi. */
    var dlg;
    function ouvrir(body, from) {
        if (!dlg) {
            dlg = document.createElement('dialog');
            dlg.className = 'ba-read';
            dlg.setAttribute('aria-labelledby', 'ba-read-t');
            dlg.innerHTML = '<div class="ba-read__box"><div class="ba-read__head"><span class="ba-read__k">À lire</span><p class="ba-read__t" id="ba-read-t"></p>'
                + '<button type="button" class="ba-read__x" aria-label="Fermer"><svg width="20" height="20" viewBox="0 0 24 24" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/></svg></button></div>'
                + '<div class="ba-read__body"></div><div class="ba-read__foot"><button type="button" class="ba-read__close">Fermer</button></div></div>';
            document.body.appendChild(dlg);
            var fermer = function () { dlg.close(); };
            dlg.querySelector('.ba-read__x').addEventListener('click', fermer);
            dlg.querySelector('.ba-read__close').addEventListener('click', fermer);
            dlg.addEventListener('click', function (e) { if (e.target === dlg) fermer(); });
            dlg.addEventListener('close', function () {
                document.documentElement.classList.remove('ba-read-open');
                if (dlg._from) dlg._from.focus({ preventScroll: true });
            });
        }
        var h1 = document.querySelector('main h1');
        dlg.querySelector('.ba-read__t').textContent = h1 ? h1.textContent.replace(/\s+/g, ' ').trim() : document.title;
        var cible = dlg.querySelector('.ba-read__body');
        cible.innerHTML = '';
        cible.appendChild(body.cloneNode(true)).className = 'ba-read__text';
        dlg._from = from;
        document.documentElement.classList.add('ba-read-open');
        if (dlg.showModal) dlg.showModal(); else dlg.setAttribute('open', '');
        cible.scrollTop = 0;
        dlg.querySelector('.ba-read__x').focus({ preventScroll: true });
    }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init); else init();
})();
</script>


{{-- Cartes « splitCard » : quand l'admin y a saisi un long texte (titre ou description), la carte
     montre un extrait et un bouton « Voir plus » qui ouvre le texte complet dans la fenêtre de lecture.
     Les cartes déjà traitées par une page (ex. Terre de traditions, .ttx) sont ignorées. --}}
<style>
html:not(#ba) body:not(#ba) main .ba-cl-long{display:-webkit-box!important;-webkit-box-orient:vertical!important;-webkit-line-clamp:6!important;overflow:hidden!important;max-height:none!important}
html:not(#ba) body:not(#ba) main .card-title.ba-cl-long{font-weight:400!important;font-size:1rem!important;line-height:1.75!important;text-transform:none!important;letter-spacing:0!important}
html:not(#ba) body:not(#ba) main .ba-cl-h{display:block;margin:0 0 .7rem;font:800 clamp(1.3rem,2.2vw,1.75rem)/1.2 Montserrat,Helvetica,Arial,sans-serif;color:#10261a}
html:not(#ba) body:not(#ba) main .ba-cl-hors-sujet{display:none!important}
html:not(#ba) body:not(#ba) main .ba-cl-plus{display:inline-flex;align-items:center;gap:.5rem;margin-top:1rem;padding:.7rem 1.3rem;border:0;border-radius:999px;background:#00754a;color:#fff!important;font:700 .9rem Montserrat,Helvetica,Arial,sans-serif;cursor:pointer;transition:background .25s,transform .25s;align-self:center}
html:not(#ba) body:not(#ba) main .ba-cl-plus:hover,html:not(#ba) body:not(#ba) main .ba-cl-plus:focus-visible{background:#005c3a;transform:translateY(-2px)}
@media (max-width:767.98px){html:not(#ba) body:not(#ba) main .ba-cl-long{-webkit-line-clamp:7!important}}
</style>
<script>
(function () {
    if (window.self !== window.top) return;
    var dlg;
    function ouvrir(titre, src, from) {
        if (!dlg) {
            dlg = document.createElement('dialog');
            dlg.className = 'ba-read';
            dlg.setAttribute('aria-labelledby', 'ba-cl-t');
            dlg.innerHTML = '<div class="ba-read__box"><div class="ba-read__head"><span class="ba-read__k">À lire</span><p class="ba-read__t" id="ba-cl-t"></p>'
                + '<button type="button" class="ba-read__x" aria-label="Fermer"><svg width="20" height="20" viewBox="0 0 24 24" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/></svg></button></div>'
                + '<div class="ba-read__body"><div class="ba-read__text"></div></div><div class="ba-read__foot"><button type="button" class="ba-read__close">Fermer</button></div></div>';
            document.body.appendChild(dlg);
            var fermer = function () { dlg.close(); };
            dlg.querySelector('.ba-read__x').addEventListener('click', fermer);
            dlg.querySelector('.ba-read__close').addEventListener('click', fermer);
            dlg.addEventListener('click', function (e) { if (e.target === dlg) fermer(); });
            dlg.addEventListener('close', function () {
                document.documentElement.classList.remove('ba-read-open');
                if (dlg._from) dlg._from.focus({ preventScroll: true });
            });
        }
        dlg.querySelector('.ba-read__t').textContent = titre;
        var box = dlg.querySelector('.ba-read__text');
        box.innerHTML = '';
        src.innerHTML.split(/<br\s*\/?>\s*(?:&nbsp;|\s)*<br\s*\/?>|<\/p>/i).forEach(function (part) {
            var tmp = document.createElement('div');
            tmp.innerHTML = part;
            var txt = tmp.textContent.replace(/\s+/g, ' ').trim();
            if (!txt) return;
            var p = document.createElement('p');
            p.textContent = txt;
            box.appendChild(p);
        });
        dlg._from = from;
        document.documentElement.classList.add('ba-read-open');
        if (dlg.showModal) dlg.showModal(); else dlg.setAttribute('open', '');
        dlg.querySelector('.ba-read__body').scrollTop = 0;
        dlg.querySelector('.ba-read__x').focus({ preventScroll: true });
    }
    function init() {
        document.querySelectorAll('main .card.splitCard').forEach(function (card) {
            if (card.closest('.ttx') || card.querySelector('.ttx-actions, .ba-cl-plus')) return;
            var t = card.querySelector('.card-title'), d = card.querySelector('.card-description');
            var texts = card.querySelector('.card-texts') || (t && t.parentNode);
            if (!texts) return;
            var titre = t ? t.textContent.replace(/\s+/g, ' ').trim() : '';
            var longEl = null;
            if (t && titre.length > 140) {
                var leg = card.querySelector('.imageObject-description');
                var court = leg && leg.textContent.trim().length < 90 ? leg.textContent.trim() : ((card.querySelector('img') || {}).alt || '');
                if (court.length > 3) {
                    var h = document.createElement('span');
                    h.className = 'ba-cl-h';
                    h.textContent = court;
                    t.parentNode.insertBefore(h, t);
                    titre = court;
                }
                if (d) d.classList.add('ba-cl-hors-sujet');
                longEl = t;
            } else if (d && d.textContent.replace(/\s+/g, ' ').trim().length > 420) {
                longEl = d;
            }
            if (!longEl) return;
            longEl.classList.add('ba-cl-long');
            card.querySelectorAll('[data-equalizer-watch]').forEach(function (e) { e.style.height = 'auto'; });
            var btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'ba-cl-plus';
            btn.setAttribute('aria-haspopup', 'dialog');
            btn.innerHTML = 'Voir plus <svg width="14" height="14" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"/></svg>';
            longEl.insertAdjacentElement('afterend', btn);
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                ouvrir(titre || document.title, longEl, btn);
            });
        });
    }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init); else init();
})();
</script>

{{-- Textes longs (tout le site) : au-delà de quelques lignes, le texte est coupé et un bouton « Voir plus »
     ouvre le texte complet dans la fenêtre de lecture. Les « Lire le texte complet » qui dépliaient
     le texte sur place ouvrent désormais cette même fenêtre. Les FAQ restent en accordéon.
     Cartes à image décalée (« D'autres envies ? ») : plus de texte posé sur l'image, le chevauchement le coupait. --}}
<style>
html:not(#ba) body:not(#ba) main .ba-lp{overflow:hidden!important;-webkit-mask-image:linear-gradient(180deg,#000 calc(100% - 3.2em),transparent);mask-image:linear-gradient(180deg,#000 calc(100% - 3.2em),transparent)}
html:not(#ba) body:not(#ba) main .ba-lp-details{display:none!important}
html:not(#ba) body:not(#ba) main .ba-lp-plus{display:inline-flex;align-items:center;gap:.5rem;margin:.75rem 0 .25rem;padding:.65rem 1.25rem;border:0;border-radius:999px;background:#00754a;color:#fff!important;font:700 .9rem/1.2 Montserrat,Helvetica,Arial,sans-serif;text-transform:none;letter-spacing:0;cursor:pointer;transition:background .25s,transform .25s}
html:not(#ba) body:not(#ba) main .ba-lp-plus:hover,html:not(#ba) body:not(#ba) main .ba-lp-plus:focus-visible{background:#005c3a;transform:translateY(-2px)}
html:not(#ba) body:not(#ba) main .ba-lp-plus svg{flex:0 0 auto}
html:not(#ba) body:not(#ba) main .overlayedCard.img-offset .card-section,html:not(#ba) body:not(#ba) main .overlayedCard.img-offset .card-titles,html:not(#ba) body:not(#ba) main .overlayedCard.img-offset .card-texts{display:none!important}
html:not(#ba) body:not(#ba) main .overlayedCard.img-offset .cardMediaWrapper::before,html:not(#ba) body:not(#ba) main .overlayedCard.img-offset .cardMediaWrapper::after,html:not(#ba) body:not(#ba) main .overlayedCard.img-offset .card-link::after{background:none!important;opacity:0!important}
html:not(#ba) body:not(#ba) main .bar-manifeste .bar-roles ol{gap:.85rem!important}
html:not(#ba) body:not(#ba) main .bar-manifeste .bar-roles li{flex-direction:column!important;align-items:flex-start!important;gap:.55rem!important;padding:1.05rem 1.15rem 1.1rem!important}
html:not(#ba) body:not(#ba) main .bar-manifeste .bar-roles li span{font-size:1.3rem!important}
html:not(#ba) body:not(#ba) main .bar-manifeste .bar-roles li p{font-size:.95rem!important;line-height:1.55!important;display:-webkit-box!important;-webkit-box-orient:vertical!important;-webkit-line-clamp:3!important;overflow:hidden!important}
html:not(#ba) body:not(#ba) main .bar-manifeste .bar-roles .ba-lp-mini{margin-top:auto;padding:0;border:0;background:none;color:#f0c45a;font:700 .85rem/1.2 Montserrat,Helvetica,Arial,sans-serif;text-decoration:underline;text-underline-offset:.25em;cursor:pointer}
.ba-read__text ul,.ba-read__text ol{margin:0 0 1em 1.2em!important;padding:0!important;color:#2b3530!important;line-height:1.75!important}
.ba-read__text li{margin:0 0 .4em!important}
.ba-read__text h2,.ba-read__text h3,.ba-read__text h4{margin:1.1em 0 .5em!important;font:800 1.1rem/1.3 Montserrat,Helvetica,Arial,sans-serif!important;color:#10261a!important;text-transform:none!important}
.ba-read__text img,.ba-read__text figure,.ba-read__text script,.ba-read__text style,.ba-read__text button{display:none!important}
</style>
<script>
(function () {
    if (window.self !== window.top) return;
    var dlg;
    function texte(el) { return (el.textContent || '').replace(/\s+/g, ' ').trim(); }
    /* Fenêtre de lecture commune : titre + contenu (nœud ou texte), fermeture par croix, bouton, Échap, clic dehors. */
    function lire(titre, contenu, from) {
        if (!dlg) {
            dlg = document.createElement('dialog');
            dlg.className = 'ba-read';
            dlg.setAttribute('aria-labelledby', 'ba-lp-t');
            dlg.innerHTML = '<div class="ba-read__box"><div class="ba-read__head"><span class="ba-read__k">À lire</span><p class="ba-read__t" id="ba-lp-t"></p>'
                + '<button type="button" class="ba-read__x" aria-label="Fermer"><svg width="20" height="20" viewBox="0 0 24 24" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/></svg></button></div>'
                + '<div class="ba-read__body"><div class="ba-read__text"></div></div><div class="ba-read__foot"><button type="button" class="ba-read__close">Fermer</button></div></div>';
            document.body.appendChild(dlg);
            var fermer = function () { dlg.close(); };
            dlg.querySelector('.ba-read__x').addEventListener('click', fermer);
            dlg.querySelector('.ba-read__close').addEventListener('click', fermer);
            dlg.addEventListener('click', function (e) { if (e.target === dlg) fermer(); });
            dlg.addEventListener('close', function () {
                document.documentElement.classList.remove('ba-read-open');
                if (dlg._from) dlg._from.focus({ preventScroll: true });
            });
        }
        dlg.querySelector('.ba-read__t').textContent = titre || (document.querySelector('main h1') ? texte(document.querySelector('main h1')) : document.title);
        var box = dlg.querySelector('.ba-read__text');
        box.innerHTML = '';
        if (typeof contenu === 'string') {
            contenu.split(/\n{2,}/).forEach(function (t) { if (t.trim()) { var p = document.createElement('p'); p.textContent = t.trim(); box.appendChild(p); } });
        } else {
            var c = contenu.cloneNode(true);
            c.querySelectorAll('.ba-lp-plus, summary').forEach(function (x) { x.remove(); });
            var tag = c.tagName;
            if (tag === 'P' || tag === 'LI' || tag === 'SPAN') {
                c.innerHTML.split(/<br\s*\/?>\s*(?:&nbsp;|\s)*<br\s*\/?>/i).forEach(function (part) {
                    var p = document.createElement('p');
                    p.innerHTML = part.trim();
                    if (texte(p)) box.appendChild(p);
                });
            } else {
                while (c.firstChild) box.appendChild(c.firstChild);
            }
            box.querySelectorAll('[class]').forEach(function (x) { x.removeAttribute('class'); });
            box.querySelectorAll('[style]').forEach(function (x) { x.removeAttribute('style'); });
        }
        dlg._from = from;
        document.documentElement.classList.add('ba-read-open');
        if (dlg.showModal) dlg.showModal(); else dlg.setAttribute('open', '');
        dlg.querySelector('.ba-read__body').scrollTop = 0;
        dlg.querySelector('.ba-read__x').focus({ preventScroll: true });
    }
    window.baLire = lire;
    /* Titre de la fenêtre : dernier titre (h2-h4) qui précède le texte dans sa section, sinon le h1. */
    function titreDe(el) {
        var sec = el.closest('section, .page-section, article, .woody-component') || document.querySelector('main');
        var hs = sec ? sec.querySelectorAll('h2, h3, h4, .section-title, .generic-block-title') : [];
        var best = null;
        for (var i = 0; i < hs.length; i++) {
            if (hs[i].compareDocumentPosition(el) & Node.DOCUMENT_POSITION_FOLLOWING && texte(hs[i]).length < 140) best = hs[i];
        }
        return best ? texte(best) : '';
    }
    function bouton(libelle) {
        var b = document.createElement('button');
        b.type = 'button';
        b.className = 'ba-lp-plus';
        b.setAttribute('aria-haspopup', 'dialog');
        b.innerHTML = '<span></span><svg width="14" height="14" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"/></svg>';
        b.firstChild.textContent = libelle;
        return b;
    }
    var EXCLU = 'header, footer, nav, dialog, details, table, form, .card, .ttx, .ba-read, .ba-intro-long, .ba-intro-body, .imageObject-caption, .imageObject-description, .hide, .tooltip-text, [aria-hidden="true"], .ba-dir, .bar-roles, .faq, [class*="faq"]';
    function visible(el) { return !!(el.offsetWidth || el.offsetHeight || el.getClientRects().length); }
    function couper(el) {
        if (el.classList.contains('ba-lp') || el.querySelector('.ba-lp')) return;
        var cs = getComputedStyle(el.tagName === 'P' ? el : (el.querySelector('p') || el));
        var lh = parseFloat(cs.lineHeight);
        if (!lh || isNaN(lh)) lh = parseFloat(cs.fontSize) * 1.6 || 26;
        var lignes = window.innerWidth < 768 ? 8 : 7;
        var max = Math.round(lh * lignes);
        if (el.scrollHeight <= max + lh * 2.5) return;
        /* Liste ou bloc : on coupe un conteneur placé à l'intérieur, pour que le bouton reste
           dans la colonne du texte (et que la puce d'un élément de liste reste visible). */
        var cible = el;
        if (el.tagName !== 'P') {
            /* descend jusqu'à la vraie colonne de texte (enveloppes à un seul enfant) */
            var hote = el;
            while (hote.children.length === 1 && !/\S/.test(Array.prototype.map.call(hote.childNodes, function (n) { return n.nodeType === 3 ? n.textContent : ''; }).join('')) && hote.firstElementChild.tagName !== 'P') {
                hote = hote.firstElementChild;
            }
            cible = document.createElement('div');
            while (hote.firstChild) cible.appendChild(hote.firstChild);
            hote.appendChild(cible);
        }
        cible.classList.add('ba-lp');
        cible.style.maxHeight = max + 'px';
        var b = bouton('Voir plus');
        cible.insertAdjacentElement('afterend', b);
        b.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            lire(titreDe(el), cible, b);
        });
    }
    function init() {
        var main = document.querySelector('main');
        if (!main) return;
        /* 1. « Lire le texte complet » / « Voir plus » qui dépliaient sur place → fenêtre de lecture */
        main.querySelectorAll('details').forEach(function (d) {
            var s = d.querySelector('summary');
            if (!s || d.closest('.ba-read')) return;
            var lib = texte(s);
            if (!/(lire|voir|afficher|tout)\b.*\b(plus|suite|complet|texte|tout)|en savoir plus|lire la suite/i.test(lib)) return;
            var b = bouton(lib.replace(/[+−–-]\s*$/, '').trim() || 'Voir plus');
            d.insertAdjacentElement('beforebegin', b);
            d.classList.add('ba-lp-details');
            b.addEventListener('click', function (e) {
                e.preventDefault();
                lire(titreDe(d), d, b);
            });
        });
        /* 2. Textes longs : blocs de texte éditoriaux, puis paragraphes isolés */
        var cands = [];
        main.querySelectorAll('.woody-component-text').forEach(function (c) {
            if (c.closest(EXCLU) || !visible(c)) return;
            if (texte(c).length > 1000) cands.push(c);
        });
        main.querySelectorAll('p, li').forEach(function (e) {
            if (e.closest(EXCLU) || !visible(e)) return;
            for (var i = 0; i < cands.length; i++) if (cands[i].contains(e)) return;
            if (e.querySelector('p, ul, ol, div, table, figure')) return;
            if (texte(e).length > 700) cands.push(e);
        });
        cands.forEach(couper);
        /* 4. Cartes « Au-delà de leur dimension symbolique… » (01 à 05) : 3 lignes, le reste dans la fenêtre */
        main.querySelectorAll('.bar-roles').forEach(function (bloc) {
            var h = bloc.querySelector('h3, h2');
            bloc.querySelectorAll('li').forEach(function (li) {
                var p = li.querySelector('p');
                if (!p || li.querySelector('.ba-lp-mini') || p.scrollHeight <= p.clientHeight + 2) return;
                var b = document.createElement('button');
                b.type = 'button';
                b.className = 'ba-lp-mini';
                b.setAttribute('aria-haspopup', 'dialog');
                b.textContent = 'Voir plus';
                li.appendChild(b);
                b.addEventListener('click', function (e) {
                    e.preventDefault();
                    lire(h ? texte(h) : '', p, b);
                });
            });
        });
        /* 3. Cartes à image décalée : le titre n'est plus affiché sur l'image, il reste lisible par les lecteurs d'écran */
        main.querySelectorAll('.overlayedCard.img-offset').forEach(function (card) {
            var a = card.querySelector('a.card-link');
            var t = card.querySelector('.card-title, .card-titles');
            if (a && t && !a.getAttribute('aria-label')) a.setAttribute('aria-label', texte(t));
            if (a && t && !a.getAttribute('title')) a.setAttribute('title', texte(t));
        });
    }
    function demarrer() { requestAnimationFrame(function () { setTimeout(init, 60); }); }
    if (document.readyState === 'complete') demarrer(); else window.addEventListener('load', demarrer);
})();
</script>

{{-- Libellés des boutons et fenêtres ajoutés par les scripts ci-dessus (« Voir plus », « Lire la suite »,
     « Fermer »…) : traduits selon la langue de la page (en, es, pt). Le français reste la source. --}}
<script data-i18n-skip>
(function () {
    var L = (document.documentElement.getAttribute('lang') || 'fr').slice(0, 2);
    var D = {
        en: {'Lire la suite': 'Read more', 'Réduire': 'Show less', 'Voir plus': 'Read more', 'À lire': 'Read', 'Fermer': 'Close',
             'Lire le texte complet': 'Read the full text', 'Découvrir la rubrique': 'Explore the section', 'Art de vivre': 'Way of life',
             'Terre de traditions': 'Land of traditions', 'Lire aussi': 'Explore the section'},
        es: {'Lire la suite': 'Leer más', 'Réduire': 'Ver menos', 'Voir plus': 'Ver más', 'À lire': 'Para leer', 'Fermer': 'Cerrar',
             'Lire le texte complet': 'Leer el texto completo', 'Découvrir la rubrique': 'Descubrir la sección', 'Art de vivre': 'Arte de vivir',
             'Terre de traditions': 'Tierra de tradiciones', 'Lire aussi': 'Descubrir la sección'},
        pt: {'Lire la suite': 'Ler mais', 'Réduire': 'Ver menos', 'Voir plus': 'Ver mais', 'À lire': 'Para ler', 'Fermer': 'Fechar',
             'Lire le texte complet': 'Ler o texto completo', 'Découvrir la rubrique': 'Descobrir a seção', 'Art de vivre': 'Arte de viver',
             'Terre de traditions': 'Terra de tradições', 'Lire aussi': 'Descobrir a seção'}
    }[L];
    if (!D) return;
    var SEL = '.ba-intro-more, .ba-cl-plus, .ba-lp-plus, .ba-lp-mini, .ttx-plus, .ttx-lien, .ttx-num, .ba-read__k, .ba-read__close, .ba-read__x';
    function tr(s) {
        var t = s.trim();
        if (!t) return null;
        if (D[t]) return s.replace(t, D[t]);
        if (/^Lire aussi\s*:/.test(t)) return s.replace(t, D['Lire aussi']);
        return null;
    }
    function fix(root) {
        try {
            var els = [];
            if (root.matches && root.matches(SEL)) els.push(root);
            if (root.querySelectorAll) els = els.concat([].slice.call(root.querySelectorAll(SEL)));
            els.forEach(function (el) {
                var w = document.createTreeWalker(el, NodeFilter.SHOW_TEXT, null);
                var n;
                while ((n = w.nextNode())) {
                    var r = tr(n.nodeValue);
                    if (r !== null) n.nodeValue = r;
                }
                var a = el.getAttribute('aria-label');
                if (a && D[a]) el.setAttribute('aria-label', D[a]);
            });
        } catch (e) {}
    }
    function run() {
        fix(document.body);
        try {
            new MutationObserver(function (ms) {
                ms.forEach(function (m) {
                    m.addedNodes && m.addedNodes.forEach(function (n) { if (n.nodeType === 1) fix(n); });
                    if (m.type === 'characterData' && m.target.parentElement && m.target.parentElement.closest(SEL)) fix(m.target.parentElement.closest(SEL));
                });
            }).observe(document.body, { childList: true, subtree: true, characterData: true });
        } catch (e) {}
    }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', run); else run();
})();
</script>

{{--
    Titres dans les langues traduites (en, es, pt) : les traductions sont souvent 15 à 30 %
    plus longues que le français. Méthode des grands sites : on ne rapetisse pas tout, on
    laisse le texte passer à la ligne proprement (lignes équilibrées, pas de mot qui
    déborde), et seuls les grands titres trop longs sont légèrement réduits,
    juste ce qu'il faut (au plus -25 %) : 2 lignes au plus sur ordinateur, 3 sur mobile. Le français n'est pas concerné.
--}}
<style>
html:not([lang^="fr"]) :is(main h1, main h2, main h3, main h4, .landswpr-title, .section-title, .card-title, .bar h2, .bar h3) {
    text-wrap: balance;
    overflow-wrap: break-word;
}
html:not([lang^="fr"]) main :is(p, li, figcaption) { text-wrap: pretty; }
@media (max-width: 767.98px) {
    html:is([lang^="es"], [lang^="pt"]) main :is(p, li) { hyphens: auto; -webkit-hyphens: auto; }
}
/* Diaporama de l'accueil : le titre ne passe plus sous les flèches. */
html:not([lang^="fr"]) .landswpr-texts { padding-left: clamp(12px, 6vw, 110px); padding-right: clamp(12px, 6vw, 110px); box-sizing: border-box; }
</style>
<script data-i18n-skip>
(function () {
    var html = document.documentElement;
    if (/^fr/i.test(html.lang || 'fr')) return;
    var SEL = '.landswpr-title, main h1, main .section-title, [class*="hero"] h1, [class*="hero"] h2, .wl-d, .bar-intro h2';
    var MIN = 0.75;
    function lignes(e) {
        var cs = getComputedStyle(e), lh = parseFloat(cs.lineHeight);
        if (!lh) lh = parseFloat(cs.fontSize) * 1.15;
        return Math.round(e.getBoundingClientRect().height / lh);
    }
    function deborde(e) { return e.scrollWidth > e.clientWidth + 2; }
    function ajuster(e) {
        if (!e.offsetWidth || e.closest('.show-for-sr')) return;
        e.style.fontSize = '';
        var base = parseFloat(getComputedStyle(e).fontSize);
        if (base < 22) return; // seuls les grands titres
        var max = window.innerWidth < 768 ? 3 : 2, f = 1;
        while ((lignes(e) > max || deborde(e)) && f > MIN) {
            f = Math.round((f - 0.05) * 100) / 100;
            e.style.fontSize = (base * f) + 'px';
        }
    }
    function tout() { document.querySelectorAll(SEL).forEach(ajuster); }
    var t;
    function plusTard() { clearTimeout(t); t = setTimeout(tout, 150); }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', tout); else tout();
    window.addEventListener('load', tout);
    window.addEventListener('resize', plusTard, { passive: true });
    if (document.fonts && document.fonts.ready) document.fonts.ready.then(tout);
})();
</script>
