/* --------------------------------------------------------------------------
   Bénin tourisme — carrousels : boucle continue et défilement automatique.

   Fichier propre au projet ; il ne vient pas du miroir. Chargé après les scripts
   du thème, il corrige deux choses que le thème ne permet pas de configurer :

   1. Le carrousel d'accroche de l'accueil s'arrête sur sa dernière diapositive.
      Son `loop: false` est écrit en dur dans `main.mjs`, pour le gabarit tpl_03 :
      aucun attribut ne le change. Il faut donc le réinitialiser après coup.

   2. Aucun carrousel ne défile seul. Les carrousels de cartes, eux, bouclent
      déjà correctement : il leur manque uniquement le défilement automatique,
      qui s'ajoute sans les reconstruire.

   Une préférence système pour un mouvement réduit désactive le défilement
   automatique — la boucle, elle, reste : elle ne bouge que sur action.
   -------------------------------------------------------------------------- */

(() => {
    'use strict';

    const DÉFILEMENT = {
        delay: 5000,
        disableOnInteraction: false,
        pauseOnMouseEnter: true,
    };

    const ACCROCHE = '.woody-component-landswpr .woody-landing-swiper';

    // Le thème initialise ses carrousels de façon asynchrone : on attend qu'ils
    // existent, sans attendre indéfiniment si la page n'en contient aucun.
    const ATTENTE_MAX = 10000;
    const PAS = 200;

    const mouvementRéduit = () =>
        window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    /**
     * Rend le carrousel d'accroche continu.
     *
     * Swiper n'accepte pas qu'on lui pose `loop` après coup : il faut le recréer.
     * On reprend les paramètres d'origine — pagination, parallaxe, vitesse — en
     * écartant `el` et `init`, qui désignent l'instance qu'on remplace et
     * feraient échouer la nouvelle.
     */
    function rendreContinu(element) {
        const ancien = element.swiper;
        if (!ancien) return false;

        const { el, init, ...paramètres } = ancien.passedParams;
        const pagination = paginationTitrée(element, paramètres.pagination);
        // Sur mobile le thème crée déjà une boucle, mais sans vignettes.
        if (ancien.params.loop && !pagination) return false;
        ancien.destroy(true, true);

        new window.Swiper(element, {
            ...paramètres,
            ...(pagination ? { pagination } : {}),
            loop: true,
            ...(mouvementRéduit() ? {} : { autoplay: DÉFILEMENT }),
        });
        return true;
    }

    /**
     * Vignettes de défilement (titre + trait de progression) à toutes les largeurs.
     *
     * Sous 64em le thème remplace les vignettes par de simples points, qu'il
     * masque ensuite : le mobile n'avait plus aucun repère. Sur desktop le thème
     * produit déjà les vignettes ; on garde alors sa version telle quelle.
     */
    function paginationTitrée(element, pagination) {
        if (!pagination || pagination.bulletClass === 'swiper-pagination-item') return null;

        const échapper = (texte) => texte.replace(/[&<>"]/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' })[c]);
        const titres = [...element.querySelectorAll('.swiper-slide:not(.swiper-slide-duplicate)')].map((diapo) => {
            const repère = diapo.querySelector('.landswpr-titles-wrapper');
            const titre = (repère && repère.dataset.paginationTitle) || diapo.querySelector('.landswpr-title')?.textContent || '';
            return échapper(titre.trim());
        });

        return {
            ...pagination,
            bulletClass: 'swiper-pagination-item',
            bulletActiveClass: 'active',
            renderBullet: (index, classe) =>
                `<span class="${classe}"><span class="pagination-wicon wicon"></span>`
                + '<span class="pagination-pretitle"></span>'
                + `<span class="pagination-title">${titres[index] || ''}</span>`
                + '<span class="pagination-separator"><span class="pagination-separator-loader"></span></span></span>',
        };
    }

    /**
     * Boucle sans fin visible pour les carrousels centrés à peu de cartes
     * (« Explorer les 5 régions »).
     *
     * Le thème demande à Swiper dix cartes d'avance de chaque côté
     * (loopAdditionalSlides: 10) alors qu'il n'y en a que cinq : Swiper les
     * range toutes à gauche, le côté droit reste blanc et l'on voit où la boucle
     * finit. On double les cartes (copies masquées aux lecteurs d'écran et à la
     * tabulation) et on ramène l'avance à deux cartes : les deux côtés restent
     * remplis à chaque pas, dans les deux sens.
     */
    const CARTES_MIN = 8;

    // Sur mobile le thème crée ce carrousel sans boucle : on la force.
    const TOUJOURS_EN_BOUCLE = '.woody-component-focus.tpl_131';

    function boucleSansFin(element) {
        const ancien = element.swiper;
        if (!ancien || element.dataset.baBoucle === '1') return false;

        const forcer = Boolean(element.closest(TOUJOURS_EN_BOUCLE));
        if (!forcer && (!ancien.params.loop || !ancien.params.centeredSlides)) return false;

        const wrapper = element.querySelector('.swiper-wrapper');
        const cartes = () => [...wrapper.children].filter((c) => c.classList.contains('swiper-slide'));
        const nombre = cartes().length;
        if (nombre === 0) return false;
        if (nombre >= CARTES_MIN && ancien.params.loop) return false;

        const { el, init, ...paramètres } = ancien.passedParams;
        ancien.destroy(true, true);

        // La boucle de Swiper déplace les cartes dans le DOM ; on reprend la
        // liste après destruction, triée par numéro, pour copier A B C D E
        // dans l'ordre (sinon : … D E + E A B …, deux fois la même carte).
        const numéro = (c) => Number(c.getAttribute('data-swiper-slide-index') ?? 0);
        const originales = cartes().sort((a, b) => numéro(a) - numéro(b));
        originales.forEach((carte) => wrapper.appendChild(carte));

        const tours = Math.max(0, Math.ceil(CARTES_MIN / originales.length) - 1);
        for (let t = 0; t < tours; t += 1) {
            originales.forEach((carte) => {
                const copie = carte.cloneNode(true);
                copie.setAttribute('aria-hidden', 'true');
                copie.querySelectorAll('a, button, [tabindex]').forEach((x) => x.setAttribute('tabindex', '-1'));
                wrapper.appendChild(copie);
            });
        }

        // Swiper range les cartes d'après data-swiper-slide-index : une copie
        // qui garde le numéro de son original se placerait juste à côté de lui
        // (« Le littoral » deux fois de suite). On efface ces numéros et les
        // états hérités ; Swiper renumérote 0 à n-1 dans l'ordre A B C… A B C…
        wrapper.querySelectorAll('.swiper-slide').forEach((carte) => {
            carte.removeAttribute('data-swiper-slide-index');
            carte.removeAttribute('style');
            carte.classList.remove(
                'swiper-slide-active', 'swiper-slide-next', 'swiper-slide-prev',
                'swiper-slide-visible', 'swiper-slide-fully-visible', 'swiper-slide-duplicate',
            );
        });

        element.dataset.baBoucle = '1';
        new window.Swiper(element, { ...paramètres, loop: true, loopAdditionalSlides: 2 });
        return true;
    }

    /**
     * Fait défiler un carrousel qui boucle déjà.
     */
    function faireDéfiler(swiper) {
        if (!swiper || !swiper.autoplay || swiper.autoplay.running) return false;

        swiper.params.autoplay = { ...DÉFILEMENT, enabled: true };
        swiper.autoplay.start();
        return true;
    }

    function appliquer() {
        const accroche = document.querySelector(ACCROCHE);
        if (accroche) rendreContinu(accroche);

        document.querySelectorAll('.woody-component-focus .swiper').forEach(boucleSansFin);

        // Le traitement dédié au carrousel des régions (rendreRégionsContinu)
        // a été retiré le 3/11 ; son appel restait ici et levait une
        // ReferenceError qui empêchait le défilement automatique ci-dessous.

        if (mouvementRéduit()) return;

        document.querySelectorAll('.swiper').forEach((element) => {
            if (element.closest('.woody-component-landswpr')) return;
            faireDéfiler(element.swiper);
        });
    }

    /**
     * Attend que le thème ait initialisé ses carrousels, puis applique les réglages.
     */
    function quandLesCarrouselsSontPrêts() {
        const début = Date.now();
        const sonde = setInterval(() => {
            const carrousels = [...document.querySelectorAll('.swiper')];
            const prêts = carrousels.length > 0 && carrousels.every((el) => el.swiper);

            if (prêts || Date.now() - début > ATTENTE_MAX) {
                clearInterval(sonde);
                if (prêts) appliquer();
            }
        }, PAS);
    }

    if (document.readyState === 'complete') {
        quandLesCarrouselsSontPrêts();
    } else {
        window.addEventListener('load', quandLesCarrouselsSontPrêts);
    }
})();
