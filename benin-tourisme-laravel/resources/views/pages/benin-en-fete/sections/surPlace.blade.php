{{-- Fiche événement — section « surPlace ». Titres propres à chaque événement : $t (benin-events-identite.php), sinon les titres d'origine. --}}
{{-- SUR PLACE --}}
@if(!empty($event['surPlace']))
<section class="event-surplace" data-anim-reveal aria-label="Sur place">
    <div class="event-section-head">
        <p class="event-section-eyebrow">{{ $t['eyebrow'] ?? 'Repères pratiques' }}</p>
        <h2 class="event-section-title">{{ $event['surPlace']['titre'] }}</h2>
    </div>
    <div class="event-surplace__grid">
        @foreach($event['surPlace']['points'] as $i => $s)
            <div class="event-surplace__item" data-anim-reveal style="--i: {{ $i }}">
                <span class="event-surplace__ic" aria-hidden="true">{!! $eventIcons[$s['label']] ?? $eventIconFallback !!}</span>
                <div>
                    <span class="event-surplace__label">{{ $s['label'] }}</span>
                    <p class="event-surplace__text">{{ $s['texte'] }}</p>
                </div>
            </div>
        @endforeach
    </div>
</section>
@endif
