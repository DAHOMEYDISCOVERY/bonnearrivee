# Bénin tourisme

## Lancer le projet

```bash
./demarrer.sh
```

C'est tout. Le script vérifie PHP, crée la configuration, vide les caches et sert le
site sur **http://127.0.0.1:8010** — le port que les liens de la carte d'architecture
attendent. Il n'y a ni installation de MySQL, ni compte à créer : le contenu vit dans
les vues, et la base SQLite (`database/database.sqlite`) porte le registre des pages
et un utilisateur admin par défaut.

Le **back-office** tourne sur http://127.0.0.1:8010/admin
(`admin@bonnearrivee.bj` / `BonneArrivee2025!`) — voir [ARCHITECTURE.md § 6 bis](ARCHITECTURE.md).

| Il faut | Il ne faut pas |
|---|---|
| PHP 8.3 ou plus récent | Composer — les dépendances sont dans l'archive |
| — | Node, MySQL, Docker, une connexion |

Ensuite, ouvrir `docs/atlas.html` dans un navigateur : c'est la carte des 89 pages, et
ses liens ouvrent le site qui tourne.

Pour préparer l'archive à partager : `./tools/archive.sh`.

Portage du site statique `~/Desktop/benin-tourisme-copie` — 89 pages HTML issues d'un
miroir WordPress — vers **Laravel 13 + Blade**, à rendu identique.

Le CSS, les images, les polices et le composant `benin-atlas` sont repris **sans une
modification**. Seul le HTML change de forme : 89 fichiers générés deviennent un
gabarit, cinq partiels partagés et une vue par page.

| | Avant | Après |
|---|---|---|
| Fichiers HTML | 89, de 26 à 968 Ko | 89 vues, médiane 27 Ko |
| HTML généré transporté | 6 782 Ko | **2 676 Ko** — composants + retrait des services morts |
| En-tête et pied de page | écrits 89 fois | écrits **une** fois |
| `<head>` | écrit 89 fois | **3 profils** selon les ressources chargées |
| Métadonnées des pages | noyées dans le HTML | `resources/pages.php`, un tableau |
| Routes | l'arborescence de fichiers | une route, un registre |

## Lancer

```bash
php artisan serve --port=8010     # http://127.0.0.1:8010/

# et le miroir statique, pour comparer
cd ~/Desktop/benin-tourisme-copie && python3 serve.py 8777
```

## Structure

```
resources/
├── pages.php                      registre des 89 pages : vue, titre, description,
│                                  rubrique de menu active, profil de <head>
└── views/
    ├── layouts/app.blade.php      gabarit des 87 pages Woody
    ├── partials/
    │   ├── head/standard.blade.php   79 pages
    │   ├── head/listing.blade.php     5 pages — filtres et carte
    │   ├── head/offre.blade.php       3 pages — fiche prestataire
    │   ├── headnavs.blade.php        lien d'évitement, bouton de recherche
    │   ├── header.blade.php          28 Ko — menu principal, état actif paramétré
    │   ├── footer.blade.php
    │   └── overlays.blade.php        moteur de recherche et modales
    ├── components/
    │   ├── card.blade.php         509 cartes du site
    │   ├── media/figure.blade.php 931 images, avec leur crédit
    │   └── accueil/               les 7 sections de la page d'accueil
    └── pages/…                    une vue par page, calquée sur les URL
app/
├── Http/Controllers/PageController.php   une action pour les 89 pages
└── Support/PageRepository.php            accès au registre
tools/
├── convert.py                     miroir → vues Blade
├── nettoyage.py                   retrait des services distants inaccessibles
├── reparation.py                  restitution des titres de carte perdus
├── ajouts.py                      scripts propres au projet, ajoutés au gabarit
├── photos.py                      recherche de visuels libres, et substitutions
├── contenu.py                     corrections éditoriales déclarées
├── relecture.py                   fiche de relecture des 89 pages
├── card.py                        reconnaît les cartes, extrait leurs propriétés
├── media.py                       idem pour les images
├── equivalence.py                 le contrat « même rendu », et ses cas de test
├── roundtrip.php                  éprouve un composant contre le HTML d'origine
├── compare.py                     rendu Laravel ↔ miroir, sur les 89 pages
└── stabiliser.js                  fige une page avant capture d'écran
```

Le contrôleur lit le registre par `PageRepository`. Quand le back-office prendra la
main sur le contenu, seule l'implémentation de ce dépôt changera : le contrôleur et
les vues voient un dépôt, pas un fichier.

## Vérifier que le rendu n'a pas bougé

```bash
python3 tools/convert.py          # régénère les vues
python3 tools/compare.py          # compare les 89 pages au miroir
python3 tools/compare.py --page explorez/index.html --diff
```

`convert.py` refuse d'écrire si la concaténation de ses régions ne reconstitue pas le
`<body>` source au caractère près, ou si deux pages classées dans le même profil de
`<head>` en diffèrent.

`compare.py` traite le `<main>` — le contenu propre à la page — **sans aucune
tolérance** : structure et contenu doivent être identiques. Autour, le gabarit peut
porter des écarts, mais chacun doit se rattacher à une classe connue ; un écart
inclassable fait échouer la comparaison.

Ce que « identique » veut dire est écrit noir sur blanc dans `tools/equivalence.py`,
avec quinze cas de test : mêmes balises, mêmes attributs, mêmes valeurs, même texte ;
le blanc entre attributs, l'ordre des attributs, la barre oblique auto-fermante et
l'encodage des entités sont sans effet et donc ignorés. `tools/roundtrip.php` applique
le même contrat côté PHP, composant par composant.

```bash
python3 tools/equivalence.py     # les cas de test du contrat
python3 tools/reparation.py      # les cas de test de la réparation des titres
python3 tools/ajouts.py          # les cas de test des ajouts du projet
python3 tools/media.py           # 931 / 931 figures rendues à l'équivalent
python3 tools/card.py            # 538 / 538 cartes rendues à l'équivalent
```

### Résultat

```
Les 89 pages sont équivalentes au miroir.

Écarts connus, tous imputables au miroir :
  · 1036  texte alternatif du gabarit, repris de l'accueil
  ·  470  légende des cartes d'attente du moteur de recherche
  ·   86  logo de pied de page : le miroir le laisse pointer sur la page courante
  ·   86  carte modèle du moteur de recherche : même défaut de lien
  ·    4  carte du menu déroulant reprise de l'accueil
  ·    1  lien du pied de page rétabli
```

Contrôle visuel, captures pleine page en 1440 px après `tools/stabiliser.js` appliqué
à l'identique des deux côtés :

| Page | Pixels identiques | |
|---|---|---|
| accueil | 96,55 % | l'écart tient dans le bruit propre de la page — voir plus bas |
| `explorez/terre-de-traditions/le-vodun` | **99,999 %** | 48 pixels sur 8,6 millions |
| `explorez/les-cinq-benin` (40 cartes) | **99,999 %** | 47 pixels sur 10,3 millions |

L'accueil n'est pas déterministe : ses repères de carte s'animent. Deux captures du
**même** miroir diffèrent de 19 717 pixels ; miroir contre Laravel, 21 023 — soit
1,07 fois ce bruit. Toujours mesurer ce témoin avant de conclure.

## Ce que la migration répare

Le miroir portait des défauts que le partage du gabarit corrige mécaniquement.

**Le menu de navigation était cassé sur 48 pages.** Ses vingt vignettes y avaient
toutes été remplacées par l'image de la page : sur `/sejourner/restauration/restaurants`,
les vingt cartes du menu montraient la même photo de table. Le menu de l'accueil,
lui, est correct — c'est celui que sert désormais le site.

**Le logo du pied de page pointait sur la page courante.** Le miroir écrit
`href="index.html"` sur les 89 pages sans le relativiser, alors que le logo d'en-tête,
lui, l'est correctement. Il ramène maintenant à l'accueil partout.

**Le lien d'évitement rechargeait la page** (`index.html#` au lieu de `#`).

**Deux liens manquaient au pied de page** de `/mentions-legales`.

**223 titres de carte avaient perdu leur enveloppe.** Le miroir écrivait
`<div class="card-titles">Gîtes, lodges et écolodges</span>` : les ouvertures de
`card-texts` et du titre avaient disparu, leurs fermetures étaient restées. Le titre
s'affichait en 16 px sans style, sur des vignettes de 1 300 px de haut. `tools/reparation.py`
restitue les deux ouvertures — les fermetures orphelines prouvent qu'elles existaient.
Sur la page hôtels, la mise en page retrouve sa hauteur : 5 743 px → 3 111 px.

## Les composants

Deux composants prennent en charge 1,19 Mo de HTML généré. Chacun n'est écrit qu'une
fois, et `tools/roundtrip.php` vérifie qu'il rend l'original à l'équivalent avant que
la conversion ne remplace quoi que ce soit.

| Composant | Instances | Ce qu'il modélise |
|---|---|---|
| `x-media.figure` | **931 / 931** | une image et son crédit ; trois stratégies de chargement |
| `x-card` | **538 / 613** | une carte : conteneur, lien, média en contenu, textes, bouton |

Les 75 cartes restantes sortent du modèle et sont laissées telles quelles :

- **51 cartes scindées** disposent média et textes dans une grille — une autre
  ossature, qui appellera son propre composant ;
- **5 cartes portent deux titres** : le vrai, et la légende de leur image qui s'y est
  glissée. Trancher lequel garder reviendrait à décider à la place de l'auteur ;
- le reste : cartes sans lien, ou dont le média n'est pas une figure lisible.

## Ce que le projet ajoute au thème

Deux fichiers appartiennent au projet et ne viennent pas du miroir :

| Fichier | Rôle |
|---|---|
| `public/assets/benin-carrousels.js` | boucle continue et défilement automatique des carrousels |
| `public/assets/benin-overrides.css` | filet de sécurité du thème, plus les ajustements du projet |

**Les carrousels.** Les quatre carrousels de cartes bouclaient déjà correctement.
Celui d'accroche, non : son `loop: false` est écrit en dur dans le `main.mjs` du
thème, pour le gabarit `tpl_03`, et aucun attribut ne le change. Il est donc
réinitialisé après coup, en reprenant ses paramètres d'origine — pagination,
parallaxe, vitesse — sans `el` ni `init`, qui désignent l'instance remplacée et
feraient échouer la nouvelle.

Le défilement automatique (5 s, en pause au survol) est ajouté aux cinq. Une
préférence système pour un mouvement réduit le désactive ; la boucle, elle, reste.

**Le centrage des cartes.** Le thème cale les textes à gauche ; le projet les veut
centrés. Une règle sur `.card-link`, qui enveloppe titre, sous-titre, description
et commune.

**Les cartes scindées sans visuel.** Le thème garde leur cellule de texte en
`large-8` — deux tiers de la carte — même quand la cellule d'image a disparu : le
bloc reste calé à gauche, avec un tiers de vide à sa droite. Le défaut est dans le
thème d'origine. Corrigé pour les 3 cartes concernées ; les autres cartes sans
visuel occupent déjà toute leur largeur et ne sont pas touchées.

**L'empreinte de version.** Les fichiers du projet — `benin-overrides.css`,
`benin-carrousels.js`, `benin-atlas.*` — n'avaient aucune version dans leur URL.
Un navigateur qui les avait déjà chargés continuait de servir l'ancienne : les
corrections restaient invisibles. Leur URL porte désormais `?v=` suivi d'une
empreinte du contenu, qui ne change que si le fichier change.

L'ajout du script est une transformation déclarée, appliquée aux deux côtés de la
comparaison — comme le retrait des services et la réparation des titres.

⚠ `public/assets/benin-overrides.css` a désormais divergé du miroir : ne pas le
resynchroniser depuis `~/Desktop/benin-tourisme-copie/assets/`.

## Les photographies

Le site tient sur **51 photographies** pour 86 sujets de page — certaines reviennent
plus de deux cents fois. Toutes sont libres et créditées : 60 en CC BY-SA, le reste
en CC0, domaine public ou CC BY.

`tools/photos.py` cherche des remplaçantes sur Wikimedia Commons et **vérifie**
chaque candidate avant de la retenir : ses coordonnées doivent tomber dans le
rectangle du Bénin, ou sa catégorie Commons doit nommer le pays. Une image qui ne
prouve ni l'un ni l'autre est écartée, quelle que soit sa qualité — le miroir porte
déjà la trace de ce piège, une tata du **Togo** ayant servi à illustrer le pays
Betammaribé.

```bash
python3 tools/photos.py              # les candidates vérifiées, par sujet
python3 tools/photos.py --integre    # télécharge les retenues et crée les variantes
```

**Trois photographies ajoutées** — la Mékrou dans le parc W, le marché Dantokpa,
le lever du jour sur la Bouche du Roy — avec leurs 20 variantes chacune et leurs
crédits.

**Onze pages ont changé de visuel**, parce que le leur ne montrait pas leur sujet :
une plage illustrait la cuisine béninoise et la restauration, un babouin illustrait
les éléphants, un hippopotame de la Pendjari illustrait le parc W. Chaque
substitution est déclarée dans `REMPLACEMENTS`, avec sa raison.

Reste sans solution : **les hébergements**. Personne ne photographie les hôtels sous
licence libre — ces visuels viendront des établissements, par le back-office.

## Le contenu hérité de Mayotte

La conversion Mayotte → Bénin a remplacé les toponymes, mais des passages entiers
sont restés. Le plus visible occupait le chapô de **49 pages** :

> « Le Réserve de biosphère du Bénin est le premier parc de France. D'une superficie
> de 68 400 km²… »

Trois erreurs : le Bénin n'est pas en France ; 68 400 km² est la superficie du parc
naturel marin de **Mayotte** ; « Le Réserve » est fautif. Corrigé par
`tools/contenu.py`, sous ses **trois formes** — enrichie de `<strong>` dans la page,
nue dans la méta-description, échappée dans le balisage schema.org. Le miroir
n'échappe qu'une partie de ses accents ; la forme JSON est donc visée par motif.

**Il ne reste plus rien de Mayotte dans le projet.** Ce qui a été traité :

| | |
|---|---|
| Adresse de l'agence | relevée sur `anpt.bj` : Zongo, immeuble FAWAZ, rue des Cheminots, 08 BP 1134 Tri-Postal, Cotonou |
| Coordonnées des fiches | −12,7 / 45,1 → les villes que les fiches nomment (Cotonou, Abomey, Natitingou) |
| Contacts d'autrui | téléphones 0269 et 0639, codes postaux 976xx, page Facebook et site d'établissements mahorais — retirés, non remplacés |
| Paragraphes faux | la pointe Mahabou, le sultan Andriantsouli, la plus vieille mosquée de France, la pêche au djarifa — **retirés**, non réécrits |
| Cartes d'identité | 10,95 km² pour Petite-Terre, 80,68 km² pour Mamoudzou-Dembéni — retirées |
| Images mortes | 81 vignettes et les tableaux `image` du balisage, tous en 404 sous des noms mahorais |

Une règle a guidé le tri : **supprimer une fausseté ne demande d'inventer rien, la
réécrire demanderait des sources.** Les passages entièrement hérités sont donc
retirés ; ceux qui ne portaient qu'un toponyme sont retouchés au minimum.

**Toutes les mentions de la France ne sont pas des scories**, et celles qui restent
sont volontaires : le français **est** la langue officielle du Bénin, France
Diplomatie **est** la source des avis aux voyageurs cités, et la conquête de 1892 a
eu lieu. Les retirer rendrait le contenu faux.

```bash
python3 tools/relecture.py --fichier    # écrit RELECTURE.md
```

`RELECTURE.md` liste les 89 pages avec leur lien, leur nombre de mots, leurs photos,
et ce qui leur manque. **45 pages demandent un regard.**

## Cinq pièges de Blade rencontrés

**Blade compile ce qu'il ne devrait pas.** Le JSON-LD du gabarit contient `@context`
et `@type`, directives Blade depuis Laravel 11 ; `jquery@ananas` en est une aux yeux
du compilateur ; les pages de liste embarquent des gabarits Twig avec des `{{ }}`.
L'échappement `@@` ne suffit pas — Blade ne déséchappe pas `@@ananas.min.mjs`, suivi
d'un point. Tout le HTML importé est donc encapsulé dans des blocs `@verbatim`.

**Les blocs `@verbatim` se terminent par un `@`.** Un `{{` placé juste après formerait
`@{{`, un écho échappé : l'expression s'afficherait au lieu d'être évaluée. Les
fragments dynamiques commencent donc par `<`, ou sont précédés d'un saut de ligne.

**`@section('nom', 'valeur')` échappe sa valeur.** La forme en ligne transforme les
guillemets en entités ; les attributs du `<main>` passent donc par la forme en bloc.

**Deux directives collées ne se compilent pas.** `@endif@endif` ne compile que la
première : Blade exige une frontière de mot avant `@`. Dans les composants, chaque
directive termine donc sa ligne — PHP avale le saut de ligne qui suit `?>`, si bien
que la mise en forme du gabarit ne ressort pas dans le rendu.

**Blade ne décode pas les entités des attributs de composant.** Une valeur écrite
`&quot;` arrive telle quelle dans la propriété ; le composant qui la reçoit doit la
rendre verbatim, sans quoi elle se retrouve doublement échappée.

## Le HTML source est déséquilibré

Le `<header>` du gabarit referme deux `<div>` ouverts hors de lui : le document compte
deux `</div>` de plus que de `<div>`. Les navigateurs l'admettent et le rendu en
dépend. Les liaisons sont transportées telles quelles dans `layouts/app.blade.php`.
**Ne pas les « corriger »** sans relancer `tools/compare.py`.

## Les services distants, et ce qu'ils sont devenus

Vérifié le 25 août 2026, service par service — voir `tools/nettoyage.py` :

| Service | État | Décision |
|---|---|---|
| `api.tourism-system.com` | point d'entrée **404** ; base touristique française, elle n'indexe rien du Bénin | **retiré** |
| `touristicmaps.com` | tuiles **404** sans la clé, que le site n'a pas | **retiré** |
| `maps.googleapis.com` | **fonctionne au Bénin** — la clé du miroir géocode Cotonou. Mais elle appartient à l'éditeur d'origine, et elle est **active** | **clé retirée**, intégration à rétablir avec une clé du projet |

Retiré au total : **2 904 Ko** de blocs de résultats sur 5 pages, 106 appels à un
service injoignable, 8 blocs de configuration porteurs d'identifiants.

Les 5 pages de liste — hôtels, restauration, opérateurs de safari, séjour sur le lac,
safari et randonnée — gardent leur couverture, leur titre, leur introduction et leurs
cartes. Leur liste reviendra quand le back-office en sera la source.

Le retrait est appliqué **des deux côtés de la comparaison** : `compare.py` le passe
sur le miroir avant de comparer, si bien que le contrôle reste strict sur tout le reste.

### Pour remplacer ces services

Il n'existe pas d'API touristique béninoise. Le portail
[data.gouv.bj](https://data.gouv.bj/) expose des données ouvertes, mais rien sur
l'hébergement ni la restauration. **OpenStreetMap**, en revanche, couvre le Bénin et
c'est déjà la source de `benin-atlas` :

| | Objets au Bénin (OSM, août 2026) |
|---|---|
| hôtels | 408 |
| restaurants | 714 |
| chambres d'hôtes | 282 |
| sites touristiques | 67 |
| musées | 45 |

Autres limites, inchangées depuis le miroir :
- **Une erreur de console subsiste**, identique au miroir : la « cookie toolbox »
  appelle un point d'entrée WordPress (`/wp-json/…`) qui n'existe pas.
- **Les polices aux noms atypiques** (`woody-icons.woff2_t=1787124204775`) sont servies
  sans type MIME par le serveur de développement. Les navigateurs les acceptent ;
  prévoir une règle explicite côté Apache ou Nginx.

## Suite

| # | Étape | |
|---|---|---|
| 1 | Socle, gabarit, page d'accueil | **fait** |
| 2 | Les 88 autres pages, registre, routage | **fait** |
| 3 | Composants Blade à props : image, carte | **en cours** — 1,19 Mo repris |
| 4 | Les autres composants Woody : hero, focus, swiper, onglets, teaser | à faire |
| 5 | Modèles, migrations, back-office Filament | à faire |
| 6 | Réécriture des 5 comportements JS, sans jQuery | à faire |
| 7 | Purge du CSS — 78 % des classes stylées n'existent nulle part | à faire |

## Les cartes d'architecture

Le site vient d'un gabarit : les pages existent, leur câblage n'avait jamais été vérifié.
Deux cartes le rendent lisible, produites du même relevé :

    python3 tools/architecture.py --json && python3 tools/atlas.py

| | |
|---|---|
| `docs/atlas.html` | l'outil de travail — chaque page s'ouvre sur le serveur local |
| `docs/projet.html` | la version qui se suffit à elle-même, avec un extrait du texte de chaque page |

Elle donne, par page : le volume, les illustrations, le sommaire, ce qui manque, et
le graphe — ce que la page cite et **ce qui la cite**. Les liens du menu, de l'en-tête et
du pied de page sont écartés du compte : identiques sur les 89 pages, ils noieraient le
signal.

**Ce que le relevé établit :** 684 liens internes, aucune page orpheline, aucun lien
brisé. Restent 18 pages à revoir, 9 sous 165 mots, 3 culs-de-sac et 3 contresens
géographiques — des pages de l'intérieur du pays qui décrivent encore la mer.
