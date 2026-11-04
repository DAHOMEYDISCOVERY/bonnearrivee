{{-- Fiche événement — section « tips ». Titres propres à chaque événement : $t (benin-events-identite.php), sinon les titres d'origine. --}}
{{-- BON À SAVOIR / TIPS --}}
@if(!empty($event['tips']))
<section class="event-tips" data-anim-reveal aria-label="Bon à savoir">
    <div class="event-section-head">
        <p class="event-section-eyebrow">{{ $t['eyebrow'] ?? 'Bon à savoir' }}</p>
        <h2 class="event-section-title">{{ $t['titre'] ?? 'Les conseils de nos guides' }}</h2>
        <p class="event-section-lead">{{ $t['lead'] ?? 'Ces règles évitent les faux pas et rendent le séjour plus fluide. Notre équipe les rappelle systématiquement à chaque groupe.' }}</p>
    </div>
    <div class="event-tips__grid">
        @foreach($event['tips'] as $i => $tip)
            <div class="event-tips__card" data-anim-reveal style="--i: {{ $i }}">
                <span class="event-tips__ic" aria-hidden="true">{!! $eventIcons[$tip['label']] ?? $eventIconFallback !!}</span>
                <div>
                    <span class="event-tips__label">{{ $tip['label'] }}</span>
                    <p class="event-tips__text">{{ $tip['texte'] }}</p>
                </div>
            </div>
        @endforeach
    </div>
</section>
@endif
