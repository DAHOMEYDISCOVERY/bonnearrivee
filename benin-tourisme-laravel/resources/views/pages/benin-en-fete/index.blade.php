{{-- Bénin en Fête — agenda des grands rendez-vous culturels du Bénin.
     Mise en page : frise de la saison, puis un chapitre par événement, chacun dans sa couleur.
     Les dates, lieux et photos viennent de resources/data/benin-events.php (+ identite). --}}
@extends('layouts.app')

@section('bodyClass', 'page menus-v2 prod gabarit-basic_page')
@section('mainAttributes')role="main" class="content-type-page basic_page" id="main-content"@endsection

@section('headGlobals')@verbatim<script>window.globals={"ancestors":{"chapter1":"Bénin en Fête","chapter2":null,"chapter3":null},"context":"website","current_lang":"fr","current_locale":"fr","current_locale_region":"fr_FR","env":"prod","languages":["fr","nl"],"page_type":"basic_page","post_id":9001,"post_title":"Bénin en Fête","post_type":"page","site_key":"benin","tags":{"places":[],"seasons":[],"themes":[],"targets":[]},"woody_options_pages":{"search_url":2043,"weather_url":0},"woody_timezone":"Europe/Paris","search":{"api":{"uri":"","args":{"size":15,"threshold":0.5}}},"sheet_bordereaux_with_dates":[],"short_date_bordereaux":["FMA","ASC"],"tmaps":{"url_api":"","url_api_lazy":""},"hawwwai":{"sheet_options":{"nearby_distance_max":5000}}}</script>@endverbatim@endsection
@section('headSchema')@verbatim<script type="application/ld+json" class="benin-schema-graph">{"@context":"https://schema.org","@type":"WebPage","url":"https://www.bonnearrivee.bj/benin-en-fete","name":"Bénin en Fête","description":"Les grands événements culturels du Bénin : Vodun Days, Festival des Masques, La Gaani, JISTNA, Weloveya."}</script>@endverbatim@endsection

@section('content')
@php
    $befEvents = require resource_path('data/benin-events.php');
    $befIds = is_file(resource_path('data/benin-events-identite.php')) ? require resource_path('data/benin-events-identite.php') : [];
    // Ordre de la saison, de décembre à août.
    $befOrdre = ['weloveya', 'vodun-days', 'festival-des-masques', 'jistna', 'la-gaani'];
    $befMois = ['Déc.', 'Janv.', 'Févr.', 'Mars', 'Avr.', 'Mai', 'Juin', 'Juil.', 'Août'];
    $befPos = ['weloveya' => 0, 'vodun-days' => 1, 'festival-des-masques' => 8, 'jistna' => 8, 'la-gaani' => 8];
    $befDesc = [
        'vodun-days' => 'Les Vodun Days, le premier rendez-vous international autour des arts, de la culture et de la spiritualité Vodun.',
    ];
    // Photos des cartes : mêmes fichiers (src, srcset, alt) que l'ancienne grille, pour que les
    // images remplacées dans l'admin (« Textes et images ») continuent de s'appliquer.
    $befImg = [
        'vodun-days' => ['/assets/img/vodun-640x480.webp', '/assets/img/vodun-640x480.webp 640w, /assets/img/vodun-1200x900.webp 1200w', 'Vodun Days — ceremonie'],
        'weloveya' => ['/assets/img/danse-640x480.webp', '/assets/img/danse-640x480.webp 640w, /assets/img/danse-1200x900.webp 1200w', 'Weloveya — danses et musique'],
        'festival-des-masques' => ['/assets/img/gelede-640x480.webp', '/assets/img/gelede-640x480.webp 640w, /assets/img/gelede-1200x675.webp 1200w', 'Festival des Masques — gelede'],
        'jistna' => ['/assets/img/jistna-ceremonie-1024x682.webp', null, 'JISTNA'],
        'la-gaani' => ['/assets/img/la-gaani-1024x682.jpg', null, 'La Gaani'],
    ];
    $befStyle = ['vodun-days' => ['#0e0b0c', '#f3ece1', '#d9a441'], 'weloveya' => ['#fcd116', '#16112b', '#ff4f2e'], 'festival-des-masques' => ['#f3ebe0', '#24170f', '#a2582a'], 'jistna' => ['#e9efd9', '#1f2a1a', '#3f7f22'], 'la-gaani' => ['#1b2140', '#f4ead7', '#c98b2b']];
@endphp
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,600;0,9..144,700;1,9..144,500&display=swap">
<link rel="stylesheet" href="/assets/bef.css?v=20261120a">
<style>
.bfx{--bf-bg:#f7f3ec;--bf-accent:#00844d}
.bfx .bfx-intro{padding:clamp(40px,6vw,70px) 0 clamp(50px,7vw,90px);background:var(--bf-bg)}
.bfx .bfx-saison{margin-top:0;background:#fff;border-radius:30px;padding:30px clamp(18px,3vw,36px) 26px;box-shadow:0 30px 60px -44px rgba(30,25,15,.5)}
.bfx .bfx-mois{display:grid;grid-template-columns:repeat(9,1fr);gap:6px}
.bfx .bfx-mois span{text-align:center;font-size:.76rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#9a938a;padding-bottom:12px}
.bfx .bfx-ligne{position:relative;height:6px;border-radius:6px;background:linear-gradient(90deg,#00844d 0 33.3%,#fcd116 33.3% 66.6%,#e8112d 66.6%)}
.bfx .bfx-pins{display:grid;grid-template-columns:repeat(9,1fr);gap:6px;margin-top:16px}
.bfx .bfx-pins div{display:flex;flex-direction:column;gap:6px;align-items:center}
.bfx .bfx-pin{display:inline-block;padding:7px 12px;border-radius:999px;font-size:.78rem;font-weight:700;text-decoration:none!important;white-space:nowrap;transition:transform .25s}
.bfx .bfx-pin:hover{transform:translateY(-2px)}
.bfx .bfx-ev{position:relative;display:grid;grid-template-columns:1.05fr .95fr;min-height:620px;color:var(--fg);background:var(--bg)}
.bfx .bfx-ev:nth-of-type(even) .bfx-ev__img{order:2}
.bfx .bfx-ev__img{position:relative;overflow:hidden}
.bfx .bfx-ev__img img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;transition:transform 1.4s cubic-bezier(.2,.7,.3,1)}
.bfx .bfx-ev:hover .bfx-ev__img img{transform:scale(1.04)}
.bfx .bfx-ev__txt{display:flex;flex-direction:column;justify-content:center;padding:clamp(40px,6vw,96px)}
.bfx .bfx-ev__n{font-family:Fraunces,serif;font-size:clamp(4rem,8vw,7rem);line-height:.8;color:var(--ac);opacity:.9}
.bfx .bfx-ev__date{display:inline-flex;gap:10px;align-items:center;margin:18px 0 8px;font-weight:700;font-size:.9rem;letter-spacing:.06em;text-transform:uppercase;color:var(--ac)}
.bfx .bfx-ev h3{font-family:Fraunces,serif;font-weight:700;font-size:clamp(2.2rem,4.4vw,3.8rem);line-height:1.02;margin:0 0 14px}
.bfx .bfx-ev__sub{font-family:Fraunces,serif;font-style:italic;font-size:1.2rem;opacity:.85;margin-bottom:16px}
.bfx .bfx-ev__desc{opacity:.82;max-width:46ch}
.bfx .bfx-ev__cta{align-self:flex-start;margin-top:30px;display:inline-flex;gap:10px;align-items:center;padding:15px 26px;border-radius:999px;background:var(--ac);color:var(--bg)!important;font-weight:700;text-decoration:none!important;transition:transform .25s}
.bfx .bfx-ev__cta:hover{transform:translateY(-2px)}
.bfx .bfx-fin{padding:clamp(70px,9vw,120px) 0}
@media (max-width:900px){
 .bfx .bfx-mois,.bfx .bfx-pins{grid-template-columns:repeat(9,minmax(64px,1fr))}
 .bfx .bfx-saison{overflow-x:auto}
 .bfx .bfx-saison>div{min-width:640px}
 .bfx .bfx-ev{grid-template-columns:1fr;min-height:0}
 .bfx .bfx-ev__img{min-height:58vw}
 .bfx .bfx-ev:nth-of-type(even) .bfx-ev__img{order:0}
}
</style>
<div class="content-wrapper no-terms">
    <div class="emerv-hero">
        <img class="emerv-hero__img"
             src="/assets/img/zangbeto-1920x960.webp"
             srcset="/assets/img/zangbeto-640x320.webp 640w, /assets/img/zangbeto-1200x600.webp 1200w, /assets/img/zangbeto-1920x960.webp 1920w"
             sizes="100vw"
             alt="Sortie de zangbéto"
             fetchpriority="high">
        <div class="emerv-hero__overlay">
            <nav class="emerv-hero__breadcrumb" aria-label="Fil d'ariane">
                <ol>
                    <li><a href="/">Accueil</a></li>
                    <li aria-hidden="true">/</li>
                    <li aria-current="page">Bénin en Fête</li>
                </ol>
            </nav>
            <div class="emerv-hero__center">
                <p class="emerv-hero__eyebrow">Les événements à découvrir au Bénin</p>
                <h1 class="emerv-hero__title">L'agenda qui rythme la destination</h1>
                <p class="emerv-hero__lead">Fêtes traditionnelles, grands rendez-vous : la saison culturelle du Bénin ne s'arrête jamais.</p>
            </div>
        </div>
    </div>

<div class="bf bfx" id="evenements">
    <section class="bfx-intro">
        <div class="bf-in">
            <div class="bfx-saison bf-rv on" aria-label="La saison des fêtes, de décembre à août">
                <div>
                    <div class="bfx-mois">@foreach ($befMois as $m)<span>{{ $m }}</span>@endforeach</div>
                    <div class="bfx-ligne" aria-hidden="true"></div>
                    <div class="bfx-pins">
                        @foreach ($befMois as $i => $m)
                            <div>
                                @foreach ($befOrdre as $slug)
                                    @if (($befPos[$slug] ?? -1) === $i && isset($befEvents[$slug]))
                                        <a class="bfx-pin" href="#fete-{{ $slug }}" style="background:{{ $befStyle[$slug][2] }};color:#fff">{{ $slug === 'festival-des-masques' ? 'Festival des Masques' : $befEvents[$slug]['title'] }}</a>
                                    @endif
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    @foreach ($befOrdre as $n => $slug)
        @continue(! isset($befEvents[$slug]))
        @php
            $e = array_replace($befEvents[$slug], array_diff_key($befIds[$slug] ?? [], ['remplace' => 1, 'ordre' => 1, 'titres' => 1, 'signature' => 1]));
            [$bg, $fg, $ac] = $befStyle[$slug];
        @endphp
        <section class="bfx-ev" id="fete-{{ $slug }}" style="--bg: {{ $bg }}; --fg: {{ $fg }}; --ac: {{ $ac }}">
            <div class="bfx-ev__img"><img src="{{ $befImg[$slug][0] ?? $e['hero'] }}" @if (! empty($befImg[$slug][1])) srcset="{{ $befImg[$slug][1] }}" sizes="(max-width: 900px) 100vw, 52vw" @endif alt="{{ $befImg[$slug][2] ?? $e['title'] }}" loading="{{ $n === 0 ? 'eager' : 'lazy' }}" decoding="async"></div>
            <div class="bfx-ev__txt bf-rv">
                <span class="bfx-ev__n" aria-hidden="true">{{ str_pad((string) ($n + 1), 2, '0', STR_PAD_LEFT) }}</span>
                <span class="bfx-ev__date">{{ $e['dates'] }} · {{ $e['lieu'] }}</span>
                <h3>{{ $slug === 'festival-des-masques' ? 'Festival des Masques' : $e['title'] }}</h3>
                <p class="bfx-ev__sub">{{ $e['subtitle'] }}</p>
                <p class="bfx-ev__desc">{{ $befDesc[$slug] ?? $e['description'] }}</p>
                <a class="bfx-ev__cta" href="/benin-en-fete/{{ $slug }}">En savoir plus <span aria-hidden="true">→</span></a>
            </div>
        </section>
    @endforeach

    <section class="bfx-fin">
        <div class="bf-in">
            <div class="bf-cta bf-rv">
                <img src="/assets/img/danse-1920x960.webp" alt="" loading="lazy" decoding="async">
                <span class="bf-k">Composer votre séjour</span>
                <h2>Caler votre voyage sur une fête du Bénin</h2>
                <p>Bonne Arrivée organise des séjours sur mesure autour de chaque rendez-vous : accueil, hébergement, transport et guide francophone.</p>
                <div class="bf-cta__btns">
                    <a class="bf-btn bf-btn--main" href="/sejourner/toutes-les-infos-pratiques/nous-contacter">Nous confier votre séjour <span aria-hidden="true">→</span></a>
                    <a class="bf-btn bf-btn--ghost" href="/agenda">Voir l'agenda complet</a>
                </div>
                <p class="bf-partner">Séjours réalisés avec notre agence partenaire Dahomey Discovery.</p>
            </div>
        </div>
    </section>
</div>
</div>
<script>
(function () {
    var rv = document.querySelectorAll('.bfx .bf-rv:not(.on)');
    if (!('IntersectionObserver' in window)) { rv.forEach(function (e) { e.classList.add('on'); }); return; }
    var o = new IntersectionObserver(function (es) { es.forEach(function (e) { if (e.isIntersecting) { e.target.classList.add('on'); o.unobserve(e.target); } }); }, { threshold: .1 });
    rv.forEach(function (e) { o.observe(e); });
})();
</script>
@endsection
