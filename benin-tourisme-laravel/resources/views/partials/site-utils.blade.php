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
