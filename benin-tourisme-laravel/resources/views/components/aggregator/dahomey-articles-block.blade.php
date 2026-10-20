{{--
    Articles du Dahomey Mag (dahomeydiscovery.com/dahomey-mag) — format
    magazine, image en haut + titre + extrait + date + tag catégorie.

    Usage :
        <x-aggregator.dahomey-articles-block
            pretitle="À la une du Dahomey Mag"
            title="Le Bénin, décrypté par notre agence"
            description="..." />
--}}
@props([
    'pretitle'    => 'À la une du Dahomey Mag',
    'title'       => 'Actualités & guides pratiques du Bénin',
    'description' => null,
    'limit'       => 6,
    'bgClass'     => '',
])
@php
    $aggregator = app(\App\Services\Aggregator\Aggregator::class);
    $items = $aggregator->dahomeyArticles($limit);
    $largeUp = min(max($items->count(), 1), 3);

    // Fallback images génériques quand le post n'a pas de featured_media
    $fallbacks = [
        '/assets/img/cotonou-1200x900.webp',
        '/assets/img/pendjari-lion-640x480.webp',
        '/assets/img/vodun-640x480.webp',
        '/assets/img/abomey-palais-640x480.webp',
    ];

    // Formattage date FR — extrait le mois/année depuis publishedAt (ISO)
    $formatDate = function ($iso) {
        if (! $iso) return null;
        try {
            return \Carbon\Carbon::parse($iso)->locale('fr_FR')->translatedFormat('j F Y');
        } catch (\Throwable) {
            return null;
        }
    };
@endphp
@if($items->isNotEmpty())
<div class="page-section">
    <section class="section {{ $bgClass }} padd-top-sm padd-bottom-sm">
        <article class="section-layout-wrapper grid-container">
            @if($title || $pretitle || $description)
                <div class="grid-x align-center marg-bottom-md">
                    <div class="cell large-10 text-center">
                        @if($pretitle)<span class="section-pretitle block-span">{{ $pretitle }}</span>@endif
                        @if($title)<h2 class="section-title">{{ $title }}</h2>@endif
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
                        $date = $formatDate($item->publishedAt);
                    @endphp
                    <div class="cell">
                        <a href="{{ $item->url }}" target="_blank" rel="noopener noreferrer"
                           class="card basicCard isRel tpl_02"
                           style="display:flex;flex-direction:column;background:#fff;border-radius:.4rem;overflow:hidden;height:100%;color:inherit;text-decoration:none;box-shadow:0 2px 10px rgba(0,0,0,.08)">
                            <div style="position:relative;aspect-ratio:16/10;overflow:hidden">
                                <img src="{{ $imgSrc }}" alt="{{ $item->title }}"
                                     loading="lazy" decoding="async"
                                     style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover" />
                                <span style="position:absolute;top:.75rem;left:.75rem;background:rgba(0,132,77,.9);color:#fff;font-size:.7rem;padding:.25rem .6rem;border-radius:2px;font-weight:600;text-transform:uppercase;letter-spacing:.06em">
                                    Dahomey Mag
                                </span>
                            </div>
                            <div style="padding:1.1rem 1.25rem;display:flex;flex-direction:column;flex:1">
                                @if($date)
                                    <span style="font-size:.75rem;color:#888;font-weight:500;margin-bottom:.4rem">{{ $date }}</span>
                                @endif
                                <h3 style="margin:0 0 .5rem 0;font-size:1.05rem;font-weight:700;line-height:1.35;color:#111">{{ $item->title }}</h3>
                                @if($item->excerpt)
                                    <p style="margin:0;font-size:.9rem;color:#555;line-height:1.55;flex:1">{{ mb_substr($item->excerpt, 0, 160) }}{{ mb_strlen($item->excerpt) > 160 ? '…' : '' }}</p>
                                @endif
                                <span style="display:inline-flex;align-items:center;gap:.4em;margin-top:.9rem;color:#00844d;font-weight:600;font-size:.85rem">
                                    Lire l’article <span aria-hidden="true">→</span>
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
