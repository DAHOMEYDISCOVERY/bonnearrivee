{{-- Fiche événement — section « blocks ». Titres propres à chaque événement : $t (benin-events-identite.php), sinon les titres d'origine. --}}
{{-- BLOCS ÉDITORIAUX --}}
<section class="event-blocks">
    @foreach($event['blocks'] as $i => $b)
        <article class="event-block" data-anim-reveal style="--i: {{ $i }}">
            <span class="event-block__num">{{ str_pad((string)($i+1), 2, '0', STR_PAD_LEFT) }}</span>
            <div class="event-block__body">
                <h2>{{ $b['titre'] }}</h2>
                <p>{{ $b['texte'] }}</p>
            </div>
        </article>
    @endforeach
</section>
