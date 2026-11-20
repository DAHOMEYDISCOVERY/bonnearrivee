{{-- Fil d'Ariane : toujours sous l'image d'en-tête, jamais dessus. --}}
<div class="ba-crumbbar">
    <nav aria-label="Fil d'ariane">
        <ol>
            <li><a href="/">Accueil</a></li>
            <li aria-hidden="true">/</li>
            <li><a href="/benin-en-fete">Bénin en Fête</a></li>
            <li aria-hidden="true">/</li>
            <li aria-current="page">{{ $event['title'] }}</li>
        </ol>
    </nav>
</div>
