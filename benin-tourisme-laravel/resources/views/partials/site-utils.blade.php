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

{{-- Barre de navigation mobile : masquer au scroll vers le bas,
     revenir au scroll vers le haut (meme comportement que le header
     desktop natif Woody). Woody ne pose pas ce comportement sur
     .woody-component-mobilenavs par defaut : on ajoute une classe
     .ba-nav-hidden qui glisse la barre vers le haut, et on la retire
     au scroll vers le haut, en haut de page ou menu mobile ouvert. --}}
<script>
(function () {
    var mobileNav  = document.querySelector('.woody-component-mobilenavs');
    var desktopNav = document.querySelector('.woody-component-headnavs');
    if (!mobileNav && !desktopNav) return;

    var lastY     = window.pageYOffset || document.documentElement.scrollTop;
    var ticking   = false;
    var threshold = 10;          // marge pour eviter les flap autour de 0
    var topZone   = 24;          // on reste visible dans les 24 premiers px

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
