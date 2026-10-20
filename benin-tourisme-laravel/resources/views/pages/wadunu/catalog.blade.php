@extends('layouts.app')

@section('bodyClass', 'page menus-v2 prod gabarit-basic_page wadunu-catalog-page')
@section('mainAttributes')role="main" class="content-type-page basic_page" id="main-content"@endsection

@section('content')
<div class="content-wrapper no-terms">
    <section class="wadunu-hero" aria-label="Wa Dùnù">
        <div class="wadunu-hero__inner">
            <p class="wadunu-hero__eyebrow">Wa Dùnù · Édition 2025</p>
            <h1 class="wadunu-hero__title">Votre carnet d'adresses gourmandes du Bénin</h1>
            <p class="wadunu-hero__lead">
                {{ $restos->count() }} restaurants sélectionnés. Accès actif jusqu'au
                <strong>{{ $expiresAt->translatedFormat('j F Y') }}</strong>.
                Marquez ce lien en favori — il vous suit sur tous vos appareils.
            </p>
        </div>
    </section>

    <section class="wadunu-filters" aria-label="Filtres">
        <form method="get" class="wadunu-filters__form">
            <input type="search" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Rechercher un nom, une spécialité, un quartier…" class="wadunu-filters__search">
            <select name="ville" class="wadunu-filters__select">
                <option value="">Toutes les villes</option>
                @foreach($villes as $v)
                    <option value="{{ $v }}" @selected(($filters['ville'] ?? '') === $v)>{{ $v }}</option>
                @endforeach
            </select>
            <select name="categorie" class="wadunu-filters__select">
                <option value="">Toutes les catégories</option>
                @foreach($categories as $key => $label)
                    <option value="{{ $key }}" @selected(($filters['categorie'] ?? '') === $key)>{{ $label }}</option>
                @endforeach
            </select>
            <label class="wadunu-filters__check">
                <input type="checkbox" name="coup_de_coeur" value="1" @checked(!empty($filters['coup_de_coeur']))>
                Coups de cœur seulement
            </label>
            <button type="submit" class="wadunu-filters__submit">Filtrer</button>
        </form>
    </section>

    <section class="wadunu-grid">
        @forelse($restos as $r)
            <a class="wadunu-card" href="{{ route('wadunu.restaurant', ['token' => $token, 'slug' => $r->slug]) }}">
                @if($r->coup_de_coeur)
                    <span class="wadunu-card__coeur" aria-label="Coup de cœur Dahomey Discovery">♥</span>
                @endif
                <div class="wadunu-card__media">
                    @if(!empty($r->photos) && count($r->photos))
                        <img src="{{ asset('storage/' . $r->photos[0]) }}" alt="" loading="lazy">
                    @else
                        <div class="wadunu-card__placeholder">
                            <span>{{ mb_substr($r->nom, 0, 1) }}</span>
                        </div>
                    @endif
                    <span class="wadunu-card__cat">{{ $categories[$r->categorie] ?? $r->categorie }}</span>
                </div>
                <div class="wadunu-card__body">
                    <span class="wadunu-card__ville">{{ $r->ville }}@if($r->quartier) · {{ $r->quartier }}@endif</span>
                    <h2 class="wadunu-card__nom">{{ $r->nom }}</h2>
                    <p class="wadunu-card__sub">{{ $r->sous_titre }}</p>
                    <div class="wadunu-card__meta">
                        <span>{{ $r->budget_label }}</span>
                    </div>
                </div>
            </a>
        @empty
            <div class="wadunu-empty">
                <p>Aucun restaurant ne correspond à ces filtres. Essayez d'élargir votre recherche.</p>
                <a href="{{ route('wadunu.catalog', $token) }}">Réinitialiser les filtres</a>
            </div>
        @endforelse
    </section>

    <footer class="wadunu-footer">
        <p>Édité par <strong>Dahomey Discovery</strong> · Mise à jour continue · Une question ? <a href="https://wa.me/2290167110220" target="_blank" rel="noopener noreferrer">WhatsApp</a></p>
    </footer>
</div>
@endsection
