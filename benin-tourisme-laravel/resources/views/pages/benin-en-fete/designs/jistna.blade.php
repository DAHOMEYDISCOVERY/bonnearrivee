{{-- JISTNA — « L'herbier ».
     Fiche en carnet de naturaliste : papier crème, encre verte, notes manuscrites,
     planches d'herbier pour les lieux, déroulé des deux journées en onglets. --}}
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Caveat:wght@500;700&family=Fraunces:ital,opsz,wght@0,9..144,500;0,9..144,700;1,9..144,500&display=swap">
<style>
.jn{--bf-bg:#f6f1e4;--bf-ink:#1f2a1a;--bf-soft:#5d6652;--bf-surface:#fffdf6;--bf-accent:#3f7f22;--encre:#2f5d24;--feuille:#9cc46b;--terre:#b8642e}
.jn .jn-f{font-family:Fraunces,Georgia,serif}
.jn .jn-m{font-family:Caveat,cursive}
.jn .bf-k{color:var(--encre)}
.jn .jn-hero{padding:140px 0 90px;background:radial-gradient(70% 60% at 85% 20%,rgba(156,196,107,.28),transparent 70%),var(--bf-bg)}
.jn .jn-hero__grid{display:grid;grid-template-columns:1fr 1fr;gap:clamp(30px,6vw,90px);align-items:center}
.jn .jn-hero h1{font-family:Fraunces,serif;font-weight:700;font-size:clamp(3.6rem,9vw,7.6rem);line-height:.9;color:var(--encre);letter-spacing:-.02em;margin:14px 0 10px}
.jn .jn-hero__sub{font-family:Fraunces,serif;font-style:italic;font-size:clamp(1.1rem,1.8vw,1.35rem);color:var(--bf-soft);max-width:34ch}
.jn .jn-hero__lead{margin-top:22px;max-width:48ch}
.jn .jn-note{display:inline-block;margin-top:28px;font-family:Caveat,cursive;font-size:1.8rem;color:var(--terre);transform:rotate(-2deg)}
.jn .jn-photo{position:relative;background:#fff;padding:14px 14px 56px;transform:rotate(2.5deg);box-shadow:0 30px 60px -30px rgba(31,42,26,.55)}
.jn .jn-photo img{width:100%;aspect-ratio:4/3;object-fit:cover}
.jn .jn-photo figcaption{position:absolute;left:18px;right:18px;bottom:14px;font-family:Caveat,cursive;font-size:1.5rem;color:var(--bf-soft)}
.jn .jn-photo::before,.jn .jn-photo::after{content:"";position:absolute;width:110px;height:30px;background:rgba(214,198,150,.75);top:-12px;left:30px;transform:rotate(-8deg)}
.jn .jn-photo::after{left:auto;right:30px;transform:rotate(7deg)}
.jn .jn-fiches{display:grid;grid-template-columns:repeat(3,1fr);gap:20px}
.jn .jn-fiche{background:var(--bf-surface);border-radius:8px 28px 8px 28px;padding:30px 28px;box-shadow:0 20px 40px -34px rgba(31,42,26,.6)}
.jn .jn-fiche small{font-family:Caveat,cursive;font-size:1.5rem;color:var(--terre)}
.jn .jn-fiche h3{font-family:Fraunces,serif;font-size:1.45rem;font-weight:700;margin:4px 0 12px;color:var(--encre)}
.jn .jn-fiche p{color:var(--bf-soft);font-size:.96rem}
.jn .jn-chapo{font-family:Fraunces,serif;font-size:clamp(1.4rem,2.6vw,2.1rem);line-height:1.35;max-width:36ch;margin:0 auto 56px;text-align:center;color:var(--encre)}
.jn .jn-etiquettes{display:flex;flex-wrap:wrap;justify-content:center;gap:16px;margin-top:56px}
.jn .jn-etiq{background:#fff;border-radius:6px;padding:18px 26px 16px;min-width:200px;text-align:center;box-shadow:0 14px 30px -24px rgba(31,42,26,.6);position:relative}
.jn .jn-etiq::before{content:"";position:absolute;top:10px;left:50%;width:10px;height:10px;margin-left:-5px;border-radius:50%;background:var(--bf-bg);box-shadow:inset 0 1px 2px rgba(0,0,0,.2)}
.jn .jn-etiq b{display:block;font-family:Fraunces,serif;font-size:2.4rem;color:var(--encre);margin-top:10px;line-height:1}
.jn .jn-etiq span{font-family:Caveat,cursive;font-size:1.3rem;color:var(--bf-soft)}
.jn .jn-carnet{background:#fffdf6;border-radius:26px;padding:clamp(28px,5vw,60px);box-shadow:0 40px 80px -50px rgba(31,42,26,.6);background-image:repeating-linear-gradient(180deg,transparent 0 37px,rgba(63,127,34,.12) 37px 38px)}
.jn .jn-carnet__grid{display:grid;grid-template-columns:repeat(2,1fr);gap:clamp(24px,4vw,56px);margin-top:40px}
.jn .jn-page h3{font-family:Caveat,cursive;font-size:2.2rem;color:var(--terre);line-height:1;margin-bottom:8px}
.jn .jn-page p{font-size:1rem;line-height:38px;color:var(--bf-ink)}
.jn .jn-h2{font-family:Fraunces,serif;font-weight:700;font-size:clamp(2.2rem,4.4vw,3.6rem);line-height:1.04;color:var(--encre);margin:12px 0 16px}
.jn .jn-tabs{display:flex;gap:8px;margin:40px 0 24px;flex-wrap:wrap}
.jn .jn-tab{appearance:none;cursor:pointer;padding:12px 22px;border-radius:999px;background:#fff;color:var(--encre);font-weight:700;font-family:inherit;font-size:.95rem;box-shadow:inset 0 0 0 1px rgba(63,127,34,.25)}
.jn .jn-tab[aria-selected="true"]{background:var(--encre);color:#fff;box-shadow:none}
.jn .jn-panel{display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:16px}
.jn .jn-panel[hidden]{display:none}
.jn .jn-creneau{background:#fff;border-radius:24px;padding:26px;box-shadow:0 18px 40px -32px rgba(31,42,26,.6)}
.jn .jn-creneau small{font-family:Caveat,cursive;font-size:1.5rem;color:var(--terre)}
.jn .jn-creneau h3{font-family:Fraunces,serif;font-size:1.35rem;font-weight:700;margin:2px 0 4px;color:var(--encre)}
.jn .jn-creneau em{display:inline-block;font-style:normal;font-size:.8rem;font-weight:700;color:#fff;background:var(--bf-accent);padding:4px 10px;border-radius:999px;margin-bottom:10px}
.jn .jn-creneau p{color:var(--bf-soft);font-size:.94rem}
.jn .jn-planches{display:grid;grid-template-columns:repeat(4,1fr);gap:22px;margin-top:44px}
.jn .jn-planche{background:#fff;padding:16px 16px 20px;border-radius:4px;box-shadow:0 24px 44px -32px rgba(31,42,26,.6)}
.jn .jn-planche img{width:100%;aspect-ratio:3/4;object-fit:cover;filter:saturate(.9)}
.jn .jn-planche span{display:block;margin-top:14px;font-size:.7rem;letter-spacing:.18em;text-transform:uppercase;color:var(--bf-soft)}
.jn .jn-planche h3{font-family:Fraunces,serif;font-style:italic;font-size:1.3rem;font-weight:500;color:var(--encre);margin:2px 0 6px}
.jn .jn-planche p{font-size:.9rem;color:var(--bf-soft)}
.jn .jn-moments{display:grid;grid-template-columns:repeat(5,1fr);gap:14px;margin-top:40px}
.jn .jn-mom{border-radius:22px;padding:22px;background:rgba(63,127,34,.07)}
.jn .jn-mom svg{width:28px;height:28px;color:var(--bf-accent);margin-bottom:12px}
.jn .jn-mom small{font-family:Caveat,cursive;font-size:1.3rem;color:var(--terre)}
.jn .jn-mom h4{font-family:Fraunces,serif;font-weight:700;font-size:1.08rem;margin:2px 0 6px;color:var(--encre)}
.jn .jn-mom p{font-size:.88rem;color:var(--bf-soft)}
.jn .jn-amb{display:grid;grid-template-columns:1fr 1fr;gap:clamp(30px,5vw,80px);align-items:center}
.jn .jn-amb__img{display:grid;grid-template-columns:1fr 1fr;gap:14px}
.jn .jn-amb__img img{width:100%;height:220px;object-fit:cover;border-radius:120px 120px 20px 20px}
.jn .jn-amb__img img:first-child{grid-row:span 2;height:100%;border-radius:20px 20px 200px 200px}
.jn .jn-amb h2{font-family:Fraunces,serif;font-weight:700;font-size:clamp(1.9rem,3.4vw,2.8rem);line-height:1.1;color:var(--encre);margin:12px 0 16px}
.jn .jn-amb ul{margin-top:20px;display:grid;gap:10px}
.jn .jn-amb li{display:flex;gap:12px;align-items:baseline}
.jn .jn-amb li::before{content:"✿";color:var(--bf-accent)}
.jn .jn-conseils{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:16px;margin-top:40px}
.jn .jn-conseil{background:#fff;border-radius:24px;padding:24px}
.jn .jn-conseil span{display:grid;place-items:center;width:48px;height:48px;border-radius:50%;background:rgba(63,127,34,.1);color:var(--bf-accent);margin-bottom:14px}
.jn .jn-conseil span svg{width:22px;height:22px}
.jn .jn-conseil h3{font-family:Caveat,cursive;font-size:1.7rem;color:var(--terre);line-height:1;margin-bottom:6px}
.jn .jn-conseil p{color:var(--bf-soft);font-size:.94rem}
.jn .jn-hist{display:grid;grid-template-columns:.8fr 1.2fr;gap:clamp(30px,6vw,90px)}
.jn .jn-hist p{color:var(--bf-soft);margin-bottom:16px;font-size:1.04rem}
.jn .jn-duo{display:grid;grid-template-columns:1fr 1fr;gap:22px}
.jn .jn-box{background:#fff;border-radius:28px;padding:clamp(26px,4vw,42px)}
.jn .jn-box h2{font-family:Fraunces,serif;font-weight:700;font-size:1.8rem;color:var(--encre);margin:10px 0 18px}
.jn .jn-box li{display:grid;grid-template-columns:auto 1fr;gap:14px;padding:12px 0}
.jn .jn-box li+li{box-shadow:0 -1px 0 rgba(31,42,26,.08)}
.jn .jn-box li span{color:var(--bf-accent);width:22px}.jn .jn-box li span svg{width:22px;height:22px}
.jn .jn-box b{display:block;margin-bottom:2px}
.jn .jn-box p{color:var(--bf-soft);font-size:.94rem}
@media (max-width:900px){
 .jn .jn-hero__grid,.jn .jn-fiches,.jn .jn-carnet__grid,.jn .jn-amb,.jn .jn-hist,.jn .jn-duo{grid-template-columns:1fr}
 .jn .jn-planches{grid-template-columns:1fr 1fr}
 .jn .jn-moments{grid-template-columns:1fr 1fr}
}
</style>
@php
    $jnJours = [];
    foreach ($event['programme'] as $p) { $jnJours[implode(' ', array_slice(explode(' ', $p['jour']), 0, 2))][] = $p; }
    $jnLeaf = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M5 21c0-9 5-15 15-17-1 10-7 15-15 17z"/><path d="M5 21l8-9"/></svg>';
@endphp

<div class="bf jn">
    <header class="jn-hero">
        <div class="bf-in jn-hero__grid">
            <div>
                <span class="bf-k">Bénin en fête · savoirs & nature</span>
                <h1>{{ $event['title'] }}</h1>
                <p class="jn-hero__sub">{{ $event['subtitle'] }}</p>
                <p class="jn-hero__lead">{{ $event['description'] }}</p>
                <span class="jn-note">{{ $event['lieu'] }} — {{ $event['dates'] }}</span>
            </div>
            <figure class="jn-photo"><img src="{{ $event['hero'] }}" alt="{{ $event['title'] }}" fetchpriority="high" decoding="async"><figcaption>{{ $event['title'] }}, {{ explode(' · ', $event['lieu'])[0] }}</figcaption></figure>
        </div>
    </header>
    @include('pages.benin-en-fete.designs._crumb')

    <section class="bf-sec">
        <div class="bf-in">
            <p class="jn-chapo bf-rv">{{ $event['chapo'] }}</p>
            <div class="jn-fiches">
                @foreach ($event['blocks'] as $i => $b)
                    <article class="jn-fiche bf-rv" style="--d: {{ $i * 100 }}ms"><small>Fiche n° {{ $i + 1 }}</small><h3>{{ $b['titre'] }}</h3><p>{{ $b['texte'] }}</p></article>
                @endforeach
            </div>
            @if (! empty($event['stats']))
            <div class="jn-etiquettes">
                @foreach ($event['stats'] as $i => $s)<div class="jn-etiq bf-rv" style="--d: {{ $i * 80 }}ms"><b data-bf-count>{{ $s['nb'] }}</b><span>{{ $s['label'] }}</span></div>@endforeach
            </div>
            @endif
        </div>
    </section>

    @if (! empty($event['signature']['items']))
    <section class="bf-sec" style="padding-top:0" aria-label="{{ $event['signature']['titre'] }}">
        <div class="bf-in">
            <div class="jn-carnet bf-rv">
                @if (! empty($event['signature']['eyebrow']))<span class="bf-k">{{ $event['signature']['eyebrow'] }}</span>@endif
                <h2 class="jn-h2">{{ $event['signature']['titre'] }}</h2>
                @if (! empty($event['signature']['lead']))<p class="bf-lead">{{ $event['signature']['lead'] }}</p>@endif
                <div class="jn-carnet__grid">
                    @foreach ($event['signature']['items'] as $it)<div class="jn-page"><h3>{{ $it['t'] }}</h3><p>{{ $it['d'] }}</p></div>@endforeach
                </div>
            </div>
        </div>
    </section>
    @endif

    <section class="bf-sec" id="programme" style="padding-top:0">
        <div class="bf-in">
            <span class="bf-k bf-rv">{{ $T('programme', 'eyebrow', 'Le déroulé') }}</span>
            <h2 class="jn-h2 bf-rv">{{ $T('programme', 'titre', 'Programme type') }}</h2>
            <p class="bf-lead bf-rv">{{ $T('programme', 'lead', 'Un aperçu de ce qui vous attend jour après jour. Le programme officiel varie chaque année — vérifiez la date exacte sur le site du festival.') }}</p>
            <div class="jn-tabs" role="tablist">
                @foreach (array_keys($jnJours) as $k => $jour)
                    <button class="jn-tab" type="button" role="tab" id="jn-tab-{{ $k }}" aria-controls="jn-panel-{{ $k }}" aria-selected="{{ $k === 0 ? 'true' : 'false' }}">{{ $jour }}</button>
                @endforeach
            </div>
            @foreach (array_values($jnJours) as $k => $moments)
                <div class="jn-panel" role="tabpanel" id="jn-panel-{{ $k }}" aria-labelledby="jn-tab-{{ $k }}" @if ($k > 0) hidden @endif>
                    @foreach ($moments as $p)
                        <article class="jn-creneau"><small>{{ $p['jour'] }}</small><h3>{{ $p['titre'] }}</h3><em>{{ $p['lieu'] }}</em><p>{{ $p['texte'] }}</p></article>
                    @endforeach
                </div>
            @endforeach
        </div>
    </section>

    @if (! empty($event['lieux']))
    <section class="bf-sec" style="padding-top:0">
        <div class="bf-in">
            <span class="bf-k bf-rv">{{ $T('lieux', 'eyebrow', 'Les scènes') }}</span>
            <h2 class="jn-h2 bf-rv">{{ $T('lieux', 'titre', 'Les lieux emblématiques') }}</h2>
            <p class="bf-lead bf-rv">{{ $T('lieux', 'lead', 'Chaque événement respire dans des lieux précis. Les connaître, c\'est déjà commencer à comprendre le déroulé.') }}</p>
            <div class="jn-planches">
                @foreach ($event['lieux'] as $i => $l)
                    <article class="jn-planche bf-rv" style="--d: {{ $i * 90 }}ms"><img src="{{ $l['photo'] }}" alt="{{ $l['nom'] }}" loading="lazy" decoding="async"><span>Planche {{ $pad($i) }}</span><h3>{{ $l['nom'] }}</h3><p>{{ $l['desc'] }}</p></article>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @if (! empty($event['tempsForts']))
    <section class="bf-sec" style="padding-top:0">
        <div class="bf-in">
            <span class="bf-k bf-rv">{{ $T('tempsForts', 'eyebrow', 'Les vagues de programmation') }}</span>
            <h2 class="jn-h2 bf-rv">{{ $T('tempsForts', 'titre', 'Les temps forts') }}</h2>
            <p class="bf-lead bf-rv">{{ $T('tempsForts', 'lead', 'Chaque édition suit un fil narratif similaire — voici les cinq vagues qui structurent l\'expérience.') }}</p>
            <div class="jn-moments">
                @foreach ($event['tempsForts'] as $i => $v)
                    <div class="jn-mom bf-rv" style="--d: {{ $i * 70 }}ms">{!! $jnLeaf !!}<small>{{ $v['sous'] }}</small><h4>{{ $v['titre'] }}</h4><p>{{ $v['texte'] }}</p></div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @if (! empty($event['ambiance']))
    <section class="bf-sec" style="padding-top:0">
        <div class="bf-in jn-amb">
            <div class="jn-amb__img bf-rv">@foreach ($event['ambiance']['photos'] as $ph)<img src="{{ $ph }}" alt="" loading="lazy" decoding="async">@endforeach</div>
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
            <h2 class="jn-h2 bf-rv">{{ $T('tips', 'titre', 'Les conseils pratiques') }}</h2>
            <p class="bf-lead bf-rv">{{ $T('tips', 'lead', 'Ces règles évitent les faux pas et rendent la visite plus fluide.') }}</p>
            <div class="jn-conseils">
                @foreach ($event['tips'] as $i => $tip)
                    <div class="jn-conseil bf-rv" style="--d: {{ $i * 70 }}ms"><span aria-hidden="true">{!! $icon($tip['label']) !!}</span><h3>{{ $tip['label'] }}</h3><p>{{ $tip['texte'] }}</p></div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @if (! empty($event['histoire']))
    <section class="bf-sec" style="padding-top:0">
        <div class="bf-in jn-hist">
            <div class="bf-rv"><span class="bf-k">{{ $T('histoire', 'eyebrow', 'L\'origine') }}</span><h2 class="jn-h2">{{ $event['histoire']['titre'] }}</h2></div>
            <div class="bf-rv">@foreach ($event['histoire']['paragraphs'] as $p)<p>{{ $p }}</p>@endforeach</div>
        </div>
    </section>
    @endif

    <section class="bf-sec" style="padding-top:0">
        <div class="bf-in jn-duo">
            @if (! empty($event['autour']))
            <div class="jn-box bf-rv">
                <span class="bf-k">{{ $T('autour', 'eyebrow', 'À faire à côté') }}</span>
                <h2>{{ $event['autour']['titre'] }}</h2>
                <p style="color:var(--bf-soft);margin:-6px 0 6px">{{ $T('autour', 'lead', 'Étirez votre séjour d\'une journée ou deux — voici les visites qui prolongent l\'expérience.') }}</p>
                <ul>@foreach ($event['autour']['points'] as $i => $a)<li><span aria-hidden="true">{!! $jnLeaf !!}</span><div><b>{{ $a['t'] }}</b><p>{{ $a['d'] }}</p></div></li>@endforeach</ul>
            </div>
            @endif
            @if (! empty($event['surPlace']))
            <div class="jn-box bf-rv" style="--d:120ms">
                <span class="bf-k">{{ $T('surPlace', 'eyebrow', 'Repères pratiques') }}</span>
                <h2>{{ $event['surPlace']['titre'] }}</h2>
                <ul>@foreach ($event['surPlace']['points'] as $s)<li><span aria-hidden="true">{!! $icon($s['label']) !!}</span><div><b>{{ $s['label'] }}</b><p>{{ $s['texte'] }}</p></div></li>@endforeach</ul>
            </div>
            @endif
        </div>
    </section>

    @if (! empty($event['faq']))
    <section class="bf-sec" style="padding-top:0">
        <div class="bf-in">
            <span class="bf-k bf-rv">{{ $T('faq', 'eyebrow', 'Vous nous demandez souvent') }}</span>
            <h2 class="jn-h2 bf-rv" style="margin-bottom:30px">{{ $T('faq', 'titre', 'Les questions fréquentes') }}</h2>
            @include('pages.benin-en-fete.designs._faq')
        </div>
    </section>
    @endif

    @if (! empty($event['combinerAvec']))
    <section class="bf-sec" style="padding-top:0">
        <div class="bf-in">
            <span class="bf-k bf-rv">{{ $T('combine', 'eyebrow', 'Prolonger le voyage') }}</span>
            <h2 class="jn-h2 bf-rv">{{ $T('combine', 'titre', 'À combiner avec') }}</h2>
            <p class="bf-lead bf-rv" style="margin-bottom:32px">{{ $T('combine', 'lead', 'Ces rendez-vous s\'inscrivent dans la même saison ou la même thématique. Ils gagnent à être vécus dans le même voyage.') }}</p>
            @include('pages.benin-en-fete.designs._combine')
        </div>
    </section>
    @endif

    <section class="bf-sec" style="padding-top:0">
        <div class="bf-in">@include('pages.benin-en-fete.designs._cta', ['ctaImage' => '/assets/img/bouche-du-roy-1920x960.webp'])</div>
    </section>
</div>
<script>
(function () {
    var tabs = document.querySelectorAll('.jn-tab');
    tabs.forEach(function (t) {
        t.addEventListener('click', function () {
            tabs.forEach(function (o) {
                var on = o === t;
                o.setAttribute('aria-selected', on ? 'true' : 'false');
                var p = document.getElementById(o.getAttribute('aria-controls'));
                if (p) p.hidden = !on;
            });
        });
    });
})();
</script>
