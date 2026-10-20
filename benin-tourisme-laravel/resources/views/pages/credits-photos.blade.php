{{-- Généré par tools/convert.py — ne pas modifier à la main. --}}
{{--
    Page autonome : elle n'utilise pas le gabarit Woody. Écrite à la main lors de
    la rédaction du site, elle porte son propre <head> et ses propres styles.
--}}
@verbatim<!doctype html>
<html lang="fr"><head><meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Crédits — Bonne Arrivée</title>
<meta name="description" content="Crédits photos et cartographie du portail Bonne Arrivée — sources Wikimedia Commons, geoBoundaries, OpenStreetMap, Natural Earth.">
<link rel="icon" href="/assets/favicon.svg">
@endverbatim<link rel="canonical" href="{{ url()->current() }}">@verbatim
<style>
  :root{--green:#008751;--yellow:#fcd116;--red:#e8112d;--ink:#0b3d2c;
        --muted:#606060;--line:#e3e3e3;--bg:#fff;--md:15px;--xl:30px;--xxl:60px}
  *{box-sizing:border-box}
  body{margin:0;background:var(--bg);color:#1c1c1c;
       font:16px/1.6 Montserrat,-apple-system,BlinkMacSystemFont,"Segoe UI",Helvetica,Arial,sans-serif}
  header{background:var(--ink);color:#fff;padding:var(--xxl) var(--xl)}
  .flag{display:flex;width:120px;height:8px;margin-bottom:var(--md)}
  .flag i{flex:1} .flag i:nth-child(1){background:var(--green)}
  .flag i:nth-child(2){background:var(--yellow)} .flag i:nth-child(3){background:var(--red)}
  h1{margin:0 0 var(--md);font-size:2rem}
  h2{margin:var(--xxl) 0 var(--md);font-size:1.3rem;color:var(--ink)}
  h2:first-of-type{margin-top:0}
  header p{margin:0;max-width:64ch;opacity:.85} header a{color:var(--yellow)}
  main{max-width:1100px;margin:0 auto;padding:var(--xxl) var(--xl)}
  .note{border-left:4px solid var(--green);background:#f8f8f8;
        padding:var(--md) var(--xl);margin-bottom:var(--xl)}
  .note.osm{border-left-color:var(--yellow)}
  table{width:100%;border-collapse:collapse;font-size:.9rem}
  th{text-align:left;text-transform:uppercase;letter-spacing:.06em;font-size:.72rem;
     color:var(--muted);border-bottom:2px solid var(--ink);padding:0 var(--md) var(--md)}
  td{border-bottom:1px solid var(--line);padding:var(--md);vertical-align:middle}
  td.thumb{width:120px;padding-left:0}
  .thumb img{width:110px;height:62px;object-fit:cover;display:block;background:#eee}
  .file{color:var(--muted);font-size:.76rem;font-family:ui-monospace,Menlo,monospace}
  .lic{display:inline-block;background:#eef6f1;color:var(--ink);padding:2px 8px;
       font-size:.75rem;white-space:nowrap}
  a{color:var(--green)}
  dl{margin:0} dt{font-weight:700;color:var(--ink);margin-top:var(--md)}
  dd{margin:2px 0 0;color:#444}
  footer{border-top:1px solid var(--line);margin-top:var(--xxl);padding:var(--xl);
         text-align:center;color:var(--muted);font-size:.85rem}
  .wrap{overflow-x:auto}
  @media (max-width:767px){td.thumb,th:first-child{display:none}}
</style></head>
<body>
<header>
  <div class="flag"><i></i><i></i><i></i></div>
  <h1>Crédits</h1>
  <p>Toutes les photographies et toutes les données cartographiques de ce site sont
     réutilisables sous licence libre. Cette page fournit l’attribution exigée par
     ces licences. <a href="/">Retour à l’accueil</a></p>
</header>
<main>

  <div class="note">
    <strong>72 photographies</strong> composent la photothèque du site et
    <strong>29</strong> illustrent la carte interactive. Toutes viennent de
    <a href="https://commons.wikimedia.org" target="_blank" rel="noopener noreferrer">Wikimedia&nbsp;Commons</a>.
    Les licences CC&nbsp;BY et CC&nbsp;BY-SA imposent de citer l’auteur et la licence :
    c’est l’objet des tableaux ci-dessous. Les visuels en CC0 ou domaine public y figurent
    par transparence.
  </div>

  <div class="note osm">
    <strong>Données de la carte.</strong> Les tracés proviennent de trois sources, et
    <strong>les trois exigent leur mention</strong> :
    <dl>
      <dt>Frontières et départements</dt>
      <dd><a href="https://www.geoboundaries.org/" target="_blank" rel="noopener noreferrer">geoBoundaries</a> (ADM1, Bénin) — <strong>CC&nbsp;BY</strong>.
          Projection équirectangulaire : positions et distances indicatives.</dd>
      <dt>Fleuves, lacs, routes et voie ferrée</dt>
      <dd><a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener noreferrer">OpenStreetMap</a> —
          <strong>ODbL&nbsp;1.0</strong>, « Data © OpenStreetMap contributors ».</dd>
      <dt>Relief et fleuve Niger</dt>
      <dd><a href="https://www.naturalearthdata.com/" target="_blank" rel="noopener noreferrer">Natural Earth</a> — domaine public.</dd>
      <dt>Polices de la carte</dt>
      <dd>Bricolage Grotesque et Instrument Sans — SIL Open Font License.</dd>
    </dl>
  </div>

  <h2>Photothèque du site — 72 visuels</h2>
  <p style="color:var(--muted);margin-top:0">Chaque image du site est un recadrage de l’un de ces originaux.</p>
  <div class="wrap"><table>
    <thead><tr><th>Visuel</th><th>Sujet</th><th>Auteur</th><th>Licence</th><th>Source</th></tr></thead>
    <tbody>
    <tr>
      <td class="thumb"><img src="/assets/img/acadja-640x480.webp" alt="Pêcheurs en pirogue sur la lagune" loading="lazy"></td>
      <td><strong>Pêcheurs en pirogue sur la lagune</strong><br><span class="file">File:Cours du Mono au Bénin (14).jpg</span></td>
      <td>Ji-Elle</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/cours-du-mono-au-benin-14.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/img/aeroport-640x480.webp" alt="L&#x27;aéroport de Cotonou" loading="lazy"></td>
      <td><strong>L&#x27;aéroport de Cotonou</strong><br><span class="file">File:Cadjehoun Airport 7822.jpg</span></td>
      <td>GuillaumeG</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/cadjehoun-airport-7822.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/img/bouche-du-roy-640x480.webp" alt="Le lever du jour sur la Bouche du Roy" loading="lazy"></td>
      <td><strong>Le lever du jour sur la Bouche du Roy</strong><br><span class="file">File:Sunrise à l&#x27;embouchure de la Bouche du Roy.jpg</span></td>
      <td>Padonou Dotou</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/sunrise-a-l-embouchure-de-la-bouche-du-roy.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/img/coton-640x480.webp" alt="Un champ de coton" loading="lazy"></td>
      <td><strong>Un champ de coton</strong><br><span class="file">File:Bénin-Champ de coton.jpg</span></td>
      <td>Ji-Elle</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/benin-champ-de-coton.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/img/dantokpa-640x480.webp" alt="Le marché Dantokpa à Cotonou" loading="lazy"></td>
      <td><strong>Le marché Dantokpa à Cotonou</strong><br><span class="file">File:Marché DANTOKPA.jpg</span></td>
      <td>jbdodane</td>
      <td><span class="lic">CC BY 2.0</span></td>
      <td><a href="/assets/img/wikimedia/marche-dantokpa.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/img/dassa-collines-640x480.webp" alt="Les collines de Dassa-Zoumè" loading="lazy"></td>
      <td><strong>Les collines de Dassa-Zoumè</strong><br><span class="file">File:Vue de Dassa depuis la colline de Kamaté.jpg</span></td>
      <td>Padonou Dotou</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/vue-de-dassa-depuis-la-colline-de-kamate.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/img/ecole-640x480.webp" alt="Écoliers sur le chemin de l&#x27;école" loading="lazy"></td>
      <td><strong>Écoliers sur le chemin de l&#x27;école</strong><br><span class="file">File:DEGAN Gabin -( school children on their way to school ).jpg</span></td>
      <td>DEGAN Gabin</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/degan-gabin-school-children-on-their-way-to-school.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/img/egungun-640x480.webp" alt="Des Egungun en procession" loading="lazy"></td>
      <td><strong>Des Egungun en procession</strong><br><span class="file">File:Dans folklorique de Kouvitô (Egungun) au Bénin 03.jpg</span></td>
      <td>DoussFrance</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/dans-folklorique-de-kouvito-egungun-au-benin-03.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/img/foret-kpasse-640x480.webp" alt="La forêt sacrée de Kpassè" loading="lazy"></td>
      <td><strong>La forêt sacrée de Kpassè</strong><br><span class="file">File:Forêt sacrée de Ouidah 12.jpg</span></td>
      <td>Ivannovna</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/foret-sacree-de-ouidah-12.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/img/franc-cfa-640x480.webp" alt="Billets de franc CFA" loading="lazy"></td>
      <td><strong>Billets de franc CFA</strong><br><span class="file">File:Billet de monnaie - Bénin.jpg</span></td>
      <td>Houss 2020</td>
      <td><span class="lic">CC0</span></td>
      <td><a href="/assets/img/wikimedia/billet-de-monnaie-benin.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/img/frontiere-640x480.webp" alt="Le tampon d&#x27;entrée au Bénin" loading="lazy"></td>
      <td><strong>Le tampon d&#x27;entrée au Bénin</strong><br><span class="file">File:Benin entry stamp.jpg</span></td>
      <td>Vitor Abdala</td>
      <td><span class="lic">Public domain</span></td>
      <td><a href="/assets/img/wikimedia/benin-entry-stamp.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/img/gare-routiere-640x480.webp" alt="Un car de transport interurbain" loading="lazy"></td>
      <td><strong>Un car de transport interurbain</strong><br><span class="file">File:Bus de la Fondation zinsou, Ouidah au Bénin.jpg</span></td>
      <td>Saliousoft</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/bus-de-la-fondation-zinsou-ouidah-au-benin.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/img/gingembre-640x480.webp" alt="Vendeurs de boisson au gingembre" loading="lazy"></td>
      <td><strong>Vendeurs de boisson au gingembre</strong><br><span class="file">File:Epices devant des vélos 02.jpg</span></td>
      <td>Fawaz.tairou</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/epices-devant-des-velos-02.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/img/grand-popo2-640x480.webp" alt="La plage de Grand-Popo" loading="lazy"></td>
      <td><strong>La plage de Grand-Popo</strong><br><span class="file">File:Mono river estuary in Avloh, Grand-Popo, Benin.jpg</span></td>
      <td>Kulttuurinavigaattori</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/mono-river-estuary-in-avloh-grand-popo-benin.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/img/hebergement-640x480.webp" alt="Un écolodge au Bénin" loading="lazy"></td>
      <td><strong>Un écolodge au Bénin</strong><br><span class="file">File:Lodge dans le parc national de la Pendjari.jpg</span></td>
      <td>Padonou Dotou</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/lodge-dans-le-parc-national-de-la-pendjari.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/img/maquis-640x480.webp" alt="La terrasse d&#x27;un maquis" loading="lazy"></td>
      <td><strong>La terrasse d&#x27;un maquis</strong><br><span class="file">File:Intérieur d&#x27;un bar Restaurant a Cotonou Bénin2.jpg</span></td>
      <td>Adoscam</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/interieur-d-un-bar-restaurant-a-cotonou-benin2.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/img/marche-rue-640x480.webp" alt="Un marché de rue" loading="lazy"></td>
      <td><strong>Un marché de rue</strong><br><span class="file">File:Street market view in Come Benin Dec 2017.jpg</span></td>
      <td>Kulttuurinavigaattori</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/street-market-view-in-come-benin-dec-2017.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/img/nikki-640x480.webp" alt="La Gaani à cheval, à Nikki" loading="lazy"></td>
      <td><strong>La Gaani à cheval, à Nikki</strong><br><span class="file">File:Plaisir d&#x27;un équestre au Benin. 05.jpg</span></td>
      <td>FiossifrejusduBénin</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/plaisir-d-un-equestre-au-benin-05.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/img/ouidah-musee-640x480.webp" alt="Le musée d&#x27;histoire de Ouidah" loading="lazy"></td>
      <td><strong>Le musée d&#x27;histoire de Ouidah</strong><br><span class="file">File:Ouidah history museum.jpg</span></td>
      <td>Francisco Anzola</td>
      <td><span class="lic">CC BY 2.0</span></td>
      <td><a href="/assets/img/wikimedia/ouidah-history-museum.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/img/parc-w-640x480.webp" alt="La Mékrou dans le parc national du W" loading="lazy"></td>
      <td><strong>La Mékrou dans le parc national du W</strong><br><span class="file">File:Mekrou river in W-National Park MS 6380.JPG</span></td>
      <td>Marco Schmidt</td>
      <td><span class="lic">CC BY-SA 3.0</span></td>
      <td><a href="/assets/img/wikimedia/mekrou-river-in-w-national-park-ms-6380.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/img/piment-640x480.webp" alt="Piments sur un étal" loading="lazy"></td>
      <td><strong>Piments sur un étal</strong><br><span class="file">File:Piment-vert-jaune-rouge.jpg</span></td>
      <td>Richardspa</td>
      <td><span class="lic">CC0</span></td>
      <td><a href="/assets/img/wikimedia/piment-vert-jaune-rouge.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/img/sante-640x480.webp" alt="Un centre de santé" loading="lazy"></td>
      <td><strong>Un centre de santé</strong><br><span class="file">File:Centre de santé de Zogbodomey au Bénin 02.jpg</span></td>
      <td>Adoscam</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/centre-de-sante-de-zogbodomey-au-benin-02.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/img/varan-640x480.webp" alt="Un varan du Nil" loading="lazy"></td>
      <td><strong>Un varan du Nil</strong><br><span class="file">File:Le Varan du Nil a la Pendjari.jpg</span></td>
      <td>AMADOU BAHLEMAN FARID</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/le-varan-du-nil-a-la-pendjari.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/img/vodun-days-640x480.webp" alt="La fête du Vodun à Ouidah" loading="lazy"></td>
      <td><strong>La fête du Vodun à Ouidah</strong><br><span class="file">File:10 Janvier 2023, Fête de vodoun à Ouidah 03.jpg</span></td>
      <td>Dieu229</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/10-janvier-2023-fete-de-vodoun-a-ouidah-03.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/img/abomey-basrelief-360x203.webp" alt="Le musée historique d’Abomey" loading="lazy"></td>
      <td><strong>Le musée historique d’Abomey</strong><br><span class="file">File:Entrée du Musée historique d&#x27;Abomey (2).jpg</span></td>
      <td>Ji-Elle</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/entree-du-musee-historique-d-abomey-2.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/img/abomey-palais-360x203.webp" alt="Les palais royaux d’Abomey" loading="lazy"></td>
      <td><strong>Les palais royaux d’Abomey</strong><br><span class="file">File:Royal Palaces of Abomey-133471.jpg</span></td>
      <td>Karalyn Monteil</td>
      <td><span class="lic">CC BY-SA 3.0 igo</span></td>
      <td><a href="/assets/img/wikimedia/royal-palaces-of-abomey-133471.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/img/amazone-360x203.webp" alt="Les Amazones du Dahomey" loading="lazy"></td>
      <td><strong>Les Amazones du Dahomey</strong><br><span class="file">File:Dahomey Amazons 1793.png</span></td>
      <td>Dalziel, Archibald (1740–1811)</td>
      <td><span class="lic">Public domain</span></td>
      <td><a href="/assets/img/wikimedia/dahomey-amazons-1793.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/img/ananas-360x203.webp" alt="L’ananas du Bénin" loading="lazy"></td>
      <td><strong>L’ananas du Bénin</strong><br><span class="file">File:Ananas du Bénin.jpg</span></td>
      <td>Saliousoft</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/ananas-du-benin.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/img/artisanat-360x203.webp" alt="Artisanat traditionnel béninois" loading="lazy"></td>
      <td><strong>Artisanat traditionnel béninois</strong><br><span class="file">File:Tissage de filets de pêche artisanale.jpg</span></td>
      <td>Sunday Berlioz KAKPO</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/tissage-de-filets-de-peche-artisanale.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/img/atacora-360x203.webp" alt="Les paysages de l’Atacora" loading="lazy"></td>
      <td><strong>Les paysages de l’Atacora</strong><br><span class="file">File:ATACORA.jpg</span></td>
      <td>KOUAGOU Damien</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/atacora.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/img/cajou-360x203.webp" alt="L’anacarde, l’or jaune du Bénin" loading="lazy"></td>
      <td><strong>L’anacarde, l’or jaune du Bénin</strong><br><span class="file">File:Anacarde.jpg</span></td>
      <td>KOUAGOU Damien</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/anacarde.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/img/chutes-kota-360x203.webp" alt="Les chutes de Kota" loading="lazy"></td>
      <td><strong>Les chutes de Kota</strong><br><span class="file">File:Chute de Kota 2024.jpg</span></td>
      <td>Line 1921</td>
      <td><span class="lic">CC0</span></td>
      <td><a href="/assets/img/wikimedia/chute-de-kota-2024.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/img/chutes-tanougou-360x203.webp" alt="Les chutes de Tanougou" loading="lazy"></td>
      <td><strong>Les chutes de Tanougou</strong><br><span class="file">File:Chutes de Tanougou (2).jpg</span></td>
      <td>Ji-Elle</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/chutes-de-tanougou-2.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/img/cotonou-360x203.webp" alt="Une rue de Cotonou" loading="lazy"></td>
      <td><strong>Une rue de Cotonou</strong><br><span class="file">File:Lever de soleil sur l&#x27;Esplanade de l&#x27;amazone à Co</span></td>
      <td>Fawaz.tairou</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/lever-de-soleil-sur-l-esplanade-de-l-amazone-a-cotonou.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/img/cuisine-360x203.webp" alt="La cuisine béninoise" loading="lazy"></td>
      <td><strong>La cuisine béninoise</strong><br><span class="file">File:Arts culinaire du Bénin - Les plats de la cuisine béninoise</span></td>
      <td>Saliousoft</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/arts-culinaire-du-benin-les-plats-de-la-cuisine-beninoise-30.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/img/danse-360x203.webp" alt="Danseurs traditionnels béninois" loading="lazy"></td>
      <td><strong>Danseurs traditionnels béninois</strong><br><span class="file">File:Benin Art Fair 2025 traditional dancers 07.jpg</span></td>
      <td>Dappasolomon001</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/benin-art-fair-2025-traditional-dancers-07.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/img/dassa-360x203.webp" alt="Les collines de Dassa-Zoumè" loading="lazy"></td>
      <td><strong>Les collines de Dassa-Zoumè</strong><br><span class="file">File:Vue de Dassa depuis la colline de Kamaté.jpg</span></td>
      <td>Padonou Dotou</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/vue-de-dassa-depuis-la-colline-de-kamate.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/img/epices-360x203.webp" alt="Le piment, base des sauces béninoises" loading="lazy"></td>
      <td><strong>Le piment, base des sauces béninoises</strong><br><span class="file">File:Piment vert écrasé dans une canari au Bénin.jpg</span></td>
      <td>Adoscam</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/piment-vert-ecrase-dans-une-canari-au-benin.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/img/foret-sacree-360x203.webp" alt="La forêt sacrée de Kpassè à Ouidah" loading="lazy"></td>
      <td><strong>La forêt sacrée de Kpassè à Ouidah</strong><br><span class="file">File:Portail d&#x27;entrée de la Forêt sacrée de KPASSE OUIDAH a</span></td>
      <td>Yelouassi</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/portail-d-entree-de-la-foret-sacree-de-kpasse-ouidah-au-benin.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/img/ganvie-360x203.webp" alt="Ganvié, la cité lacustre du lac Nokoué" loading="lazy"></td>
      <td><strong>Ganvié, la cité lacustre du lac Nokoué</strong><br><span class="file">File:The village of Ganvié on Lake Nokoué.jpg</span></td>
      <td>Dr. Ondřej Havelka (cestovatel)</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/the-village-of-ganvie-on-lake-nokoue.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/img/gelede-360x203.webp" alt="Masque Gèlèdè" loading="lazy"></td>
      <td><strong>Masque Gèlèdè</strong><br><span class="file">File:Brooklyn Museum 22.1584 Gelede Mask (2).jpg</span></td>
      <td>Unknown authorUnknown author</td>
      <td><span class="lic">CC BY 3.0</span></td>
      <td><a href="/assets/img/wikimedia/brooklyn-museum-22-1584-gelede-mask-2.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/img/hotel-360x203.webp" alt="Hébergement au Bénin" loading="lazy"></td>
      <td><strong>Hébergement au Bénin</strong><br><span class="file">File:FRONT VIEW OF HOTEL MARIE STELLA IN COTONOU, BENIN.jpg</span></td>
      <td>Adoscam</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/front-view-of-hotel-marie-stella-in-cotonou-benin.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/img/karite-360x203.webp" alt="Le karité" loading="lazy"></td>
      <td><strong>Le karité</strong><br><span class="file">File:Parc national de la Pendjari-Vitellaria paradoxa (3).jpg</span></td>
      <td>Ji-Elle</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/parc-national-de-la-pendjari-vitellaria-paradoxa-3.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/img/lac-aheme-360x203.webp" alt="Le lac Ahémé" loading="lazy"></td>
      <td><strong>Le lac Ahémé</strong><br><span class="file">File:Lac aheme à Guezin.jpg</span></td>
      <td>Rachad sanoussi</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/lac-aheme-a-guezin.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/img/mangrove-360x203.webp" alt="La mangrove du fleuve Mono" loading="lazy"></td>
      <td><strong>La mangrove du fleuve Mono</strong><br><span class="file">File:Cours du Mono au Bénin (40).jpg</span></td>
      <td>Ji-Elle</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/cours-du-mono-au-benin-40.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/img/marche-360x203.webp" alt="Le marché Dantokpa à Cotonou" loading="lazy"></td>
      <td><strong>Le marché Dantokpa à Cotonou</strong><br><span class="file">File:Autoroute sous la passerelle du marché dantokpa à Cotonou B</span></td>
      <td>Adoscam</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/autoroute-sous-la-passerelle-du-marche-dantokpa-a-cotonou-benin.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/img/mariage-360x203.webp" alt="Un mariage traditionnel au Bénin" loading="lazy"></td>
      <td><strong>Un mariage traditionnel au Bénin</strong><br><span class="file">File:On the way to giving gifts for dowry (traditional marriage)</span></td>
      <td>Adoscam</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/on-the-way-to-giving-gifts-for-dowry-traditional-marriage-in-benin.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/img/natitingou-360x203.webp" alt="Natitingou, porte de l’Atacora" loading="lazy"></td>
      <td><strong>Natitingou, porte de l’Atacora</strong><br><span class="file">File:Benin Natitingou.JPG</span></td>
      <td>Baliola</td>
      <td><span class="lic">CC BY-SA 3.0</span></td>
      <td><a href="/assets/img/wikimedia/benin-natitingou.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/img/oiseau-360x203.webp" alt="Faune sauvage du Bénin" loading="lazy"></td>
      <td><strong>Faune sauvage du Bénin</strong><br><span class="file">File:Oiseau en alimentation.jpg</span></td>
      <td>Florentin66</td>
      <td><span class="lic">CC BY 4.0</span></td>
      <td><a href="/assets/img/wikimedia/oiseau-en-alimentation.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/img/ouidah-porte-360x203.webp" alt="La Porte du Non-Retour à Ouidah" loading="lazy"></td>
      <td><strong>La Porte du Non-Retour à Ouidah</strong><br><span class="file">File:Vue de nuit la porte du non retour a Ouidah 01.jpg</span></td>
      <td>Chiemelacebelae</td>
      <td><span class="lic">CC0</span></td>
      <td><a href="/assets/img/wikimedia/vue-de-nuit-la-porte-du-non-retour-a-ouidah-01.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/img/ouidah-pythons-360x203.webp" alt="Le Temple des Pythons à Ouidah" loading="lazy"></td>
      <td><strong>Le Temple des Pythons à Ouidah</strong><br><span class="file">File:Entrée du Temple des Pythons (Ouidah).jpg</span></td>
      <td>Ji-Elle</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/entree-du-temple-des-pythons-ouidah.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/img/ouidah-route-360x203.webp" alt="La Route de l’Esclave à Ouidah" loading="lazy"></td>
      <td><strong>La Route de l’Esclave à Ouidah</strong><br><span class="file">File:Route des esclaves à Ouidah au Bénin.jpg</span></td>
      <td>Hinnou Gontran Serge</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/route-des-esclaves-a-ouidah-au-benin.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/img/pagne-360x203.webp" alt="Le pagne béninois" loading="lazy"></td>
      <td><strong>Le pagne béninois</strong><br><span class="file">File:Tissu Woodin.jpg</span></td>
      <td>Osia ZANNOU</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/tissu-woodin.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/img/paysage-360x203.webp" alt="Paysage de l’Atacora" loading="lazy"></td>
      <td><strong>Paysage de l’Atacora</strong><br><span class="file">File:Atacora-Habitations et manguiers.jpg</span></td>
      <td>Ji-Elle</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/atacora-habitations-et-manguiers.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/img/pecheur-360x203.webp" alt="Pêcheurs sur le lac Nokoué" loading="lazy"></td>
      <td><strong>Pêcheurs sur le lac Nokoué</strong><br><span class="file">File:Fishermen near Ganvie Benin 2017.jpg</span></td>
      <td>Kulttuurinavigaattori</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/fishermen-near-ganvie-benin-2017.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/img/pendjari-babouin-360x203.webp" alt="Babouin dans le parc de la Pendjari" loading="lazy"></td>
      <td><strong>Babouin dans le parc de la Pendjari</strong><br><span class="file">File:Baboon, Pendjari (49024107223).jpg</span></td>
      <td>Raini Svensson</td>
      <td><span class="lic">Public domain</span></td>
      <td><a href="/assets/img/wikimedia/baboon-pendjari-49024107223.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/img/pendjari-buffle-360x203.webp" alt="Troupeau de buffles dans la Pendjari" loading="lazy"></td>
      <td><strong>Troupeau de buffles dans la Pendjari</strong><br><span class="file">File:Un troupeau de buffle dans le parc national de la pendjari </span></td>
      <td>Djouberou yekini</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/un-troupeau-de-buffle-dans-le-parc-national-de-la-pendjari-au-benini.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/img/pendjari-elephant-360x203.webp" alt="Éléphant du parc national de la Pendjari" loading="lazy"></td>
      <td><strong>Éléphant du parc national de la Pendjari</strong><br><span class="file">File:Eléphants in Pendjari.jpg</span></td>
      <td>Farid AMADOU BAHLEMAN</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/elephants-in-pendjari.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/img/pendjari-hippo-360x203.webp" alt="Hippopotames de la Pendjari" loading="lazy"></td>
      <td><strong>Hippopotames de la Pendjari</strong><br><span class="file">File:Parc national de la Pendjari-Hippopotames à la Mare Sacrée </span></td>
      <td>Ji-Elle</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/parc-national-de-la-pendjari-hippopotames-a-la-mare-sacree-3.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/img/pendjari-lion-360x203.webp" alt="Lion du parc national de la Pendjari" loading="lazy"></td>
      <td><strong>Lion du parc national de la Pendjari</strong><br><span class="file">File:Lion, Pendjari National Park (49039087968).jpg</span></td>
      <td>Raini Svensson</td>
      <td><span class="lic">Public domain</span></td>
      <td><a href="/assets/img/wikimedia/lion-pendjari-national-park-49039087968.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/img/pendjari-savane-360x203.webp" alt="Savane du parc national de la Pendjari" loading="lazy"></td>
      <td><strong>Savane du parc national de la Pendjari</strong><br><span class="file">File:Pendjari.JPG</span></td>
      <td>Martin Wegmann</td>
      <td><span class="lic">CC BY-SA 3.0</span></td>
      <td><a href="/assets/img/wikimedia/pendjari.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/img/peuple-360x203.webp" alt="Rencontres au Bénin" loading="lazy"></td>
      <td><strong>Rencontres au Bénin</strong><br><span class="file">File:Enfants jouant Sékou (Bénin) en se transportant à l&#x27;ai</span></td>
      <td>Adoscam</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/enfants-jouant-sekou-benin-en-se-transportant-a-l-aide-d-une-charrette-a-deux-ro.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/img/pirogue-360x203.webp" alt="Pirogue sur le lac Nokoué" loading="lazy"></td>
      <td><strong>Pirogue sur le lac Nokoué</strong><br><span class="file">File:Pirogue taxi a Ganvié la cité lacustre du sud du Bénin.jpg</span></td>
      <td>Ahya ATINDEHOU</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/pirogue-taxi-a-ganvie-la-cite-lacustre-du-sud-du-benin.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/img/plage-fidjrosse-360x203.webp" alt="La plage de Fidjrossè à Cotonou" loading="lazy"></td>
      <td><strong>La plage de Fidjrossè à Cotonou</strong><br><span class="file">File:Fidjrossè Plage (Cotonou).jpg</span></td>
      <td>Rofik Adam</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/fidjrosse-plage-cotonou.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/img/plage-grand-popo-360x203.webp" alt="La plage de Grand-Popo" loading="lazy"></td>
      <td><strong>La plage de Grand-Popo</strong><br><span class="file">File:Mono river estuary in Avloh, Grand-Popo, Benin.jpg</span></td>
      <td>Kulttuurinavigaattori</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/mono-river-estuary-in-avloh-grand-popo-benin.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/img/porto-novo-360x203.webp" alt="La Grande Mosquée de Porto-Novo" loading="lazy"></td>
      <td><strong>La Grande Mosquée de Porto-Novo</strong><br><span class="file">File:Grande Mosquee Porto-Novo Benin Joseph Herve Ahissou.jpg</span></td>
      <td>GBETONGNINOUGBO JOSEPH HERVE AHISSOU</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/grande-mosquee-porto-novo-benin-joseph-herve-ahissou.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/img/restaurant-360x203.webp" alt="Table béninoise" loading="lazy"></td>
      <td><strong>Table béninoise</strong><br><span class="file">File:Fonio couscous served at the Restaurant du Fonio in Natitin</span></td>
      <td>Crop Trust</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/fonio-couscous-served-at-the-restaurant-du-fonio-in-natitingou-benin-55015102555.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/img/sunset-360x203.webp" alt="Coucher de soleil sur le Bénin" loading="lazy"></td>
      <td><strong>Coucher de soleil sur le Bénin</strong><br><span class="file">File:Coucher soleil benin.jpg</span></td>
      <td>GbetoC</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/coucher-soleil-benin.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/img/tata-somba-360x203.webp" alt="Une tata somba du pays Betammaribe" loading="lazy"></td>
      <td><strong>Une tata somba du pays Betammaribe</strong><br><span class="file">File:Tata somba, la maison traditionnelle à étage.jpg</span></td>
      <td>Justin ABADJAYE</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/tata-somba-la-maison-traditionnelle-a-etage.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/img/vodun-360x203.webp" alt="La fête du Vodoun à Ouidah" loading="lazy"></td>
      <td><strong>La fête du Vodoun à Ouidah</strong><br><span class="file">File:10 Janvier 2023, Fête de vodoun à Ouidah 04.jpg</span></td>
      <td>Dieu229</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/10-janvier-2023-fete-de-vodoun-a-ouidah-04.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/img/zangbeto-360x203.webp" alt="Rituel de Zangbéto" loading="lazy"></td>
      <td><strong>Rituel de Zangbéto</strong><br><span class="file">File:Zangbeto on board.jpg</span></td>
      <td>GBETONGNINOUGBO JOSEPH HERVE AHISSOU</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/zangbeto-on-board.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/img/zemidjan-360x203.webp" alt="Zémidjan, le taxi-moto de Cotonou" loading="lazy"></td>
      <td><strong>Zémidjan, le taxi-moto de Cotonou</strong><br><span class="file">File:Zemidjan de cotonou Bénin.jpg</span></td>
      <td>Rachad sanoussi</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/zemidjan-de-cotonou-benin.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    </tbody>
  </table></div>

  <h2>Carte interactive — 29 visuels</h2>
  <p style="color:var(--muted);margin-top:0">Les vingt-neuf lieux repérés sur la carte du Bénin.</p>
  <div class="wrap"><table>
    <thead><tr><th>Visuel</th><th>Lieu</th><th>Auteur</th><th>Licence</th><th>Source</th></tr></thead>
    <tbody>
    <tr>
      <td class="thumb"><img src="/assets/atlas-photos/abomey.webp" alt="Palais royaux d’Abomey" loading="lazy"></td>
      <td><strong>Palais royaux d’Abomey</strong><br><span class="file">Royal Palaces of Abomey-133471.jpg</span></td>
      <td>Karalyn Monteil</td>
      <td><span class="lic">CC BY-SA 3.0 igo</span></td>
      <td><a href="/assets/img/wikimedia/royal-palaces-of-abomey-133471.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/atlas-photos/aeroport.webp" alt="Aéroport de Cotonou" loading="lazy"></td>
      <td><strong>Aéroport de Cotonou</strong><br><span class="file">Avion de la Royal Air Maroc à Cotonou 03.jpg</span></td>
      <td>Houss 2020</td>
      <td><span class="lic">CC0</span></td>
      <td><a href="/assets/img/wikimedia/avion-royal-air-maroc-cotonou.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/atlas-photos/aheme.webp" alt="Lac Ahémé" loading="lazy"></td>
      <td><strong>Lac Ahémé</strong><br><span class="file">Lac aheme à Guezin.jpg</span></td>
      <td>Rachad sanoussi</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/lac-aheme-a-guezin.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/atlas-photos/allada.webp" alt="Allada" loading="lazy"></td>
      <td><strong>Allada</strong><br><span class="file">Divinite-vodun-legba-lac-sacre-awouto-allada-benin.jpg</span></td>
      <td>Netho639</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/divinite-vodun-legba-lac-sacre-awouto-allada-benin.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/atlas-photos/boukoumbe.webp" alt="Tata somba à Boukoumbé" loading="lazy"></td>
      <td><strong>Tata somba à Boukoumbé</strong><br><span class="file">Tata somba, la maison traditionnelle à étage.jpg</span></td>
      <td>Justin ABADJAYE</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/tata-somba-la-maison-traditionnelle-a-etage.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/atlas-photos/cotonou.webp" alt="Esplanade de l’Amazone, Cotonou" loading="lazy"></td>
      <td><strong>Esplanade de l’Amazone, Cotonou</strong><br><span class="file">Lever de soleil sur l&#x27;Esplanade de l&#x27;amazone à Cotonou</span></td>
      <td>Fawaz.tairou</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/lever-de-soleil-sur-l-esplanade-de-l-amazone-a-cotonou.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/atlas-photos/dassa.webp" alt="Collines de Dassa-Zoumè" loading="lazy"></td>
      <td><strong>Collines de Dassa-Zoumè</strong><br><span class="file">Vue de Dassa depuis la colline de Kamaté.jpg</span></td>
      <td>Padonou Dotou</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/vue-de-dassa-depuis-la-colline-de-kamate.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/atlas-photos/djougou.webp" alt="Djougou" loading="lazy"></td>
      <td><strong>Djougou</strong><br><span class="file">Straßenbild djougou.JPG</span></td>
      <td>Gregor Rom</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/stra-enbild-djougou.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/atlas-photos/dogbo.webp" alt="Dogbo" loading="lazy"></td>
      <td><strong>Dogbo</strong><br><span class="file">Salle d&#x27;accueil du site homme à queue à Dogbo (Bénin).jpg</span></td>
      <td>Adoscam</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/salle-d-accueil-du-site-homme-a-queue-a-dogbo-benin.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/atlas-photos/ganvie.webp" alt="Ganvié" loading="lazy"></td>
      <td><strong>Ganvié</strong><br><span class="file">The village of Ganvié on Lake Nokoué.jpg</span></td>
      <td>Dr. Ondřej Havelka (cestovatel)</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/the-village-of-ganvie-on-lake-nokoue.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/atlas-photos/grand-popo.webp" alt="Embouchure du Mono, Grand-Popo" loading="lazy"></td>
      <td><strong>Embouchure du Mono, Grand-Popo</strong><br><span class="file">Mono river estuary in Avloh, Grand-Popo, Benin.jpg</span></td>
      <td>Kulttuurinavigaattori</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/mono-river-estuary-in-avloh-grand-popo-benin.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/atlas-photos/ketou.webp" alt="Kétou" loading="lazy"></td>
      <td><strong>Kétou</strong><br><span class="file">Gèlèdé masked dancer, Kétou, Benin.jpg</span></td>
      <td>Jonathanbwilson1</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/gelede-masked-dancer-ketou-benin.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/atlas-photos/kota.webp" alt="Chutes de Kota" loading="lazy"></td>
      <td><strong>Chutes de Kota</strong><br><span class="file">Baignade aux chutes de Kota.jpg</span></td>
      <td>Ji-Elle</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/baignade-aux-chutes-de-kota.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/atlas-photos/lama.webp" alt="Forêt classée de la Lama" loading="lazy"></td>
      <td><strong>Forêt classée de la Lama</strong><br><span class="file">Natural forest of Lama in Benin.jpg</span></td>
      <td>Rolex2019</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/natural-forest-of-lama-in-benin.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/atlas-photos/malanville.webp" alt="Malanville" loading="lazy"></td>
      <td><strong>Malanville</strong><br><span class="file">On the edge from the Niger river, Malanville.jpg</span></td>
      <td>Banasylve</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/on-the-edge-from-the-niger-river-malanville.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/atlas-photos/natitingou.webp" alt="Natitingou" loading="lazy"></td>
      <td><strong>Natitingou</strong><br><span class="file">Benin Natitingou.JPG</span></td>
      <td>Baliola</td>
      <td><span class="lic">CC BY-SA 3.0</span></td>
      <td><a href="/assets/img/wikimedia/benin-natitingou.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/atlas-photos/nikki.webp" alt="Nikki" loading="lazy"></td>
      <td><strong>Nikki</strong><br><span class="file">Place publique pour la Gaani, Nikki, département du Borgou, Béni</span></td>
      <td>Saliousoft</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/place-publique-pour-la-gaani-nikki-departement-du-borgou-benin.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/atlas-photos/ouidah.webp" alt="Porte du Non-Retour, Ouidah" loading="lazy"></td>
      <td><strong>Porte du Non-Retour, Ouidah</strong><br><span class="file">Porte du non-retour au Benin.jpg</span></td>
      <td>Borisghost</td>
      <td><span class="lic">CC0</span></td>
      <td><a href="/assets/img/wikimedia/porte-du-non-retour-au-benin.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/atlas-photos/parakou.webp" alt="Parakou" loading="lazy"></td>
      <td><strong>Parakou</strong><br><span class="file">ParakouMosque.jpg</span></td>
      <td>UXrocks</td>
      <td><span class="lic">CC BY 3.0</span></td>
      <td><a href="/assets/img/wikimedia/parakoumosque.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/atlas-photos/parc-w.webp" alt="Parc national du W" loading="lazy"></td>
      <td><strong>Parc national du W</strong><br><span class="file">Mekrou river in W-National Park MS 6380.JPG</span></td>
      <td>Marco Schmidt</td>
      <td><span class="lic">CC BY-SA 3.0</span></td>
      <td><a href="/assets/img/wikimedia/mekrou-river-in-w-national-park-ms-6380.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/atlas-photos/pendjari.webp" alt="Parc national de la Pendjari" loading="lazy"></td>
      <td><strong>Parc national de la Pendjari</strong><br><span class="file">Eléphants in Pendjari.jpg</span></td>
      <td>Farid AMADOU BAHLEMAN</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/elephants-in-pendjari.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/atlas-photos/port.webp" alt="Port de Cotonou" loading="lazy"></td>
      <td><strong>Port de Cotonou</strong><br><span class="file">Port autonome de Cotonou vu de haut (1).jpg</span></td>
      <td>Fawaz.tairou</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/port-autonome-de-cotonou-vu-de-haut-1.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/atlas-photos/porto-novo.webp" alt="Porto-Novo" loading="lazy"></td>
      <td><strong>Porto-Novo</strong><br><span class="file">Grande Mosquée de Porto-Novo au Bénin 01.jpg</span></td>
      <td>Gildaskiki</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/grande-mosquee-de-porto-novo-au-benin-01.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/atlas-photos/savalou.webp" alt="Savalou" loading="lazy"></td>
      <td><strong>Savalou</strong><br><span class="file">Monument à Savalou.jpg</span></td>
      <td>MahugnonCam</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/monument-a-savalou.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/atlas-photos/save.webp" alt="Savè" loading="lazy"></td>
      <td><strong>Savè</strong><br><span class="file">Les Collines de save .jpg</span></td>
      <td>OBA</td>
      <td><span class="lic">CC BY-SA 3.0</span></td>
      <td><a href="/assets/img/wikimedia/les-collines-de-save.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/atlas-photos/taneka.webp" alt="Pays Tanéka" loading="lazy"></td>
      <td><strong>Pays Tanéka</strong><br><span class="file">L&#x27;entrée du village Tanéka Béri 1.jpg</span></td>
      <td>Aimeabibis</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/l-entree-du-village-taneka-beri-1.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/atlas-photos/tanguieta.webp" alt="Tanguiéta" loading="lazy"></td>
      <td><strong>Tanguiéta</strong><br><span class="file">Entrée de la ville de Tanguiéta.jpg</span></td>
      <td>Barke11</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/entree-de-la-ville-de-tanguieta.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/atlas-photos/tanougou.webp" alt="Chutes de Tanougou" loading="lazy"></td>
      <td><strong>Chutes de Tanougou</strong><br><span class="file">Chutes de Tanougou (2).jpg</span></td>
      <td>Ji-Elle</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/chutes-de-tanougou-2.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    <tr>
      <td class="thumb"><img src="/assets/atlas-photos/tchaourou.webp" alt="Tchaourou" loading="lazy"></td>
      <td><strong>Tchaourou</strong><br><span class="file">Scène de marché, commune de Tchaourou 01.jpg</span></td>
      <td>Saliousoft</td>
      <td><span class="lic">CC BY-SA 4.0</span></td>
      <td><a href="/assets/img/wikimedia/scene-de-marche-commune-de-tchaourou-01.webp" target="_blank" rel="noopener noreferrer">Commons</a></td>
    </tr>
    </tbody>
  </table></div>

</main>
<footer>
  Bonne Arrivée — site de démonstration. Structure et gabarits repris d’un site d’office
  de tourisme à des fins d’étude ; contenus, visuels et charte adaptés au Bénin.
</footer>
    <script src="/assets/benin-carrousels.js?v=a043a557"></script>
</body></html>@endverbatim
