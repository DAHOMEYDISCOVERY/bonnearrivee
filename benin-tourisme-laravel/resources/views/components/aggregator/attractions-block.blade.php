{{--
    « Depuis les profondeurs… » — grille d'attractions Dahomey Discovery
    reprenant le format `woody-component-focus tpl_316` de Mayotte : cards
    carrées avec titre + type + ville + description.

    Usage :
        <x-aggregator.attractions-block
            pretitle="Plonger au cœur du littoral"
            title="À vivre sur place"
            :filters="['city' => 'cotonou']"
            :limit="4" />
--}}
@props([
    'pretitle' => 'Plonger au cœur du littoral',
    'title'    => 'À vivre sur place',
    'filters'  => [],
    'limit'    => 4,
])
@php
    $aggregator = app(\App\Services\Aggregator\Aggregator::class);
    $items = $aggregator->attractions($filters, $limit);
    $swiperOptions = '{"slidesPerView":1,"centeredSlides":false,"initialSlide":0,"spaceBetween":30,"breakpoints":{"1024":{"slidesPerView":4,"centeredSlides":false,"initialSlide":0},"767":{"slidesPerView":2,"centeredSlides":false}}}';
@endphp
@if($items->isNotEmpty())
<div class="page-section">
    <section class="section bg-motif padd-top-sm padd-bottom-sm marg-top-md marg-bottom-md">
        <header class="section-header-wrapper grid-container grid-default container-min">
            <div class="section-header text-center">
                <div class="section-titles flex-container flex-dir-column align-middle align-center text-center">
                    <div class="section-texts has-pretitle has-title">
                        <span class="section-pretitle block-span">{{ $pretitle }}</span>
                        <h2 class="section-title">{{ $title }}</h2>
                    </div>
                </div>
            </div>
        </header>
        <article class="section-layout-wrapper grid-container grid-default">
            <div class="grid_1col_tpl_01 grid-x align-middle grid-padding-x grid-padding-y">
                <div class="cell xmedium-down-order-9">
                    <div class="woody-component woody-component-focus tpl_316 swiperOut isRel no-sessions" data-equalizer="data-equalizer">
                        <div class="swiper swiper-container swResp woody-swiper has-navigation" data-options="{{ $swiperOptions }}">
                            <div class="swiper-wrapper swRespW swRespGrid" role="list">
                                @foreach($items as $item)
                                    <div class="swiper-slide swRespS swRespGridS" role="listitem">
                                        <div class="card basicCard tpl_01 isRel ratio_square">
                                            <a class="card-link flex-container flex-dir-column" href="{{ $item->url }}" target="_blank" rel="noopener noreferrer">
                                                <div class="isRel imageObject cardMediaWrapper" style="aspect-ratio:1/1;overflow:hidden">
                                                    @if($item->image)
                                                        <img src="{{ $item->image }}" alt="{{ $item->title }}"
                                                             loading="lazy" decoding="async"
                                                             style="width:100%;height:100%;object-fit:cover;transition:transform .5s ease" />
                                                    @else
                                                        <div style="width:100%;height:100%;background:linear-gradient(135deg,#00844d,#025c37)"></div>
                                                    @endif
                                                </div>
                                                <div class="card-section" data-equalizer-watch style="padding:1rem 1.25rem;background:#fff">
                                                    <div class="card-titles" style="text-align:left">
                                                        <div class="card-texts has-title has-desc">
                                                            <span class="card-title block-span" style="display:block;font-weight:700;color:#111;font-size:1rem;line-height:1.3;margin-bottom:.35rem">{{ $item->title }}</span>
                                                            <span class="card-type block-span" style="display:block;font-size:.7rem;color:#00844d;text-transform:uppercase;letter-spacing:.06em;font-weight:600;margin-bottom:.35rem">Activité culturelle</span>
                                                            @if($item->excerpt)
                                                                <div class="card-description" style="font-size:.85rem;color:#555;line-height:1.45;margin-bottom:.5rem">{{ mb_substr($item->excerpt, 0, 110) }}{{ mb_strlen($item->excerpt) > 110 ? '…' : '' }}</div>
                                                            @endif
                                                            @if($item->city)
                                                                <span class="card-town block-span" style="display:inline-flex;align-items:center;gap:.35em;font-size:.8rem;color:#666">
                                                                    <span class="wicon wicon-039-pin" aria-hidden="true"></span>{{ $item->city }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="swiper-controls">
                            <div class="swiper-nav is-mobile">
                                <div class="swiper-button-prev"><i aria-hidden="true" class="wicon wicon-026-precedent"></i></div>
                                <div class="swiper-button-next"><i aria-hidden="true" class="wicon wicon-025-suivant"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </article>
    </section>
</div>
@endif
