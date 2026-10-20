{{--
    Gallery photos horizontale — reprend le composant `woody-component-gallery
    tpl_503` de Mayotte : swiper 5 images en ligne (2 sur mobile, 5 sur
    desktop), avec crédit auteur en overlay.

    Les images doivent exister sous /assets/img/{slug}-640x360.webp.

    Usage :
        <x-aggregator.gallery-block :photos="[
            ['slug' => 'pirogue', 'caption' => 'Pirogueur sur le lac Nokoué', 'author' => 'Wikimedia Commons'],
            ...
        ]" />
--}}
@props([
    'photos' => [],
])
@if(count($photos) > 0)
<div class="page-section">
    <section class="section padd-top-sm padd-bottom-sm marg-top-none marg-bottom-none is-full-width">
        <article class="section-layout-wrapper">
            <div class="grid_1col_tpl_01 grid-x align-middle grid-padding-x grid-padding-y">
                <div class="cell xmedium-down-order-9">
                    <div class="woody-component woody-component-gallery tpl_503 no-sessions">
                        <div class="gallery-wrapper">
                            <div class="woodyGallery grid-x small-up-2 medium-up-5" itemscope itemtype="http://schema.org/ImageGallery">
                                <div class="swiper swiper-container swResp woody-swiper has-navigation" data-options='{"spaceBetween":10}'>
                                    <div class="swiper-wrapper swRespW" role="list">
                                        @foreach($photos as $p)
                                            <div class="swiper-slide swRespS" role="listitem">
                                                <span class="mediaCard tpl_01" title="{{ $p['caption'] ?? '' }}">
                                                    <figure itemscope itemtype="http://schema.org/ImageObject" class="isRel imageObject">
                                                        <img src="/assets/img/{{ $p['slug'] }}-640x360.webp"
                                                             alt="{{ $p['caption'] ?? '' }}"
                                                             loading="lazy" decoding="async"
                                                             class="imageObject-img img-responsive"
                                                             style="width:100%;height:auto;display:block;aspect-ratio:16/9;object-fit:cover" />
                                                        @if(!empty($p['caption']))
                                                            <figcaption class="imageObject-caption flex-container align-middle isAbs bottom left">
                                                                <span class="imageObject-icon copyright-icon flex-container align-center align-middle">©</span>
                                                                <p class="spanned imageObject-text imageObject-description">{{ $p['caption'] }}</p>
                                                                @if(!empty($p['author']))
                                                                    <p class="spanned imageObject-text separator">|</p>
                                                                    <p class="spanned imageObject-text imageObject-author">{{ $p['author'] }}</p>
                                                                @endif
                                                            </figcaption>
                                                        @endif
                                                    </figure>
                                                </span>
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
                </div>
            </div>
        </article>
    </section>
</div>
@endif
