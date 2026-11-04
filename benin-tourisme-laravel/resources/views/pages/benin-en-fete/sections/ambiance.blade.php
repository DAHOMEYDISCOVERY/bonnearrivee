{{-- Fiche événement — section « ambiance ». Titres propres à chaque événement : $t (benin-events-identite.php), sinon les titres d'origine. --}}
{{-- AMBIANCE --}}
@if(!empty($event['ambiance']))
<section class="event-ambiance" data-anim-reveal aria-label="L'ambiance">
    <div class="event-ambiance__grid">
        <div class="event-ambiance__body">
            <p class="event-section-eyebrow">{{ $t['eyebrow'] ?? 'L\'ambiance' }}</p>
            <h2>{{ $event['ambiance']['titre'] }}</h2>
            <p class="event-ambiance__lead">{{ $event['ambiance']['texte'] }}</p>
            <ul class="event-ambiance__points">
                @foreach($event['ambiance']['points'] as $p)
                    <li>{{ $p }}</li>
                @endforeach
            </ul>
        </div>
        <div class="event-ambiance__gallery">
            @foreach($event['ambiance']['photos'] as $i => $ph)
                <img src="{{ $ph }}" alt="" loading="lazy" decoding="async" referrerpolicy="no-referrer" style="--i: {{ $i }}">
            @endforeach
        </div>
    </div>
</section>
@endif
