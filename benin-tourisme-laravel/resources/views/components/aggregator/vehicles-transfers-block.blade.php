{{--
    Grille véhicules + transferts Dahomey Discovery. Priorité aux items
    avec image (les autres arrivent après), fallback image locale, bouton
    « Réserver » en overlay.

    Usage :
        <x-aggregator.vehicles-transfers-block
            pretitle="Une fois arrivé·e"
            title="Transferts aéroport et location de véhicules"
            description="..."
            bgClass="bg-motif" />
--}}
@props([
    'title'       => 'Se déplacer sur place',
    'pretitle'    => null,
    'description' => null,
    'bgClass'     => '',
    'limit'       => 6,
])
@php
    $aggregator = app(\App\Services\Aggregator\Aggregator::class);
    // On fetch large (20) pour pouvoir prioriser les items avec image
    $vehicles  = $aggregator->vehicles([], 20);
    $transfers = $aggregator->transfers([], 20);
    $all = $vehicles->concat($transfers);

    // Tri : items avec image d'abord, sans image ensuite
    $withImg    = $all->filter(fn ($it) => (bool) $it->image);
    $withoutImg = $all->filter(fn ($it) => ! $it->image);
    $items      = $withImg->concat($withoutImg)->take($limit);

    $largeUp = min(max($items->count(), 1), 3);

    // Fallback pour les cards sans image : illustrations locales génériques
    $fallbacks = [
        '/assets/img/aeroport-640x480.webp',
        '/assets/img/cotonou-640x480.webp',
        '/assets/img/frontiere-640x480.webp',
        '/assets/img/pirogue-640x480.webp',
    ];
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
                    @php
                        $imgSrc = $item->image ?: $fallbacks[crc32($item->id) % count($fallbacks)];
                        $isTransfer = str_contains(strtolower($item->type ?? ''), 'transfer');
                        $categoryLabel = $isTransfer ? 'Transfert' : 'Véhicule';
                    @endphp
                    <div class="cell">
                        <a href="{{ $item->url }}" target="_blank" rel="noopener noreferrer"
                           class="card overlayedCard isRel tpl_05 ratio_4_3"
                           style="display:block;position:relative;overflow:hidden;border-radius:.25rem;height:100%;min-height:16rem">
                            <img src="{{ $imgSrc }}" alt="{{ $item->title }}"
                                 loading="lazy" decoding="async"
                                 style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover" />
                            <div style="position:absolute;inset:0;background:linear-gradient(to top,rgba(0,0,0,.85) 0%,rgba(0,0,0,.25) 55%,transparent 100%)"></div>
                            <div style="position:absolute;left:0;right:0;bottom:0;padding:1rem 1.25rem;color:#fff">
                                <span style="display:inline-block;font-size:.7rem;text-transform:uppercase;letter-spacing:.08em;opacity:.85;margin-bottom:.35rem;padding:.15rem .5rem;background:rgba(0,132,77,.85);border-radius:2px;font-weight:600">
                                    {{ $categoryLabel }}
                                </span>
                                <h3 style="margin:0 0 .75rem 0;font-size:1.05rem;font-weight:600;line-height:1.3;color:#fff">{{ $item->title }}</h3>
                                <span style="display:inline-flex;align-items:center;gap:.4em;padding:.5rem 1rem;background:#f6d047;color:#111;font-weight:700;font-size:.85rem;border-radius:3px;text-transform:uppercase;letter-spacing:.05em">
                                    Réserver <span aria-hidden="true">→</span>
                                </span>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </article>
    </section>
</div>
@endif
