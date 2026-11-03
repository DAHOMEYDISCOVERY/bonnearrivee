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
    const RÉGIONS = '.woody-component-focus.tpl_131 .swiper-container';
    const FACTEUR_DE_DUPLICATION = 3; // 5 slides -> 15 slides après duplication

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
        if (!ancien || ancien.params.loop) return false;

        const { el, init, ...paramètres } = ancien.passedParams;
        ancien.destroy(true, true);

        new window.Swiper(element, {
            ...paramètres,
            loop: true,
            ...(mouvementRéduit() ? {} : { autoplay: DÉFILEMENT }),
        });
        return true;
    }

    /**
     * Rend le carrousel des 5 régions vraiment continu dans les deux sens.
     *
     * Swiper 11 avec slidesPerView:'auto' + loop:true ne clone PAS les slides
     * dans le DOM (changement v10 -> v11). Résultat : 5 slides, le wrapper
     * finit avant le bord droit du swiper-container -> blanc visible dans
     * le padding droit au repos, et saut à la jointure fin -> début.
     *
     * Solution : on duplique 2 fois les slides originales dans le DOM
     * (15 au total) puis on recrée l'instance Swiper. Les clones sont
     * invisibles côté CMS ; seul le JS les voit.
     */
    function rendreRégionsContinu(element) {
        const ancien = element.swiper;
        if (!ancien) return false;
        const wrapper = element.querySelector('.swiper-wrapper');
        if (!wrapper) return false;

        // On ne duplique qu'une seule fois par chargement : si le wrapper
        // a déjà été étendu, on sort.
        if (wrapper.dataset.baDupliqué === '1') return false;

        const originales = [...wrapper.querySelectorAll(':scope > .swiper-slide:not(.swiper-slide-duplicate)')];
        if (originales.length < 2) return false;

        const { el, init, ...paramètres } = ancien.passedParams;
        const indexInitial = ancien.realIndex || 0;
        ancien.destroy(true, true);

        // On ajoute (FACTEUR - 1) jeux complets de clones après les originales.
        // Pour FACTEUR=3 : 5 originales + 10 clones = 15 slides.
        for (let tour = 1; tour < FACTEUR_DE_DUPLICATION; tour++) {
            originales.forEach((slide) => {
                const clone = slide.cloneNode(true);
                clone.setAttribute('aria-hidden', 'true');
                clone.classList.add('ba-slide-clone');
                wrapper.appendChild(clone);
            });
        }
        wrapper.dataset.baDupliqué = '1';

        new window.Swiper(element, {
            ...paramètres,
            loop: true,
            initialSlide: indexInitial,
        });
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

        const régions = document.querySelector(RÉGIONS);
        if (régions) rendreRégionsContinu(régions);

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
