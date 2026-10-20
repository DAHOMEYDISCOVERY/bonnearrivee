{{--
    Grille « à la une » des actualités du portail. Reprend l'ossature
    `woody_list_grid tpl_203 large-up-2` des cards Woody d'origine —
    grandes tuiles overlay tpl_03 — mais alimentées par les vraies
    sources externes : Dahomey Mag (blog éditorial) + Wanilo Studio
    (regards, formats longs).

    Rendu : 4 tuiles alternant les deux sources, taggées « ACTUALITÉ »
    (Dahomey Mag) ou « REGARD » (Wanilo Studio).

    Usage :
        <x-aggregator.actualites-featured />
--}}
@props([
    'limit' => 4,
])
@php
    $aggregator = app(\App\Services\Aggregator\Aggregator::class);
    $dahomey = $aggregator->dahomeyArticles(3)->map(fn($it) => ['it' => $it, 'kind' => 'actualite']);
    $wanilo  = $aggregator->waniloArticles(3)->map(fn($it) => ['it' => $it, 'kind' => 'regard']);

    // Mélange alterné (Dahomey, Wanilo, Dahomey, Wanilo…) pour un vrai
    // fil chronologique mixte plutôt qu'un bloc puis l'autre.
    $items = collect();
    $max = max($dahomey->count(), $wanilo->count());
    for ($i = 0; $i < $max; $i++) {
        if (isset($dahomey[$i])) $items->push($dahomey[$i]);
        if (isset($wanilo[$i]))  $items->push($wanilo[$i]);
    }
    $items = $items->take($limit);

    $fallbacks = [
        '/assets/img/cotonou-1200x900.webp',
        '/assets/img/vodun-640x480.webp',
        '/assets/img/danse-640x480.webp',
        '/assets/img/abomey-palais-640x480.webp',
    ];
@endphp
@if($items->isNotEmpty())
<div class="page-section">
    <section class="section bg-motif padd-top-md padd-bottom-sm marg-top-none marg-bottom-none">
        <article class="section-layout-wrapper grid-container grid-default">
            <div class="grid_1col_tpl_01 grid-x align-middle grid-padding-x grid-padding-y">
                <div class="cell xmedium-down-order-9">
                    <div class="woody_base_grid_wrapper">
                        <div class="woody_list_grid tpl_203 grid-x large-up-2 grid-padding-x grid-padding-y" role="list">
                            @foreach($items as $row)
                                @php
                                    $it = $row['it'];
                                    $kind = $row['kind'];
                                    $imgSrc = $it->image ?: $fallbacks[crc32($it->id) % count($fallbacks)];
                                    $tag = $kind === 'actualite' ? 'Actualité — Dahomey Discovery' : 'Regard — Wanilo Studio';
                                    $tagBg = $kind === 'actualite' ? 'rgba(0,132,77,.92)' : 'rgba(17,17,17,.9)';
                                    $tagColor = $kind === 'actualite' ? '#fff' : '#f6d047';
                                    $cta = $kind === 'actualite' ? 'Lire l’article' : 'Voir sur wanilo.studio';
                                @endphp
                                <div class="cell" role="listitem">
                                    <a href="{{ $it->url }}" target="_blank" rel="noopener noreferrer"
                                       class="card overlayedCard isRel tpl_03 ratio_16_9"
                                       style="display:block;position:relative;overflow:hidden;border-radius:.4rem;height:100%;min-height:18rem;box-shadow:0 4px 14px rgba(0,0,0,.1)">
                                        <img src="{{ $imgSrc }}" alt="{{ $it->title }}"
                                             loading="lazy" decoding="async"
                                             style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;transition:transform .5s ease" />
                                        <div style="position:absolute;inset:0;background:linear-gradient(to top,rgba(0,0,0,.85) 0%,rgba(0,0,0,.35) 45%,rgba(0,0,0,.15) 100%)"></div>
                                        <span style="position:absolute;top:1rem;left:1rem;background:{{ $tagBg }};color:{{ $tagColor }};font-size:.7rem;padding:.3rem .7rem;border-radius:2px;font-weight:700;text-transform:uppercase;letter-spacing:.08em">
                                            {{ $tag }}
                                        </span>
                                        <div style="position:absolute;left:0;right:0;bottom:0;padding:1.5rem 1.75rem;color:#fff">
                                            <h3 style="margin:0 0 .5rem 0;font-size:1.3rem;font-weight:700;line-height:1.25;color:#fff">{{ $it->title }}</h3>
                                            @if($it->excerpt)
                                                <p style="margin:0 0 1rem 0;font-size:.9rem;opacity:.9;line-height:1.5;max-width:52ch">{{ mb_substr($it->excerpt, 0, 130) }}{{ mb_strlen($it->excerpt) > 130 ? '…' : '' }}</p>
                                            @endif
                                            <span style="display:inline-flex;align-items:center;gap:.4em;padding:.55rem 1.1rem;background:#fff;color:#111;font-weight:700;font-size:.85rem;border-radius:3px;text-transform:uppercase;letter-spacing:.05em">
                                                {{ $cta }} <span aria-hidden="true">→</span>
                                            </span>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </article>
    </section>
</div>
@endif
