{{-- Fiche événement — section « stats ». Titres propres à chaque événement : $t (benin-events-identite.php), sinon les titres d'origine. --}}
{{-- STATS EN CHIFFRES --}}
@if(!empty($event['stats']))
<section class="event-stats" data-anim-reveal aria-label="En chiffres">
    @foreach($event['stats'] as $i => $s)
        <div class="event-stat" style="--i: {{ $i }}">
            <span class="event-stat__nb">{{ $s['nb'] }}</span>
            <span class="event-stat__label">{{ $s['label'] }}</span>
        </div>
    @endforeach
</section>
@endif
