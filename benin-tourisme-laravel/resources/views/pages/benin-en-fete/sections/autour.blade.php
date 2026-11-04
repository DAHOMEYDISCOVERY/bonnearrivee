{{-- Fiche événement — section « autour ». Titres propres à chaque événement : $t (benin-events-identite.php), sinon les titres d'origine. --}}
{{-- AUTOUR DE L'ÉVÉNEMENT --}}
@if(!empty($event['autour']))
<section class="event-autour" data-anim-reveal aria-label="Autour de l'événement">
    <div class="event-section-head">
        <p class="event-section-eyebrow">{{ $t['eyebrow'] ?? 'À faire à côté' }}</p>
        <h2 class="event-section-title">{{ $event['autour']['titre'] }}</h2>
        <p class="event-section-lead">{{ $t['lead'] ?? 'Étirez votre séjour d\'une journée ou deux — voici les visites qui prolongent l\'expérience.' }}</p>
    </div>
    <div class="event-autour__grid">
        @foreach($event['autour']['points'] as $i => $a)
            <article class="event-autour__card" data-anim-reveal style="--i: {{ $i }}">
                <span class="event-autour__num" aria-hidden="true">{{ str_pad((string)($i+1), 2, '0', STR_PAD_LEFT) }}</span>
                <div>
                    <h3>{{ $a['t'] }}</h3>
                    <p>{{ $a['d'] }}</p>
                </div>
            </article>
        @endforeach
    </div>
</section>
@endif
