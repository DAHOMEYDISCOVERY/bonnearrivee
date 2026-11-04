{{-- Fiche événement — section « combine ». Titres propres à chaque événement : $t (benin-events-identite.php), sinon les titres d'origine. --}}
{{-- COMBINER AVEC (cross-liens) --}}
@if(!empty($event['combinerAvec']))
<section class="event-combine" data-anim-reveal aria-label="Combiner avec d'autres événements">
    <div class="event-section-head">
        <p class="event-section-eyebrow">{{ $t['eyebrow'] ?? 'Prolonger le voyage' }}</p>
        <h2 class="event-section-title">{{ $t['titre'] ?? 'À combiner avec' }}</h2>
        <p class="event-section-lead">{{ $t['lead'] ?? 'Ces rendez-vous s\'inscrivent dans la même saison ou la même thématique. Ils gagnent à être vécus dans le même voyage.' }}</p>
    </div>
    <div class="event-combine__grid">
        @foreach($event['combinerAvec'] as $i => $c)
            @php $target = $allEvents[$c['slug']] ?? null; @endphp
            @if($target)
                <a class="event-combine__card" href="/benin-en-fete/{{ $c['slug'] }}" data-anim-reveal style="--i: {{ $i }}; --event-color: {{ $target['themeCouleur'] }}">
                    <img src="{{ $target['hero'] }}" alt="{{ $c['titre'] }}" loading="lazy" decoding="async" referrerpolicy="no-referrer">
                    <div class="event-combine__body">
                        <span class="event-combine__lieu">{{ $target['lieu'] }} · {{ $target['dates'] }}</span>
                        <h3>{{ $c['titre'] }}</h3>
                        <p>{{ $c['note'] }}</p>
                        <span class="event-combine__arrow" aria-hidden="true">Découvrir →</span>
                    </div>
                </a>
            @endif
        @endforeach
    </div>
</section>
@endif
