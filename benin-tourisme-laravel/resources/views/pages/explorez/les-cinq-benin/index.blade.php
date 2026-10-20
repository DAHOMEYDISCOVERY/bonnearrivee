{{-- Les cinq visages du Bénin — refonte 2026 : hero, chapô, mosaïque 5
     régions avec descriptions détaillées, bloc éditorial explicatif,
     CTA « La vie s'émerveille », écosystème. --}}
@extends('layouts.app')

@section('bodyClass', 'page menus-v2 prod gabarit-basic_page benin-hotels-page')
@section('mainAttributes')role="main" class="content-type-page basic_page" id="main-content"@endsection

@section('headGlobals')@verbatim<script>window.globals={"ancestors":{"chapter1":"Découvrez","chapter2":"Les cinq Bénin","chapter3":null},"context":"website","current_lang":"fr","current_locale":"fr","current_locale_region":"fr_FR","env":"prod","languages":["fr","nl"],"page_type":"basic_page","post_id":689,"post_title":"Les cinq Bénin","post_type":"page","site_key":"benin","tags":{"places":[],"seasons":[],"themes":[],"targets":[]},"woody_options_pages":{"search_url":2043,"weather_url":0},"woody_timezone":"Europe/Paris","search":{"api":{"uri":"","args":{"size":15,"threshold":0.5}}},"sheet_bordereaux_with_dates":[],"short_date_bordereaux":["FMA","ASC"],"tmaps":{"url_api":"","url_api_lazy":""},"hawwwai":{"sheet_options":{"nearby_distance_max":5000}}}</script>@endverbatim@endsection
@section('headSchema')@verbatim<script type="application/ld+json">{"@context":"https://schema.org","@type":"CollectionPage","name":"Les cinq visages du Bénin | Bonne Arrivée","url":"https://www.bonnearrivee.bj/explorez/les-cinq-benin","description":"Un pays étroit et long, cinq ensembles distincts : côte mémorielle, littoral atlantique, plateau royal, centre mystique, grand Nord."}</script>@endverbatim@endsection
@section('headImageOg')@verbatim<meta property="og:image" content="/assets/img/paysage-1920x960.webp"/>@endverbatim@endsection

@section('content')
@verbatim
<div class="content-wrapper no-terms">

    <div class="emerv-hero">
        <img class="emerv-hero__img"
             src="/assets/img/paysage-1920x960.webp"
             srcset="/assets/img/paysage-640x320.webp 640w, /assets/img/paysage-1200x600.webp 1200w, /assets/img/paysage-1920x960.webp 1920w"
             sizes="100vw"
             alt="Paysage béninois"
             fetchpriority="high">
        <div class="emerv-hero__overlay">
            <nav class="emerv-hero__breadcrumb" aria-label="Fil d'ariane">
                <ol>
                    <li><a href="/">Accueil</a></li>
                    <li aria-hidden="true">/</li>
                    <li><a href="/explorez">Découvrez</a></li>
                    <li aria-hidden="true">/</li>
                    <li aria-current="page">Les cinq Bénin</li>
                </ol>
            </nav>
            <div class="emerv-hero__center">
                <p class="emerv-hero__eyebrow">Explorer · Comprendre · Choisir</p>
                <h1 class="emerv-hero__title">Les cinq visages du Bénin</h1>
                <p class="emerv-hero__lead">Un pays étroit et long, cinq ensembles bien distincts. Choisissez votre point cardinal — chaque région raconte une histoire différente.</p>
            </div>
        </div>
    </div>

    <section class="benin-page-chapo">
        <p>Du golfe de Guinée aux collines de l'Atacora, sept cents kilomètres et cinq mondes se succèdent : la <strong>mémoire</strong> de la Route de l'Esclave à Ouidah, la <strong>modernité</strong> lagunaire de Cotonou, les <strong>palais</strong> royaux d'Abomey et Porto-Novo, la <strong>mystique</strong> des collines de Dassa, la <strong>savane</strong> et les parcs du grand Nord.</p>
    </section>

    <section class="benin-littoral" aria-label="Les cinq régions du Bénin">
        <div class="benin-littoral__head">
            <p class="benin-littoral__eyebrow">Cinq visages, cinq mondes</p>
            <h2 class="benin-littoral__title">Choisir son point cardinal</h2>
            <p class="benin-littoral__lead">Chaque région est un pays dans le pays. Voici comment le Bénin se lit du sud au nord — cliquez sur celle qui vous parle pour ouvrir la fiche complète.</p>
        </div>
        <div class="benin-littoral__grid">

            <a class="benin-littoral__card benin-littoral__card--wide" href="/explorez/les-cinq-benin/la-cote-memorielle">
                <img src="/assets/img/ouidah-porte-1200x600.webp" alt="La côte mémorielle" loading="lazy" decoding="async">
                <div class="benin-littoral__card-inner">
                    <span class="benin-littoral__cat">Sud-Ouest</span>
                    <h3>La côte mémorielle</h3>
                    <p>Ouidah, Grand-Popo, la Route de l'Esclave, la Porte du Non-Retour. Une bande côtière où la mémoire de la traite négrière habite chaque plage.</p>
                </div>
            </a>

            <a class="benin-littoral__card" href="/explorez/les-cinq-benin/le-littoral-atlantique">
                <img src="/assets/img/cotonou-640x480.webp" alt="Le littoral atlantique" loading="lazy" decoding="async">
                <div class="benin-littoral__card-inner">
                    <span class="benin-littoral__cat">Sud</span>
                    <h3>Le littoral atlantique</h3>
                    <p>Cotonou, Ganvié, le lac Nokoué. La capitale économique bruyante, les marchés géants, la vie lacustre à quelques minutes.</p>
                </div>
            </a>

            <a class="benin-littoral__card" href="/explorez/les-cinq-benin/le-plateau-royal">
                <img src="/assets/img/abomey-palais-640x480.webp" alt="Le plateau royal" loading="lazy" decoding="async">
                <div class="benin-littoral__card-inner">
                    <span class="benin-littoral__cat">Centre-Sud</span>
                    <h3>Le plateau royal</h3>
                    <p>Abomey, Porto-Novo, Kétou. Trois siècles de royaumes, les palais du Dahomey inscrits à l'UNESCO, les masques gèlèdè.</p>
                </div>
            </a>

            <a class="benin-littoral__card" href="/explorez/les-cinq-benin/le-centre-mystique">
                <img src="/assets/img/dassa-collines-640x480.webp" alt="Le centre mystique" loading="lazy" decoding="async">
                <div class="benin-littoral__card-inner">
                    <span class="benin-littoral__cat">Centre</span>
                    <h3>Le centre mystique</h3>
                    <p>Dassa-Zoumè, Savalou, Savé. Les collines granitiques, les grottes sacrées, les pèlerinages d'août.</p>
                </div>
            </a>

            <a class="benin-littoral__card benin-littoral__card--wide" href="/explorez/les-cinq-benin/le-grand-nord">
                <img src="/assets/img/atacora-1200x900.webp" alt="Le grand Nord" loading="lazy" decoding="async">
                <div class="benin-littoral__card-inner">
                    <span class="benin-littoral__cat">Nord</span>
                    <h3>Le grand Nord</h3>
                    <p>Natitingou, la chaîne de l'Atacora, les tata sombas, les parcs de la Pendjari et du W. La savane, les cascades, les éléphants — le Bénin sauvage.</p>
                </div>
            </a>

        </div>
    </section>

    <section class="benin-page-blocks" aria-label="Comprendre le pays">
        <article class="benin-page-block">
            <span class="benin-page-block__num">01</span>
            <h2 class="benin-page-block__title">Un pays étroit et long</h2>
            <p>Le Bénin s'étire sur près de <strong>700 kilomètres du nord au sud</strong> pour à peine <strong>325 kilomètres d'est en ouest</strong>. Cette forme allongée fait qu'on traverse en une journée de route trois climats, trois végétations, trois façons de vivre.</p>
            <p>La conséquence pratique : on ne visite pas le Bénin en un cercle, mais en une <strong>ligne</strong>. Les circuits classiques descendent ou remontent l'axe Cotonou – Natitingou, en s'arrêtant sur les rubans transversaux (côte, plateau, collines, Atacora).</p>
        </article>

        <article class="benin-page-block benin-page-block--highlight">
            <span class="benin-page-block__num">02</span>
            <h2 class="benin-page-block__title">Trois climats en une frontière</h2>
            <p>Sur la côte, un climat <strong>équatorial</strong> avec deux saisons des pluies (avril-juin et septembre-octobre) et une chaleur humide constante. Sur le plateau et les collines, un climat <strong>sub-équatorial</strong> plus contrasté. Au nord, un climat <strong>soudanien</strong> avec une longue saison sèche (novembre-avril) et une saison des pluies concentrée en été.</p>
            <p>La conséquence : le nord est <strong>ouvert d'octobre à mai</strong> pour les safaris (Pendjari), tandis que la côte se visite <strong>toute l'année</strong>, avec un pic entre novembre et février.</p>
        </article>

        <article class="benin-page-block">
            <span class="benin-page-block__num">03</span>
            <h2 class="benin-page-block__title">Trois écosystèmes, trois populations</h2>
            <p>Le sud est <strong>Fon, Yoruba, Adja</strong> — un pays de lagunes, de mangroves, de villages sur pilotis, de vodun vécu au quotidien. Le centre est <strong>Mahi, Nago</strong> — un plateau de granit, de savane arborée, de royaumes plus anciens. Le nord est <strong>Bariba, Somba, Peul</strong> — une savane herbeuse, des collines de l'Atacora, un islam mêlé aux traditions animistes, et les grands parcs animaliers.</p>
            <p>Chaque région parle sa langue, mange sa cuisine, danse sa danse. Ce que « visiter le Bénin » veut dire dépend de la région qu'on choisit.</p>
        </article>
    </section>

    <section class="benin-emerv-cta">
        <div>
            <p class="benin-emerv-cta__eyebrow">Sélection éditoriale</p>
            <h2>La vie s'émerveille au Bénin</h2>
            <p>Vingt-neuf lieux en une seule vue — plages, palais royaux, cités lacustres, cascades, cités vodun. Filtrez par ce qui vous parle et composez votre itinéraire.</p>
            <a href="/explorez/la-vie-semerveille-au-benin">
                Voir la sélection complète <span aria-hidden="true">→</span>
            </a>
        </div>
        <div class="benin-emerv-cta__images">
            <img src="/assets/img/plage-grand-popo-640x480.webp" alt="Grand-Popo">
            <img src="/assets/img/pendjari-elephant-640x480.webp" alt="Pendjari">
            <img src="/assets/img/ganvie-640x480.webp" alt="Ganvié">
            <img src="/assets/img/vodun-640x480.webp" alt="Vodun">
        </div>
    </section>
</div>
@endverbatim

<x-aggregator.ecosysteme-block />
@endsection
