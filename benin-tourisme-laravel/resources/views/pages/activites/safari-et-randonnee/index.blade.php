{{-- Page « Safari et randonnée » — refonte du 18/11/2026 (n’est plus générée par tools/convert.py). --}}
@extends('layouts.app')

@section('bodyClass', 'page page-id-769 page-child parent-pageid-764 menus-v2 prod gabarit-playlist_tourism has-hero has-blocks-hero-tpl_02 apirender apirender-playlist apirender-wordpress')
@section('mainAttributes')role="main" class="content-type-page playlist_tourism" id="main-content"@endsection


@section('headGlobals')@verbatim<script>window.globals={"ancestors":{"chapter1":"Vivez","chapter2":"Sur terre","chapter3":null},"context":"website","current_lang":"fr","current_locale":"fr","current_locale_region":"fr_FR","env":"prod","languages":["fr","nl"],"page_type":"playlist_tourism","post_id":769,"post_image":"","post_title":"Sur terre","post_type":"page","post_modified":"2022-01-12 13:17:07+01:00","site_key":"benin","tags":{"places":[],"seasons":[],"themes":{"envies":["Nature sauvage"],"cibles":["Randonneurs","Sportifs"]},"targets":[]},"woody_options_pages":{"search_url":2043,"weather_url":0},"woody_timezone":"Europe\/Paris","search":{"api":{"uri":"","args":{"size":15,"threshold":0.5}}},"sheet_bordereaux_with_dates":[],"short_date_bordereaux":["FMA","ASC"],"tmaps":{"url_api":"","url_api_lazy":""},"hawwwai":{"sheet_options":{"nearby_distance_max":5000}}}</script>@endverbatim@endsection
@section('headDataLayer')@verbatim<script>window.dataLayer=window.dataLayer?window.dataLayer:[];window.dataLayer.push({"event":"globals","data":{"env":"prod","site_key":"benin","page":{"id_page":769,"name":"Sur terre","page_type":"playlist_tourism","tags":{"places":[],"seasons":[],"themes":{"envies":["Nature sauvage"],"cibles":["Randonneurs","Sportifs"]},"targets":[]}},"lang":"fr","playlist":{"id_playlist":null,"id_playlist_conf":"23775","pagination":1}}});</script>@endverbatim@endsection
@section('headSchema')@verbatim<script type="application/ld+json" class="benin-schema-graph">{"@context":"https:\/\/schema.org","@graph":[{"@type":"WebSite","url":"https:\/\/www.bonnearrivee.bj","name":"Bonne Arrivée","description":"Bienvenue sur le site de l’Agence Attractivit\u00e9 &amp; Tourisme Bénin. Ici, la vie s’\u00e9merveille, et mille surprises vous attendent.","potentialAction":[{"@type":"SearchAction","target":"https:\/\/www.bonnearrivee.bj\/rechercher\/?query={search_term_string}","query-input":"required name=search_term_string"}]},{"@type":"WebPage","url":"https:\/\/www.bonnearrivee.bj\/activites\/sur-terre\/","headline":"Sur terre | Bonne Arrivée","inLanguage":"fr-FR","datePublished":"2021-12-07 10:11:21","dateModified":"2022-01-12 12:17:07","description":"Bénin ne se d\u00e9couvre pas qu\u2019en mer ou dans les airs. Dans ses terres, l\u2019\u00eele hippocampe regorge de surprises.","image":[]},{"@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"Accueil","item":"https:\/\/www.bonnearrivee.bj\/"},{"@type":"ListItem","position":2,"name":"Vivez","item":"https:\/\/www.bonnearrivee.bj\/activites\/"},{"@type":"ListItem","position":3,"name":"Sur terre","item":"https:\/\/www.bonnearrivee.bj\/activites\/sur-terre\/"}]}]}</script>@endverbatim@endsection
@section('headPagination')@verbatim<link href="/activites/safari-et-randonnee" rel="next"/>@endverbatim@endsection
@section('headImageOg')@verbatim<meta property="og:image" content="/assets/img/pendjari-elephant-1920x960.webp"/>@endverbatim@endsection
@section('headImageTwitter')@verbatim<meta property="twitter:image" content="/assets/img/pendjari-elephant-1920x960.webp"/>@endverbatim@endsection
@section('scripts')@verbatim
<script defer src="/assets/site/theme/js/static/angular-config.min.js" id="hawwwai_angular_config_footer-js"></script>


        <script src="/assets/benin-carrousels.js?v=a043a557"></script>
@endverbatim@endsection
@section('content')
@php
    $srStar = '<svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M12 2.5l2.9 6 6.6.8-4.9 4.6 1.3 6.6L12 17.3l-5.9 3.2 1.3-6.6L2.5 9.3l6.6-.8z"/></svg>';
    $srPin = '<svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M12 2a7 7 0 0 0-7 7c0 5 7 13 7 13s7-8 7-13a7 7 0 0 0-7-7zm0 9.5A2.5 2.5 0 1 1 12 6.5a2.5 2.5 0 0 1 0 5z"/></svg>';
    $srArrow = '<svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M13.2 5.3 11.8 6.7 16.1 11H4v2h12.1l-4.3 4.3 1.4 1.4L20 12z"/></svg>';
    $srInfo = '<svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20zm1 15h-2v-6h2zm0-8h-2V7h2z"/></svg>';
    // Lieux : [titre, région, photo, texte, lien, avis (cle, libellé), type]
    $srLieux = [
        ['Parc national de la Pendjari', 'Atakora · Nord-Ouest', '/assets/img/pendjari-elephant-640x480.webp', 'Le plus visité des deux parcs : pistes, mares et grandes plaines de savane. Éléphants, buffles, hippopotames et antilopes s’y observent à l’aube et en fin de journée.', '/explorez/naturellement-sauvage/le-parc-de-la-pendjari', ['rouge', 'Formellement déconseillé'], 'Safari'],
        ['Parc national du W', 'Alibori · Nord-Est', '/assets/img/parc-w-640x480.webp', 'La part béninoise d’un immense complexe partagé avec le Niger et le Burkina Faso. Plus retiré, plus sauvage, plus difficile d’accès.', '/explorez/naturellement-sauvage/le-parc-w', ['rouge', 'Formellement déconseillé'], 'Safari'],
        ['Chaîne de l’Atacora', 'Atakora', '/assets/img/atacora-640x480.webp', 'Collines, falaises et villages de tata somba : la marche à la rencontre du pays Betammaribé, au rythme d’un guide local.', '/explorez/naturellement-sauvage/la-chaine-de-latacora', ['orange', 'Déconseillé sauf raison impérative'], 'Randonnée'],
        ['Chutes de Tanougou', 'Tanguiéta · Atakora', '/assets/img/chutes-tanougou-640x480.webp', 'Un sentier en escaliers mène à des vasques d’eau claire au pied des chutes, au cœur de la végétation.', '/explorez/naturellement-sauvage/les-chutes-de-tanougou', ['orange', 'Déconseillé sauf raison impérative'], 'Randonnée'],
        ['Collines de Dassa-Zoumè', 'Collines · Centre', '/assets/img/dassa-collines-640x480.webp', 'La « ville aux quarante et une collines » : des balades sur les rochers, des points de vue dégagés et le lieu de pèlerinage d’Arigbo.', '/explorez/les-cinq-benin/le-centre-mystique', ['vert', 'Vigilance renforcée'], 'Randonnée'],
        ['Forêt classée de la Lama', 'Zou · Sud', null, 'Une des dernières forêts denses du sud du pays, refuge de primates menacés comme le singe à ventre rouge, endémique de la région.', null, ['vert', 'Vigilance renforcée'], 'Nature'],
    ];
    $srFaune = [
        ['Éléphants', '/assets/img/pendjari-elephant-640x640.webp', '/explorez/naturellement-sauvage/la-faune-emblematique/observer-les-elephants'],
        ['Hippopotames', '/assets/img/pendjari-hippo-640x640.webp', '/explorez/naturellement-sauvage/la-faune-emblematique/lhippopotame'],
        ['Buffles', '/assets/img/pendjari-buffle-640x640.webp', '/explorez/naturellement-sauvage/la-faune-emblematique'],
        ['Babouins', '/assets/img/pendjari-babouin-640x640.webp', '/explorez/naturellement-sauvage/la-faune-emblematique'],
        ['Lions, rares', '/assets/img/pendjari-lion-640x480.webp', '/explorez/naturellement-sauvage/la-faune-emblematique'],
        ['Varan du Nil', '/assets/img/varan-640x480.webp', '/explorez/naturellement-sauvage/la-faune-emblematique/le-varan-du-nil'],
    ];
    $srMois = ['J','F','M','A','M','J','J','A','S','O','N','D'];
    // Saison : 's' sèche, 'p' pluies, 'f' fortes pluies (pistes parfois inondées)
    $srSaison = ['s','s','s','s','s','p','p','f','f','f','s','s'];
@endphp
@verbatim
<div class="content-wrapper term-nature-sauvage term-randonneurs term-sportifs">
  <div class="woody-component woody-component-hero has-title has-pretitle tpl_02 isRel bgimg-op100 bg-black has-bgimg">
    <div class="hero-bg backgroundMedia img">
@endverbatim<x-media.figure
    src="/assets/img/pendjari-elephant-1920x480.webp"
    alt="Éléphants dans le parc de la Pendjari"
    loading="eager"
    srcset="/assets/img/pendjari-elephant-360x90.webp 360w, /assets/img/pendjari-elephant-640x160.webp 640w, /assets/img/pendjari-elephant-1920x480.webp 1920w"
    published="2026-11-18 10:00:00"
    description="Éléphants dans le parc de la Pendjari"
    caption="Éléphants dans le parc de la Pendjari"
    author="Bonne Arrivée / Tous droits réservés"
    :nosnippet="true"
/>@verbatim
    </div>
    <div class="fullcenterbox fcbCol grid-container padd-top-md padd-bottom-md">
      <div class="hero-titles flex-container flex-dir-column align-center align-middle text-center"><div class="hero-texts has-title has-pretitle"><span class="hero-pretitle block-span">Vivez · Sur terre</span><h1 class="hero-title">Le safari et la randonnée</h1></div></div>
    </div>
  </div>

  <header class="page-teaser" id="the-page-teaser">
    <div class="woody-component woody-component-teaser trip-info-banner tpl_01 padd-bottom-md">
      <div class="woody-component-breadcrumb breadcrumb-wrapper padd-all-sm"><ol vocab="https://schema.org/" typeof="BreadcrumbList" class="flex-container align-middle wrap grid-container"><li property="itemListElement" typeof="ListItem"><a property="item" typeof="WebPage" href="/"><span property="name">Accueil</span></a><meta property="position" content="1"></li><span class="wicon wicon-034-fleche-suivant"></span><li property="itemListElement" typeof="ListItem"><a property="item" typeof="WebPage" href="/activites"><span property="name">Vivez</span></a><meta property="position" content="2"></li><span class="wicon wicon-034-fleche-suivant"></span><li property="itemListElement" typeof="ListItem"><span property="item" typeof="WebPage" itemid="index.html"><span property="name">Sur terre</span></span><meta property="position" content="3"></li></ol></div>
      <div class="grid-container isRel"><div class="grid-x"><div class="cell texts-wrapper has-desc"><div class="page-main-description-wrapper"><div class="page-main-description"><p>Le Bénin ne se découvre pas qu’en mer ou au fil des lagunes. Dans ses terres, savanes, collines et forêts réservent de vraies rencontres&nbsp;: éléphants au point d’eau, sentiers en pays Betammaribé, chutes cachées dans la végétation.</p></div></div></div></div></div>
    </div>
  </header>
@endverbatim

<div class="sr">

  {{-- ====== EN UN COUP D'ŒIL ====== --}}
  <section class="sr-chiffres" aria-label="En un coup d’œil">
    <div class="sr-wrap sr-chiffres__in">
      <div class="sr-chiffre"><b>2</b><span>parcs nationaux au nord</span></div>
      <div class="sr-chiffre"><b>UNESCO</b><span>complexe W-Arly-Pendjari, patrimoine mondial depuis 2017</span></div>
      <div class="sr-chiffre"><b>Nov.&nbsp;– mai</b><span>saison sèche, la plus favorable</span></div>
      <div class="sr-chiffre"><b>2 à 4&nbsp;h</b><span>pour la plupart des sentiers, avec un guide</span></div>
    </div>
  </section>

  {{-- ====== AVANT DE PARTIR : SÉCURITÉ ====== --}}
  <section class="sr-alerte" aria-labelledby="sr-alerte-titre">
    <div class="sr-wrap">
      <div class="sr-alerte__carte">
        <span class="sr-alerte__ic">{!! $srInfo !!}</span>
        <div>
          <h2 class="sr-alerte__titre" id="sr-alerte-titre">Avant de partir vers le Nord</h2>
          <p>Les autorités françaises <strong>déconseillent formellement</strong> les parcs de la Pendjari et du W ainsi que leurs abords, et déconseillent <strong>sauf raison impérative</strong> le centre de l’Atakora (Natitingou, Tanguiéta, Boukoumbé), en raison d’un risque d’enlèvement. Ces avis évoluent&nbsp;: consultez ceux de votre pays avant tout projet, voyagez avec un opérateur agréé et suivez ses consignes sur place.</p>
          <p class="sr-alerte__liens"><a href="/sejourner/toutes-les-infos-pratiques/securite">Notre page Sécurité</a><a href="https://www.diplomatie.gouv.fr/fr/information-par-pays/benin/conseils-aux-voyageurs-securite" target="_blank" rel="noopener noreferrer">Conseils aux voyageurs (France)</a><a href="/activites/les-operateurs-de-safari">Les opérateurs de safari</a></p>
        </div>
      </div>
    </div>
  </section>

  {{-- ====== OÙ ALLER ====== --}}
  <section class="sr-lieux" aria-labelledby="sr-lieux-titre">
    <div class="sr-wrap">
      <div class="sr-tete">
        <p class="sr-surtitre">Où aller</p>
        <h2 class="sr-h2" id="sr-lieux-titre">Savanes, collines et forêts</h2>
        <p class="sr-lead">Des grands parcs du Nord aux collines du Centre et aux forêts du Sud&nbsp;: six terrains de jeu, avec pour chacun l’avis de sécurité en vigueur.</p>
      </div>
      <div class="sr-lieux__grille">
        @foreach ($srLieux as [$titre, $region, $img, $texte, $lien, $avis, $type])
          <article class="sr-lieu">
            <div class="sr-lieu__media">
              @if ($img)
                <img src="{{ $img }}" alt="{{ $titre }}" loading="lazy" decoding="async">
              @else
                <div class="sr-lieu__ph" aria-hidden="true"><svg viewBox="0 0 24 24"><path fill="currentColor" d="M12 2 6 11h3l-4 6h6v5h2v-5h6l-4-6h3z"/></svg></div>
              @endif
              <span class="sr-lieu__type">{{ $type }}</span>
            </div>
            <div class="sr-lieu__corps">
              <p class="sr-lieu__region">{!! $srPin !!}{{ $region }}</p>
              <h3 class="sr-lieu__titre">{{ $titre }}</h3>
              <p class="sr-lieu__texte">{{ $texte }}</p>
              <p class="sr-avis sr-avis--{{ $avis[0] }}"><i aria-hidden="true"></i>Avis voyageurs&nbsp;: {{ $avis[1] }}</p>
              @if ($lien)<a class="sr-lieu__lien" href="{{ $lien }}">Découvrir {!! $srArrow !!}</a>@endif
            </div>
          </article>
        @endforeach
      </div>
      <p class="sr-note">Avis de sécurité&nbsp;: ministère français de l’Europe et des Affaires étrangères, mise à jour du 15 septembre 2026.</p>
    </div>
  </section>

  {{-- ====== LA FAUNE ====== --}}
  <section class="sr-faune" aria-labelledby="sr-faune-titre">
    <div class="sr-wrap">
      <div class="sr-tete sr-tete--clair">
        <p class="sr-surtitre">Safari</p>
        <h2 class="sr-h2" id="sr-faune-titre">Ce qu’on peut observer</h2>
        <p class="sr-lead">Éléphants, buffles, hippopotames, cobes de Buffon, hippotragues, ourébis, phacochères et babouins. La Pendjari abrite aussi l’un des derniers noyaux de lions d’Afrique de l’Ouest&nbsp;: les voir reste rare. La faune se montre surtout à l’aube et en fin d’après-midi, autour des mares.</p>
      </div>
      <div class="sr-faune__grille">
        @foreach ($srFaune as [$nom, $img, $lien])
          <a class="sr-animal" href="{{ $lien }}">
            <img src="{{ $img }}" alt="{{ $nom }}" loading="lazy" decoding="async">
            <span>{{ $nom }}</span>
          </a>
        @endforeach
      </div>
      <p class="sr-faune__oiseaux"><a href="/explorez/naturellement-sauvage/la-faune-emblematique/observer-les-oiseaux">Et des centaines d’espèces d’oiseaux, dont de nombreux migrateurs en saison sèche {!! $srArrow !!}</a></p>
    </div>
  </section>

  {{-- ====== RANDONNER ====== --}}
  <section class="sr-rando" aria-labelledby="sr-rando-titre">
    <div class="sr-wrap sr-rando__in">
      <div class="sr-rando__visuel">
        <img src="/assets/img/tata-somba-640x854.webp" alt="Tata somba en pays Betammaribé" loading="lazy" decoding="async">
        <img src="/assets/img/chutes-tanougou-640x640.webp" alt="Les chutes de Tanougou" loading="lazy" decoding="async">
      </div>
      <div>
        <p class="sr-surtitre">Randonnée</p>
        <h2 class="sr-h2" id="sr-rando-titre">Marcher au Bénin</h2>
        <p class="sr-lead">Des sentiers courts et variés, presque toujours en compagnie d’un guide local qui connaît les usages des villages.</p>
        <ol class="sr-sentiers">
          <li><b>Les chutes de Tanougou</b><span>Sentier en escaliers jusqu’aux vasques au pied des chutes.</span></li>
          <li><b>Les collines de Boukoumbé</b><span>Montée pour le coucher du soleil sur le pays Betammaribé.</span></li>
          <li><b>Perma et Kouaba</b><span>Boucles à la rencontre des tata somba.</span></li>
          <li><b>Les collines de Dassa-Zoumè</b><span>Balades sur les rochers et points de vue sur le Centre.</span></li>
        </ol>
        <p class="sr-petit">Compter deux à quatre heures pour la plupart des sentiers. Dans l’Atakora, vérifiez d’abord l’avis de sécurité ci-dessus.</p>
      </div>
    </div>
  </section>

  {{-- ====== QUAND PARTIR ====== --}}
  <section class="sr-saison" aria-labelledby="sr-saison-titre">
    <div class="sr-wrap">
      <div class="sr-tete">
        <p class="sr-surtitre">Quand partir</p>
        <h2 class="sr-h2" id="sr-saison-titre">Le bon moment</h2>
      </div>
      <div class="sr-calendrier" role="img" aria-label="Saison sèche de novembre à mai ; saison des pluies de juin à octobre, avec de fortes pluies d’août à octobre">
        @foreach ($srMois as $i => $m)
          <div class="sr-mois sr-mois--{{ $srSaison[$i] }}"><span>{{ $m }}</span></div>
        @endforeach
      </div>
      <div class="sr-legende">
        <p><i class="sr-mois--s"></i><b>Novembre à mai — saison sèche.</b> Pistes ouvertes, végétation basse, faune regroupée autour des points d’eau&nbsp;: la meilleure période pour un safari. Nuits plus fraîches en brousse.</p>
        <p><i class="sr-mois--p"></i><b>Juin à octobre — saison des pluies.</b> La Pendjari reste ouverte toute l’année selon son gestionnaire, mais d’août à octobre de fortes pluies peuvent inonder les pistes.</p>
      </div>
    </div>
  </section>

  {{-- ====== BIEN SE PRÉPARER ====== --}}
  <section class="sr-prep" aria-labelledby="sr-prep-titre">
    <div class="sr-wrap">
      <div class="sr-tete">
        <p class="sr-surtitre">Pratique</p>
        <h2 class="sr-h2" id="sr-prep-titre">Bien se préparer</h2>
      </div>
      <ul class="sr-liste">
        <li><b>Un 4×4 avec chauffeur</b><span>Indispensable sur les pistes des parcs.</span></li>
        <li><b>Le permis d’accès au parc</b><span>À l’entrée du parc ou par votre opérateur.</span></li>
        <li><b>Un guide local</b><span>Pour les sentiers comme pour l’entrée dans les villages.</span></li>
        <li><b>Des vêtements couvrants</b><span>Aux couleurs neutres, avec des chaussures fermées.</span></li>
        <li><b>Des jumelles</b><span>Pour la faune comme pour les oiseaux.</span></li>
        <li><b>La santé</b><span>Moustiquaire imprégnée et traitement antipaludéen à jour&nbsp;: voir <a href="/sejourner/toutes-les-infos-pratiques/sante-et-vaccins">Santé et vaccins</a>.</span></li>
      </ul>
    </div>
  </section>

</div>

{{-- ====== PARTIR AVEC UN GUIDE (circuits des partenaires) ====== --}}
<x-aggregator.circuits-block
    pretitle="Partir avec un guide"
    title="Les circuits de nos partenaires"
    :limit="4"
 />

@verbatim
<style>
/* ===== Safari et randonnée — styles scopés .sr ===== */
.sr{--sr-ink:#14201a;--sr-soft:#4b5550;--sr-line:#e6e8e2;--sr-paper:#f7f6f1;--sr-green:var(--benin-green,#00a05d);--sr-dark:#0b3d2c;--sr-r:20px;color:var(--sr-ink)}
.sr *{box-sizing:border-box}
.sr-wrap{max-width:1240px;margin:0 auto;padding:0 24px}
.sr-surtitre{margin:0 0 10px;font-size:.74rem;font-weight:700;letter-spacing:.22em;text-transform:uppercase;color:var(--sr-green)}
.sr-h2{margin:0 0 12px;font-family:"Playfair Display",Georgia,serif;font-weight:500;font-size:clamp(1.9rem,3.4vw,2.8rem);line-height:1.12;color:var(--sr-ink)}
.sr-lead{margin:0;font-size:1.04rem;line-height:1.65;color:var(--sr-soft);max-width:720px}
.sr-tete{margin-bottom:36px}
.sr-tete--clair .sr-h2,.sr-tete--clair .sr-lead{color:#fff}
.sr-tete--clair .sr-lead{color:rgba(255,255,255,.82)}
.sr-tete--clair .sr-surtitre{color:var(--benin-yellow,#fcd116)}
.sr-petit{margin:16px 0 0;font-size:.88rem;color:var(--sr-soft)}
.sr svg{width:1em;height:1em}

/* chiffres */
.sr-chiffres{padding:10px 0 46px}
.sr-chiffres__in{display:grid;grid-template-columns:repeat(4,1fr);gap:0;border-top:1px solid var(--sr-line);border-bottom:1px solid var(--sr-line)}
.sr-chiffre{padding:26px 22px;border-right:1px solid var(--sr-line)}
.sr-chiffre:last-child{border-right:0}
.sr-chiffre b{display:block;font-family:"Playfair Display",Georgia,serif;font-weight:500;font-size:1.9rem;line-height:1.05;color:var(--sr-dark)}
.sr-chiffre span{display:block;margin-top:6px;font-size:.86rem;line-height:1.4;color:var(--sr-soft)}

/* alerte */
.sr-alerte{padding:0 0 70px}
.sr-alerte__carte{display:flex;gap:18px;align-items:flex-start;padding:26px 28px;border-radius:var(--sr-r);background:#fff8e6}
.sr-alerte__ic{flex:0 0 28px;color:#c98a00}
.sr-alerte__ic svg{width:28px;height:28px}
.sr-alerte__titre{margin:0 0 8px;font-size:1.15rem;font-weight:700;color:var(--sr-ink)}
.sr-alerte p{margin:0 0 10px;font-size:.95rem;line-height:1.6;color:#3d3a2f}
.sr-alerte__liens{display:flex;flex-wrap:wrap;gap:8px 18px;margin:4px 0 0!important}
.sr-alerte__liens a{font-weight:600;color:var(--sr-dark);text-decoration:underline;text-underline-offset:3px}

/* lieux */
.sr-lieux{padding:0 0 90px}
.sr-lieux__grille{display:grid;grid-template-columns:repeat(3,1fr);gap:26px}
.sr-lieu{display:flex;flex-direction:column;border-radius:var(--sr-r);overflow:hidden;background:#fff;box-shadow:0 0 0 1px var(--sr-line),0 22px 44px -32px rgba(11,61,44,.4);transition:transform .35s cubic-bezier(.2,.7,.2,1),box-shadow .35s}
.sr-lieu:hover{transform:translateY(-5px);box-shadow:0 0 0 1px var(--sr-line),0 30px 56px -30px rgba(11,61,44,.5)}
.sr-lieu__media{position:relative;aspect-ratio:4/3;overflow:hidden;background:#e8ebe4}
.sr-lieu__media img{width:100%;height:100%;object-fit:cover;display:block;transition:transform 1.4s cubic-bezier(.16,.84,.24,1)}
.sr-lieu:hover .sr-lieu__media img{transform:scale(1.08)}
.sr-lieu__ph{position:absolute;inset:0;display:grid;place-items:center;color:rgba(255,255,255,.9);background-image:radial-gradient(rgba(255,255,255,.12) 1px,transparent 1.5px),linear-gradient(135deg,#0b3d2c,#2a7a4e);background-size:14px 14px,100% 100%}
.sr-lieu__ph svg{width:64px;height:64px}
.sr-lieu__type{position:absolute;top:14px;left:14px;padding:6px 12px;border-radius:999px;font-size:.72rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:#fff;background:rgba(12,20,16,.45);backdrop-filter:blur(8px);-webkit-backdrop-filter:blur(8px)}
.sr-lieu__corps{display:flex;flex-direction:column;flex:1;padding:22px 22px 24px}
.sr-lieu__region{display:flex;align-items:center;gap:6px;margin:0 0 6px;font-size:.8rem;color:var(--sr-soft)}
.sr-lieu__region svg{color:var(--sr-green)}
.sr-lieu__titre{margin:0 0 8px;font-family:"Playfair Display",Georgia,serif;font-weight:500;font-size:1.4rem;line-height:1.2;color:var(--sr-ink)}
.sr-lieu__texte{margin:0 0 14px;font-size:.92rem;line-height:1.55;color:var(--sr-soft)}
.sr-avis{display:flex;align-items:center;gap:8px;margin:0 0 16px;font-size:.8rem;font-weight:600;color:var(--sr-ink)}
.sr-avis i{width:9px;height:9px;border-radius:50%;flex:0 0 9px}
.sr-avis--rouge i{background:#d6332d;box-shadow:0 0 0 4px rgba(214,51,45,.14)}
.sr-avis--orange i{background:#e59a12;box-shadow:0 0 0 4px rgba(229,154,18,.16)}
.sr-avis--vert i{background:var(--sr-green);box-shadow:0 0 0 4px rgba(0,160,93,.14)}
.sr-lieu__lien{margin-top:auto;display:inline-flex;align-items:center;gap:8px;font-weight:600;font-size:.92rem;color:var(--sr-dark);text-decoration:none}
.sr-lieu__lien svg{transition:transform .25s}
.sr-lieu__lien:hover svg{transform:translateX(4px)}
.sr-note{margin:18px 0 0;font-size:.8rem;color:var(--sr-soft)}

/* faune */
.sr-faune{padding:90px 0;background:radial-gradient(900px 480px at 85% 0%,rgba(0,160,93,.28),transparent 60%),var(--sr-dark);color:#fff}
.sr-faune__grille{display:grid;grid-template-columns:repeat(6,1fr);gap:14px}
.sr-animal{position:relative;display:block;aspect-ratio:3/4;border-radius:16px;overflow:hidden;color:#fff;text-decoration:none;isolation:isolate}
.sr-animal img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;z-index:-2;transition:transform 1.4s cubic-bezier(.16,.84,.24,1)}
.sr-animal::after{content:"";position:absolute;inset:0;z-index:-1;background:linear-gradient(180deg,rgba(0,0,0,0) 45%,rgba(4,14,9,.8))}
.sr-animal span{position:absolute;left:14px;bottom:12px;font-weight:600;font-size:.95rem}
.sr-animal:hover img{transform:scale(1.08)}
.sr-animal:hover,.sr-animal:focus{color:#fff}
.sr-faune__oiseaux{margin:24px 0 0}
.sr-faune__oiseaux a{display:inline-flex;align-items:center;gap:8px;color:var(--benin-yellow,#fcd116);font-weight:600;text-decoration:none}

/* randonnée */
.sr-rando{padding:90px 0}
.sr-rando__in{display:grid;grid-template-columns:1fr 1.1fr;gap:56px;align-items:center}
.sr-rando__visuel{position:relative;min-height:460px}
.sr-rando__visuel img{position:absolute;object-fit:cover;border-radius:var(--sr-r);box-shadow:0 26px 50px -28px rgba(11,61,44,.55)}
.sr-rando__visuel img:first-child{left:0;top:0;width:66%;height:88%}
.sr-rando__visuel img:last-child{right:0;bottom:0;width:48%;height:52%;border:6px solid #fff}
.sr-sentiers{list-style:none;counter-reset:s;margin:26px 0 0;padding:0;display:grid;gap:14px}
.sr-sentiers li{counter-increment:s;display:grid;grid-template-columns:44px 1fr;column-gap:14px;align-items:baseline}
.sr-sentiers li::before{content:counter(s,decimal-leading-zero);grid-row:1/3;font-family:"Playfair Display",Georgia,serif;font-size:1.4rem;color:var(--sr-green)}
.sr-sentiers b{font-size:1rem}
.sr-sentiers span{font-size:.9rem;color:var(--sr-soft)}

/* saison */
.sr-saison{padding:80px 0;background:var(--sr-paper)}
.sr-calendrier{display:grid;grid-template-columns:repeat(12,1fr);gap:6px;margin-bottom:26px}
.sr-mois{display:flex;align-items:flex-end;justify-content:center;height:84px;border-radius:12px;padding-bottom:10px;font-weight:700;font-size:.85rem}
.sr-mois--s{background:linear-gradient(180deg,#fcd116,#f0a91a);color:#3a2a00}
.sr-mois--p{background:linear-gradient(180deg,#8fd0b0,#3fae7c);color:#06301e}
.sr-mois--f{background:linear-gradient(180deg,#3fae7c,#0b6b46);color:#fff}
.sr-legende{display:grid;grid-template-columns:1fr 1fr;gap:24px}
.sr-legende p{margin:0;font-size:.94rem;line-height:1.6;color:var(--sr-soft)}
.sr-legende b{color:var(--sr-ink)}
.sr-legende i{display:inline-block;width:14px;height:14px;margin-right:10px;vertical-align:-2px;border-radius:4px}

/* préparation */
.sr-prep{padding:80px 0 90px}
.sr-liste{list-style:none;margin:0;padding:0;display:grid;grid-template-columns:repeat(3,1fr);gap:16px}
.sr-liste li{padding:22px;border-radius:16px;background:#fff;box-shadow:0 0 0 1px var(--sr-line)}
.sr-liste b{display:block;margin-bottom:4px;font-size:1rem}
.sr-liste span{font-size:.9rem;line-height:1.5;color:var(--sr-soft)}

@media (max-width:1024px){
  .sr-lieux__grille{grid-template-columns:repeat(2,1fr)}
  .sr-faune__grille{grid-template-columns:repeat(3,1fr)}
  .sr-liste{grid-template-columns:repeat(2,1fr)}
  .sr-chiffres__in{grid-template-columns:repeat(2,1fr)}
  .sr-chiffre:nth-child(2){border-right:0}
  .sr-chiffre:nth-child(-n+2){border-bottom:1px solid var(--sr-line)}
}
@media (max-width:700px){
  .sr-wrap{padding:0 16px}
  .sr-lieux__grille,.sr-liste,.sr-legende,.sr-rando__in{grid-template-columns:1fr}
  .sr-faune__grille{grid-template-columns:repeat(2,1fr)}
  .sr-rando__visuel{min-height:340px}
  .sr-calendrier{gap:3px}
  .sr-mois{height:60px;border-radius:8px;font-size:.75rem}
  .sr-alerte__carte{flex-direction:column;padding:22px}
  .sr-faune,.sr-rando,.sr-saison,.sr-prep{padding:60px 0}
  .sr-lieux{padding-bottom:60px}
}
@media (prefers-reduced-motion:reduce){.sr *{transition:none!important}}
</style>
@endverbatim

@verbatim
</div>
@endverbatim
{{-- BEGIN ecosysteme --}}
<x-aggregator.ecosysteme-block />
{{-- END ecosysteme --}}
@verbatim<link rel="stylesheet" href="/assets/ba-recit.css?v=20261119d"><script type="application/json" class="ba-recit-cfg">[{"v": "atouts", "inline": true, "src": ".sr-alerte__carte p:not(.sr-alerte__liens)", "min": 900, "stats": [{"re": "(deux grandes réserves) classées", "l": "au patrimoine mondial de l’UNESCO"}, {"re": "environ ([\\d\\s ]+km²)", "l": "pour la Pendjari, gérée avec African Parks"}, {"re": "plus de (\\d+) espèces d[’']oiseaux", "l": "espèces d’oiseaux"}, {"re": "(\\d+[\\s ]?\\d*\\s?FCFA) par jour", "l": "l’entrée par jour (visiteurs étrangers)"}], "kw": ["savanes", "mares", "forêts-galeries", "massifs granitiques", "cascades", "Tatas Somba", "guidage villageois", "accueil communautaire"], "note": "<strong>Bon à savoir :</strong> les visites des parcs de la Pendjari et du W se font avec un guide et un opérateur agréé, et certaines zones proches des frontières du nord sont réglementées. Renseignez-vous auprès du parc ou de votre opérateur avant de partir."}]</script><script src="/assets/ba-recit.js?v=20261119d" defer></script>@endverbatim
@endsection
