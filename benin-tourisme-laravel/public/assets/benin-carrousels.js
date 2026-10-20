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
