{{-- Fiche événement — section « histoire ». Titres propres à chaque événement : $t (benin-events-identite.php), sinon les titres d'origine. --}}
{{-- HISTOIRE --}}
@if(!empty($event['histoire']))
<section class="event-histoire" data-anim-reveal aria-label="Comment est né l'événement">
    <div class="event-histoire__grid">
        <aside class="event-histoire__aside">
            <p class="event-section-eyebrow">{{ $t['eyebrow'] ?? 'L\'origine' }}</p>
            <p class="event-histoire__annee">{{ $event['dates'] }}</p>
            <p class="event-histoire__lieu">{{ $event['lieu'] }}</p>
        </aside>
        <div class="event-histoire__body">
            <h2>{{ $event['histoire']['titre'] }}</h2>
            @foreach($event['histoire']['paragraphs'] as $p)
                <p>{{ $p }}</p>
            @endforeach
        </div>
    </div>
</section>
@endif
