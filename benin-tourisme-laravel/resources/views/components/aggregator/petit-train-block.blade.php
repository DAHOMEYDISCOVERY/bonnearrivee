{{--
    Bloc Petit Train — grille compacte 3 cards, prix mis en évidence.

    Priorité aux produits WooCommerce (avec prix) ; fallback silencieux
    sur les CPT circuits si l'API WC n'a rien renvoyé.

    Usage :
        <x-aggregator.petit-train-block />
--}}
@props([
    'title'    => 'Découvrir Cotonou en petit train',
])
@php
    $aggregator = app(\App\Services\Aggregator\Aggregator::class);
    $items = $aggregator->petitTrainProducts(6);
    if ($items->isEmpty()) {
        $items = $aggregator->petitTrainCircuits(3);
    } else {
        $items = $items->take(3);
    }
    $largeUp = min(max($items->count(), 1), 3);
@endphp
@if($items->isNotEmpty())
<div class="page-section">
    <section class="section padd-top-sm padd-bottom-sm">
        <article class="section-layout-wrapper grid-container">
            @if($title)
                <div class="grid-x align-center marg-bottom-md">
                    <div class="cell large-10 text-center">
                        <h2 class="section-title">{{ $title }}</h2>
                    </div>
                </div>
            @endif
            <div class="grid-x grid-margin-x grid-margin-y small-up-1 medium-up-2 large-up-{{ $largeUp }} align-stretch">
                @foreach($items as $item)
                    <div class="cell">
                        <a href="{{ $item->url }}" target="_blank" rel="noopener noreferrer"
                           class="card basicCard isRel tpl_02"
                           style="display:flex;flex-direction:column;background:#fff;border-radius:.35rem;overflow:hidden;height:100%;color:inherit;text-decoration:none;box-shadow:0 4px 12px rgba(0,0,0,.08)">
                            <div style="position:relative;aspect-ratio:4/3;overflow:hidden">
                                @if($item->image)
                                    <img src="{{ $item->image }}" alt="{{ $item->title }}"
                                         loading="lazy" decoding="async"
                                         style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover" />
                                @else
                                    <div style="position:absolute;inset:0;background:linear-gradient(135deg,#f6d047,#f39c12);display:flex;align-items:center;justify-content:center;font-size:3.5rem" aria-hidden="true">🚂</div>
                                @endif
                                @if($item->price)
                                    <span style="position:absolute;top:.75rem;right:.75rem;background:#00844d;color:#fff;padding:.35rem .75rem;font-weight:600;font-size:.9rem;border-radius:2px">
                                        {{ $item->price }}
                                    </span>
                                @endif
                            </div>
                            <div style="padding:1rem 1.25rem;display:flex;flex-direction:column;flex:1">
                                <h3 style="margin:0 0 .5rem 0;font-size:1.05rem;font-weight:600;line-height:1.3">{{ $item->title }}</h3>
                                @if($item->excerpt)
                                    <p style="margin:0 0 .5rem 0;font-size:.9rem;color:#555;line-height:1.55;flex:1">{{ $item->excerpt }}</p>
                                @endif
                                <span style="display:inline-flex;align-items:center;gap:.4em;margin-top:.75rem;color:#00844d;font-weight:600;font-size:.9rem">
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
