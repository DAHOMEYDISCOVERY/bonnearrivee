{{-- Fiche événement — section « faq ». Titres propres à chaque événement : $t (benin-events-identite.php), sinon les titres d'origine. --}}
{{-- FAQ --}}
@if(!empty($event['faq']))
<section class="event-faq" data-anim-reveal aria-label="Questions fréquentes">
    <div class="event-section-head">
        <p class="event-section-eyebrow">{{ $t['eyebrow'] ?? 'Vous nous demandez souvent' }}</p>
        <h2 class="event-section-title">{{ $t['titre'] ?? 'Les questions fréquentes' }}</h2>
    </div>
    <div class="event-faq__list">
        @foreach($event['faq'] as $i => $qa)
            <details class="event-faq__item" data-anim-reveal style="--i: {{ $i }}">
                <summary>
                    <span>{{ $qa['q'] }}</span>
                    <span class="event-faq__chevron" aria-hidden="true">+</span>
                </summary>
                <p>{{ $qa['r'] }}</p>
            </details>
        @endforeach
    </div>
</section>
@endif
