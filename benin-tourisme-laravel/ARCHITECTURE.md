# Bénin tourisme — comprendre et reprendre le projet

Ce document s'adresse à qui reprend le développement. Il dit ce qu'est ce projet,
comment le lancer, comment il est organisé, et surtout **quelles règles ne pas
casser**. Comptez vingt minutes de lecture ; elles vous en feront gagner beaucoup.

---

## 1. Ce que c'est, et d'où ça vient

Un site touristique du Bénin : **89 pages**, servies par **Laravel 13 + Blade**.

Son histoire explique tout le reste. Le site a été fabriqué en reprenant l'ossature
d'un autre office de tourisme — un site WordPress sous le thème « Woody » — puis en
remplaçant les noms de lieux par des noms béninois. L'opération a réussi sur la
forme : la navigation tient, le style tient. Elle a échoué sur le fond : le contenu
décrivait encore l'autre pays.

Le projet actuel fait deux choses :

1. **Il porte ces 89 pages sous Laravel**, à rendu strictement identique, en
   remplaçant le HTML copié par des composants Blade ;
2. **Il corrige ce qui était faux** — contenu, coordonnées, photographies, chemins.

Il reste une troisième chose à faire, la plus longue : **écrire le contenu**. Voir
§ 8.

### Deux dossiers, deux rôles

| | |
|---|---|
| `~/Desktop/benin-tourisme-copie` | **le miroir** — les 89 pages HTML d'origine. Source de vérité, jamais servi. |
| `~/Desktop/benin-tourisme-laravel` | **le projet** — l'application Laravel. C'est ici qu'on travaille. |

Le miroir n'est pas une archive morte : **tout le pipeline le relit à chaque
génération**. Ne le supprimez pas, ne le modifiez pas à la main.

---

## 2. Démarrer

```bash
cd benin-tourisme-laravel
./demarrer.sh
```

C'est tout. Le script vérifie PHP, crée la configuration, génère la clé, vide les
caches et sert le site sur **http://127.0.0.1:8010**.

| Il faut | Il ne faut pas |
|---|---|
| PHP 8.3 ou plus récent | Composer — `vendor/` est dans l'archive |
| Python 3.11+ (pour les outils) | Node, MySQL, Docker, une connexion |

Une base **SQLite** vit dans `database/database.sqlite` — elle porte le registre
des 89 pages et un utilisateur admin par défaut, et sert le back-office (§ 5 bis).
Le contenu des pages, lui, reste dans les vues Blade jusqu'à ce qu'un rédacteur
décide de l'éditer.

Ouvrez ensuite `docs/atlas.html` dans un navigateur. C'est la carte des 89 pages :
arborescence, volume, illustrations, ce qui manque, et le graphe des liens — ce que
chaque page cite et **ce qui la cite**. Ses liens ouvrent le site qui tourne.

---

## 3. L'idée centrale : le rendu est vérifié, pas espéré

C'est le point à comprendre avant tout le reste.

Le cahier des charges était : *convertir en Laravel sans changer le rendu d'un
pixel*. Une promesse pareille ne se tient pas à l'œil sur 89 pages. Elle se
**mesure** :

```bash
python3 tools/compare.py
# → Les 89 pages sont équivalentes au miroir.
```

`compare.py` charge chaque page servie par Laravel, la compare à la page du miroir,
et exige l'égalité. Le `<main>` est contrôlé **sans aucune tolérance** : mêmes
balises, mêmes attributs, mêmes valeurs, même texte.

### Le mécanisme qui rend cela possible

Certaines choses **devaient** changer : retirer une API morte, corriger un contenu
faux, passer les images en WebP. Si on les changeait d'un seul côté, la comparaison
échouerait partout et deviendrait inutile.

D'où la règle qui structure tout le projet :

> **Toute transformation est déclarée dans un module de `tools/`, et appliquée aux
> deux côtés de la comparaison.**

`convert.py` la passe sur le miroir avant d'écrire les vues ; `compare.py` la passe
sur le miroir avant de comparer. Le contrôle reste donc strict sur *tout le reste*.

**Corollaire pratique :** si vous voulez modifier le HTML de toutes les pages, ne
touchez pas aux vues générées. Écrivez un module de transformation, câblez-le dans
`convert.py` **et** dans `compare.py`, et régénérez. Sinon la prochaine génération
écrasera votre travail, ou la comparaison cassera.

---

## 4. Arborescence

```
benin-tourisme-laravel/
├── demarrer.sh              lance tout (§ 2)
├── README.md                notes de développement
├── ARCHITECTURE.md          ce document
├── RELECTURE.md             les 89 pages, ce qui manque à chacune (§ 8)
├── architecture.json        relevé du graphe des liens, régénérable
│
├── app/
│   ├── Http/Controllers/PageController.php    résout une URL vers une vue
│   └── Support/PageRepository.php             le registre des pages
├── routes/web.php           une seule route : /{path?} attrape tout
│
├── resources/
│   ├── pages.php            registre généré : route → vue → profil de <head>
│   └── views/
│       ├── layouts/app.blade.php              le gabarit unique
│       ├── partials/
│       │   ├── head/{standard,listing,offre}.blade.php   3 profils de <head>
│       │   └── {headnavs,header,footer,overlays}.blade.php
│       ├── components/
│       │   ├── card.blade.php                 538 cartes du site
│       │   ├── media/figure.blade.php         931 figures
│       │   └── accueil/                       7 sections de la page d'accueil
│       └── pages/                             les 89 vues, générées
│
├── public/assets/
│   ├── img/                 les découpes servies + `_src/` : les 72 originaux
│   ├── site/                le thème (CSS, JS, polices) — voir § 7
│   ├── benin-overrides.css  nos correctifs de style
│   ├── benin-carrousels.js  boucle et défilement des carrousels
│   └── benin-atlas.{css,js} la carte interactive du Bénin
│
├── docs/atlas.html          la carte des 89 pages, régénérable
└── tools/                   la chaîne de conversion — § 5
```

### `resources/views/pages/` est **généré**

Ne modifiez jamais ces 89 fichiers à la main : ils sont réécrits à chaque
`convert.py`. Pour changer une page, voir § 6.

---

## 5. Les outils

Tous en Python 3, dans `tools/`. Chacun se lance seul et rend un compte.

### La chaîne de conversion

| Outil | Rôle |
|---|---|
| **`convert.py`** | le cœur : lit le miroir, applique les transformations, écrit les 89 vues |
| **`compare.py`** | compare les pages servies au miroir. **Le juge de paix.** |
| `equivalence.py` | le contrat d'équivalence : ce qui compte comme identique |
| `roundtrip.php` | rend un composant Blade et le compare au fragment d'origine |

### Les transformations déclarées

Chacune s'applique **des deux côtés**. Chacune porte ses cas de test —
`python3 tools/<nom>.py` les exécute.

| Outil | Ce qu'il transforme |
|---|---|
| `reparation.py` | restaure 223 ouvertures de titres manquantes dans le miroir |
| `photos.py` | cherche, vérifie et intègre les photographies ; recadre ; crédite |
| `contenu.py` | corrige le contenu faux : chapôs, adresse, coordonnées, toponymes |
| `nettoyage.py` | retire les services distants morts et les identifiants en clair |
| `webp.py` | réécrit les adresses d'images en `.webp` |
| `desencastre.py` | sort les chemins du thème hors du nom de l'agence d'origine |
| `ajouts.py` | ajoute nos scripts et l'empreinte de version sur nos fichiers |

**L'ordre compte.** Il est fixé dans `convert.py`, fonction `load()` :

```
Rewriter → réparation des titres → photographies → crédits →
contenu → fiches → nettoyage → WebP → désencastrement → ajouts
```

### La componentisation

| Outil | Ce qu'il vérifie |
|---|---|
| `media.py` | 931 figures : chaque appel du composant rend le fragment d'origine |
| `card.py` | 538 cartes, même contrôle |

### Le pilotage

| Outil | Ce qu'il produit |
|---|---|
| `architecture.py` | relève l'arborescence, le graphe des liens, ce qui manque |
| `atlas.py` | en fait `docs/atlas.html`, la carte consultable |
| `relecture.py` | écrit `RELECTURE.md` : les pages à revoir, avec leurs liens |
| `archive.sh` | fabrique l'archive à partager |

---

## 6. Comment faire les tâches courantes

### Régénérer tout, après n'importe quelle modification

```bash
rm -rf resources/views/pages resources/views/components/accueil resources/pages.php
python3 tools/convert.py
php artisan view:clear
```

### Le contrôle complet, avant de livrer

```bash
for t in equivalence reparation ajouts contenu webp desencastre; do python3 tools/$t.py; done
python3 tools/media.py && python3 tools/card.py
python3 tools/compare.py          # ← celui qui compte
./vendor/bin/pint --test app/ routes/
```

Tout doit passer. `compare.py` doit dire **« Les 89 pages sont équivalentes au
miroir »**. S'il dit autre chose, ne livrez pas.

### Modifier le contenu d'une page

Deux cas.

**Une correction ponctuelle** (une phrase, une adresse) : ajoutez-la dans
`tools/contenu.py`, avec sa justification, puis régénérez. C'est plus long qu'un
coup d'éditeur, mais c'est ce qui garde la comparaison utilisable — et c'est
reproductible.

**Une réécriture de fond** : c'est le chantier ouvert, § 8. À terme le contenu
viendra d'une base, via le back-office ; le pipeline actuel est une étape.

### Changer la photographie d'une page

1. Cherchez sur Wikimedia Commons : `python3 tools/photos.py --sujet <nom>`.
   L'outil ne retient que les images **vérifiées au Bénin** (coordonnées dans le
   pays, ou catégorie qui le nomme) et **sous licence libre**.
2. Ajoutez-la à `TÉLÉCHARGEMENTS` dans `tools/photos.py` — fichier, auteur,
   licence, légende. Les crédits se mettent à jour tout seuls.
3. Affectez-la à une page dans `REMPLACEMENTS`, avec la raison.
4. Téléchargez, découpez, régénérez.

> **Regardez les images avant de les retenir.** Trier par taille ne suffit pas : le
> plus gros fichier de Commons pour « Natitingou » est une planche d'herbier. Montez
> une planche-contact (`magick montage`) et examinez-la.

### Mettre la carte à jour

```bash
python3 tools/architecture.py --json && python3 tools/atlas.py
python3 tools/relecture.py --fichier
```

### Fabriquer l'archive à partager

```bash
./tools/archive.sh      # → ~/Desktop/benin-tourisme.zip, ~145 Mo
```

---

## 6 bis. Le back-office Filament

Un panneau d'administration Filament tourne à **http://127.0.0.1:8010/admin**.
Il permet d'éditer les métadonnées des 89 pages (titre, description, profil de
`<head>`, menu actif), et d'y attacher un **contenu personnalisé** qui remplace
la vue Blade d'origine sans la perdre.

### Se connecter

Identifiants par défaut créés au premier boot :
- **Email** : `admin@bonnearrivee.bj`
- **Mot de passe** : `BonneArrivee2025!`

Ces identifiants sont posés par un `User::create()` dans une seed manuelle. Un
compte réel devra être créé et celui-ci désactivé avant mise en production.

### La table `pages`

Toutes les colonnes sont dans la migration [create_pages_table](database/migrations/2026_08_27_211428_create_pages_table.php) :

| Colonne | Rôle |
|---|---|
| `path` (unique) | Chemin URL servi par Laravel — clé métier |
| `view` | Vue Blade rendue par défaut (« pages.accueil ») |
| `title`, `description` | Métadonnées HTML |
| `active_menu` | Identifiant de la rubrique à marquer active |
| `head_profile` | `standard` / `listing` / `offre` |
| `standalone` | Page hors gabarit Woody (credits-photos, fiche-de-relecture) |
| `content_override` | HTML édité depuis Filament, nullable |
| `use_override` | Interrupteur : sert `content_override` plutôt que la vue |

### Bascule fichier ↔ base

`PageRepository` est une interface avec deux implémentations :

- `FilePageRepository` — lit `resources/pages.php`, la source d'origine ;
- `DatabasePageRepository` — lit la table `pages`.

[AppServiceProvider](app/Providers/AppServiceProvider.php) choisit
automatiquement : si la table existe et n'est pas vide → base, sinon → fichier
(fallback pour installation minimale sans migration). Le contrôleur voit une
interface, jamais l'implémentation.

### Régénérer le registre depuis `resources/pages.php`

Après un run de `tools/convert.py`, le fichier `resources/pages.php` est
réécrit. Pour propager les changements en base :

```bash
php artisan db:seed --class=PageSeeder
```

Le seeder est idempotent (`updateOrCreate` sur `path`) : il crée les nouvelles
pages, met à jour celles qui ont bougé, ne touche pas au `content_override`
que le back-office aurait ajouté.

### Contenu personnalisé — le contrat

Quand un rédacteur active le contenu personnalisé, le contrôleur bascule sur
la vue générique [pages.editable](resources/views/pages/editable.blade.php).
Cette vue rend le HTML édité dans le layout `app` — header, footer, gabarit
Woody intacts. Elle est utilisée seulement quand :

> `use_override = true`   ET   `content_override` non vide

Sinon la vue Blade historique (`view` de la page) est servie. Le brouillon
reste en base, un simple toggle le réactive.

Le rich editor de Filament produit du HTML basique (h2, h3, p, ul, ol, strong,
em, a, img) qu'il faut compléter à la main pour les mises en page complexes.
Pour les blocs riches (cartes, focus, swiper), passer par le pipeline Python
reste la voie autoritative.

---

## 7. Les règles à ne pas casser

**1. Ne modifiez pas `resources/views/pages/`.** Généré. Passez par une
transformation.

**2. Ne modifiez pas le miroir.** C'est la référence de la comparaison.

**3. Ne resynchronisez pas `public/assets/benin-overrides.css`** depuis le miroir :
il en a divergé volontairement (centrage des cartes).

**4. Toute transformation se câble des deux côtés** — `convert.py` *et*
`compare.py`. Une seule des deux, et la comparaison ment.

**5. Les photographies sont créditées.** Elles viennent toutes de Wikimedia
Commons ; les licences CC BY et CC BY-SA **imposent** de nommer l'auteur. La page
`credits-photos` le fait pour les 72. N'en ajoutez pas sans crédit.

**6. Le préfixe `woody-` des classes CSS reste.** Il est employé par 42 fichiers et
se confond avec des noms de dossiers. Le renommer casserait le rendu pour un gain
cosmétique. Les chemins, eux, ont été sortis du nom de l'agence : tout est sous
`/assets/site/`.

**7. Le rendu d'abord.** Le projet a une promesse : le rendu ne change pas. Devant
un doute, mesurez — ne supposez pas.

**8. Le back-office est autoritatif quand la table `pages` est peuplée.** Ne
recréez pas `resources/pages.php` derrière son dos ; passez par le seeder
(`php artisan db:seed --class=PageSeeder`), qui préserve les `content_override`
saisis dans Filament.

---

## 8. Où en est le projet, et ce qui reste

### Fait

- Les 89 pages sous Laravel, rendu vérifié identique
- 538 cartes et 931 figures en composants
- Services distants morts coupés, identifiants en clair retirés
- Tout ce qui venait de l'autre pays effacé — contenu, adresse, coordonnées
- **72 photographies** du Bénin, toutes créditées, en WebP, recadrées sur le sujet
- Plus aucun chemin ne porte le nom de l'agence d'origine
- La carte d'architecture et la fiche de relecture
- **Contenu éditorial des 36 pages « à revoir »** — retraits mahorais, remplacement
  par du contenu béninois authentique (Atacora, palais d'Abomey, Collines,
  Ganvié, safaris, hôtels…), tout dans le pipeline (`contenu.py`,
  `contenu_remplacements.py`)
- **Back-office Filament** installé et opérationnel — les 89 pages sont en base,
  éditables par un rédacteur non-développeur, avec bascule fichier ↔ base au boot
  (§ 6 bis)
- **Édition du contenu** depuis Filament : chaque page peut recevoir un
  `content_override` HTML qui remplace la vue Blade sans la perdre
- **Nom de site** unifié en « Bonne Arrivée » — les résidus « Bénin Forever » et
  « BENINFOREVER » (hashtag socialwall inclus) traqués et rétablis. Verrouillé
  par des règles dans le pipeline (`NOM_DU_SITE` dans `contenu.py`).
- **Charte tricolore vert-jaune-rouge** — le trait de soulignement bicolore du
  thème est repeint sur tous les emplacements (hero, focus, cartes overlay,
  section-titles, interactive-map-titles, socialwall-titles, generic-block-titles,
  page-main-titles), et le composant `.benin-atlas .rule` du custom carte reprend
  la même bande. Le cercle jaune de pagination du landswpr passe en vert, et le
  hover des boutons prev/next du carrousel aussi.
- **Carte interactive du Bénin** — composant `<x-carte-du-benin />` extrait du
  mockup autonome à 4 niveaux (Monde → Afrique de l'Ouest → Golfe de Guinée →
  12 départements). CSS/JS séparés, images sorties du base64 vers
  `public/assets/img/carte/`, isolé dans un scope `.benin-carte-interactive`
  pour ne pas repeindre le site. Publié en page dédiée `/carte-du-benin`
  (route explicite hors registre) et injecté en bas de la page « Comment venir ».
  Lien découvrable depuis le bouton du footer.
- **SEO / partage social**
    - `/sitemap.xml` généré depuis le registre + routes hors registre — voir
      `App\Http\Controllers\SitemapController`
    - `/robots.txt` renforcé : `Disallow: /admin`, référence au sitemap
    - Fallbacks meta : chaque page qui ne fournit ni `@section('headImageOg')`
      ni `$description` reçoit une image et une description par défaut. Le
      fallback est ajouté par la transformation `assure_les_fallbacks_meta`
      dans `desencastre.py`, donc idempotent.
    - **JSON-LD enrichi** : la home expose maintenant `WebSite` + `Organization`
      (Dahomey Discovery complet — logo, adresse, contactPoint, `sameAs` Facebook/Instagram/
      Twitter) + `TouristDestination`. Les 3 fiches offres ont leur type propre
      (`LodgingBusiness`, `Restaurant`, `TravelAgency`) + `BreadcrumbList`.
      La page carte du Bénin a `WebPage` + `BreadcrumbList` reliés à
      `#website` / `#destination` par `@id`.
- **Fiches offres** — la sidebar Angular non résolue
  (`{{userProfile.name}}`, `ng-if`, `ng-href`, téléphones vides…) est remplacée
  sur les 3 fiches par un simple bloc statique « Cet établissement vous
  intéresse ? — Contactez Dahomey Discovery ». Zéro placeholder Angular visible.
- **Accessibilité**
    - `alt` ajouté sur le logo du header — corrige 88 pages en un fix
    - `<h1>` ajouté sur `/carte-du-benin` (auparavant page sans h1)
    - `aria-label` sur les 4 liens muets du footer (logo + 3 icônes sociales,
      `wicon` marqué `aria-hidden`, `rel="noopener noreferrer"` pour les liens
      externes)
    - `aria-label` sur le bouton loupe de la recherche
    - **Score final** sur 4 pages représentatives : 0 img sans alt, 0 lien
      muet, 0 bouton muet, 0 placeholder Angular résiduel
- **Pages d'erreur personnalisées** — `errors/404.blade.php` et
  `errors/500.blade.php` héritent du layout du site avec CTA de récupération ;
  `errors/503.blade.php` est autonome (la maintenance coupe les services que le
  layout consomme), aux couleurs du drapeau béninois.
- **PWA basique** — `public/site.webmanifest` (name, short_name, theme_color
  `#00a05d`, icons apple-touch-icon), `<link rel="manifest">` et
  `<meta theme-color>` injectés dans les 3 partials head. Idempotent via la
  transformation `ajoute_le_manifest_pwa` du pipeline.
- **Allègement HTML/JS** — 3 scripts WordPress morts retirés (`wp-i18n-js`,
  `wp-hooks-js`, `wp-i18n-ltr-js` : aucune référence côté code, sauvegardé
  par `retire_les_scripts_wordpress_morts`) ; 30 feuilles CSS chargées deux
  fois dédoublonnées sur les 3 partials head
  (`dedoublonne_les_feuilles_de_style`). Baisse de 24 → 21 balises `<script>`
  sur la home ; **-7 requêtes CSS/page**.
- **Purge CSS chirurgicale** — en 3 étapes, préfixes 100 % absents du HTML
  servi retirés règle par règle : `.woody-component-sheet-booking`, `.apirender`,
  `.rc_lego`, `.api_filter_alpha`, `.plyr`, `.leaflet`, `.flatpickr`, `.slick`,
  `.chosen`, `.itinerary`, `.focusH-overlayed`, `.woody-lang_switcher`,
  `.lang_flag`, `.profileCard`, `.off-canvas`, `.woody-component-sheet-`, puis
  passe règle-par-règle qui garde uniquement les sélecteurs dont au moins une
  classe/id existe dans le HTML servi (safelist minimale pour les utilitaires
  éditoriaux). **2 737 Ko → 1 066 Ko (-61 %)** sur disque, **1 470 → 785 Ko**
  servi sur la home. 2 chunks retombés à zéro (`tourism_chunk_4/5.css`) sont
  supprimés du disque et des `<link>`.
- **`.htaccess`** — compression `mod_deflate` (HTML/CSS/JS/SVG/fonts), cache
  `mod_expires` (1 an sur assets, no-cache sur HTML, 1 h sur sitemap), en-têtes
  sécurité (`X-Frame-Options`, `X-Content-Type-Options`, `Referrer-Policy`,
  `Permissions-Policy`). Effectif dès qu'Apache a `mod_deflate` et `mod_expires`
  chargés — sur WAMP par défaut, `mod_headers` seul est actif.
- **Suite de feature tests** — `tests/Feature/SiteSmokeTest.php`, 12 tests /
  61 assertions : les 89 pages du registre répondent 200, sitemap XML valide,
  robots.txt, webmanifest, carte-du-benin, 404 personnalisée, meta description
  + og:image sur toutes les pages, `<h1>` sur chaque page, JSON-LD contient
  `Organization` et `TouristDestination` sur la home, aucune fiche offre ne
  laisse voir de placeholder Angular, aucune trace de « Bénin Forever ».
  Verrous de non-régression pour tout futur chantier.

### À faire, par ordre

**1. Alléger le JavaScript.** 21 scripts encore chargés (~670 Ko).
`obf.min.js` (435 `data-obf` dépendants) et `jquery@ananas.min.mjs`
(25 imports dans main.mjs) sont ESSENTIELS — ne pas retirer. Restent
candidats : les 5 modules `woody_addon_devtools_*`, les 4
`woody_addon_touristicmaps_*` (services déjà nettoyés), les 3
`woody_addon_weather_*`, les 4 `woody_hawwwai_itinerary_*`, et
`highcharts` + ses modules Core (~1 MB dans l'importmap mais probablement
pas chargés faute d'usage). Les cinq comportements à réécrire sans jQuery
(carrousels, cartes interactives, recherche, révélation au scroll, hotspots)
restent — chantier dédié multi-session.

**2. Compte admin en production.** Le compte par défaut
(`admin@bonnearrivee.bj` / `BonneArrivee2025!`) est un placeholder — le
créer proprement, désactiver le placeholder, et passer les identifiants dans
`.env` (voir `.env.example`).

**3. Vérification visuelle CSS.** La purge chirurgicale a passé la suite
smoke, mais les tests ne comparent pas pixel-à-pixel. Une revue humaine
sur 5-6 pages représentatives (accueil, fiche offre, page rubrique, carte,
mentions légales) devrait confirmer qu'aucun style critique n'a été perdu.

---

## 9. Pièges connus

Ceux-là ont déjà coûté du temps. Ils sont ici pour ne pas le coûter deux fois.

**Blade**
- `@endif@endif` sur une même ligne : seul le premier compile. Une directive par ligne.
- `@verbatim` finit par `@` : un `{{` qui suit devient un echo échappé. Faites
  commencer les fragments dynamiques par `<`, ou par un saut de ligne.
- `@section('nom','valeur')` échappe sa valeur ; la forme en bloc, non.
- Blade ne décode pas les entités dans les attributs de composants : un `&quot;`
  rendu avec `{{ }}` est doublement échappé. Utilisez `{!! !!}`.

**Le miroir**
- Il échappe les accents de façon incohérente dans le JSON-LD (`Bénin` en clair mais
  `écosystème` échappé). **Cherchez par motif, jamais par chaîne littérale.**
- 21 ressources répondent 404 — **elles y manquaient déjà**. Vérifiez contre le
  miroir avant de vous accuser.

**Les images**
- Le `srcset` fait que **toutes les tailles d'un sujet doivent partager le même
  cadrage** ; en refaire une partie ferait sauter le cadrage selon l'écran.
- Un recadrage centré décapite. `photos.py` note structure, teinte de peau et
  saturation, puis fait glisser la fenêtre. Deux réglages sont contre-intuitifs :
  la structure se lit sur une **forte réduction** (sinon la latérite, très
  texturée, attire le cadrage vers le sol), et la saturation pèse peu (la terre
  rouge est saturée).
- WebP **qualité 75**, choisie au SSIM. Plus haut ne gagne rien : les JPEG
  d'origine sont déjà encodés autour de q65.

**Le navigateur**
- Nos fichiers portent une empreinte `?v=<hash>`. Sans elle, un cache tenace fait
  croire qu'un correctif n'a pas marché.
- Une transition de carrousel dure 500 ms : un test qui n'attend que 400 ms avale
  le clic et conclut à tort que le carrousel est bloqué.
- Les flèches des carrousels sont **hors** du conteneur `.swiper` : les chercher
  dedans revient à ne rien cliquer.

**Les services distants**
- Trois API du site d'origine sont mortes. Une quatrième, Google Maps, fonctionne —
  mais sa clé appartenait à l'éditeur du site d'origine et lui était facturée. Elle
  a été retirée. Pour rétablir la carte, mettez une clé du projet.
- L'API de Wikimedia Commons ralentit sous une rafale d'appels. `photos.py`
  réessaie et lève `CommonsMuet` plutôt que de faire passer un silence pour une
  absence.

---

## 10. En cas de doute

Le projet est instrumenté exprès : presque toute question a une commande qui y
répond.

| Question | Commande |
|---|---|
| Le rendu a-t-il bougé ? | `python3 tools/compare.py` |
| Quelle page mène où ? | ouvrez `docs/atlas.html` |
| Que manque-t-il, page par page ? | `RELECTURE.md` |
| Ce composant rend-il bien l'original ? | `python3 tools/media.py`, `tools/card.py` |
| Quelles images sont demandées sans exister ? | voir la fonction `complete_les_decoupes` |

**Mesurez avant d'affirmer.** C'est la seule méthode qui a tenu sur ce projet.
