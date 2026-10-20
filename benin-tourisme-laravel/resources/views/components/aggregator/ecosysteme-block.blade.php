{{-- Bloc « Notre écosystème » — design editorial moderne, inspire des sections
     partenaires premium (Airbnb, Klook, Wander) : grille de 3 cards avec media
     en pleine hauteur, overlay degrade, badge marque, contenu et CTA anime. --}}
@php
    $ecoCards = [
        [
            'href'    => 'https://dahomeydiscovery.com',
            'brand'   => '#00844d',
            'image'   => '/assets/img/ecosysteme/dahomey-discovery.webp',
            'imageAlt'=> 'Dahomey Discovery — accueil',
            'badge'   => 'Agence réceptive',
            'eyebrow' => 'Depuis 2018',
            'title'   => 'Dahomey Discovery',
            'text'    => 'Circuits sur mesure, hébergements, transferts, immersions culturelles. L\'agence fondée par Zoulfati Hassan Derby édite ce portail Bonne Arrivée.',
            'cta'     => 'dahomeydiscovery.com',
        ],
        [
            'href'    => 'https://www.petit-train-cotonou.com',
            'brand'   => '#e8112d',
            'image'   => '/assets/img/petit-train-cotonou.webp',
            'imageAlt'=> 'Le Petit Train de Cotonou',
            'badge'   => 'Loisirs urbains',
            'eyebrow' => 'Trois circuits',
            'title'   => 'Le Petit Train de Cotonou',
            'text'    => 'Trois parcours commentés (15 min, 30 min, 1 h) au cœur de la capitale. Départs depuis le Rond-point Akpakpa, en famille, en groupe ou en privatisation.',
            'cta'     => 'petit-train-cotonou.com',
        ],
        [
            'href'    => 'https://www.wanilo.studio',
            'brand'   => '#fcd116',
            'image'   => '/assets/img/ecosysteme/wanilo-studio.webp',
            'imageAlt'=> 'Wanilo Studio — accueil',
            'badge'   => 'Studio créatif',
            'eyebrow' => 'Cotonou · Bénin',
            'title'   => 'Wanilo Studio',
            'text'    => 'Production vidéo, podcast, photo, décors et équipe technique locale. Le studio béninois qui signe l\'image de nos séjours et de ce portail.',
            'cta'     => 'wanilo.studio',
        ],
    ];
@endphp

<section class="ba-ecosystem" aria-labelledby="ecosysteme-titre">
    <div class="ba-ecosystem__container">
        <header class="ba-ecosystem__head">
            <p class="ba-ecosystem__eyebrow">Notre écosystème</p>
            <h2 id="ecosysteme-titre" class="ba-ecosystem__title">Un portail, trois marques</h2>
            <p class="ba-ecosystem__lead">Bonne Arrivée n'est pas un site isolé — c'est la vitrine d'une famille de marques béninoises qui font vivre le tourisme au Bénin au quotidien.</p>
        </header>

        <div class="ba-ecosystem__grid">
            @foreach($ecoCards as $i => $c)
                <a class="ba-eco-card"
                   href="{{ $c['href'] }}"
                   target="_blank"
                   rel="noopener noreferrer"
                   style="--brand: {{ $c['brand'] }}; --i: {{ $i }}"
                   aria-label="{{ $c['title'] }} — {{ $c['badge'] }}">
                    <div class="ba-eco-card__media">
                        <img src="{{ $c['image'] }}"
                             alt="{{ $c['imageAlt'] }}"
                             loading="lazy"
                             decoding="async"
                             referrerpolicy="no-referrer">
                        <span class="ba-eco-card__badge">{{ $c['badge'] }}</span>
                        <span class="ba-eco-card__scrim" aria-hidden="true"></span>
                    </div>
                    <div class="ba-eco-card__body">
                        <span class="ba-eco-card__eyebrow">{{ $c['eyebrow'] }}</span>
                        <h3 class="ba-eco-card__title">{{ $c['title'] }}</h3>
                        <p class="ba-eco-card__text">{{ $c['text'] }}</p>
                        <span class="ba-eco-card__cta">
                            <span class="ba-eco-card__cta-label">{{ $c['cta'] }}</span>
                            <span class="ba-eco-card__cta-arrow" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                            </span>
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
