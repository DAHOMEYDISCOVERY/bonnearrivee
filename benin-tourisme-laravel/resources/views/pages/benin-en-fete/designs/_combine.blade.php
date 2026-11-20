{{-- « À combiner avec » : cartes vers les autres fiches. --}}
@if (! empty($event['combinerAvec']))
<div class="bf-combi">
    @foreach ($event['combinerAvec'] as $i => $c)
        @php($cible = $allEvents[$c['slug']] ?? null)
        @if ($cible)
            <a class="bf-rv" style="--d: {{ $i * 90 }}ms" href="/benin-en-fete/{{ $c['slug'] }}">
                <img src="{{ $cible['hero'] }}" alt="" loading="lazy" decoding="async">
                <div>
                    <small>{{ $cible['lieu'] }} · {{ $cible['dates'] }}</small>
                    <h3>{{ $c['titre'] }}</h3>
                    <p>{{ $c['note'] }}</p>
                </div>
            </a>
        @endif
    @endforeach
</div>
@endif
