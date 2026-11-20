{{-- La Gaani — « La chronique royale ».
     Fiche en chronique d'un royaume : capitales gravées, bandeau de motifs du Borgou,
     récit d'ouverture, lexique bariba, procession des trois jours, route vers Nikki. --}}
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600;700&family=Cormorant+Garamond:ital,wght@1,500&display=swap">
<style>
.ga{--bf-bg:#f4ead7;--bf-ink:#211a14;--bf-soft:#6b5b49;--bf-surface:#fbf5e9;--bf-accent:#b0232a;--indigo:#1b2140;--ocre:#c98b2b;--sable:#f4ead7}
.ga .ga-c{font-family:Cinzel,Georgia,serif;letter-spacing:.04em}
.ga .bf-k{color:var(--bf-accent)}
.ga .ga-hero{position:relative;min-height:min(100vh,880px);display:grid;align-items:end;color:#fff;isolation:isolate;text-align:center}
.ga .ga-hero>img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;z-index:-2}
.ga .ga-hero::after{content:"";position:absolute;inset:0;z-index:-1;background:linear-gradient(180deg,rgba(27,33,64,.35) 0%,rgba(27,33,64,.2) 35%,rgba(27,33,64,.92) 100%)}
.ga .ga-hero__in{padding:0 20px clamp(60px,9vw,110px)}
.ga .ga-hero .bf-k{color:var(--ocre);background:rgba(27,33,64,.62);backdrop-filter:blur(6px);padding:8px 16px;border-radius:999px}
.ga .ga-hero h1{font-family:Cinzel,serif;font-weight:700;font-size:clamp(3.6rem,10vw,8.6rem);line-height:.95;letter-spacing:.06em;margin:16px 0 14px}
.ga .ga-hero__sub{font-family:"Cormorant Garamond",serif;font-style:italic;font-size:clamp(1.3rem,2.4vw,1.9rem);opacity:.92}
.ga .ga-hero__meta{display:inline-flex;gap:0;margin-top:30px;border-radius:999px;overflow:hidden;background:rgba(255,255,255,.12);backdrop-filter:blur(8px)}
.ga .ga-hero__meta span{padding:12px 22px;font-weight:600;font-size:.92rem}
.ga .ga-hero__meta span+span{background:var(--ocre);color:var(--indigo)}
.ga .ga-motif{height:26px;background:var(--indigo);background-image:linear-gradient(135deg,var(--ocre) 25%,transparent 25%),linear-gradient(225deg,var(--ocre) 25%,transparent 25%),linear-gradient(315deg,var(--bf-accent) 25%,transparent 25%),linear-gradient(45deg,var(--bf-accent) 25%,transparent 25%);background-size:26px 26px;background-position:0 0,0 0,13px 13px,13px 13px;opacity:.95}
.ga .ga-chron{display:grid;grid-template-columns:.75fr 1.25fr;gap:clamp(30px,6vw,90px)}
.ga .ga-chron h2{font-family:Cinzel,serif;font-weight:600;font-size:clamp(2rem,4vw,3.2rem);line-height:1.1;margin-top:12px;color:var(--indigo)}
.ga .ga-chron__txt{columns:2;column-gap:44px}
.ga .ga-chron__txt p{margin-bottom:16px;color:var(--bf-soft);font-size:1.03rem;break-inside:avoid}
.ga .ga-chron__txt p:first-child::first-letter{font-family:Cinzel,serif;float:left;font-size:4.8rem;line-height:.82;padding:6px 12px 0 0;color:var(--bf-accent)}
.ga .ga-chapo{font-family:"Cormorant Garamond",serif;font-style:italic;font-size:clamp(1.5rem,2.8vw,2.3rem);line-height:1.3;text-align:center;max-width:32ch;margin:0 auto;color:var(--indigo)}
.ga .ga-chiffres{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-top:56px}
.ga .ga-chiffre{text-align:center;padding:30px 16px;border-radius:200px 200px 24px 24px;background:var(--indigo);color:#fff}
.ga .ga-chiffre:nth-child(even){background:var(--bf-accent)}
.ga .ga-chiffre b{display:block;font-family:Cinzel,serif;font-size:clamp(2rem,3.6vw,2.9rem);color:var(--ocre);line-height:1.1}
.ga .ga-chiffre:nth-child(even) b{color:#fff}
.ga .ga-chiffre span{font-size:.85rem;opacity:.85}
.ga .ga-lex{background:var(--indigo);color:#f4ead7}
.ga .ga-lex .bf-k{color:var(--ocre)}
.ga .ga-h2{font-family:Cinzel,serif;font-weight:600;font-size:clamp(2rem,4.2vw,3.4rem);line-height:1.08;margin:12px 0 16px}
.ga .ga-lex .bf-lead{color:rgba(244,234,215,.75)}
.ga .ga-mots{display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:18px;margin-top:48px}
.ga .ga-mot{padding:30px;border-radius:24px;background:rgba(255,255,255,.05);transition:background .4s}
.ga .ga-mot:hover{background:rgba(201,139,43,.14)}
.ga .ga-mot dt{font-family:Cinzel,serif;font-size:clamp(1.7rem,2.6vw,2.2rem);color:var(--ocre);margin-bottom:10px}
.ga .ga-mot dd{color:rgba(244,234,215,.8);font-size:.97rem}
.ga .ga-proc{position:relative;display:grid;grid-template-columns:repeat(5,1fr);gap:18px;margin-top:60px}
.ga .ga-proc::before{content:"";position:absolute;left:5%;right:5%;top:31px;height:2px;background:repeating-linear-gradient(90deg,var(--ocre) 0 10px,transparent 10px 18px)}
.ga .ga-etape{position:relative;text-align:center}
.ga .ga-etape i{position:relative;display:grid;place-items:center;width:64px;height:64px;margin:0 auto 18px;border-radius:50%;background:var(--bf-accent);color:#fff;font-family:Cinzel,serif;font-style:normal;font-size:1.3rem;font-weight:700;box-shadow:0 0 0 8px var(--sable)}
.ga .ga-etape:nth-child(3) i{background:var(--indigo);color:var(--ocre);width:76px;height:76px;margin-top:-6px}
.ga .ga-etape small{font-size:.72rem;letter-spacing:.14em;text-transform:uppercase;color:var(--bf-soft)}
.ga .ga-etape h3{font-family:Cinzel,serif;font-size:1.1rem;font-weight:700;margin:6px 0 8px;color:var(--indigo)}
.ga .ga-etape p{font-size:.9rem;color:var(--bf-soft)}
.ga .ga-amb{display:grid;grid-template-columns:1.1fr .9fr;gap:0;border-radius:32px;overflow:hidden;background:var(--bf-surface)}
.ga .ga-amb__img{position:relative;min-height:520px}
.ga .ga-amb__img img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover}
.ga .ga-amb__txt{padding:clamp(30px,5vw,60px)}
.ga .ga-amb__txt h2{font-family:Cinzel,serif;font-weight:600;font-size:clamp(1.7rem,3vw,2.4rem);line-height:1.15;margin:12px 0 16px;color:var(--indigo)}
.ga .ga-amb ul{margin-top:20px;display:grid;gap:10px}
.ga .ga-amb li{display:flex;gap:12px;align-items:baseline;color:var(--bf-soft)}
.ga .ga-amb li::before{content:"";flex:none;width:10px;height:10px;transform:rotate(45deg);background:var(--ocre)}
.ga .ga-amb__mini{display:flex;gap:10px;margin-top:24px}
.ga .ga-amb__mini img{width:120px;height:84px;object-fit:cover;border-radius:12px}
.ga .ga-lieux{display:grid;grid-template-columns:repeat(4,1fr);gap:18px;margin-top:44px}
.ga .ga-lieu{position:relative;border-radius:200px 200px 22px 22px;overflow:hidden;min-height:420px;color:#fff;isolation:isolate;display:flex;flex-direction:column;justify-content:flex-end;padding:24px}
.ga .ga-lieu img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;z-index:-2;transition:transform 1s}
.ga .ga-lieu::after{content:"";position:absolute;inset:0;z-index:-1;background:linear-gradient(180deg,transparent 40%,rgba(27,33,64,.92))}
.ga .ga-lieu:hover img{transform:scale(1.06)}
.ga .ga-lieu h3{font-family:Cinzel,serif;font-size:1.25rem;font-weight:700;margin-bottom:6px}
.ga .ga-lieu p{font-size:.9rem;opacity:.88}
.ga .ga-jours{display:grid;gap:14px;margin-top:44px}
.ga .ga-jour{display:grid;grid-template-columns:180px 1fr auto;gap:28px;align-items:center;background:#fff;border-radius:24px;padding:24px 30px}
.ga .ga-jour b{font-family:Cinzel,serif;font-size:1.4rem;color:var(--bf-accent)}
.ga .ga-jour h3{font-weight:700;font-size:1.15rem;margin-bottom:4px}
.ga .ga-jour p{color:var(--bf-soft);font-size:.94rem}
.ga .ga-jour em{font-style:normal;padding:8px 14px;border-radius:999px;background:var(--sable);font-weight:600;font-size:.82rem;white-space:nowrap}
.ga .ga-route{background:var(--indigo);color:#fff;border-radius:32px;padding:clamp(30px,5vw,60px);margin-top:20px}
.ga .ga-route .bf-k{color:var(--ocre)}
.ga .ga-route__ligne{display:grid;grid-template-columns:auto 1fr auto 1fr auto;align-items:center;gap:14px;margin:30px 0 10px}
.ga .ga-route__ligne span{font-family:Cinzel,serif;font-size:clamp(1rem,2vw,1.4rem);font-weight:700}
.ga .ga-route__ligne span:last-child{color:var(--ocre)}
.ga .ga-route__ligne i{height:2px;background:repeating-linear-gradient(90deg,rgba(255,255,255,.6) 0 8px,transparent 8px 14px)}
.ga .ga-route p{opacity:.8;max-width:70ch}
.ga .ga-blocs{display:grid;grid-template-columns:repeat(3,1fr);gap:20px}
.ga .ga-bloc{background:#fff;border-radius:26px;padding:30px}
.ga .ga-bloc b{font-family:Cinzel,serif;font-size:2.4rem;color:var(--ocre);line-height:1}
.ga .ga-bloc h3{font-family:Cinzel,serif;font-weight:700;font-size:1.25rem;margin:12px 0 10px;color:var(--indigo)}
.ga .ga-bloc p{color:var(--bf-soft);font-size:.95rem}
.ga .ga-conseils{display:grid;grid-template-columns:repeat(auto-fit,minmax(210px,1fr));gap:14px;margin-top:40px}
.ga .ga-conseil{background:var(--bf-surface);border-radius:22px;padding:24px}
.ga .ga-conseil span{display:grid;place-items:center;width:46px;height:46px;border-radius:50%;background:var(--indigo);color:var(--ocre);margin-bottom:14px}
.ga .ga-conseil span svg{width:22px;height:22px}
.ga .ga-conseil h3{font-family:Cinzel,serif;font-size:1rem;font-weight:700;margin-bottom:6px}
.ga .ga-conseil p{color:var(--bf-soft);font-size:.93rem}
.ga .ga-duo{display:grid;grid-template-columns:1fr 1fr;gap:22px}
.ga .ga-box{background:#fff;border-radius:28px;padding:clamp(26px,4vw,42px)}
.ga .ga-box h2{font-family:Cinzel,serif;font-weight:600;font-size:1.7rem;margin:10px 0 18px;color:var(--indigo)}
.ga .ga-box li{display:grid;grid-template-columns:auto 1fr;gap:14px;padding:12px 0}
.ga .ga-box li+li{box-shadow:0 -1px 0 rgba(33,26,20,.08)}
.ga .ga-box li span{color:var(--bf-accent);width:22px}.ga .ga-box li span svg{width:22px;height:22px}
.ga .ga-box b{display:block;margin-bottom:2px}
.ga .ga-box p{color:var(--bf-soft);font-size:.94rem}
@media (max-width:900px){
 .ga .ga-chron,.ga .ga-amb,.ga .ga-duo,.ga .ga-blocs{grid-template-columns:1fr}
 .ga .ga-chron__txt{columns:1}
 .ga .ga-chiffres,.ga .ga-lieux{grid-template-columns:1fr 1fr}
 .ga .ga-proc{grid-template-columns:1fr;gap:26px}
 .ga .ga-proc::before{left:31px;right:auto;top:0;bottom:0;width:2px;height:auto;background:repeating-linear-gradient(180deg,var(--ocre) 0 10px,transparent 10px 18px)}
 .ga .ga-etape{text-align:left;display:grid;grid-template-columns:64px 1fr;gap:0 18px}
 .ga .ga-etape i,.ga .ga-etape:nth-child(3) i{margin:0;grid-row:span 3;width:64px;height:64px}
 .ga .ga-amb__img{min-height:300px}
 .ga .ga-jour{grid-template-columns:1fr;gap:8px}
 .ga .ga-jour em{justify-self:start}
 .ga .ga-route__ligne span{font-size:.95rem}
}
@media (max-width:560px){.ga .ga-lieux{grid-template-columns:1fr}.ga .ga-lieu{min-height:360px}}
</style>

<div class="bf ga">
    <header class="ga-hero">
        <img src="{{ $event['hero'] }}" alt="{{ $event['title'] }}" fetchpriority="high" decoding="async">
        <div class="ga-hero__in">
            <span class="bf-k">Bénin en fête · royaume de Nikki</span>
            <h1>{{ $event['title'] }}</h1>
            <p class="ga-hero__sub">{{ $event['subtitle'] }}</p>
            <div class="ga-hero__meta"><span>{{ $event['lieu'] }}</span><span>{{ $event['dates'] }}</span></div>
        </div>
    </header>
    <div class="ga-motif" aria-hidden="true"></div>
    @include('pages.benin-en-fete.designs._crumb')

    <section class="bf-sec">
        <div class="bf-in">
            <p class="ga-chapo bf-rv">{{ $event['chapo'] }}</p>
            <p class="bf-lead bf-rv" style="text-align:center;margin:22px auto 0">{{ $event['description'] }}</p>
            @if (! empty($event['stats']))
            <div class="ga-chiffres">
                @foreach ($event['stats'] as $i => $s)<div class="ga-chiffre bf-rv" style="--d: {{ $i * 80 }}ms"><b data-bf-count>{{ $s['nb'] }}</b><span>{{ $s['label'] }}</span></div>@endforeach
            </div>
            @endif
        </div>
    </section>

    @if (! empty($event['histoire']))
    <section class="bf-sec" style="padding-top:0">
        <div class="bf-in ga-chron">
            <div class="bf-rv"><span class="bf-k">{{ $T('histoire', 'eyebrow', 'L\'origine') }}</span><h2>{{ $event['histoire']['titre'] }}</h2></div>
            <div class="ga-chron__txt bf-rv">@foreach ($event['histoire']['paragraphs'] as $p)<p>{{ $p }}</p>@endforeach</div>
        </div>
    </section>
    @endif

    @if (! empty($event['signature']['items']))
    <section class="ga-lex bf-sec" aria-label="{{ $event['signature']['titre'] }}">
        <div class="bf-in">
            @if (! empty($event['signature']['eyebrow']))<span class="bf-k bf-rv">{{ $event['signature']['eyebrow'] }}</span>@endif
            <h2 class="ga-h2 bf-rv">{{ $event['signature']['titre'] }}</h2>
            @if (! empty($event['signature']['lead']))<p class="bf-lead bf-rv">{{ $event['signature']['lead'] }}</p>@endif
            <dl class="ga-mots">
                @foreach ($event['signature']['items'] as $i => $it)
                    <div class="ga-mot bf-rv" style="--d: {{ ($i % 3) * 90 }}ms"><dt>{{ $it['t'] }}</dt><dd>{{ $it['d'] }}</dd></div>
                @endforeach
            </dl>
        </div>
    </section>
    @endif

    @if (! empty($event['tempsForts']))
    <section class="bf-sec">
        <div class="bf-in">
            <span class="bf-k bf-rv">{{ $T('tempsForts', 'eyebrow', 'Les vagues de programmation') }}</span>
            <h2 class="ga-h2 bf-rv" style="color:var(--indigo)">{{ $T('tempsForts', 'titre', 'Les temps forts') }}</h2>
            <p class="bf-lead bf-rv">{{ $T('tempsForts', 'lead', 'Chaque édition suit un fil narratif similaire — voici les cinq vagues qui structurent l\'expérience.') }}</p>
            <div class="ga-proc">
                @foreach ($event['tempsForts'] as $i => $v)
                    <div class="ga-etape bf-rv" style="--d: {{ $i * 90 }}ms"><i aria-hidden="true">{{ $i + 1 }}</i><small>{{ $v['sous'] }}</small><h3>{{ $v['titre'] }}</h3><p>{{ $v['texte'] }}</p></div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @if (! empty($event['ambiance']))
    <section class="bf-sec" style="padding-top:0">
        <div class="bf-in">
            <div class="ga-amb bf-rv">
                <div class="ga-amb__img"><img src="{{ $event['ambiance']['photos'][0] ?? $event['hero'] }}" alt="" loading="lazy" decoding="async"></div>
                <div class="ga-amb__txt">
                    <span class="bf-k">{{ $T('ambiance', 'eyebrow', 'L\'ambiance') }}</span>
                    <h2>{{ $event['ambiance']['titre'] }}</h2>
                    <p style="color:var(--bf-soft)">{{ $event['ambiance']['texte'] }}</p>
                    <ul>@foreach ($event['ambiance']['points'] as $p)<li>{{ $p }}</li>@endforeach</ul>
                    <div class="ga-amb__mini">@foreach (array_slice($event['ambiance']['photos'], 1) as $ph)<img src="{{ $ph }}" alt="" loading="lazy" decoding="async">@endforeach</div>
                </div>
            </div>
        </div>
    </section>
    @endif

    @if (! empty($event['lieux']))
    <section class="bf-sec" style="padding-top:0">
        <div class="bf-in">
            <span class="bf-k bf-rv">{{ $T('lieux', 'eyebrow', 'Les scènes') }}</span>
            <h2 class="ga-h2 bf-rv" style="color:var(--indigo)">{{ $T('lieux', 'titre', 'Les lieux emblématiques') }}</h2>
            <p class="bf-lead bf-rv">{{ $T('lieux', 'lead', 'Chaque événement respire dans des lieux précis. Les connaître, c\'est déjà commencer à comprendre le déroulé.') }}</p>
            <div class="ga-lieux">
                @foreach ($event['lieux'] as $i => $l)
                    <article class="ga-lieu bf-rv" style="--d: {{ $i * 90 }}ms"><img src="{{ $l['photo'] }}" alt="{{ $l['nom'] }}" loading="lazy" decoding="async"><h3>{{ $l['nom'] }}</h3><p>{{ $l['desc'] }}</p></article>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <section class="bf-sec" id="programme" style="padding-top:0">
        <div class="bf-in">
            <span class="bf-k bf-rv">{{ $T('programme', 'eyebrow', 'Le déroulé') }}</span>
            <h2 class="ga-h2 bf-rv" style="color:var(--indigo)">{{ $T('programme', 'titre', 'Programme type') }}</h2>
            <p class="bf-lead bf-rv">{{ $T('programme', 'lead', 'Un aperçu de ce qui vous attend jour après jour. Le programme officiel varie chaque année — vérifiez la date exacte sur le site du festival.') }}</p>
            <div class="ga-jours">
                @foreach ($event['programme'] as $i => $p)
                    <article class="ga-jour bf-rv" style="--d: {{ $i * 80 }}ms"><b>{{ $p['jour'] }}</b><div><h3>{{ $p['titre'] }}</h3><p>{{ $p['texte'] }}</p></div><em>{{ $p['lieu'] }}</em></article>
                @endforeach
            </div>
            <div class="ga-route bf-rv">
                <span class="bf-k">Prendre la route</span>
                <div class="ga-route__ligne" aria-hidden="true"><span>Cotonou</span><i></i><span>Parakou</span><i></i><span>Nikki</span></div>
                <p>{{ collect($event['tips'] ?? [])->firstWhere('label', 'Route')['texte'] ?? $event['lieu'] }}</p>
            </div>
        </div>
    </section>

    <section class="bf-sec" style="padding-top:0">
        <div class="bf-in ga-blocs">
            @foreach ($event['blocks'] as $i => $b)
                <article class="ga-bloc bf-rv" style="--d: {{ $i * 100 }}ms"><b>{{ ['I', 'II', 'III', 'IV'][$i] ?? $i + 1 }}</b><h3>{{ $b['titre'] }}</h3><p>{{ $b['texte'] }}</p></article>
            @endforeach
        </div>
    </section>

    @if (! empty($event['tips']))
    <section class="bf-sec" style="padding-top:0">
        <div class="bf-in">
            <span class="bf-k bf-rv">{{ $T('tips', 'eyebrow', 'Bon à savoir') }}</span>
            <h2 class="ga-h2 bf-rv" style="color:var(--indigo)">{{ $T('tips', 'titre', 'Les conseils de nos guides') }}</h2>
            <p class="bf-lead bf-rv">{{ $T('tips', 'lead', 'Ces règles évitent les faux pas et rendent le séjour plus fluide. Notre équipe les rappelle systématiquement à chaque groupe.') }}</p>
            <div class="ga-conseils">
                @foreach ($event['tips'] as $i => $tip)
                    <div class="ga-conseil bf-rv" style="--d: {{ $i * 70 }}ms"><span aria-hidden="true">{!! $icon($tip['label']) !!}</span><h3>{{ $tip['label'] }}</h3><p>{{ $tip['texte'] }}</p></div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <section class="bf-sec" style="padding-top:0">
        <div class="bf-in ga-duo">
            @if (! empty($event['surPlace']))
            <div class="ga-box bf-rv">
                <span class="bf-k">{{ $T('surPlace', 'eyebrow', 'Repères pratiques') }}</span>
                <h2>{{ $event['surPlace']['titre'] }}</h2>
                <ul>@foreach ($event['surPlace']['points'] as $s)<li><span aria-hidden="true">{!! $icon($s['label']) !!}</span><div><b>{{ $s['label'] }}</b><p>{{ $s['texte'] }}</p></div></li>@endforeach</ul>
            </div>
            @endif
            @if (! empty($event['autour']))
            <div class="ga-box bf-rv" style="--d:120ms">
                <span class="bf-k">{{ $T('autour', 'eyebrow', 'À faire à côté') }}</span>
                <h2>{{ $event['autour']['titre'] }}</h2>
                <p style="color:var(--bf-soft);margin:-6px 0 6px">{{ $T('autour', 'lead', 'Étirez votre séjour d\'une journée ou deux — voici les visites qui prolongent l\'expérience.') }}</p>
                <ul>@foreach ($event['autour']['points'] as $i => $a)<li><span aria-hidden="true" class="ga-c" style="font-size:1.2rem;line-height:1">{{ ['I', 'II', 'III', 'IV', 'V'][$i] ?? $i + 1 }}</span><div><b>{{ $a['t'] }}</b><p>{{ $a['d'] }}</p></div></li>@endforeach</ul>
            </div>
            @endif
        </div>
    </section>

    @if (! empty($event['faq']))
    <section class="bf-sec" style="padding-top:0">
        <div class="bf-in">
            <span class="bf-k bf-rv">{{ $T('faq', 'eyebrow', 'Vous nous demandez souvent') }}</span>
            <h2 class="ga-h2 bf-rv" style="color:var(--indigo);margin-bottom:30px">{{ $T('faq', 'titre', 'Les questions fréquentes') }}</h2>
            @include('pages.benin-en-fete.designs._faq')
        </div>
    </section>
    @endif

    @if (! empty($event['combinerAvec']))
    <section class="bf-sec" style="padding-top:0">
        <div class="bf-in">
            <span class="bf-k bf-rv">{{ $T('combine', 'eyebrow', 'Prolonger le voyage') }}</span>
            <h2 class="ga-h2 bf-rv" style="color:var(--indigo)">{{ $T('combine', 'titre', 'À combiner avec') }}</h2>
            <p class="bf-lead bf-rv" style="margin-bottom:32px">{{ $T('combine', 'lead', 'Ces rendez-vous s\'inscrivent dans la même saison ou la même thématique. Ils gagnent à être vécus dans le même voyage.') }}</p>
            @include('pages.benin-en-fete.designs._combine')
        </div>
    </section>
    @endif

    <section class="bf-sec" style="padding-top:0">
        <div class="bf-in">@include('pages.benin-en-fete.designs._cta', ['ctaImage' => '/assets/img/nikki-1920x960.webp'])</div>
    </section>
</div>
