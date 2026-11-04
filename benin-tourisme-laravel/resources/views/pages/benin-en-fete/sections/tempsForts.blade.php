{{-- Fiche événement — section « tempsForts ». Titres propres à chaque événement : $t (benin-events-identite.php), sinon les titres d'origine. --}}
{{-- TEMPS FORTS (vagues de programmation) --}}
@if(!empty($event['tempsForts']))
<section class="event-vagues" data-anim-reveal aria-label="Les temps forts">
    <div class="event-section-head">
        <p class="event-section-eyebrow">{{ $t['eyebrow'] ?? 'Les vagues de programmation' }}</p>
        <h2 class="event-section-title">{{ $t['titre'] ?? 'Les temps forts' }}</h2>
        <p class="event-section-lead">{{ $t['lead'] ?? 'Chaque édition suit un fil narratif similaire — voici les cinq vagues qui structurent l\'expérience.' }}</p>
    </div>
    <div class="event-vagues__list">
        @foreach($event['tempsForts'] as $i => $v)
            <article class="event-vague" data-anim-reveal style="--i: {{ $i }}">
                <span class="event-vague__index">{{ str_pad((string)($i+1), 2, '0', STR_PAD_LEFT) }}</span>
                <div class="event-vague__body">
                    <span class="event-vague__sous">{{ $v['sous'] }}</span>
                    <h3>{{ $v['titre'] }}</h3>
                    <p>{{ $v['texte'] }}</p>
                </div>
            </article>
        @endforeach
    </div>
</section>
@endif
