{{--
    Pied de page du site.

    - Le BALISAGE est ici : toute modification de ce fichier change le rendu.
    - Le CONTENU (accroche, réseaux sociaux, coordonnées, boutons) vient de
      \App\Support\SiteLayout::footer() : la version enregistrée dans le
      back-office (page « En-tête et pied de page ») si elle existe, sinon les
      valeurs par défaut de config/site_layout.php.
--}}
@php($__footer = \App\Support\SiteLayout::footer())
<footer role="contentinfo" class="site-footer">
        <div class="woody_footer grid-x">
    <div class="brand-block align-middle align-center grid-y small-12 medium-6 large-3 padd-all-md">
        <div class="brand-wrapper flex-container align-center">
            <a class="brand-logo isRel" href="/" aria-label="Bonne Arrivée — retour à l'accueil">
                <img src="/assets/site/divers/app/themes/benin/src/img/logo/logo.png" alt="Bonne Arrivée — Portail du tourisme au Bénin">
            </a>
        </div>
@if (! empty($__footer['tagline']))
        <p class="brand-tagline">{{ $__footer['tagline'] }}</p>
@endif

@if (! empty($__footer['socials']))
        <div class="social-networks">
                            <ul class="list-unstyled flex-container align-middle align-center">
@foreach ($__footer['socials'] as $__social)
                                            <li>
                            <a class="no-ext" href="{{ $__social['url'] }}" target="_blank" aria-label="{{ $__social['label'] ?? '' }}" rel="noopener noreferrer">
                                <span class="wicon {{ $__social['icon'] ?? '' }}" aria-hidden="true"></span>
                            </a>
                        </li>
@endforeach
                                    </ul>
                    </div>
@endif
    </div>

    <div class="infos-block bg-primary small-12 medium-6 large-3 padd-all-lg flex-container flex-dir-column align-center text-center">
@if (! empty($__footer['company']))
        <p><strong>{{ $__footer['company'] }}</strong></p>
@endif
@if (! empty($__footer['address']))
        <p>{!! nl2br(e(trim($__footer['address'])), false) !!}</p>
@endif
@if (! empty($__footer['phone']) || ! empty($__footer['email']))
        <p>@if (! empty($__footer['phone']))Tél. <a href="tel:{{ \App\Support\SiteLayout::telHref($__footer['phone']) }}" style="color:inherit;text-decoration:underline">{{ $__footer['phone'] }}</a>@endif @if (! empty($__footer['phone']) && ! empty($__footer['email']))<br>
@endif
@if (! empty($__footer['email']))           <a href="mailto:{{ $__footer['email'] }}" style="color:inherit;text-decoration:underline">{{ $__footer['email'] }}</a>@endif</p>
@endif
    </div>

    <div class="buttons-block bg-primary small-12 large-3 padd-all-lg flex-container flex-dir-column align-center align-middle">
@foreach ($__footer['buttons'] ?? [] as $__button)
                                    <a class="button primary" href="{{ $__button['url'] }}" title="{{ $__button['title'] ?? $__button['label'] }}" target="_self">
                    {{ $__button['label'] }}
                </a>
@endforeach
                        </div>

    <div class="map-block bg-primary small-12 large-3 padd-all-lg flex-container flex-dir-column align-center">
        <img src="/assets/img/world-benin.svg" alt="Localisation du Bénin sur la carte du monde" class="world-map-footer"/>
    </div>
</div>
    </footer>
