{{-- Fiche événement — section « signature » : contenu propre à un seul événement
     (benin-events-identite.php). Mises en forme : cartes, frise, glossaire, carnet. --}}
@if(!empty($event['signature']['items']))
@php($sig = $event['signature'])
<section class="event-signature event-signature--{{ $sig['style'] ?? 'cartes' }}" data-anim-reveal aria-label="{{ $sig['titre'] }}">
    <div class="event-section-head">
        @if(!empty($sig['eyebrow']))<p class="event-section-eyebrow">{{ $sig['eyebrow'] }}</p>@endif
        <h2 class="event-section-title">{{ $sig['titre'] }}</h2>
        @if(!empty($sig['lead']))<p class="event-section-lead">{{ $sig['lead'] }}</p>@endif
    </div>
    @if(($sig['style'] ?? 'cartes') === 'glossaire')
        <dl class="event-signature__list">
            @foreach($sig['items'] as $i => $item)
                <div class="event-signature__item" data-anim-reveal style="--i: {{ $i }}">
                    <dt>{{ $item['t'] }}</dt>
                    <dd>{{ $item['d'] }}</dd>
                </div>
            @endforeach
        </dl>
    @else
        <ol class="event-signature__list">
            @foreach($sig['items'] as $i => $item)
                <li class="event-signature__item" data-anim-reveal style="--i: {{ $i }}">
                    <span class="event-signature__num" aria-hidden="true">{{ str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) }}</span>
                    <h3>{{ $item['t'] }}</h3>
                    <p>{{ $item['d'] }}</p>
                </li>
            @endforeach
        </ol>
    @endif
</section>
@endif
