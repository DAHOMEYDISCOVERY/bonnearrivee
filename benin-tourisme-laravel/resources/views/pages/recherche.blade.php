{{-- Page "Recherche" : affiche les resultats groupes par source
     (restaurants, pages, evenements). GET /recherche?q=... --}}
@extends('layouts.app')

@section('bodyClass', 'page menus-v2 prod gabarit-basic_page benin-recherche-page')
@section('mainAttributes')role="main" class="content-type-page basic_page" id="main-content"@endsection

@section('content')
<div class="content-wrapper no-terms">

    <section class="ba-page-hero" aria-label="Recherche">
        <img src="/assets/img/aeroport-1920x960.webp" alt="" loading="eager" fetchpriority="high">
        <div class="ba-page-hero__overlay">
            <nav class="ba-page-hero__breadcrumb" aria-label="Fil d'ariane">
                <ol>
                    <li><a href="/">Accueil</a></li>
                    <li aria-hidden="true">/</li>
                    <li aria-current="page">Recherche</li>
                </ol>
            </nav>
            <div class="ba-page-hero__center">
                <p class="ba-page-hero__eyebrow">Trouvez votre adresse</p>
                <h1 class="ba-page-hero__title">Recherche</h1>
                <p class="ba-page-hero__lead">Un restaurant, un événement, une page pratique — tapez quelques lettres.</p>
            </div>
        </div>
    </section>

    <section class="ba-search">
        <div class="ba-search__inner">
            <form class="ba-search__form" method="get" action="/recherche" role="search">
                <label for="ba-search-q" class="sr-only">Que recherchez-vous&nbsp;?</label>
                <input
                    type="search"
                    id="ba-search-q"
                    name="q"
                    value="{{ $q }}"
                    placeholder="Un restaurant, un lieu, un événement…"
                    autocomplete="off"
                    autofocus
                    minlength="2"
                    required>
                <button type="submit">Rechercher</button>
            </form>

            @if ($q === '')
                <p class="ba-search__hint">Essayez par exemple&nbsp;: <a href="/recherche?q=cotonou">Cotonou</a>, <a href="/recherche?q=vodun">Vodun</a>, <a href="/recherche?q=maquis">maquis</a>.</p>
            @elseif ($total === 0)
                <p class="ba-search__empty">Aucun résultat pour <strong>«&nbsp;{{ $q }}&nbsp;»</strong>. Essayez un autre mot-clé.</p>
            @else
                <p class="ba-search__count">{{ $total }} résultat{{ $total > 1 ? 's' : '' }} pour <strong>«&nbsp;{{ $q }}&nbsp;»</strong>.</p>
            @endif

            @foreach ([
                'restaurants' => ['Restaurants', 'wicon-071-couverts'],
                'evenements'  => ['Événements', 'wicon-032-calendrier'],
                'pages'       => ['Pages', 'wicon-073-article'],
            ] as $key => $meta)
                @php([$label, $icon] = $meta)
                @if (!empty($results[$key]))
                    <div class="ba-search__group">
                        <h2 class="ba-search__group-title">
                            <span class="wicon {{ $icon }}" aria-hidden="true"></span>
                            {{ $label }}
                            <small>({{ count($results[$key]) }})</small>
                        </h2>
                        <ul class="ba-search__list">
                            @foreach ($results[$key] as $hit)
                                <li class="ba-search__item">
                                    <a href="{{ $hit['url'] }}">
                                        <div class="ba-search__item-head">
                                            <span class="ba-search__item-title">{{ $hit['title'] }}</span>
                                            @if (!empty($hit['badge']))
                                                <span class="ba-search__item-badge">{{ $hit['badge'] }}</span>
                                            @endif
                                        </div>
                                        @if (!empty($hit['subtitle']))
                                            <span class="ba-search__item-sub">{{ $hit['subtitle'] }}</span>
                                        @endif
                                        @if (!empty($hit['excerpt']))
                                            <p class="ba-search__item-excerpt">{{ $hit['excerpt'] }}</p>
                                        @endif
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            @endforeach
        </div>
    </section>

</div>

<style>
.ba-search { padding: clamp(2rem, 4vw, 4rem) 0; background: #fafaf7; }
.ba-search__inner { max-width: 880px; margin: 0 auto; padding: 0 1.25rem; }
.ba-search__form { display: flex; gap: .5rem; margin-bottom: 1.75rem; }
.ba-search__form input[type="search"] {
    flex: 1 1 auto; padding: .9rem 1.1rem; border: 1px solid #d9d9d2;
    border-radius: 999px; font-size: 1rem; background: #fff;
}
.ba-search__form input[type="search"]:focus { outline: 2px solid #008751; outline-offset: 2px; }
.ba-search__form button {
    padding: .9rem 1.5rem; border: 0; border-radius: 999px;
    background: #008751; color: #fff; font-weight: 600; cursor: pointer;
    transition: background .15s ease;
}
.ba-search__form button:hover { background: #006a3f; }
.ba-search__hint, .ba-search__empty, .ba-search__count { margin: 0 0 1.5rem; color: #555; }
.ba-search__hint a { color: #008751; text-decoration: underline; }
.ba-search__group { margin-top: 2rem; }
.ba-search__group-title {
    display: flex; align-items: center; gap: .5rem;
    font-size: 1.1rem; margin: 0 0 .75rem; color: #222;
    border-bottom: 1px solid #e9e9e2; padding-bottom: .5rem;
}
.ba-search__group-title small { color: #888; font-weight: 400; }
.ba-search__list { list-style: none; padding: 0; margin: 0; }
.ba-search__item { border-bottom: 1px solid #eee; }
.ba-search__item a {
    display: block; padding: 1rem 0; color: inherit; text-decoration: none;
    transition: background .15s ease, padding .15s ease;
}
.ba-search__item a:hover { background: #fff; padding-left: .75rem; padding-right: .75rem; }
.ba-search__item-head { display: flex; align-items: center; gap: .5rem; flex-wrap: wrap; }
.ba-search__item-title { font-weight: 600; color: #222; font-size: 1.05rem; }
.ba-search__item-badge {
    font-size: .72rem; font-weight: 600; padding: .15rem .5rem;
    background: #e8112d; color: #fff; border-radius: 999px; letter-spacing: .02em;
}
.ba-search__item-sub { display: block; color: #666; font-size: .9rem; margin-top: .15rem; }
.ba-search__item-excerpt { margin: .35rem 0 0; color: #555; font-size: .92rem; line-height: 1.5; }
.sr-only { position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0,0,0,0); border: 0; }
@media (max-width: 540px) {
    .ba-search__form { flex-direction: column; }
    .ba-search__form button { width: 100%; }
}
</style>
@endsection
