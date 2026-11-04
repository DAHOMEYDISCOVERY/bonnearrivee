{{-- Fiche événement — section « programme ». Titres propres à chaque événement : $t (benin-events-identite.php), sinon les titres d'origine. --}}
{{-- PROGRAMME TIMELINE --}}
@if(!empty($event['programme']))
<section class="event-programme" id="programme" data-anim-reveal aria-label="Programme type">
    <div class="event-section-head">
        <p class="event-section-eyebrow">{{ $t['eyebrow'] ?? 'Le déroulé' }}</p>
        <h2 class="event-section-title">{{ $t['titre'] ?? 'Programme type' }}</h2>
        <p class="event-section-lead">{{ $t['lead'] ?? 'Un aperçu de ce qui vous attend jour après jour. Le programme officiel varie chaque année — vérifiez la date exacte sur le site du festival.' }}</p>
    </div>
    <ol class="event-timeline">
        @foreach($event['programme'] as $i => $p)
            <li class="event-timeline__item" data-anim-reveal style="--i: {{ $i }}">
                <div class="event-timeline__marker" aria-hidden="true">
                    <span class="event-timeline__dot"></span>
                </div>
                <div class="event-timeline__body">
                    <div class="event-timeline__meta">
                        <span class="event-timeline__day">{{ $p['jour'] }}</span>
                        <span class="event-timeline__lieu"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 1 1 16 0z"/><circle cx="12" cy="10" r="3"/></svg>{{ $p['lieu'] }}</span>
                    </div>
                    <h3 class="event-timeline__title">{{ $p['titre'] }}</h3>
                    <p class="event-timeline__desc">{{ $p['texte'] }}</p>
                </div>
            </li>
        @endforeach
    </ol>
</section>
@endif
