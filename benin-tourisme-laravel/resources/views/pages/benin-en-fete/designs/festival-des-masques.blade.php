{{-- Festival International des Masques — « La galerie ».
     Fiche muséale : mur clair, photo encadrée et cartel, salles d'exposition pour les
     quatre familles de masques, schéma de la distance à respecter, week-end en trois jours. --}}
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,500&display=swap">
<style>
.fm{--bf-bg:#f3ebe0;--bf-ink:#24170f;--bf-soft:#6e5a4b;--bf-surface:#fbf7f1;--bf-accent:#a2582a;--terre:#a2582a;--ocre:#d89a4b;--nuit:#1d130d}
.fm .fm-s{font-family:"Playfair Display",Georgia,serif}
.fm .fm-hero{padding:150px 0 90px}
.fm .fm-hero__grid{display:grid;grid-template-columns:1.25fr .75fr;gap:clamp(30px,5vw,80px);align-items:center}
.fm .fm-cadre{background:#fff;padding:clamp(14px,2vw,26px);border-radius:6px;box-shadow:0 40px 80px -40px rgba(36,23,15,.55),0 2px 0 rgba(36,23,15,.05)}
.fm .fm-cadre img{width:100%;aspect-ratio:16/10;object-fit:cover;border-radius:2px}
.fm .fm-cartel{background:#fff;padding:30px 30px 26px;border-radius:4px;box-shadow:0 18px 40px -30px rgba(36,23,15,.5)}
.fm .fm-cartel small{display:block;font-size:.72rem;letter-spacing:.2em;text-transform:uppercase;color:var(--terre);font-weight:700}
.fm .fm-cartel h1{font-family:"Playfair Display",serif;font-size:clamp(2.2rem,4vw,3.4rem);line-height:1.02;margin:14px 0 6px;font-weight:600}
.fm .fm-cartel em{display:block;font-family:"Playfair Display",serif;font-size:1.1rem;color:var(--bf-soft);margin-bottom:18px}
.fm .fm-cartel dl{display:grid;grid-template-columns:auto 1fr;gap:6px 16px;font-size:.88rem;margin:18px 0}
.fm .fm-cartel dt{color:var(--bf-soft)}
.fm .fm-cartel dd{font-weight:600}
.fm .fm-cartel p{font-size:.92rem;color:var(--bf-soft)}
.fm .fm-intro{background:var(--nuit);color:#f3ebe0}
.fm .fm-intro__q{font-family:"Playfair Display",serif;font-style:italic;font-size:clamp(1.6rem,3.2vw,2.6rem);line-height:1.3;max-width:34ch}
.fm .fm-chiffres{display:grid;grid-template-columns:repeat(4,1fr);gap:0;margin-top:60px;background:rgba(255,255,255,.05);border-radius:24px;overflow:hidden}
.fm .fm-chiffres div{padding:28px 24px}
.fm .fm-chiffres div+div{box-shadow:-1px 0 0 rgba(255,255,255,.08)}
.fm .fm-chiffres b{display:block;font-family:"Playfair Display",serif;font-size:clamp(2.2rem,4vw,3.2rem);color:var(--ocre);line-height:1;font-weight:600}
.fm .fm-chiffres span{font-size:.84rem;opacity:.75}
.fm .fm-salles{display:grid;gap:clamp(40px,6vw,90px);margin-top:60px}
.fm .fm-salle{display:grid;grid-template-columns:.8fr 1.2fr;gap:clamp(24px,5vw,70px);align-items:center}
.fm .fm-salle:nth-child(even){grid-template-columns:1.2fr .8fr}
.fm .fm-salle:nth-child(even) .fm-salle__img{order:2}
.fm .fm-salle__img{background:#fff;padding:14px;border-radius:4px;box-shadow:0 30px 60px -36px rgba(36,23,15,.6)}
.fm .fm-salle__img img{width:100%;aspect-ratio:4/5;object-fit:cover}
.fm .fm-salle__n{font-family:"Playfair Display",serif;font-size:clamp(4rem,8vw,7rem);line-height:.8;color:rgba(162,88,42,.18)}
.fm .fm-salle h3{font-family:"Playfair Display",serif;font-size:clamp(2rem,4vw,3.2rem);font-weight:600;margin:-10px 0 16px}
.fm .fm-salle p{font-size:1.05rem;color:var(--bf-soft);max-width:48ch}
.fm .fm-h2{font-family:"Playfair Display",serif;font-size:clamp(2.2rem,4.4vw,3.6rem);line-height:1.05;font-weight:600;margin:12px 0 16px}
.fm .fm-regles{display:grid;grid-template-columns:.9fr 1.1fr;gap:clamp(30px,5vw,80px);align-items:center}
.fm .fm-cercle{position:relative;aspect-ratio:1;max-width:440px;width:100%;margin:0 auto}
.fm .fm-cercle i{position:absolute;inset:0;border-radius:50%;background:rgba(162,88,42,.07)}
.fm .fm-cercle i:nth-child(2){inset:16%;background:rgba(162,88,42,.12)}
.fm .fm-cercle i:nth-child(3){inset:34%;background:var(--terre)}
.fm .fm-cercle span{position:absolute;left:50%;top:50%;transform:translate(-50%,-50%);color:#fff;font-family:"Playfair Display",serif;font-size:1.1rem;text-align:center;line-height:1.2}
.fm .fm-cercle b{position:absolute;left:50%;top:6%;transform:translateX(-50%);font-size:.78rem;letter-spacing:.16em;text-transform:uppercase;color:var(--terre)}
.fm .fm-cercle em{position:absolute;left:50%;top:20%;transform:translateX(-50%);font-style:normal;font-size:.78rem;color:var(--bf-soft)}
.fm .fm-regles ol{display:grid;gap:12px;counter-reset:r}
.fm .fm-regles li{display:grid;grid-template-columns:auto 1fr;gap:18px;background:var(--bf-surface);border-radius:20px;padding:20px 22px;align-items:start}
.fm .fm-regles li span{display:grid;place-items:center;width:44px;height:44px;border-radius:50%;background:#fff;color:var(--terre)}
.fm .fm-regles li span svg{width:22px;height:22px}
.fm .fm-regles h3{font-size:.95rem;font-weight:700;margin-bottom:2px}
.fm .fm-regles p{color:var(--bf-soft);font-size:.93rem}
.fm .fm-week{display:grid;grid-template-columns:repeat(3,1fr);gap:18px;margin-top:44px}
.fm .fm-jour{background:#fff;border-radius:26px;padding:28px;box-shadow:0 20px 44px -34px rgba(36,23,15,.55)}
.fm .fm-jour>h3{font-family:"Playfair Display",serif;font-size:1.9rem;font-weight:600;margin-bottom:18px;color:var(--terre)}
.fm .fm-moment+.fm-moment{margin-top:20px;padding-top:20px;box-shadow:0 -1px 0 rgba(36,23,15,.08)}
.fm .fm-moment small{display:inline-block;font-size:.72rem;letter-spacing:.14em;text-transform:uppercase;font-weight:700;color:var(--bf-soft)}
.fm .fm-moment h4{font-size:1.08rem;font-weight:700;margin:4px 0}
.fm .fm-moment em{font-style:normal;font-size:.84rem;color:var(--terre);font-weight:600}
.fm .fm-moment p{color:var(--bf-soft);font-size:.93rem;margin-top:6px}
.fm .fm-mur{display:grid;grid-template-columns:repeat(4,1fr);gap:22px;margin-top:44px;align-items:start}
.fm .fm-oeuvre:nth-child(even){margin-top:60px}
.fm .fm-oeuvre figure{background:#fff;padding:10px;border-radius:3px;box-shadow:0 26px 50px -34px rgba(36,23,15,.6)}
.fm .fm-oeuvre img{width:100%;aspect-ratio:3/4;object-fit:cover}
.fm .fm-oeuvre h3{font-family:"Playfair Display",serif;font-size:1.3rem;font-weight:600;margin:16px 0 4px}
.fm .fm-oeuvre p{color:var(--bf-soft);font-size:.9rem}
.fm .fm-amb{position:relative;border-radius:32px;overflow:hidden;color:#fff;isolation:isolate;min-height:560px;display:grid;align-items:end}
.fm .fm-amb>img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;z-index:-2}
.fm .fm-amb::after{content:"";position:absolute;inset:0;z-index:-1;background:linear-gradient(90deg,rgba(29,19,13,.92),rgba(29,19,13,.55) 55%,rgba(29,19,13,.15))}
.fm .fm-amb__in{padding:clamp(30px,6vw,72px);max-width:680px}
.fm .fm-amb h2{font-family:"Playfair Display",serif;font-size:clamp(2rem,3.8vw,3rem);line-height:1.1;font-weight:600;margin:12px 0 16px}
.fm .fm-amb ul{display:flex;flex-wrap:wrap;gap:8px;margin-top:22px}
.fm .fm-amb li{background:rgba(255,255,255,.14);backdrop-filter:blur(6px);padding:9px 14px;border-radius:999px;font-size:.86rem}
.fm .fm-amb .bf-k{color:var(--ocre)}
.fm .fm-thumbs{display:flex;gap:10px;margin-top:22px}
.fm .fm-thumbs img{width:110px;height:76px;object-fit:cover;border-radius:12px}
.fm .fm-moments{display:grid;grid-template-columns:repeat(5,1fr);gap:14px;margin-top:40px}
.fm .fm-mom{background:var(--bf-surface);border-radius:22px;padding:22px}
.fm .fm-mom b{font-family:"Playfair Display",serif;font-size:2rem;color:var(--terre);display:block;line-height:1;margin-bottom:10px}
.fm .fm-mom small{font-size:.72rem;letter-spacing:.12em;text-transform:uppercase;color:var(--bf-soft)}
.fm .fm-mom h4{font-weight:700;margin:4px 0 6px;font-size:1rem}
.fm .fm-mom p{font-size:.87rem;color:var(--bf-soft)}
.fm .fm-hist{display:grid;grid-template-columns:1fr 1fr;gap:clamp(30px,6vw,90px)}
.fm .fm-hist p{color:var(--bf-soft);margin-bottom:16px;font-size:1.03rem}
.fm .fm-prat{display:grid;grid-template-columns:1fr 1fr;gap:22px}
.fm .fm-box{background:#fff;border-radius:28px;padding:clamp(26px,4vw,42px)}
.fm .fm-box h2{font-family:"Playfair Display",serif;font-size:1.9rem;font-weight:600;margin:10px 0 18px}
.fm .fm-box li{display:grid;grid-template-columns:auto 1fr;gap:14px;padding:12px 0}
.fm .fm-box li+li{box-shadow:0 -1px 0 rgba(36,23,15,.08)}
.fm .fm-box li span{color:var(--terre);width:22px}.fm .fm-box li span svg{width:22px;height:22px}
.fm .fm-box b{display:block;margin-bottom:2px}
.fm .fm-box p{color:var(--bf-soft);font-size:.94rem}
@media (max-width:900px){
 .fm .fm-hero{padding:120px 0 60px}
 .fm .fm-hero__grid,.fm .fm-salle,.fm .fm-salle:nth-child(even),.fm .fm-regles,.fm .fm-hist,.fm .fm-prat{grid-template-columns:1fr}
 .fm .fm-salle:nth-child(even) .fm-salle__img{order:0}
 .fm .fm-chiffres{grid-template-columns:1fr 1fr}
 .fm .fm-week{grid-template-columns:1fr}
 .fm .fm-mur{grid-template-columns:1fr 1fr}
 .fm .fm-oeuvre:nth-child(even){margin-top:30px}
 .fm .fm-moments{grid-template-columns:1fr 1fr}
}
</style>
@php
    $fmImages = ['gèlèdè' => '/assets/img/gelede-640x854.webp', 'egungun' => '/assets/img/egungun-640x640.webp', 'zangbéto' => '/assets/img/zangbeto-640x854.webp', 'kaléta' => '/assets/img/danse-640x854.webp'];
    $fmJours = [];
    foreach ($event['programme'] as $p) { $fmJours[strtok($p['jour'], ' ')][] = $p; }
    $romain = ['I', 'II', 'III', 'IV', 'V', 'VI'];
@endphp

<div class="bf fm">
    <header class="fm-hero">
        <div class="bf-in fm-hero__grid">
            <div class="fm-cadre bf-rv on"><img src="{{ $event['hero'] }}" alt="{{ $event['title'] }}" fetchpriority="high" decoding="async"></div>
            <div class="fm-cartel bf-rv on" style="--d:150ms">
                <small>Bénin en fête · exposition vivante</small>
                <h1>{{ $event['title'] }}</h1>
                <em>{{ $event['subtitle'] }}</em>
                <dl><dt>Lieu</dt><dd>{{ $event['lieu'] }}</dd><dt>Dates</dt><dd>{{ $event['dates'] }}</dd></dl>
                <p>{{ $event['description'] }}</p>
            </div>
        </div>
    </header>
    @include('pages.benin-en-fete.designs._crumb')

    <section class="fm-intro bf-sec">
        <div class="bf-in">
            <span class="bf-k bf-rv" style="color:var(--ocre)">Le festival</span>
            <p class="fm-intro__q bf-rv" style="margin-top:16px">{{ $event['chapo'] }}</p>
            @if (! empty($event['stats']))
            <div class="fm-chiffres bf-rv">
                @foreach ($event['stats'] as $s)<div><b data-bf-count>{{ $s['nb'] }}</b><span>{{ $s['label'] }}</span></div>@endforeach
            </div>
            @endif
        </div>
    </section>

    @if (! empty($event['signature']['items']))
    <section class="bf-sec" aria-label="{{ $event['signature']['titre'] }}">
        <div class="bf-in">
            @if (! empty($event['signature']['eyebrow']))<span class="bf-k bf-rv">{{ $event['signature']['eyebrow'] }}</span>@endif
            <h2 class="fm-h2 bf-rv">{{ $event['signature']['titre'] }}</h2>
            @if (! empty($event['signature']['lead']))<p class="bf-lead bf-rv">{{ $event['signature']['lead'] }}</p>@endif
            <div class="fm-salles">
                @foreach ($event['signature']['items'] as $i => $it)
                    <article class="fm-salle bf-rv">
                        <figure class="fm-salle__img"><img src="{{ $fmImages[mb_strtolower($it['t'])] ?? '/assets/img/gelede-640x854.webp' }}" alt="{{ $it['t'] }}" loading="lazy" decoding="async"></figure>
                        <div><span class="fm-salle__n" aria-hidden="true">{{ $romain[$i] ?? $i + 1 }}</span><span class="bf-k" style="display:block">Salle {{ $romain[$i] ?? $i + 1 }}</span><h3>{{ $it['t'] }}</h3><p>{{ $it['d'] }}</p></div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @if (! empty($event['tips']))
    <section class="bf-sec" style="padding-top:0">
        <div class="bf-in fm-regles">
            <div class="fm-cercle bf-rv" aria-hidden="true"><i></i><i></i><i></i><b>Zone des percussions</b><em>3 à 4 mètres</em><span>Le<br>masque</span></div>
            <div>
                <span class="bf-k bf-rv">{{ $T('tips', 'eyebrow', 'Bon à savoir') }}</span>
                <h2 class="fm-h2 bf-rv">{{ $T('tips', 'titre', 'Les conseils pratiques') }}</h2>
                <p class="bf-lead bf-rv" style="margin-bottom:26px">{{ $T('tips', 'lead', 'Ces règles évitent les faux pas et rendent la visite plus fluide.') }}</p>
                <ol>
                    @foreach ($event['tips'] as $i => $tip)
                        <li class="bf-rv" style="--d: {{ $i * 60 }}ms"><span aria-hidden="true">{!! $icon($tip['label']) !!}</span><div><h3>{{ $tip['label'] }}</h3><p>{{ $tip['texte'] }}</p></div></li>
                    @endforeach
                </ol>
            </div>
        </div>
    </section>
    @endif

    <section class="bf-sec" style="padding-top:0">
        <div class="bf-in">
            <div class="fm-hist">
                @foreach ($event['blocks'] as $i => $b)
                    @if ($i < 2)
                    <div class="bf-rv"><span class="bf-k">{{ $romain[$i] }}</span><h3 class="fm-s" style="font-size:1.9rem;font-weight:600;margin:8px 0 12px">{{ $b['titre'] }}</h3><p>{{ $b['texte'] }}</p></div>
                    @endif
                @endforeach
            </div>
        </div>
    </section>

    <section class="bf-sec" id="programme" style="padding-top:0">
        <div class="bf-in">
            <span class="bf-k bf-rv">{{ $T('programme', 'eyebrow', 'Le déroulé') }}</span>
            <h2 class="fm-h2 bf-rv">{{ $T('programme', 'titre', 'Programme type') }}</h2>
            <p class="bf-lead bf-rv">{{ $T('programme', 'lead', 'Un aperçu de ce qui vous attend jour après jour. Le programme officiel varie chaque année — vérifiez la date exacte sur le site du festival.') }}</p>
            <div class="fm-week">
                @foreach ($fmJours as $jour => $moments)
                    <div class="fm-jour bf-rv" style="--d: {{ $loop->index * 100 }}ms">
                        <h3>{{ $jour }}</h3>
                        @foreach ($moments as $p)
                            <div class="fm-moment"><small>{{ $p['jour'] }}</small><h4>{{ $p['titre'] }}</h4><em>{{ $p['lieu'] }}</em><p>{{ $p['texte'] }}</p></div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    @if (! empty($event['lieux']))
    <section class="bf-sec" style="padding-top:0">
        <div class="bf-in">
            <span class="bf-k bf-rv">{{ $T('lieux', 'eyebrow', 'Les scènes') }}</span>
            <h2 class="fm-h2 bf-rv">{{ $T('lieux', 'titre', 'Les lieux emblématiques') }}</h2>
            <p class="bf-lead bf-rv">{{ $T('lieux', 'lead', 'Chaque événement respire dans des lieux précis. Les connaître, c\'est déjà commencer à comprendre le déroulé.') }}</p>
            <div class="fm-mur">
                @foreach ($event['lieux'] as $i => $l)
                    <article class="fm-oeuvre bf-rv" style="--d: {{ $i * 90 }}ms"><figure><img src="{{ $l['photo'] }}" alt="{{ $l['nom'] }}" loading="lazy" decoding="async"></figure><h3>{{ $l['nom'] }}</h3><p>{{ $l['desc'] }}</p></article>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @if (! empty($event['ambiance']))
    <section class="bf-sec" style="padding-top:0">
        <div class="bf-in">
            <div class="fm-amb bf-rv">
                <img src="{{ $event['ambiance']['photos'][0] ?? $event['hero'] }}" alt="" loading="lazy" decoding="async">
                <div class="fm-amb__in">
                    <span class="bf-k">{{ $T('ambiance', 'eyebrow', 'L\'ambiance') }}</span>
                    <h2>{{ $event['ambiance']['titre'] }}</h2>
                    <p style="opacity:.88">{{ $event['ambiance']['texte'] }}</p>
                    <ul>@foreach ($event['ambiance']['points'] as $p)<li>{{ $p }}</li>@endforeach</ul>
                    <div class="fm-thumbs">@foreach (array_slice($event['ambiance']['photos'], 1) as $ph)<img src="{{ $ph }}" alt="" loading="lazy" decoding="async">@endforeach</div>
                </div>
            </div>
        </div>
    </section>
    @endif

    @if (! empty($event['tempsForts']))
    <section class="bf-sec" style="padding-top:0">
        <div class="bf-in">
            <span class="bf-k bf-rv">{{ $T('tempsForts', 'eyebrow', 'Les vagues de programmation') }}</span>
            <h2 class="fm-h2 bf-rv">{{ $T('tempsForts', 'titre', 'Les temps forts') }}</h2>
            <p class="bf-lead bf-rv">{{ $T('tempsForts', 'lead', 'Chaque édition suit un fil narratif similaire — voici les cinq vagues qui structurent l\'expérience.') }}</p>
            <div class="fm-moments">
                @foreach ($event['tempsForts'] as $i => $v)
                    <div class="fm-mom bf-rv" style="--d: {{ $i * 70 }}ms"><b>{{ $romain[$i] ?? $i + 1 }}</b><small>{{ $v['sous'] }}</small><h4>{{ $v['titre'] }}</h4><p>{{ $v['texte'] }}</p></div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <section class="bf-sec" style="padding-top:0">
        <div class="bf-in fm-hist">
            @if (! empty($event['histoire']))
            <div class="bf-rv"><span class="bf-k">{{ $T('histoire', 'eyebrow', 'L\'origine') }}</span><h2 class="fm-h2">{{ $event['histoire']['titre'] }}</h2>@foreach ($event['histoire']['paragraphs'] as $p)<p>{{ $p }}</p>@endforeach</div>
            @endif
            @if (isset($event['blocks'][2]))
            <div class="bf-rv" style="--d:120ms;background:#fff;border-radius:28px;padding:clamp(26px,4vw,42px);align-self:start"><span class="bf-k">{{ $romain[2] }}</span><h3 class="fm-s" style="font-size:1.9rem;font-weight:600;margin:8px 0 12px">{{ $event['blocks'][2]['titre'] }}</h3><p>{{ $event['blocks'][2]['texte'] }}</p></div>
            @endif
        </div>
    </section>

    <section class="bf-sec" style="padding-top:0">
        <div class="bf-in fm-prat">
            @if (! empty($event['surPlace']))
            <div class="fm-box bf-rv">
                <span class="bf-k">{{ $T('surPlace', 'eyebrow', 'Repères pratiques') }}</span>
                <h2>{{ $event['surPlace']['titre'] }}</h2>
                <ul>@foreach ($event['surPlace']['points'] as $s)<li><span aria-hidden="true">{!! $icon($s['label']) !!}</span><div><b>{{ $s['label'] }}</b><p>{{ $s['texte'] }}</p></div></li>@endforeach</ul>
            </div>
            @endif
            @if (! empty($event['autour']))
            <div class="fm-box bf-rv" style="--d:120ms">
                <span class="bf-k">{{ $T('autour', 'eyebrow', 'À faire à côté') }}</span>
                <h2>{{ $event['autour']['titre'] }}</h2>
                <p style="color:var(--bf-soft);margin:-6px 0 6px">{{ $T('autour', 'lead', 'Étirez votre séjour d\'une journée ou deux — voici les visites qui prolongent l\'expérience.') }}</p>
                <ul>@foreach ($event['autour']['points'] as $i => $a)<li><span aria-hidden="true" class="fm-s" style="font-size:1.3rem;line-height:1">{{ $romain[$i] ?? $i + 1 }}</span><div><b>{{ $a['t'] }}</b><p>{{ $a['d'] }}</p></div></li>@endforeach</ul>
            </div>
            @endif
        </div>
    </section>

    @if (! empty($event['faq']))
    <section class="bf-sec" style="padding-top:0">
        <div class="bf-in">
            <span class="bf-k bf-rv">{{ $T('faq', 'eyebrow', 'Vous nous demandez souvent') }}</span>
            <h2 class="fm-h2 bf-rv" style="margin-bottom:30px">{{ $T('faq', 'titre', 'Les questions fréquentes') }}</h2>
            @include('pages.benin-en-fete.designs._faq')
        </div>
    </section>
    @endif

    @if (! empty($event['combinerAvec']))
    <section class="bf-sec" style="padding-top:0">
        <div class="bf-in">
            <span class="bf-k bf-rv">{{ $T('combine', 'eyebrow', 'Prolonger le voyage') }}</span>
            <h2 class="fm-h2 bf-rv">{{ $T('combine', 'titre', 'À combiner avec') }}</h2>
            <p class="bf-lead bf-rv" style="margin-bottom:32px">{{ $T('combine', 'lead', 'Ces rendez-vous s\'inscrivent dans la même saison ou la même thématique. Ils gagnent à être vécus dans le même voyage.') }}</p>
            @include('pages.benin-en-fete.designs._combine')
        </div>
    </section>
    @endif

    <section class="bf-sec" style="padding-top:0">
        <div class="bf-in">@include('pages.benin-en-fete.designs._cta', ['ctaImage' => '/assets/img/gelede-1920x960.webp'])</div>
    </section>
</div>
