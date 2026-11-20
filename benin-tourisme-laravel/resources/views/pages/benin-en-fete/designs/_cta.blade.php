{{-- Appel final : Bonne Arrivée compose le séjour, avec son agence partenaire. --}}
<div class="bf-cta bf-rv">
    <img src="{{ $ctaImage ?? $event['hero'] }}" alt="" loading="lazy" decoding="async">
    <span class="bf-k">Composer votre séjour</span>
    <h2>Vivre {{ $event['title'] }} avec Bonne Arrivée</h2>
    <p>Notre équipe organise chaque année des circuits sur mesure pendant l'événement — accueil aéroport, hébergement, transport, guide francophone, accès aux moments-clés du programme.</p>
    <div class="bf-cta__btns">
        <a class="bf-btn bf-btn--main" href="/sejourner/toutes-les-infos-pratiques/nous-contacter">Nous confier votre séjour <span aria-hidden="true">→</span></a>
        @if (! empty($event['siteOfficiel']))
            <a class="bf-btn bf-btn--ghost" href="{{ $event['siteOfficiel'] }}" target="_blank" rel="noopener noreferrer">Site officiel <span aria-hidden="true">↗</span></a>
        @endif
        <a class="bf-btn bf-btn--ghost" href="/benin-en-fete">Voir tous les événements</a>
    </div>
    <p class="bf-partner">Séjours réalisés avec notre agence partenaire Dahomey Discovery.</p>
</div>
