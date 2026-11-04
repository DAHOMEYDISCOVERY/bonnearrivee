{{-- Fiche événement — section « lieux ». Titres propres à chaque événement : $t (benin-events-identite.php), sinon les titres d'origine. --}}
{{-- LIEUX EMBLÉMATIQUES --}}
@if(!empty($event['lieux']))
<section class="event-lieux" data-anim-reveal aria-label="Les lieux emblématiques">
    <div class="event-section-head">
        <p class="event-section-eyebrow">{{ $t['eyebrow'] ?? 'Les scènes' }}</p>
        <h2 class="event-section-title">{{ $t['titre'] ?? 'Les lieux emblématiques' }}</h2>
        <p class="event-section-lead">{{ $t['lead'] ?? 'Chaque événement respire dans des lieux précis. Les connaître, c\'est déjà commencer à comprendre le déroulé.' }}</p>
    </div>
    <div class="event-lieux__grid">
        @foreach($event['lieux'] as $i => $l)
            <article class="event-lieu" data-anim-reveal style="--i: {{ $i }}">
                <div class="event-lieu__media">
                    <img src="{{ $l['photo'] }}" alt="{{ $l['nom'] }}" loading="lazy" decoding="async" referrerpolicy="no-referrer">
                </div>
                <div class="event-lieu__body">
                    <h3>{{ $l['nom'] }}</h3>
                    <p>{{ $l['desc'] }}</p>
                </div>
            </article>
        @endforeach
    </div>
</section>
@endif
