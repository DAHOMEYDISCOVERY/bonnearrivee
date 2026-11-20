{{-- Questions fréquentes d'une fiche Bénin en fête (mise en forme : assets/bef.css). --}}
@if (! empty($event['faq']))
<div class="bf-faq">
    @foreach ($event['faq'] as $i => $qa)
        <details class="bf-rv" style="--d: {{ $i * 60 }}ms">
            <summary><span>{{ $qa['q'] }}</span><i aria-hidden="true">+</i></summary>
            <p>{{ $qa['r'] }}</p>
        </details>
    @endforeach
</div>
@endif
