{{-- Overlays du site. Le composant `<woody-search-engine>` d'origine
     dependait d'une API Woody externe (ES) jamais branchee ici ; il est
     remplace par la page /recherche (voir SearchController + partials/headnavs
     ou le bouton loupe pointe maintenant). On garde ici le fallback de
     recherche minimal au cas ou le wrapper serait encore ouvert par du JS. --}}
@verbatim<div class="ba-search-fallback" role="dialog" aria-modal="false" aria-labelledby="Recherche" style="display:none">
    <form method="get" action="/recherche" role="search">
        <input type="search" name="q" placeholder="Que recherchez-vous ?" minlength="2" required>
        <button type="submit"><span class="wicon wicon-024-loupe" aria-hidden="true"></span></button>
    </form>
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