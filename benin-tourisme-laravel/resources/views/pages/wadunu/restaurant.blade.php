@extends('layouts.app')

@section('bodyClass', 'page menus-v2 prod gabarit-basic_page wadunu-fiche-page')
@section('mainAttributes')role="main" class="content-type-page basic_page" id="main-content"@endsection

@section('content')
<div class="content-wrapper no-terms">
    <section class="wadunu-fiche">
        <a class="wadunu-fiche__back" href="{{ route('wadunu.catalog', $token) }}">← Retour au catalogue</a>

        <header class="wadunu-fiche__head">
            @if($restaurant->coup_de_coeur)
                <span class="wadunu-fiche__coeur">♥ Coup de cœur Dahomey Discovery</span>
            @endif
            <p class="wadunu-fiche__ville">{{ $restaurant->ville }}@if($restaurant->quartier) · {{ $restaurant->quartier }}@endif</p>
            <h1 class="wadunu-fiche__nom">{{ $restaurant->nom }}</h1>
            <p class="wadunu-fiche__sub">{{ $restaurant->sous_titre }}</p>
        </header>

        @if(!empty($restaurant->photos) && count($restaurant->photos))
            <div class="wadunu-fiche__gallery">
                @foreach($restaurant->photos as $ph)
                    <img src="{{ asset('storage/' . $ph) }}" alt="{{ $restaurant->nom }}" loading="lazy">
                @endforeach
            </div>
        @endif

        @if(!empty($restaurant->pourquoi_y_aller))
            <section class="wadunu-fiche__why">
                <h2>Pourquoi y aller ?</h2>
                <ul>
                    @foreach($restaurant->pourquoi_y_aller as $point)
                        <li>{{ is_array($point) ? ($point['point'] ?? '') : $point }}</li>
                    @endforeach
                </ul>
            </section>
        @endif

        <section class="wadunu-fiche__infos">
            <h2>Infos pratiques</h2>
            <dl class="wadunu-fiche__dl">
                <div><dt>Budget</dt><dd>{{ $restaurant->budget_label }}</dd></div>
                @if($restaurant->specialite)<div><dt>Spécialité</dt><dd>{{ $restaurant->specialite }}</dd></div>@endif
                @if($restaurant->adresse)<div><dt>Adresse</dt><dd>{{ $restaurant->adresse }}</dd></div>@endif
                @if($restaurant->telephone)<div><dt>Contact</dt><dd><a href="tel:{{ preg_replace('/\D/', '', $restaurant->telephone) }}">{{ $restaurant->telephone }}</a></dd></div>@endif
                @if($restaurant->horaires)<div><dt>Horaires</dt><dd>{{ $restaurant->horaires }}</dd></div>@endif
            </dl>
        </section>

        @if($restaurant->conseil_dahomey)
            <section class="wadunu-fiche__conseil">
                <p class="wadunu-fiche__conseil-eyebrow">Conseil Dahomey Discovery</p>
                <p>{{ $restaurant->conseil_dahomey }}</p>
            </section>
        @endif
    </section>
</div>
@endsection
