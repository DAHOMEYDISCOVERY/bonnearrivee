{{-- Weloveya — « L'affiche ».
     Fiche en affiche de festival : capitales condensées, bloc-date géant, bandeau
     défilant, timetable de la nuit, tracklist de la bande-son, billets à détacher. --}}
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Anton&family=Space+Grotesk:wght@500;700&display=swap">
<style>
.wl{--bf-bg:#fff7e4;--bf-ink:#16112b;--bf-soft:#4d4766;--bf-surface:#fff;--bf-accent:#ff4f2e;--sun:#fcd116;--nuit:#16112b;--rose:#ff8fb1}
.wl .wl-d{font-family:Anton,Impact,sans-serif;font-weight:400;text-transform:uppercase;letter-spacing:.01em;line-height:.9}
.wl .wl-g{font-family:"Space Grotesk",Montserrat,sans-serif}
.wl .bf-k{color:var(--bf-accent);font-family:"Space Grotesk",sans-serif}
.wl .wl-hero{position:relative;background:var(--sun);padding:140px 0 0;overflow:hidden}
.wl .wl-hero__grid{display:grid;grid-template-columns:1.1fr .9fr;gap:clamp(24px,4vw,60px);align-items:end}
.wl .wl-hero h1{font-size:clamp(4.4rem,13vw,12.5rem);color:var(--nuit);margin:14px 0 0}
.wl .wl-hero__sub{font-family:"Space Grotesk",sans-serif;font-weight:700;font-size:clamp(1.1rem,2vw,1.5rem);margin:18px 0 22px;max-width:28ch}
.wl .wl-hero__lead{max-width:46ch;color:#3b3550}
.wl .wl-date{display:inline-flex;align-items:stretch;margin-top:34px;border-radius:22px;overflow:hidden;box-shadow:0 18px 40px -20px rgba(22,17,43,.55)}
.wl .wl-date b{background:var(--nuit);color:var(--sun);padding:16px 24px;font-size:clamp(2.2rem,4vw,3.2rem)}
.wl .wl-date span{background:#fff;padding:14px 22px;display:flex;flex-direction:column;justify-content:center;font-family:"Space Grotesk",sans-serif;font-weight:700;line-height:1.3}
.wl .wl-date small{font-weight:500;color:var(--bf-soft)}
.wl .wl-arch{position:relative;align-self:end;border-radius:999px 999px 0 0;overflow:hidden;aspect-ratio:4/5;max-height:640px;box-shadow:0 -10px 0 0 rgba(255,79,46,.0)}
.wl .wl-arch img{width:100%;height:100%;object-fit:cover}
.wl .wl-sticker{position:absolute;right:clamp(16px,4vw,60px);top:120px;width:150px;height:150px;border-radius:50%;background:var(--bf-accent);color:#fff;display:grid;place-items:center;text-align:center;transform:rotate(-12deg);font-family:"Space Grotesk",sans-serif;font-weight:700;font-size:.9rem;line-height:1.2;padding:18px;z-index:2;box-shadow:0 16px 40px -12px rgba(255,79,46,.7)}
.wl .wl-ticker{background:var(--nuit);color:var(--sun);overflow:hidden;white-space:nowrap;padding:18px 0}
.wl .wl-ticker div{display:inline-flex;gap:48px;animation:wlRoll 38s linear infinite;padding-left:48px}
.wl .wl-ticker span{font-family:Anton,sans-serif;font-size:1.6rem;text-transform:uppercase;letter-spacing:.02em}
.wl .wl-ticker span::after{content:"✺";margin-left:48px;color:var(--bf-accent)}
@keyframes wlRoll{to{transform:translateX(-50%)}}
.wl .wl-intro{display:grid;grid-template-columns:.9fr 1.1fr;gap:clamp(30px,5vw,80px);align-items:start}
.wl .wl-intro__chapo{font-family:"Space Grotesk",sans-serif;font-size:clamp(1.35rem,2.4vw,1.9rem);line-height:1.35;font-weight:500}
.wl .wl-blocs{display:grid;gap:14px}
.wl .wl-bloc{background:#fff;border-radius:24px;padding:26px 28px;display:grid;grid-template-columns:auto 1fr;gap:20px;box-shadow:0 14px 34px -28px rgba(22,17,43,.6)}
.wl .wl-bloc b{font-family:Anton,sans-serif;font-size:2.4rem;line-height:1;color:var(--bf-accent)}
.wl .wl-bloc h3{font-family:"Space Grotesk",sans-serif;font-size:1.2rem;font-weight:700;margin-bottom:6px}
.wl .wl-bloc p{color:var(--bf-soft);font-size:.95rem}
.wl .wl-chiffres{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-top:56px}
.wl .wl-chiffre{background:var(--sun);border-radius:24px;padding:26px 22px;text-align:left}
.wl .wl-chiffre:nth-child(2){background:var(--rose)}.wl .wl-chiffre:nth-child(3){background:var(--bf-accent);color:#fff}.wl .wl-chiffre:nth-child(4){background:var(--nuit);color:var(--sun)}
.wl .wl-chiffre b{display:block;font-family:Anton,sans-serif;font-size:clamp(2.4rem,4.4vw,3.6rem);line-height:1;font-weight:400}
.wl .wl-chiffre span{font-family:"Space Grotesk",sans-serif;font-weight:700;font-size:.9rem}
.wl .wl-nuit{background:linear-gradient(180deg,#ff7a3d 0%,#d9365e 22%,#4a1d6b 55%,#16112b 80%);color:#fff}
.wl .wl-nuit .bf-k{color:var(--sun)}
.wl .wl-h2{font-size:clamp(2.6rem,6vw,5rem);margin:14px 0 18px}
.wl .wl-nuit .bf-lead{color:rgba(255,255,255,.82)}
.wl .wl-tt{margin-top:48px;display:grid;gap:12px}
.wl .wl-slot{display:grid;grid-template-columns:200px 1fr 170px;gap:24px;align-items:center;padding:24px 28px;border-radius:24px;background:rgba(255,255,255,.08);backdrop-filter:blur(6px);transition:background .3s,transform .3s}
.wl .wl-slot:hover{background:rgba(255,255,255,.16);transform:translateX(6px)}
.wl .wl-slot__h{font-family:Anton,sans-serif;font-size:1.7rem;text-transform:uppercase;color:var(--sun)}
.wl .wl-slot h3{font-family:"Space Grotesk",sans-serif;font-size:1.3rem;font-weight:700;margin-bottom:4px}
.wl .wl-slot p{color:rgba(255,255,255,.78);font-size:.95rem}
.wl .wl-slot__lieu{justify-self:end;padding:9px 16px;border-radius:999px;background:var(--sun);color:var(--nuit);font-weight:700;font-size:.82rem;white-space:nowrap}
.wl .wl-vagues{display:grid;grid-template-columns:repeat(5,1fr);gap:12px;margin-top:64px}
.wl .wl-vague{position:relative;padding:22px 20px 24px;border-radius:22px;background:rgba(255,255,255,.06)}
.wl .wl-vague i{display:block;width:12px;height:12px;border-radius:50%;background:var(--sun);margin-bottom:14px;box-shadow:0 0 0 6px rgba(252,209,22,.18)}
.wl .wl-vague:nth-child(n+3) i{background:var(--rose)}.wl .wl-vague:nth-child(n+4) i{background:#9b8cff}
.wl .wl-vague small{font-size:.72rem;letter-spacing:.12em;text-transform:uppercase;opacity:.7}
.wl .wl-vague h4{font-family:"Space Grotesk",sans-serif;font-weight:700;margin:6px 0}
.wl .wl-vague p{font-size:.86rem;opacity:.78}
.wl .wl-tracks{margin-top:44px;display:grid;gap:10px}
.wl .wl-track{display:grid;grid-template-columns:64px 1fr auto;gap:22px;align-items:center;padding:20px 26px;background:#fff;border-radius:22px;box-shadow:0 12px 30px -26px rgba(22,17,43,.7)}
.wl .wl-track b{font-family:Anton,sans-serif;font-size:2rem;color:var(--bf-accent);line-height:1}
.wl .wl-track h3{font-family:"Space Grotesk",sans-serif;font-weight:700;font-size:1.15rem;margin-bottom:4px}
.wl .wl-track p{color:var(--bf-soft);font-size:.94rem}
.wl .wl-eq{display:flex;gap:4px;align-items:flex-end;height:30px}
.wl .wl-eq i{width:5px;border-radius:3px;background:var(--bf-accent);animation:wlEq 1.1s ease-in-out infinite;height:30%}
.wl .wl-eq i:nth-child(2){animation-delay:.2s}.wl .wl-eq i:nth-child(3){animation-delay:.45s}.wl .wl-eq i:nth-child(4){animation-delay:.1s}
@keyframes wlEq{50%{height:100%}}
.wl .wl-polas{display:grid;grid-template-columns:repeat(4,1fr);gap:26px;margin-top:48px}
.wl .wl-pola{background:#fff;padding:12px 12px 20px;border-radius:6px;box-shadow:0 22px 40px -24px rgba(22,17,43,.55);transform:rotate(var(--r,-2deg));transition:transform .4s}
.wl .wl-pola:nth-child(2){--r:2deg}.wl .wl-pola:nth-child(3){--r:-1deg}.wl .wl-pola:nth-child(4){--r:3deg}
.wl .wl-pola:hover{transform:rotate(0) translateY(-6px)}
.wl .wl-pola img{aspect-ratio:1;object-fit:cover;width:100%}
.wl .wl-pola h3{font-family:"Space Grotesk",sans-serif;font-weight:700;margin:14px 4px 4px}
.wl .wl-pola p{font-size:.88rem;color:var(--bf-soft);margin:0 4px}
.wl .wl-amb{display:grid;grid-template-columns:1fr 1fr;gap:clamp(30px,5vw,70px);align-items:center}
.wl .wl-amb__img{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.wl .wl-amb__img img{border-radius:24px;width:100%;height:230px;object-fit:cover}
.wl .wl-amb__img img:first-child{grid-column:span 2;height:300px}
.wl .wl-amb h2{font-family:"Space Grotesk",sans-serif;font-weight:700;font-size:clamp(1.8rem,3.2vw,2.6rem);line-height:1.1;margin:12px 0 16px}
.wl .wl-amb ul{display:grid;gap:10px;margin-top:22px}
.wl .wl-amb li{background:#fff;border-radius:16px;padding:12px 16px;font-weight:500;font-size:.95rem}
.wl .wl-tickets{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:18px;margin-top:44px}
.wl .wl-ticket{position:relative;background:var(--nuit);color:#fff;border-radius:22px;padding:26px 24px 24px;-webkit-mask:radial-gradient(circle 12px at 0 62%,#0000 98%,#000) left/51% 100% no-repeat,radial-gradient(circle 12px at 100% 62%,#0000 98%,#000) right/51% 100% no-repeat;mask:radial-gradient(circle 12px at 0 62%,#0000 98%,#000) left/51% 100% no-repeat,radial-gradient(circle 12px at 100% 62%,#0000 98%,#000) right/51% 100% no-repeat}
.wl .wl-ticket:nth-child(even){background:var(--bf-accent)}
.wl .wl-ticket span{display:inline-grid;place-items:center;width:44px;height:44px;border-radius:14px;background:rgba(255,255,255,.14);color:var(--sun);margin-bottom:16px}
.wl .wl-ticket:nth-child(even) span{color:#fff}
.wl .wl-ticket span svg{width:22px;height:22px}
.wl .wl-ticket h3{font-family:Anton,sans-serif;font-weight:400;text-transform:uppercase;font-size:1.5rem;margin-bottom:6px}
.wl .wl-ticket p{font-size:.93rem;opacity:.86}
.wl .wl-duo{display:grid;grid-template-columns:1fr 1fr;gap:22px}
.wl .wl-box{background:#fff;border-radius:28px;padding:clamp(26px,4vw,42px)}
.wl .wl-box h2{font-family:"Space Grotesk",sans-serif;font-weight:700;font-size:1.7rem;margin:10px 0 18px}
.wl .wl-box li{padding:12px 0;display:grid;grid-template-columns:auto 1fr;gap:14px}
.wl .wl-box li+li{box-shadow:0 -1px 0 rgba(22,17,43,.08)}
.wl .wl-box li span{color:var(--bf-accent);width:22px}.wl .wl-box li span svg{width:22px;height:22px}
.wl .wl-box b{display:block;margin-bottom:2px}
.wl .wl-box p{color:var(--bf-soft);font-size:.94rem}
.wl .wl-hist{background:var(--nuit);color:#fff;border-radius:32px;padding:clamp(32px,6vw,72px);display:grid;grid-template-columns:.8fr 1.2fr;gap:clamp(24px,5vw,70px)}
.wl .wl-hist h2{font-size:clamp(2.2rem,4.6vw,3.8rem);color:var(--sun);margin-top:12px}
.wl .wl-hist p{opacity:.85;margin-bottom:16px}
.wl .wl-h2b{font-size:clamp(2.4rem,5vw,4rem);margin:12px 0 28px}
.wl .wl-tickets{grid-template-columns:repeat(5,1fr)}
@media (max-width:1100px){.wl .wl-tickets{grid-template-columns:repeat(auto-fit,minmax(220px,1fr))}}
@media (max-width:900px){
 .wl .wl-hero__grid,.wl .wl-intro,.wl .wl-amb,.wl .wl-duo,.wl .wl-hist{grid-template-columns:1fr}
 .wl .wl-arch{max-height:420px;border-radius:200px 200px 0 0}
 .wl .wl-sticker{width:110px;height:110px;top:100px;font-size:.74rem}
 .wl .wl-chiffres{grid-template-columns:1fr 1fr}
 .wl .wl-slot{grid-template-columns:1fr;gap:8px}
 .wl .wl-slot__lieu{justify-self:start}
 .wl .wl-vagues{grid-template-columns:1fr 1fr}
 .wl .wl-polas{grid-template-columns:1fr 1fr}
 .wl .wl-track{grid-template-columns:48px 1fr}
 .wl .wl-eq{display:none}
}
</style>

<div class="bf wl">
    <header class="wl-hero">
        <div class="wl-sticker" aria-hidden="true">{{ $event['lieu'] }}</div>
        <div class="bf-in wl-hero__grid">
            <div style="padding-bottom:clamp(40px,6vw,80px)">
                <span class="bf-k">Bénin en fête · festival</span>
                <h1 class="wl-d">{{ $event['title'] }}</h1>
                <p class="wl-hero__sub">{{ $event['subtitle'] }}</p>
                <p class="wl-hero__lead">{{ $event['description'] }}</p>
                <div class="wl-date"><b class="wl-d">{{ $event['dates'] }}</b></div>
            </div>
            <div class="wl-arch"><img src="{{ $event['hero'] }}" alt="{{ $event['title'] }}" fetchpriority="high" decoding="async"></div>
        </div>
    </header>
    <div class="wl-ticker" aria-hidden="true"><div>
        @for ($r = 0; $r < 2; $r++)
            @foreach ($event['stats'] ?? [] as $s)<span>{{ $s['nb'] }} {{ $s['label'] }}</span>@endforeach
            @foreach ($event['signature']['items'] ?? [] as $it)<span>{{ $it['t'] }}</span>@endforeach
        @endfor
    </div></div>
    @include('pages.benin-en-fete.designs._crumb')

    <section class="bf-sec">
        <div class="bf-in">
            <div class="wl-intro">
                <div class="bf-rv"><span class="bf-k">Le festival</span><p class="wl-intro__chapo" style="margin-top:14px">{{ $event['chapo'] }}</p></div>
                <div class="wl-blocs">
                    @foreach ($event['blocks'] as $i => $b)
                        <article class="wl-bloc bf-rv" style="--d: {{ $i * 90 }}ms"><b>{{ $pad($i) }}</b><div><h3>{{ $b['titre'] }}</h3><p>{{ $b['texte'] }}</p></div></article>
                    @endforeach
                </div>
            </div>
            @if (! empty($event['stats']))
            <div class="wl-chiffres">
                @foreach ($event['stats'] as $i => $s)
                    <div class="wl-chiffre bf-rv" style="--d: {{ $i * 80 }}ms"><b data-bf-count>{{ $s['nb'] }}</b><span>{{ $s['label'] }}</span></div>
                @endforeach
            </div>
            @endif
        </div>
    </section>

    <section class="wl-nuit bf-sec" id="programme">
        <div class="bf-in">
            <span class="bf-k bf-rv">{{ $T('programme', 'eyebrow', 'Le déroulé') }}</span>
            <h2 class="wl-d wl-h2 bf-rv">{{ $T('programme', 'titre', 'Programme type') }}</h2>
            <p class="bf-lead bf-rv">{{ $T('programme', 'lead', 'Un aperçu de ce qui vous attend jour après jour. Le programme officiel varie chaque année — vérifiez la date exacte sur le site du festival.') }}</p>
            <div class="wl-tt">
                @foreach ($event['programme'] as $i => $p)
                    <article class="wl-slot bf-rv" style="--d: {{ $i * 80 }}ms">
                        <span class="wl-slot__h">{{ $p['jour'] }}</span>
                        <div><h3>{{ $p['titre'] }}</h3><p>{{ $p['texte'] }}</p></div>
                        <span class="wl-slot__lieu">{{ $p['lieu'] }}</span>
                    </article>
                @endforeach
            </div>
            @if (! empty($event['tempsForts']))
                <span class="bf-k bf-rv" style="display:block;margin-top:80px">{{ $T('tempsForts', 'eyebrow', 'Les vagues de programmation') }}</span>
                <h3 class="wl-d bf-rv" style="font-size:clamp(2rem,4vw,3.2rem);margin:12px 0 10px">{{ $T('tempsForts', 'titre', 'Les temps forts') }}</h3>
                <p class="bf-lead bf-rv">{{ $T('tempsForts', 'lead', 'Chaque édition suit un fil narratif similaire — voici les cinq vagues qui structurent l\'expérience.') }}</p>
                <div class="wl-vagues">
                    @foreach ($event['tempsForts'] as $i => $v)
                        <div class="wl-vague bf-rv" style="--d: {{ $i * 70 }}ms"><i aria-hidden="true"></i><small>{{ $v['sous'] }}</small><h4>{{ $v['titre'] }}</h4><p>{{ $v['texte'] }}</p></div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    @if (! empty($event['signature']['items']))
    <section class="bf-sec" aria-label="{{ $event['signature']['titre'] }}">
        <div class="bf-in">
            @if (! empty($event['signature']['eyebrow']))<span class="bf-k bf-rv">{{ $event['signature']['eyebrow'] }}</span>@endif
            <h2 class="wl-d wl-h2 bf-rv">{{ $event['signature']['titre'] }}</h2>
            @if (! empty($event['signature']['lead']))<p class="bf-lead bf-rv">{{ $event['signature']['lead'] }}</p>@endif
            <ol class="wl-tracks">
                @foreach ($event['signature']['items'] as $i => $it)
                    <li class="wl-track bf-rv" style="--d: {{ $i * 70 }}ms"><b>{{ $pad($i) }}</b><div><h3>{{ $it['t'] }}</h3><p>{{ $it['d'] }}</p></div><span class="wl-eq" aria-hidden="true"><i></i><i></i><i></i><i></i></span></li>
                @endforeach
            </ol>
        </div>
    </section>
    @endif

    @if (! empty($event['lieux']))
    <section class="bf-sec" style="padding-top:0">
        <div class="bf-in">
            <span class="bf-k bf-rv">{{ $T('lieux', 'eyebrow', 'Les scènes') }}</span>
            <h2 class="wl-d wl-h2 bf-rv">{{ $T('lieux', 'titre', 'Les lieux emblématiques') }}</h2>
            <p class="bf-lead bf-rv">{{ $T('lieux', 'lead', 'Chaque événement respire dans des lieux précis. Les connaître, c\'est déjà commencer à comprendre le déroulé.') }}</p>
            <div class="wl-polas">
                @foreach ($event['lieux'] as $i => $l)
                    <article class="wl-pola bf-rv" style="--d: {{ $i * 80 }}ms"><img src="{{ $l['photo'] }}" alt="{{ $l['nom'] }}" loading="lazy" decoding="async"><h3>{{ $l['nom'] }}</h3><p>{{ $l['desc'] }}</p></article>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @if (! empty($event['ambiance']))
    <section class="bf-sec" style="padding-top:0">
        <div class="bf-in wl-amb">
            <div class="wl-amb__img bf-rv">@foreach ($event['ambiance']['photos'] as $ph)<img src="{{ $ph }}" alt="" loading="lazy" decoding="async">@endforeach</div>
            <div class="bf-rv">
                <span class="bf-k">{{ $T('ambiance', 'eyebrow', 'L\'ambiance') }}</span>
                <h2>{{ $event['ambiance']['titre'] }}</h2>
                <p style="color:var(--bf-soft)">{{ $event['ambiance']['texte'] }}</p>
                <ul>@foreach ($event['ambiance']['points'] as $p)<li>{{ $p }}</li>@endforeach</ul>
            </div>
        </div>
    </section>
    @endif

    @if (! empty($event['tips']))
    <section class="bf-sec" style="padding-top:0">
        <div class="bf-in">
            <span class="bf-k bf-rv">{{ $T('tips', 'eyebrow', 'Bon à savoir') }}</span>
            <h2 class="wl-d wl-h2 bf-rv">{{ $T('tips', 'titre', 'Les conseils pratiques') }}</h2>
            <p class="bf-lead bf-rv">{{ $T('tips', 'lead', 'Ces règles évitent les faux pas et rendent la visite plus fluide.') }}</p>
            <div class="wl-tickets">
                @foreach ($event['tips'] as $i => $tip)
                    <div class="wl-ticket bf-rv" style="--d: {{ $i * 70 }}ms"><span aria-hidden="true">{!! $icon($tip['label']) !!}</span><h3>{{ $tip['label'] }}</h3><p>{{ $tip['texte'] }}</p></div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <section class="bf-sec" style="padding-top:0">
        <div class="bf-in wl-duo">
            @if (! empty($event['surPlace']))
            <div class="wl-box bf-rv">
                <span class="bf-k">{{ $T('surPlace', 'eyebrow', 'Repères pratiques') }}</span>
                <h2>{{ $event['surPlace']['titre'] }}</h2>
                <ul>@foreach ($event['surPlace']['points'] as $s)<li><span aria-hidden="true">{!! $icon($s['label']) !!}</span><div><b>{{ $s['label'] }}</b><p>{{ $s['texte'] }}</p></div></li>@endforeach</ul>
            </div>
            @endif
            @if (! empty($event['autour']))
            <div class="wl-box bf-rv" style="--d:120ms">
                <span class="bf-k">{{ $T('autour', 'eyebrow', 'À faire à côté') }}</span>
                <h2>{{ $event['autour']['titre'] }}</h2>
                <p style="color:var(--bf-soft);margin:-6px 0 6px">{{ $T('autour', 'lead', 'Étirez votre séjour d\'une journée ou deux — voici les visites qui prolongent l\'expérience.') }}</p>
                <ul>@foreach ($event['autour']['points'] as $i => $a)<li><span aria-hidden="true" class="wl-d" style="font-size:1.4rem">{{ $pad($i) }}</span><div><b>{{ $a['t'] }}</b><p>{{ $a['d'] }}</p></div></li>@endforeach</ul>
            </div>
            @endif
        </div>
    </section>

    @if (! empty($event['histoire']))
    <section class="bf-sec" style="padding-top:0">
        <div class="bf-in">
            <div class="wl-hist bf-rv">
                <div><span class="bf-k" style="color:var(--rose)">{{ $T('histoire', 'eyebrow', 'L\'origine') }}</span><h2 class="wl-d">{{ $event['histoire']['titre'] }}</h2></div>
                <div>@foreach ($event['histoire']['paragraphs'] as $p)<p>{{ $p }}</p>@endforeach</div>
            </div>
        </div>
    </section>
    @endif

    @if (! empty($event['faq']))
    <section class="bf-sec" style="padding-top:0">
        <div class="bf-in">
            <span class="bf-k bf-rv">{{ $T('faq', 'eyebrow', 'Vous nous demandez souvent') }}</span>
            <h2 class="wl-d wl-h2b bf-rv">{{ $T('faq', 'titre', 'Les questions fréquentes') }}</h2>
            @include('pages.benin-en-fete.designs._faq')
        </div>
    </section>
    @endif

    @if (! empty($event['combinerAvec']))
    <section class="bf-sec" style="padding-top:0">
        <div class="bf-in">
            <span class="bf-k bf-rv">{{ $T('combine', 'eyebrow', 'Prolonger le voyage') }}</span>
            <h2 class="wl-d wl-h2b bf-rv" style="margin-bottom:10px">{{ $T('combine', 'titre', 'À combiner avec') }}</h2>
            <p class="bf-lead bf-rv" style="margin-bottom:32px">{{ $T('combine', 'lead', 'Ces rendez-vous s\'inscrivent dans la même saison ou la même thématique. Ils gagnent à être vécus dans le même voyage.') }}</p>
            @include('pages.benin-en-fete.designs._combine')
        </div>
    </section>
    @endif

    <section class="bf-sec" style="padding-top:0">
        <div class="bf-in">@include('pages.benin-en-fete.designs._cta', ['ctaImage' => '/assets/img/sunset-1200x600.webp'])</div>
    </section>
</div>
