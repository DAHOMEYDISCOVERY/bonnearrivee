{{--
    Grille de circuits — même ossature que les autres sections Woody.

    Design volontairement sobre : titre centré, grille responsive de
    cards en aplat sombre. Le prix, quand présent, s'affiche discrètement
    en pretitle. Chaque card est un lien direct vers la fiche externe.

    Usage :
        <x-aggregator.circuits-block
            title="Circuits qui passent par Abomey"
            :filters="['city' => 'abomey']"
            :limit="4" />
--}}
@props([
    'title'       => null,
    'pretitle'    => null,
    'description' => null,
    'filters'     => [],
    'limit'       => 4,
    'bgClass'     => 'bg-motif',
])
@php
    $aggregator = app(\App\Services\Aggregator\Aggregator::class);
    $items = $aggregator->circuits($filters, $limit);
    // Equilibre visuel : on choisit un nombre de colonnes qui divise le
    // nombre de cards (pas de derniere ligne a moitie vide). Ex : 6 items
    // -> 3 colonnes (2 rangees pleines), 8 items -> 4 colonnes.
    $count = $items->count();
    $largeUp = match (true) {
        $count <= 1       => 1,
        $count === 2      => 2,
        $count === 3      => 3,
        $count === 4      => 4,
        $count % 4 === 0  => 4,
        $count % 3 === 0  => 3,
        $count % 2 === 0  => 2,
        default           => min($count, 4),
    };
@endphp
@if($items->isNotEmpty())
<div class="page-section">
    <section class="section {{ $bgClass }} padd-top-sm padd-bottom-sm">
        <article class="section-layout-wrapper grid-container">
            @if($title || $pretitle || $description)
                <div class="grid-x align-center marg-bottom-md">
                    <div class="cell large-10 text-center">
                        @if($pretitle)
                            <span class="section-pretitle block-span">{{ $pretitle }}</span>
                        @endif
                        @if($title)
                            <h2 class="section-title">{{ $title }}</h2>
                        @endif
                        @if($description)
                            <p style="max-width:64ch;margin:.75rem auto 0;color:#555;line-height:1.6;font-size:1rem">{{ $description }}</p>
                        @endif
                    </div>
                </div>
            @endif
            <div class="grid-x grid-margin-x grid-margin-y small-up-1 medium-up-2 large-up-{{ $largeUp }} align-stretch">
                @foreach($items as $item)
                    <div class="cell">
                        <a href="{{ $item->url }}" target="_blank" rel="noopener noreferrer"
                           class="card overlayedCard isRel tpl_05 ratio_4_3"
                           style="display:block;position:relative;overflow:hidden;border-radius:.25rem;height:100%;min-height:16rem">
                            @php
                                // Fallback : image locale « littoral béninois » quand
                                // l'API Dahomey ne retourne pas de photo — évite le carré
                                // vert plein et donne du corps à la card.
                                $fallbacks = [
                                    '/assets/img/cotonou-1200x900.webp',
                                    '/assets/img/plage-fidjrosse-1200x600.webp',
                                    '/assets/img/ganvie-640x480.webp',
                                    '/assets/img/mangrove-640x480.webp',
                                ];
                                $imgSrc = $item->image ?: $fallbacks[crc32($item->id) % count($fallbacks)];
                            @endphp
                            <img src="{{ $imgSrc }}"
                                 alt="{{ $item->title }}"
                                 loading="lazy" decoding="async"
                                 style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover" />
                            <div style="position:absolute;inset:0;background:linear-gradient(to top,rgba(0,0,0,.8) 0%,rgba(0,0,0,.2) 55%,transparent 100%)"></div>
                            <div style="position:absolute;left:0;right:0;bottom:0;padding:1rem 1.25rem;color:#fff">
                                @if($item->price)
                                    <span style="display:inline-block;font-size:.7rem;text-transform:uppercase;letter-spacing:.08em;padding:.2rem .5rem;background:#f6d047;color:#111;border-radius:2px;font-weight:600;margin-bottom:.4rem">
                                        {{ $item->price }}
                                    </span>
                                @endif
                                <h3 style="margin:0 0 .25rem 0;font-size:1.125rem;line-height:1.3;font-weight:600">{{ $item->title }}</h3>
                                @if($item->city)
                                    <span style="display:inline-flex;align-items:center;gap:.35em;font-size:.85rem;opacity:.9">
                                        <span class="wicon wicon-039-pin" aria-hidden="true"></span>{{ $item->city }}
                                    </span>
                                @endif
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </article>
    </section>
</div>
@endif
