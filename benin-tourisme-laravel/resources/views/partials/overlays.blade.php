{{-- Overlay recherche — reprend la structure visuelle du composant Woody
     d'origine (plein ecran, input gros caractere, grille de resultats),
     mais fonctionne sur l'endpoint Laravel /recherche/suggest.
     Les filtres par type de page et l'infinite scroll Woody ont ete retires. --}}
@verbatim<div class="search-results-wrapper overlayed-results" role="dialog" aria-modal="false" aria-live="polite" aria-labelledby="Recherche" aria-describedby="Recherchez un contenu sur le site" data-ba-search-overlay hidden>
    <button type="button" class="search-results-close flex-container align-middle marg-top-sm marg-right-sm" aria-label="Fermer la recherche" data-ba-search-close>
        <span class="wicon wicon-029-croix-fermer"></span>
        <span class="show-for-medium">Fermer</span>
    </button>

    <div class="search-input-wrapper flex-container align-stretch grid-gap-sm wrap isRel" role="search">
        <form class="input-group isRel" method="get" action="/recherche" data-ba-search-form>
            <input
                class="input-group-field search-input results-input"
                type="search"
                name="q"
                title="Que recherchez-vous ?"
                placeholder="Que recherchez-vous ?"
                autocomplete="off"
                data-ba-search-input>
            <span class="input-group-label close-toggler" data-ba-search-clear tabindex="0" role="button" aria-label="Effacer">
                <span class="wicon wicon-029-croix-fermer"></span>
            </span>
            <button type="submit" class="input-group-label results-toggler submit" title="Lancer la recherche" aria-label="Lancer la recherche">
                <span class="wicon wicon-024-loupe search-icon" aria-hidden="true"></span>
            </button>
        </form>
    </div>

    <div class="search-outcome-wrapper">
        <div class="flex-container align-center">
            <div class="search-results-container grid-x grid-padding-x grid-padding-y medium-up-2 large-up-3 xlarge-up-4" data-ba-search-results></div>
        </div>
        <div class="search-no-result" aria-live="polite" aria-atomic="true" role="alert" data-ba-search-empty hidden>Aucun résultat</div>
        <div class="search-hint" data-ba-search-hint>Tapez au moins 2 caractères pour lancer une recherche.</div>
    </div>
</div>

    <div aria-live="polite" aria-labelledby="Sélecteur de langue" aria-describedby="Choisissez une langue pour naviguer sur le site" class="reveal full lang_switcher-reveal" data-reveal data-animation-in="fade-in" data-animation-out="fade-out" role="dialog" tabindex="0" aria-expanded="false">
    <div class="flex-container align-center align-middle">
        <ul class="lang_switcher-langs list-unstyled">
                                </ul>
        <button class="close-button" data-close aria-label="Fermer" type="button">
            <small>Fermer</small>
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
</div>


        <script type="speculationrules">
{"prefetch":[{"source":"document","where":{"and":[{"href_matches":"\/*"},{"not":{"href_matches":["\/wp\/wp-*.php","\/wp\/wp-admin\/*","\/app\/uploads\/benin\/*","\/app\/*","\/app\/plugins\/*","\/app\/themes\/benin\/*","\/app\/themes\/benin\/*","\/*\\?(.+)"]}},{"not":{"selector_matches":"a[rel~=\"nofollow\"]"}},{"not":{"selector_matches":".no-prefetch, .no-prefetch a"}}]},"eagerness":"conservative"}]}
</script>@endverbatim
