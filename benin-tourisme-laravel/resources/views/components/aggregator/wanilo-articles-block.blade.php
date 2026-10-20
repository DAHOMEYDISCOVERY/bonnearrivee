{{--
    Fil d'articles Wanilo Studio — cards magazine, format éditorial.

    Design différent du reste (fond clair, cards avec image en haut et
    contenu texte dessous) pour marquer visuellement que c'est du contenu
    éditorial / récit, pas un catalogue.

    Usage :
        <x-aggregator.wanilo-articles-block :limit="4" />
--}}
@props([
    'title'       => 'Regards du Bénin',
    'pretitle'    => null,
    'description' => null,
    'limit'       => 4,
    'bgClass'     => '',
    'showBanner'  => false,
])
@php
    $aggregator = app(\App\Services\Aggregator\Aggregator::class);
    $items = $aggregator->waniloArticles($limit);
    $largeUp = min(max($items->count(), 1), 4);
@endphp
@if($items->isNotEmpty())
<div class="page-section">
    <section class="section {{ $bgClass }} padd-top-sm padd-bottom-sm">
        <article class="section-layout-wrapper grid-container">
            @if($showBanner)
                {{-- Bannière « Signé Wanilo Studio » — annonce visuelle avant les cards --}}
                <div class="grid-x align-center marg-bottom-md">
                    <div class="cell large-10">
                        <div style="display:flex;align-items:center;gap:1.25rem;padding:1.25rem 1.5rem;background:#111;color:#fff;border-radius:.5rem;flex-wrap:wrap">
                            <div style="flex-shrink:0;width:56px;height:56px;background:#f6d047;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#111;font-family:Georgia,serif;font-size:1.5rem;font-weight:700;font-style:italic">W</div>
                            <div style="flex:1;min-width:220px">
                                <div style="font-size:.7rem;text-transform:uppercase;letter-spacing:.12em;color:#f6d047;font-weight:600;margin-bottom:.15rem">Un partenaire du portail</div>
                                <strong style="font-size:1.05rem;line-height:1.3">Wanilo Studio — studio audiovisuel béninois</strong>
                                <p style="margin:.3rem 0 0;font-size:.9rem;opacity:.85;line-height:1.5">
                                    Production vidéo, podcast et photo à Cotonou : décors professionnels, matériel broadcast, équipe technique.
                                    Nous partageons ici les regards du studio sur la vie créative du Bénin.
                                </p>
                            </div>
                            <a href="https://www.wanilo.studio" target="_blank" rel="noopener noreferrer"
                               style="display:inline-flex;align-items:center;gap:.4em;padding:.55rem 1.1rem;background:#f6d047;color:#111;font-weight:700;font-size:.85rem;text-transform:uppercase;letter-spacing:.05em;border-radius:3px;text-decoration:none">
                                Visiter wanilo.studio <span aria-hidden="true">→</span>
                            </a>
                        </div>
                    </div>
                </div>
            @endif
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
                    <div class="cell">
                        <a href="{{ $item->url }}" target="_blank" rel="noopener noreferrer"
                           class="card basicCard isRel tpl_02"
                           style="display:flex;flex-direction:column;background:#fff;border-radius:.35rem;overflow:hidden;height:100%;color:inherit;text-decoration:none;box-shadow:0 2px 8px rgba(0,0,0,.06);transition:box-shadow .2s ease">
                            <div style="position:relative;aspect-ratio:16/10;overflow:hidden;background:#111">
                                @if($item->image)
                                    <img src="{{ $item->image }}" alt="{{ $item->title }}"
                                         loading="lazy" decoding="async"
                                         style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover" />
                                @else
                                    <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;color:#f6d047;font-family:Georgia,serif;font-size:1.5rem;font-style:italic">Wanilo</div>
                                @endif
                            </div>
                            <div style="padding:1rem 1.25rem;display:flex;flex-direction:column;flex:1">
                                <span style="font-size:.7rem;text-transform:uppercase;letter-spacing:.1em;color:#888;font-weight:600;margin-bottom:.35rem">Wanilo Studio</span>
                                <h3 style="margin:0 0 .5rem 0;font-size:1rem;font-weight:600;line-height:1.35">{{ $item->title }}</h3>
                                @if($item->excerpt)
                                    <p style="margin:0;font-size:.85rem;line-height:1.5;opacity:.75;flex:1">{{ mb_substr($item->excerpt, 0, 120) }}{{ mb_strlen($item->excerpt) > 120 ? '…' : '' }}</p>
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
