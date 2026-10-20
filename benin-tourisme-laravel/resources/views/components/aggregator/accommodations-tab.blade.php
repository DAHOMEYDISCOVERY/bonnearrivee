{{--
    Cards d'hébergement Dahomey Discovery dans le format tabs-panel Woody
    de la page /explorez/le-littoral-et-les-lacs. Reprend exactement les
    classes `woody-component-focus tpl_304 swiperOut ... swiper woody-swiper`
    de la structure existante, pour que le carrousel natif s'initialise
    normalement.

    Usage :
        <x-aggregator.accommodations-tab
            panel-id="tabs-6a8c5f8d299a7"
            :active="true"
            :filters="[]"
            fallback-title="Aucun hébergement disponible" />
--}}
@props([
    'panelId'       => null,
    'active'        => false,
    'filters'       => [],
    'limit'         => 6,
    'fallbackTitle' => null,
])
@php
    $aggregator = app(\App\Services\Aggregator\Aggregator::class);
    $items = $aggregator->accommodations($filters, $limit);
    $activeClass = $active ? 'is-active' : '';
    $swiperOptions = '{"slidesPerView":1,"centeredSlides":false,"initialSlide":0,"spaceBetween":30,"breakpoints":{"1024":{"slidesPerView":3,"centeredSlides":false,"initialSlide":0},"767":{"slidesPerView":2,"centeredSlides":false}}}';
@endphp
<div class="tabs-panel {{ $activeClass }} tabs-alignment-align-middle" @if($panelId) id="{{ $panelId }}" @endif>
    @if($items->isEmpty())
        <div class="grid_1col_tpl_01 grid-x grid-padding-x grid-padding-y">
            <div class="cell text-center padd-all-md" style="color:#fff;opacity:.9">
                {{ $fallbackTitle ?? 'Aucune offre disponible pour le moment.' }}
            </div>
        </div>
    @else
        <div class="grid_1col_tpl_01 grid-x grid-padding-x grid-padding-y">
            <div class="cell xmedium-down-order-9">
                <div class="woody-component woody-component-focus tpl_304 swiperOut isRel no-sessions" data-equalizer="data-equalizer">
                    <div class="woody-component-basic-swiper tpl_01 isRel is-swResp">
                        <div class="swiper swiper-container swResp woody-swiper has-navigation" data-options="{{ $swiperOptions }}">
                            <div class="swiper-wrapper swRespW swRespGrid" role="list">
                                @foreach($items as $item)
                                    @php
                                        // Description enrichie : excerpt API si présent, sinon phrase
                                        // générique construite depuis le titre pour éviter les cards vides.
                                        $desc = $item->excerpt;
                                        if (! $desc || mb_strlen($desc) < 30) {
                                            $lower = mb_strtolower($item->title);
                                            $desc = match (true) {
                                                str_contains($lower, 'chambre 1 salon') || str_contains($lower, 'appartement') =>
                                                    'Appartement meublé avec espace nuit séparé, cuisine équipée et coin séjour — idéal pour un court séjour à ' . ($item->city ?: 'Cotonou') . '.',
                                                str_contains($lower, 'studio') =>
                                                    'Studio meublé tout confort, WiFi, climatisation, cuisine équipée — parfait pour un voyageur seul ou en couple.',
                                                str_contains($lower, 'bois') || str_contains($lower, 'vert') =>
                                                    'Logement au calme, ambiance nature et matériaux locaux, accès facile aux sites touristiques de ' . ($item->city ?: 'la région') . '.',
                                                str_contains($lower, 'hôtel') || str_contains($lower, 'hotel') =>
                                                    'Hôtel classé avec restaurant, chambres climatisées et petit-déjeuner inclus. Idéal pour affaires ou tourisme à ' . ($item->city ?: 'Cotonou') . '.',
                                                default =>
                                                    'Hébergement sélectionné par Dahomey Discovery, contrôlé par notre équipe locale. Réservation directe sans intermédiaire.',
                                            };
                                        }
                                    @endphp
                                    <div class="swiper-slide swRespS swRespGridS cell" role="listitem">
                                        <a class="card-link" href="{{ $item->url }}" target="_blank" rel="noopener noreferrer"
                                           style="display:flex;flex-direction:column;background:#fff;border-radius:.5rem;overflow:hidden;text-decoration:none;color:inherit;box-shadow:0 4px 14px rgba(0,0,0,.1);height:100%;transition:transform .2s ease, box-shadow .2s ease"
                                           onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 8px 20px rgba(0,0,0,.15)'"
                                           onmouseout="this.style.transform='';this.style.boxShadow='0 4px 14px rgba(0,0,0,.1)'">
                                            <div style="position:relative;aspect-ratio:16/10;overflow:hidden">
                                                @if($item->image)
                                                    <img src="{{ $item->image }}"
                                                         alt="{{ $item->title }}"
                                                         loading="lazy" decoding="async"
                                                         style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover" />
                                                @else
                                                    <div style="position:absolute;inset:0;background:linear-gradient(135deg,#00844d,#025c37)"></div>
                                                @endif
                                                <span style="position:absolute;top:.6rem;left:.6rem;background:rgba(0,132,77,.9);color:#fff;font-size:.65rem;padding:.2rem .55rem;border-radius:2px;font-weight:600;text-transform:uppercase;letter-spacing:.06em">
                                                    Hébergement
                                                </span>
                                            </div>
                                            <div style="padding:1rem 1.15rem;display:flex;flex-direction:column;flex:1">
                                                <h3 style="margin:0 0 .35rem 0;font-size:1.05rem;font-weight:700;color:#111;line-height:1.3">{{ $item->title }}</h3>
                                                @if($item->city)
                                                    <span style="display:inline-flex;align-items:center;gap:.3em;font-size:.8rem;color:#666;margin-bottom:.5rem">
                                                        <span class="wicon wicon-039-pin" aria-hidden="true"></span>{{ $item->city }}
                                                    </span>
                                                @endif
                                                <p style="margin:0 0 .9rem 0;font-size:.85rem;color:#555;line-height:1.5;flex:1">{{ mb_substr($desc, 0, 180) }}{{ mb_strlen($desc) > 180 ? '…' : '' }}</p>
                                                <span style="align-self:flex-start;display:inline-flex;align-items:center;gap:.4em;padding:.55rem 1.1rem;background:#00844d;color:#fff;font-weight:700;font-size:.82rem;text-transform:uppercase;letter-spacing:.05em;border-radius:3px">
                                                    Réserver <span aria-hidden="true">→</span>
                                                </span>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="swiper-controls">
                            <div class="swiper-nav">
                                <div class="swiper-button-prev"><i aria-hidden="true" class="wicon wicon-026-precedent"></i></div>
                                <div class="swiper-button-next"><i aria-hidden="true" class="wicon wicon-025-suivant"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
