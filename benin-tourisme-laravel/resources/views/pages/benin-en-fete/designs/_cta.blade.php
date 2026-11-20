{{-- Appel final : Bonne Arrivée informe ; le séjour se réserve auprès des partenaires de l'écosystème. --}}
<div class="bf-cta bf-rv">
    <img src="{{ $ctaImage ?? $event['hero'] }}" alt="" loading="lazy" decoding="async">
    <span class="bf-k">Préparer votre venue</span>
    <h2>Vivre {{ $event['title'] }} avec les partenaires de l’écosystème</h2>
    <p>Bonne Arrivée vous informe ; les séjours se réservent directement auprès des professionnels de l’écosystème. L’agence réceptive Dahomey Discovery organise chaque année des circuits pendant l’événement — accueil aéroport, hébergement, transport, guide francophone.</p>
    <div class="bf-cta__btns">
        <a class="bf-btn bf-btn--main" href="https://dahomeydiscovery.com" target="_blank" rel="noopener noreferrer">Réserver avec Dahomey Discovery <span aria-hidden="true">↗</span></a>
        {{-- Lien vers le site officiel de l'événement retiré à la demande (l'adresse reste dans benin-events.php). --}}
        <a class="bf-btn bf-btn--ghost" href="/sejourner/toutes-les-infos-pratiques/les-liens-utiles">Toutes les agences agréées</a>
        <a class="bf-btn bf-btn--ghost" href="/benin-en-fete">Voir tous les événements</a>
    </div>
    <p class="bf-partner">Bonne Arrivée est un site d’information et ne vend aucune prestation.</p>
</div>
