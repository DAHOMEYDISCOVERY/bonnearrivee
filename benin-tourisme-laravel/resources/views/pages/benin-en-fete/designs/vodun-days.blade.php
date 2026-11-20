{{-- Vodun Days — « La nuit des esprits ».
     Fiche sombre et cérémonielle : titre en capitales serif, sceau du 10 janvier,
     panthéon en constellation, route de la mémoire en chemin de stations. --}}
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,500&display=swap">
<style>
.vd{--bf-bg:#0e0b0c;--bf-ink:#f3ece1;--bf-soft:#b9ada0;--bf-surface:#1b1517;--bf-accent:#d8263d;--or:#d9a441;--craie:#f4efe6;background:var(--bf-bg);color:var(--bf-ink)}
.vd .vd-serif{font-family:"Cormorant Garamond",Georgia,serif}
.vd .bf-k{color:var(--or)}

.vd .vd-hero{position:relative;min-height:min(100vh,860px);display:grid;grid-template-columns:minmax(0,1fr) minmax(0,1.15fr);align-items:stretch}
.vd .vd-hero__txt{position:relative;z-index:2;display:flex;flex-direction:column;justify-content:flex-end;padding:160px clamp(20px,5vw,72px) 72px}
.vd .vd-hero h1{font-family:"Cormorant Garamond",Georgia,serif;font-weight:600;font-size:clamp(3.6rem,9vw,8.4rem);line-height:.88;letter-spacing:-.01em;margin:18px 0 26px}
.vd .vd-hero h1 em{display:block;font-style:italic;color:var(--or)}
.vd .vd-hero__lead{max-width:44ch;color:var(--bf-soft);font-size:1.05rem}
.vd .vd-hero__meta{display:flex;flex-wrap:wrap;gap:10px;margin-top:30px}
.vd .vd-hero__meta span{padding:10px 18px;border-radius:999px;background:rgba(255,255,255,.07);font-size:.88rem;font-weight:600}
.vd .vd-hero__img{position:relative;overflow:hidden;clip-path:polygon(14% 0,100% 0,100% 100%,0 100%)}
.vd .vd-hero__img img{width:100%;height:100%;object-fit:cover;filter:saturate(.85) contrast(1.05);transform:scale(1.04);animation:vdZoom 18s ease-out forwards}
.vd .vd-hero__img::after{content:"";position:absolute;inset:0;background:linear-gradient(90deg,#0e0b0c 0,rgba(14,11,12,.2) 40%,rgba(14,11,12,.1))}
@keyframes vdZoom{to{transform:scale(1)}}
.vd .vd-seal{position:absolute;z-index:3;left:calc(46% - 70px);bottom:70px;width:140px;height:140px;border-radius:50%;background:var(--bf-accent);color:#fff;display:grid;place-items:center;text-align:center;box-shadow:0 20px 60px -10px rgba(216,38,61,.6)}
.vd .vd-seal svg{position:absolute;inset:0;width:100%;height:100%;animation:vdTurn 30s linear infinite}
.vd .vd-seal b{font-family:"Cormorant Garamond",serif;font-size:2.4rem;line-height:1;display:block}
.vd .vd-seal small{font-size:.66rem;letter-spacing:.18em;text-transform:uppercase}
@keyframes vdTurn{to{transform:rotate(360deg)}}

.vd .vd-mani{background:var(--craie);color:#1c1517}
.vd .vd-mani__q{font-family:"Cormorant Garamond",serif;font-size:clamp(1.7rem,3.4vw,2.9rem);line-height:1.22;max-width:30ch;margin:18px auto 0;text-align:center;font-weight:500}
.vd .vd-mani .bf-k{display:block;text-align:center;color:var(--bf-accent)}
.vd .vd-chiffres{display:grid;grid-template-columns:repeat(4,1fr);gap:20px;margin-top:clamp(48px,6vw,80px)}
.vd .vd-chiffres div{text-align:center}
.vd .vd-chiffres b{display:block;font-family:"Cormorant Garamond",serif;font-size:clamp(2.6rem,5vw,4.2rem);line-height:1;color:var(--bf-accent);font-weight:600}
.vd .vd-chiffres span{font-size:.8rem;letter-spacing:.14em;text-transform:uppercase;color:#6b5f58}

.vd .vd-piliers{display:grid;grid-template-columns:repeat(3,1fr);gap:clamp(24px,4vw,56px);margin-top:56px}
.vd .vd-piliers article b{font-family:"Cormorant Garamond",serif;font-size:4.6rem;line-height:.8;color:rgba(28,21,23,.12);display:block}
.vd .vd-piliers h3{font-size:1.25rem;font-weight:700;margin:-18px 0 12px;position:relative}
.vd .vd-piliers p{color:#5a4d47;font-size:.97rem}

.vd .vd-pan__head{display:grid;grid-template-columns:1fr 1fr;gap:40px;align-items:end;margin-bottom:56px}
.vd .vd-pan__head h2{font-family:"Cormorant Garamond",serif;font-size:clamp(2.2rem,4.6vw,3.8rem);line-height:1.02;font-weight:600;margin-top:12px}
.vd .vd-pan{display:grid;grid-template-columns:repeat(3,1fr);gap:18px}
.vd .vd-dieu{position:relative;padding:0;overflow:hidden;border-radius:26px;background:radial-gradient(120% 90% at 0% 0%,rgba(217,164,65,.14),rgba(255,255,255,.03) 55%);transition:transform .5s cubic-bezier(.2,.7,.3,1),background .5s}
.vd .vd-dieu:hover{transform:translateY(-6px);background:radial-gradient(120% 90% at 0% 0%,rgba(216,38,61,.22),rgba(255,255,255,.04) 60%)}
.vd .vd-dieu i{display:grid;place-items:center;width:54px;height:54px;border-radius:50%;background:rgba(217,164,65,.16);color:var(--or);font-family:"Cormorant Garamond",serif;font-style:normal;font-size:1.5rem;font-weight:700;margin-bottom:22px}
.vd .vd-dieu h3{font-family:"Cormorant Garamond",serif;font-size:1.9rem;font-weight:600;margin-bottom:10px;color:#fff}
.vd .vd-dieu p{color:var(--bf-soft);font-size:.95rem}

.vd .vd-route{position:relative;margin-top:60px}
.vd .vd-route::before{content:"";position:absolute;left:50%;top:0;bottom:0;width:0;transform:translateX(-50%);border-left:2px dotted rgba(217,164,65,.45)}
.vd .vd-station{position:relative;display:grid;grid-template-columns:1fr 1fr;gap:80px;align-items:center;margin-bottom:56px}
.vd .vd-station:nth-child(even) .vd-station__img{order:2}
.vd .vd-station__img{border-radius:999px 999px 28px 28px;overflow:hidden;aspect-ratio:4/5;max-width:380px;justify-self:end;width:100%}
.vd .vd-station:nth-child(even) .vd-station__img{justify-self:start}
.vd .vd-station__img img{width:100%;height:100%;object-fit:cover}
.vd .vd-station__pin{position:absolute;left:50%;top:50%;transform:translate(-50%,-50%);width:46px;height:46px;border-radius:50%;background:var(--bf-accent);color:#fff;display:grid;place-items:center;font-weight:700;font-size:.9rem;box-shadow:0 0 0 10px rgba(216,38,61,.16)}
.vd .vd-station h3{font-family:"Cormorant Garamond",serif;font-size:2.2rem;font-weight:600;margin:6px 0 10px}
.vd .vd-station p{color:var(--bf-soft);max-width:40ch}

.vd .vd-prog{background:var(--craie);color:#1c1517}
.vd .vd-prog__rail{display:grid;grid-auto-flow:column;grid-auto-columns:minmax(250px,1fr);gap:18px;overflow-x:auto;padding:8px 2px 24px;margin-top:48px;scroll-snap-type:x mandatory}
.vd .vd-jour{scroll-snap-align:start;background:#fff;border-radius:26px;padding:28px;box-shadow:0 22px 40px -30px rgba(40,20,20,.45)}
.vd .vd-jour__d{display:inline-block;padding:7px 14px;border-radius:999px;background:#1c1517;color:#fff;font-size:.78rem;font-weight:700;letter-spacing:.06em}
.vd .vd-jour:last-child .vd-jour__d{background:var(--bf-accent)}
.vd .vd-jour h3{font-family:"Cormorant Garamond",serif;font-size:1.65rem;font-weight:700;line-height:1.1;margin:18px 0 6px}
.vd .vd-jour small{display:block;color:var(--bf-accent);font-weight:600;font-size:.82rem;margin-bottom:12px}
.vd .vd-jour p{color:#5a4d47;font-size:.94rem}
.vd .vd-vagues{display:grid;grid-template-columns:repeat(5,1fr);gap:14px;margin-top:40px}
.vd .vd-vague{background:rgba(28,21,23,.05);border-radius:20px;padding:20px}
.vd .vd-vague small{font-size:.72rem;letter-spacing:.12em;text-transform:uppercase;color:#8a7a70}
.vd .vd-vague h4{font-size:1rem;font-weight:700;margin:8px 0}
.vd .vd-vague p{font-size:.86rem;color:#5a4d47}

.vd .vd-amb{display:grid;grid-template-columns:1.05fr .95fr;gap:clamp(30px,5vw,80px);align-items:center}
.vd .vd-mosa{display:grid;grid-template-columns:1fr 1fr;grid-template-rows:220px 220px;gap:14px}
.vd .vd-mosa img{width:100%;height:100%;object-fit:cover;border-radius:22px}
.vd .vd-mosa img:first-child{grid-row:span 2}
.vd .vd-amb h2{font-family:"Cormorant Garamond",serif;font-size:clamp(2rem,3.8vw,3.1rem);line-height:1.05;font-weight:600;margin:12px 0 18px}
.vd .vd-amb ul{margin-top:24px;display:grid;gap:12px}
.vd .vd-amb li{display:flex;gap:14px;align-items:baseline;color:var(--bf-soft)}
.vd .vd-amb li::before{content:"";flex:none;width:9px;height:9px;transform:rotate(45deg) translateY(-2px);background:var(--bf-accent)}

.vd .vd-code{background:#170f11}
.vd .vd-code__grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(210px,1fr));gap:16px;margin-top:44px}
.vd .vd-regle{background:rgba(255,255,255,.04);border-radius:22px;padding:26px}
.vd .vd-regle span{display:grid;place-items:center;width:46px;height:46px;border-radius:14px;background:rgba(217,164,65,.14);color:var(--or);margin-bottom:16px}
.vd .vd-regle span svg{width:22px;height:22px}
.vd .vd-regle h3{font-size:.8rem;letter-spacing:.16em;text-transform:uppercase;color:var(--or);margin-bottom:8px;font-weight:700}
.vd .vd-regle p{color:var(--bf-soft);font-size:.94rem}

.vd .vd-orig{display:grid;grid-template-columns:.8fr 1.2fr;gap:clamp(30px,6vw,90px)}
.vd .vd-orig h2{font-family:"Cormorant Garamond",serif;font-size:clamp(2rem,4vw,3.2rem);line-height:1.05;font-weight:600;margin-top:12px}
.vd .vd-orig__txt p{color:var(--bf-soft);margin-bottom:18px;font-size:1.04rem}
.vd .vd-orig__txt p:first-child::first-letter{font-family:"Cormorant Garamond",serif;float:left;font-size:4.6rem;line-height:.8;padding:6px 12px 0 0;color:var(--or)}

.vd .vd-prat{display:grid;grid-template-columns:1fr 1fr;gap:22px}
.vd .vd-prat__box{background:var(--bf-surface);border-radius:28px;padding:clamp(26px,4vw,44px)}
.vd .vd-prat__box h2{font-family:"Cormorant Garamond",serif;font-size:2.1rem;font-weight:600;margin:10px 0 22px}
.vd .vd-prat__box li{display:grid;grid-template-columns:auto 1fr;gap:16px;padding:14px 0}
.vd .vd-prat__box li+li{box-shadow:0 -1px 0 rgba(255,255,255,.07)}
.vd .vd-prat__box li span{color:var(--or);width:24px}
.vd .vd-prat__box li span svg{width:22px;height:22px}
.vd .vd-prat__box b{display:block;margin-bottom:4px}
.vd .vd-prat__box p{color:var(--bf-soft);font-size:.94rem}
.vd .vd-head2{font-family:"Cormorant Garamond",serif;font-size:clamp(2rem,4vw,3.2rem);line-height:1.05;font-weight:600;margin:12px 0 36px}
.vd .vd-dieu__img{position:relative;aspect-ratio:4/3;overflow:hidden}
.vd .vd-dieu__img img{width:100%;height:100%;object-fit:cover;object-position:50% 30%;transition:transform 1.2s cubic-bezier(.2,.7,.3,1)}
.vd .vd-dieu__img::after{content:"";position:absolute;inset:0;background:linear-gradient(180deg,rgba(14,11,12,0) 45%,rgba(14,11,12,.85))}
.vd .vd-dieu:hover .vd-dieu__img img{transform:scale(1.06)}
.vd .vd-dieu__txt{position:relative;padding:0 30px 32px}
.vd .vd-dieu__img+.vd-dieu__txt{margin-top:-30px}
.vd .vd-dieu__txt>i{position:relative;z-index:1;background:#2a1d14!important;box-shadow:0 0 0 4px rgba(14,11,12,.6)}
.vd .vd-dieu__txt:first-child{padding-top:34px}
@media (max-width:900px){
 .vd .vd-hero{grid-template-columns:1fr;min-height:0}
 .vd .vd-hero__img{clip-path:none;height:56vh;order:-1}
 .vd .vd-hero__img::after{background:linear-gradient(180deg,rgba(14,11,12,.1),#0e0b0c)}
 .vd .vd-hero__txt{padding:0 20px 56px;margin-top:-90px}
 .vd .vd-seal{left:auto;right:20px;bottom:auto;top:calc(56vh - 70px);width:110px;height:110px}
 .vd .vd-seal b{font-size:1.8rem}
 .vd .vd-chiffres{grid-template-columns:1fr 1fr;row-gap:36px}
 .vd .vd-piliers,.vd .vd-pan,.vd .vd-pan__head,.vd .vd-amb,.vd .vd-orig,.vd .vd-prat{grid-template-columns:1fr}
 .vd .vd-vagues{grid-template-columns:1fr 1fr}
 .vd .vd-route::before{left:22px}
 .vd .vd-station{grid-template-columns:1fr;gap:18px;padding-left:64px}
 .vd .vd-station__img,.vd .vd-station:nth-child(even) .vd-station__img{order:0;justify-self:start;max-width:none;aspect-ratio:16/10;border-radius:22px}
 .vd .vd-station__pin{left:22px;top:24px}
}
</style>

<div class="bf vd">
    <header class="vd-hero">
        <div class="vd-hero__txt">
            <span class="bf-k">{{ $event['subtitle'] }}</span>
            <h1>Vodun<em>Days</em></h1>
            <p class="vd-hero__lead">{{ $event['description'] }}</p>
            <div class="vd-hero__meta"><span>{{ $event['lieu'] }}</span><span>{{ $event['dates'] }}</span></div>
        </div>
        <div class="vd-hero__img"><img src="{{ $event['hero'] }}" alt="{{ $event['title'] }}" fetchpriority="high" decoding="async"></div>
        <div class="vd-seal" aria-hidden="true">
            <svg viewBox="0 0 140 140"><defs><path id="vdc" d="M70,70 m-56,0 a56,56 0 1,1 112,0 a56,56 0 1,1 -112,0"/></defs><text font-size="9.5" letter-spacing="3.2" fill="rgba(255,255,255,.85)" font-family="Montserrat,sans-serif" font-weight="700"><textPath href="#vdc">JOURNÉE NATIONALE DU VODUN · OUIDAH · </textPath></text></svg>
            <div><b>10</b><small>janvier</small></div>
        </div>
    </header>
    @include('pages.benin-en-fete.designs._crumb')

    <section class="vd-mani bf-sec">
        <div class="bf-in">
            <span class="bf-k bf-rv">La semaine du vodun</span>
            <p class="vd-mani__q bf-rv">{{ $event['chapo'] }}</p>
            @if (! empty($event['stats']))
            <div class="vd-chiffres">
                @foreach ($event['stats'] as $i => $s)
                    <div class="bf-rv" style="--d: {{ $i * 90 }}ms"><b data-bf-count>{{ $s['nb'] }}</b><span>{{ $s['label'] }}</span></div>
                @endforeach
            </div>
            @endif
            <div class="vd-piliers">
                @foreach ($event['blocks'] as $i => $b)
                    <article class="bf-rv" style="--d: {{ $i * 110 }}ms"><b aria-hidden="true">{{ ['I', 'II', 'III', 'IV'][$i] ?? $i + 1 }}</b><h3>{{ $b['titre'] }}</h3><p>{{ $b['texte'] }}</p></article>
                @endforeach
            </div>
        </div>
    </section>

    @if (! empty($event['signature']['items']))
    <section class="bf-sec" aria-label="{{ $event['signature']['titre'] }}">
        <div class="bf-in">
            <div class="vd-pan__head">
                <div class="bf-rv">
                    @if (! empty($event['signature']['eyebrow']))<span class="bf-k">{{ $event['signature']['eyebrow'] }}</span>@endif
                    <h2>{{ $event['signature']['titre'] }}</h2>
                </div>
                @if (! empty($event['signature']['lead']))<p class="bf-lead bf-rv">{{ $event['signature']['lead'] }}</p>@endif
            </div>
            <div class="vd-pan">
                @foreach ($event['signature']['items'] as $i => $it)
                    @php($vdPhoto = '/assets/img/dieu-' . \Illuminate\Support\Str::slug($it['t']) . '-900x1125.webp')
                    <article class="vd-dieu bf-rv" style="--d: {{ ($i % 3) * 100 }}ms">
                        @if (is_file(public_path(ltrim($vdPhoto, '/'))))
                            <figure class="vd-dieu__img"><img src="{{ $vdPhoto }}" alt="{{ $it['t'] }}" loading="lazy" decoding="async"></figure>
                        @endif
                        <div class="vd-dieu__txt">
                            <i aria-hidden="true">{{ mb_substr($it['t'], 0, 1) }}</i>
                            <h3>{{ $it['t'] }}</h3>
                            <p>{{ $it['d'] }}</p>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @if (! empty($event['lieux']))
    <section class="bf-sec" aria-label="{{ $T('lieux', 'titre', 'Les lieux emblématiques') }}" style="padding-top:0">
        <div class="bf-in">
            <span class="bf-k bf-rv">{{ $T('lieux', 'eyebrow', 'Les scènes') }}</span>
            <h2 class="vd-head2 bf-rv">{{ $T('lieux', 'titre', 'Les lieux emblématiques') }}</h2>
            <p class="bf-lead bf-rv">{{ $T('lieux', 'lead', 'Chaque événement respire dans des lieux précis. Les connaître, c\'est déjà commencer à comprendre le déroulé.') }}</p>
            <div class="vd-route">
                @foreach ($event['lieux'] as $i => $l)
                    <div class="vd-station bf-rv">
                        <div class="vd-station__img"><img src="{{ $l['photo'] }}" alt="{{ $l['nom'] }}" loading="lazy" decoding="async"></div>
                        <div><span class="bf-k">Station {{ $pad($i) }}</span><h3>{{ $l['nom'] }}</h3><p>{{ $l['desc'] }}</p></div>
                        <span class="vd-station__pin" aria-hidden="true">{{ $pad($i) }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <section class="vd-prog bf-sec" id="programme">
        <div class="bf-in">
            <span class="bf-k bf-rv" style="color:var(--bf-accent)">{{ $T('programme', 'eyebrow', 'Le déroulé') }}</span>
            <h2 class="vd-head2 bf-rv" style="margin-bottom:14px">{{ $T('programme', 'titre', 'Programme type') }}</h2>
            <p class="bf-lead bf-rv" style="color:#5a4d47">{{ $T('programme', 'lead', 'Un aperçu de ce qui vous attend jour après jour. Le programme officiel varie chaque année — vérifiez la date exacte sur le site du festival.') }}</p>
            <div class="vd-prog__rail">
                @foreach ($event['programme'] as $i => $p)
                    <article class="vd-jour bf-rv" style="--d: {{ $i * 80 }}ms">
                        <span class="vd-jour__d">{{ $p['jour'] }}</span>
                        <h3>{{ $p['titre'] }}</h3>
                        <small>{{ $p['lieu'] }}</small>
                        <p>{{ $p['texte'] }}</p>
                    </article>
                @endforeach
            </div>
            @if (! empty($event['tempsForts']))
                <h3 class="vd-serif bf-rv" style="font-size:1.9rem;font-weight:600;margin-top:56px">{{ $T('tempsForts', 'titre', 'Les temps forts') }}</h3>
                <p class="bf-rv" style="color:#5a4d47;margin-top:8px;max-width:62ch">{{ $T('tempsForts', 'lead', 'Chaque édition suit un fil narratif similaire — voici les cinq vagues qui structurent l\'expérience.') }}</p>
                <div class="vd-vagues">
                    @foreach ($event['tempsForts'] as $i => $v)
                        <div class="vd-vague bf-rv" style="--d: {{ $i * 70 }}ms"><small>{{ $v['sous'] }}</small><h4>{{ $v['titre'] }}</h4><p>{{ $v['texte'] }}</p></div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    @if (! empty($event['ambiance']))
    <section class="bf-sec">
        <div class="bf-in vd-amb">
            <div class="vd-mosa bf-rv">
                @foreach ($event['ambiance']['photos'] as $ph)<img src="{{ $ph }}" alt="" loading="lazy" decoding="async">@endforeach
            </div>
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
    <section class="vd-code bf-sec">
        <div class="bf-in">
            <span class="bf-k bf-rv">{{ $T('tips', 'eyebrow', 'Bon à savoir') }}</span>
            <h2 class="vd-head2 bf-rv" style="margin-bottom:12px">{{ $T('tips', 'titre', 'Les conseils pratiques') }}</h2>
            <p class="bf-lead bf-rv">{{ $T('tips', 'lead', 'Ces règles évitent les faux pas et rendent la visite plus fluide.') }}</p>
            <div class="vd-code__grid">
                @foreach ($event['tips'] as $i => $tip)
                    <div class="vd-regle bf-rv" style="--d: {{ $i * 70 }}ms"><span aria-hidden="true">{!! $icon($tip['label']) !!}</span><h3>{{ $tip['label'] }}</h3><p>{{ $tip['texte'] }}</p></div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @if (! empty($event['histoire']))
    <section class="bf-sec">
        <div class="bf-in vd-orig">
            <div class="bf-rv"><span class="bf-k">{{ $T('histoire', 'eyebrow', 'L\'origine') }}</span><h2>{{ $event['histoire']['titre'] }}</h2></div>
            <div class="vd-orig__txt bf-rv">@foreach ($event['histoire']['paragraphs'] as $p)<p>{{ $p }}</p>@endforeach</div>
        </div>
    </section>
    @endif

    <section class="bf-sec" style="padding-top:0">
        <div class="bf-in vd-prat">
            @if (! empty($event['surPlace']))
            <div class="vd-prat__box bf-rv">
                <span class="bf-k">{{ $T('surPlace', 'eyebrow', 'Repères pratiques') }}</span>
                <h2>{{ $event['surPlace']['titre'] }}</h2>
                <ul>@foreach ($event['surPlace']['points'] as $s)<li><span aria-hidden="true">{!! $icon($s['label']) !!}</span><div><b>{{ $s['label'] }}</b><p>{{ $s['texte'] }}</p></div></li>@endforeach</ul>
            </div>
            @endif
            @if (! empty($event['autour']))
            <div class="vd-prat__box bf-rv" style="--d:120ms">
                <span class="bf-k">{{ $T('autour', 'eyebrow', 'À faire à côté') }}</span>
                <h2>{{ $event['autour']['titre'] }}</h2>
                <p style="color:var(--bf-soft);margin-top:-8px;margin-bottom:8px">{{ $T('autour', 'lead', 'Étirez votre séjour d\'une journée ou deux — voici les visites qui prolongent l\'expérience.') }}</p>
                <ul>@foreach ($event['autour']['points'] as $i => $a)<li><span aria-hidden="true" class="vd-serif" style="font-size:1.4rem;line-height:1">{{ $pad($i) }}</span><div><b>{{ $a['t'] }}</b><p>{{ $a['d'] }}</p></div></li>@endforeach</ul>
            </div>
            @endif
        </div>
    </section>

    @if (! empty($event['faq']))
    <section class="bf-sec" style="padding-top:0">
        <div class="bf-in">
            <span class="bf-k bf-rv">{{ $T('faq', 'eyebrow', 'Vous nous demandez souvent') }}</span>
            <h2 class="vd-head2 bf-rv">{{ $T('faq', 'titre', 'Les questions fréquentes') }}</h2>
            @include('pages.benin-en-fete.designs._faq')
        </div>
    </section>
    @endif

    @if (! empty($event['combinerAvec']))
    <section class="bf-sec" style="padding-top:0">
        <div class="bf-in">
            <span class="bf-k bf-rv">{{ $T('combine', 'eyebrow', 'Prolonger le voyage') }}</span>
            <h2 class="vd-head2 bf-rv" style="margin-bottom:12px">{{ $T('combine', 'titre', 'À combiner avec') }}</h2>
            <p class="bf-lead bf-rv" style="margin-bottom:36px">{{ $T('combine', 'lead', 'Ces rendez-vous s\'inscrivent dans la même saison ou la même thématique. Ils gagnent à être vécus dans le même voyage.') }}</p>
            @include('pages.benin-en-fete.designs._combine')
        </div>
    </section>
    @endif

    <section class="bf-sec" style="padding-top:0">
        <div class="bf-in">@include('pages.benin-en-fete.designs._cta', ['ctaImage' => '/assets/img/ouidah-porte-1920x960.webp'])</div>
    </section>
</div>
