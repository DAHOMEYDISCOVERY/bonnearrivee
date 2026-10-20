{{--
    Grille d'hébergements — écosystème Dahomey Discovery.

    Chaque tuile pointe vers la fiche hôtel sur dahomeydiscovery.com
    (nouvelle onglet). Un badge marque l'origine, un CTA explicite en pied
    de card affiche « Réserver sur dahomeydiscovery.com ». Un bouton en fin
    de section renvoie vers la vitrine complète.

    Usage :
        <x-aggregator.accommodations-block
            title="Notre sélection d'hôtels au Bénin"
            pretitle="Réserver avec Dahomey Discovery"
            :filters="['accommodation_type' => 'hotel']"
            :limit="6"
            ctaHref="https://dahomeydiscovery.com/hebergements/" />
--}}
@props([
    'title'    => null,
    'pretitle' => null,
    'filters'  => [],
    'limit'    => 6,
    'ctaHref'  => 'https://dahomeydiscovery.com/hebergements/',
    'ctaLabel' => 'Voir toute la sélection sur dahomeydiscovery.com',
])
@php
    $aggregator = app(\App\Services\Aggregator\Aggregator::class);
    $items = $aggregator->accommodations($filters, $limit);
@endphp
@if($items->isNotEmpty())
<div class="page-section benin-agg-hebergements">
    <section class="section padd-top-md padd-bottom-md">
        <article class="section-layout-wrapper grid-container">
            @if($pretitle || $title)
                <div class="grid-x align-center marg-bottom-md">
                    <div class="cell large-10 text-center">
                        @if($pretitle)
                            <span class="benin-agg-heb__pretitle">{{ $pretitle }}</span>
                        @endif
                        @if($title)
                            <h2 class="benin-agg-heb__title">{{ $title }}</h2>
                        @endif
                    </div>
                </div>
            @endif
            <div class="grid-x grid-margin-x grid-margin-y small-up-1 medium-up-2 large-up-3 align-stretch">
                @foreach($items as $item)
                    <div class="cell">
                        <a href="{{ $item->url }}" target="_blank" rel="noopener noreferrer"
                           class="benin-agg-heb__card"
                           aria-label="{{ $item->title }} — voir sur dahomeydiscovery.com (nouvel onglet)">
                            <div class="benin-agg-heb__media">
                                @if($item->image)
                                    <img src="{{ $item->image }}"
                                         alt="{{ $item->title }}"
                                         loading="lazy" decoding="async"
                                         referrerpolicy="no-referrer">
                                @else
                                    <div class="benin-agg-heb__media-fallback"></div>
                                @endif
                                <span class="benin-agg-heb__badge">Dahomey Discovery</span>
                            </div>
                            <div class="benin-agg-heb__body">
                                <h3 class="benin-agg-heb__name">{{ $item->title }}</h3>
                                @if($item->city)
                                    <p class="benin-agg-heb__city">
                                        <span class="wicon wicon-039-pin" aria-hidden="true"></span>{{ $item->city }}
                                    </p>
                                @endif
                                <span class="benin-agg-heb__cta">
                                    Réserver sur dahomeydiscovery.com
                                    <span aria-hidden="true" class="benin-agg-heb__cta-arrow">→</span>
                                </span>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
            <div class="benin-agg-heb__footer">
                <a href="{{ $ctaHref }}" target="_blank" rel="noopener noreferrer"
                   class="benin-agg-heb__footer-cta">
                    {{ $ctaLabel }}
                    <span aria-hidden="true">↗</span>
                </a>
            </div>
        </article>
    </section>
</div>
@endif
