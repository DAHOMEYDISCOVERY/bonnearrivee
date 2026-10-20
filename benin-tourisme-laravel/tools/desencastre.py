#!/usr/bin/env python3
"""
Désencastrement : sortir le site du thème dont il est issu.

Le miroir servait ses feuilles de style, ses scripts et ses polices depuis les
domaines de l'agence qui a fabriqué le thème d'origine — `woody.cloudly.space` et
`api.cloudly.space`. Ces chemins ont été aspirés tels quels : le projet porte donc,
dans 768 adresses, le nom d'un tiers et la structure interne de son outil
(`app/dist/benin`, `app/themes/woody-theme`).

Rien de tout cela n'a de sens ici. Ce module réécrit ces adresses vers une
arborescence qui appartient au projet :

    /assets/woody.cloudly.space/app/dist/benin/…        →  /assets/site/…
    /assets/woody.cloudly.space/app/themes/woody-theme/ →  /assets/site/theme/…
    /assets/api.cloudly.space/…                         →  /assets/site/vendor/…

**Le rendu ne change pas d'un pixel** : ce sont les mêmes octets, servis depuis un
autre chemin. La réécriture s'applique aux deux côtés de la comparaison, comme les
autres transformations déclarées — `convert.py` la passe sur le miroir avant
d'écrire les vues, `compare.py` avant de comparer.

    python3 tools/desencastre.py             # les cas de test
    python3 tools/desencastre.py --deplacer  # déplace les fichiers sur le disque
"""

from __future__ import annotations

import argparse
import re
import shutil
import sys
from pathlib import Path

RACINE = Path(__file__).resolve().parent.parent
ASSETS = RACINE / "public" / "assets"

# Du plus précis au plus général : le thème avant le domaine qui le porte.
DÉPLACEMENTS = (
    ("woody.cloudly.space/app/dist/benin", "site"),
    ("woody.cloudly.space/app/themes/woody-theme/src", "site/theme"),
    ("woody.cloudly.space/app/themes/woody-theme", "site/theme"),
    ("woody.cloudly.space", "site/divers"),
    ("api.cloudly.space", "site/vendor"),
)


def _motif(ancien: str) -> re.Pattern:
    """Reconnaît l'adresse, échappée pour JSON comme en clair.

    Le miroir écrit ces chemins dans des attributs HTML, mais aussi à l'intérieur
    de charges JSON où les barres obliques sont échappées (`\\/`). Les deux formes
    doivent être réécrites, sans quoi une moitié des scripts pointerait dans le vide.
    """
    morceaux = [re.escape(m) for m in ancien.split("/")]
    return re.compile(r"(?<=/assets)(\\?/)" + r"\\?/".join(morceaux))


MOTIFS = tuple((_motif(a), n) for a, n in DÉPLACEMENTS)


def desencastre(html: str) -> tuple[str, int]:
    """Réécrit les adresses du thème et retire ses marques."""
    total = 0
    for motif, nouveau in MOTIFS:
        html, n = motif.subn(lambda m, v=nouveau: m.group(1) + v.replace("/", m.group(1)), html)
        total += n
    html = html.replace("woody-schema-graph", "benin-schema-graph")
    html, gabarits = GABARIT.subn("gabarit-", html)
    total += gabarits
    html, tiers = IMAGE_DE_L_AGENCE.subn('""', html)
    total += tiers
    html = html.replace(CHEMIN_DU_THEME, CHEMIN_DU_PROJET)
    html, polices = aligne_les_polices(html)
    total += polices
    html, carte = remplace_la_carte_du_pied(html)
    total += carte
    html, tagline = ajoute_la_tagline_du_pied(html)
    total += tagline
    html, liens_pied = retire_les_liens_internes_du_pied(html)
    total += liens_pied
    html, marques = retire_les_marques(html)
    html, doublons = dedoublonne_les_feuilles_de_style(html)
    total += doublons
    html, wp_morts = retire_les_scripts_wordpress_morts(html)
    total += wp_morts
    # NB : `retire_les_libs_sans_usage` a été retiré du pipeline en 2026-09-01.
    # Les libs candidates (plyr, flatpickr, nouislider, iframeResizer, rellax)
    # semblaient sans usage à l'audit statique, mais leur retrait a désorganisé
    # le rendu de la home — sans doute des composants Woody chargent ces libs
    # via un mécanisme non détecté par grep. La fonction est conservée dans ce
    # module au cas où un audit plus fin en réhabilite l'usage.
    html, manifest = ajoute_le_manifest_pwa(html)
    total += manifest
    html, fallbacks = assure_les_fallbacks_meta(html)
    total += fallbacks
    html, espaces = normalise_les_classes(html)
    return html, total + marques + espaces


def deplace() -> dict:
    """Déplace les fichiers sur le disque, dans l'ordre du plus précis au plus large."""
    bilan = {"deplaces": [], "absents": []}
    for ancien, nouveau in DÉPLACEMENTS:
        source, cible = ASSETS / ancien, ASSETS / nouveau
        if not source.exists():
            bilan["absents"].append(ancien)
            continue
        cible.parent.mkdir(parents=True, exist_ok=True)
        if cible.exists():
            for enfant in source.iterdir():
                destination = cible / enfant.name
                if destination.exists():
                    shutil.rmtree(destination) if destination.is_dir() else destination.unlink()
                shutil.move(str(enfant), str(destination))
            shutil.rmtree(source)
        else:
            shutil.move(str(source), str(cible))
        bilan["deplaces"].append(f"{ancien} → {nouveau}")

    # Les dossiers vides que les déplacements laissent derrière eux.
    for reste in ("woody.cloudly.space/app", "woody.cloudly.space"):
        chemin = ASSETS / reste
        if chemin.is_dir() and not any(chemin.iterdir()):
            chemin.rmdir()
    return bilan


# Marques que WordPress posait sur le <body> pour nommer son thème. Vérifié : aucune
# feuille de style ni aucun script du site ne s'en sert. `prod` et `menus-v2`, en
# revanche, sont employées par 27 et 5 fichiers — elles restent.
MARQUES = (
    "wp-theme-woody-theme",
    "wp-child-theme-benin",
    "woodypage-front",
    "page-template-default",
    "wp-singular",
)

# Le préfixe `woody-` des classes du thème (`woody-component-…`) est employé par 42
# fichiers, et se confond avec des noms de dossiers (`woody-library`, `woody-addon-…`).
# Le renommer demanderait de réécrire ces fichiers et de déplacer ces dossiers : le
# gain serait cosmétique, le risque de casser le rendu bien réel. Il reste donc.


# WordPress nommait le gabarit de chaque page `woodypage-…`. La famille est
# renommée d'un bloc — les deux feuilles de style qui s'appuient sur
# `woodypage-playlist` ont été réécrites en même temps, sans quoi la mise en page
# des listes sauterait.
GABARIT = re.compile(r"\bwoodypage-")

# Le service d'images de l'agence redimensionnait les vignettes de partage. Il ne
# répond plus pour ce site, et son adresse n'a pas à y figurer.
IMAGE_DE_L_AGENCE = re.compile(r'"https?:\\?/\\?/api\.cloudly\.space[^"]*"')

# Un motif de chemin, dans la configuration du cache, qui nomme encore le thème.
CHEMIN_DU_THEME = r"\/app\/themes\/woody-theme\/*"
CHEMIN_DU_PROJET = r"\/app\/themes\/benin\/*"

# Google Fonts servi en miroir local : le nom d'origine porte un « : » pour la
# variante (`family=Montserrat:medium`, `family=Montserrat:bold`). Ce caractère
# est interdit sur NTFS ; les fichiers ont donc été renommés en `_medium.css` et
# `_bold.css`. Le HTML capturé continue de référencer la forme d'origine et casse
# en 404 sous Windows — les titres et le gras tombent alors sur le fallback
# système. On aligne le HTML sur le nom du fichier.
POLICE_VARIANTE = re.compile(r'(family=Montserrat):(medium|bold)\.css')


def aligne_les_polices(html: str) -> tuple[str, int]:
    """Aligne les URL de polices sur les noms de fichier compatibles NTFS."""
    return POLICE_VARIANTE.subn(r'\1_\2.css', html)


# Espaces multiples dans `class="…"`, souvent laissés par un template CMS qui
# concatène des classes conditionnelles vides (`class="a {{ $x ? 'b' : '' }} c"`
# → `class="a  c"`) ou par le retrait des marques ci-dessus. Le rendu ne change
# pas ; on normalise pour rester lisible dans les diffs et les captures.
CLASSE_ATTRIBUT = re.compile(r'(\bclass=")([^"]*)"')


def normalise_les_classes(html: str) -> tuple[str, int]:
    """Réduit les whitespace multiples à un seul dans les attributs class."""
    compte = 0

    def collapse(m: re.Match) -> str:
        nonlocal compte
        original = m.group(2)
        propre = " ".join(original.split())
        if propre != original:
            compte += 1
        return f'{m.group(1)}{propre}"'

    return CLASSE_ATTRIBUT.sub(collapse, html), compte


# La silhouette du Bénin en jaune dans le pied de page est remplacée par une
# projection stylisée du monde où le pays est repéré par un tag. C'est un
# véritable indicateur de situation pour un visiteur qui ne saurait pas où
# situer le Bénin en Afrique, plutôt qu'une simple redite du contour du pays
# déjà porté par tout le contenu géographique du site.
MAP_FOOTER = re.compile(
    r'<svg version="1\.1" id="map-footer"[^>]*>.*?</svg>', re.S
)
MAP_FOOTER_NEW = (
    '<img src="/assets/img/world-benin.svg" '
    'alt="Localisation du Bénin sur la carte du monde" '
    'class="world-map-footer"/>'
)


def remplace_la_carte_du_pied(html: str) -> tuple[str, int]:
    """Substitue la silhouette du Bénin par la carte de situation mondiale."""
    return MAP_FOOTER.subn(MAP_FOOTER_NEW, html)


# Tagline institutionnelle ajoutée sous le logo du footer. Insérée juste
# après le bloc `.brand-wrapper` — idempotente (le motif d'insertion inclut
# la tagline, si elle est déjà là on ne l'ajoute pas deux fois).
LOGO_FOOTER = re.compile(
    r'(<div class="brand-wrapper flex-container align-center">\s*'
    r'<a class="brand-logo isRel" href="/">\s*'
    r'<img src="/assets/site/img/logo/logo-institutionnel\.png"[^>]*>\s*'
    r'</a>\s*</div>)(?!\s*<p class="brand-tagline")',
    re.S,
)
TAGLINE_HTML = (
    '\n        <p class="brand-tagline">'
    "Portail du Tourisme et de l'Attractivité au Bénin"
    '</p>'
)


def ajoute_la_tagline_du_pied(html: str) -> tuple[str, int]:
    """Ajoute la tagline institutionnelle sous le logo du footer."""
    return LOGO_FOOTER.subn(lambda m: m.group(1) + TAGLINE_HTML, html)


# Liens du pied de page réservés à un usage interne — la fiche de relecture
# et la page de crédits photos sont des outils de mise au point du projet,
# pas des rubriques destinées au visiteur. On les retire des boutons du
# footer ; les pages elles-mêmes restent accessibles par leur URL.
LIENS_INTERNES_FOOTER = re.compile(
    r'<a class="button primary" href="/credits-photos"[^>]*>\s*'
    r'Cr\xe9dits\s*</a>\s*'
    r'<a class="button primary" href="/fiche-de-relecture"[^>]*>\s*'
    r'Relecture\s*</a>',
    re.S,
)


def retire_les_liens_internes_du_pied(html: str) -> tuple[str, int]:
    """Retire les liens Crédits et Relecture des boutons du footer."""
    return LIENS_INTERNES_FOOTER.subn("", html)


LIEN_FEUILLE = re.compile(
    r"<link\s+[^>]*rel=['\"]stylesheet['\"][^>]*/?>",
    re.IGNORECASE,
)
HREF_DE_LA_FEUILLE = re.compile(r"href=['\"]([^'\"]+)['\"]")


def dedoublonne_les_feuilles_de_style(html: str) -> tuple[str, int]:
    """Retire les <link rel=stylesheet> chargés plusieurs fois pour la même URL.

    Le miroir WordPress agrège les feuilles déclarées par plusieurs plugins
    Woody sous des `id` distincts mais pointant vers le même fichier ; le
    navigateur les demande alors deux fois. On garde la première occurrence
    de chaque href dans l'ordre du document et on efface les suivantes.
    """
    vus: set[str] = set()
    doublons = 0

    def garde(match: re.Match) -> str:
        nonlocal doublons
        tag = match.group(0)
        m = HREF_DE_LA_FEUILLE.search(tag)
        if m is None:
            return tag
        href = m.group(1)
        if href in vus:
            doublons += 1
            return ""
        vus.add(href)
        return tag

    return LIEN_FEUILLE.sub(garde, html), doublons


BALISE_VIEWPORT = re.compile(r'(<meta name="viewport"[^>]*/?>)')
INSERTION_MANIFEST = (
    '<link rel="manifest" href="/site.webmanifest"/>'
    '<meta name="theme-color" content="#00a05d"/>'
)


def ajoute_le_manifest_pwa(html: str) -> tuple[str, int]:
    """Insère `<link rel=manifest>` + `<meta theme-color>` juste après le viewport.

    Le manifest rend le site installable sur mobile (icône d'accueil, splash
    screen). Le theme-color harmonise la barre système sur Android/Chrome
    avec la couleur institutionnelle. Idempotent : bloque si déjà présent."""
    if 'href="/site.webmanifest"' in html:
        return html, 0
    return BALISE_VIEWPORT.subn(r'\1' + INSERTION_MANIFEST, html, count=1)


SCRIPTS_LIBS_SANS_USAGE = [
    re.compile(r'<script\b[^>]*/plyr/dist/plyr\.min\.js[^>]*>[\s\S]*?</script>', re.IGNORECASE),
    re.compile(r'<script\b[^>]*/flatpickr/dist/flatpickr\.min\.js[^>]*>[\s\S]*?</script>', re.IGNORECASE),
    re.compile(r'<script\b[^>]*/flatpickr/dist/l10n/fr\.js[^>]*>[\s\S]*?</script>', re.IGNORECASE),
    re.compile(r'<script\b[^>]*/nouislider\.min\.js[^>]*>[\s\S]*?</script>', re.IGNORECASE),
    re.compile(r'<script\b[^>]*/iframeResizer\.min\.js[^>]*>[\s\S]*?</script>', re.IGNORECASE),
    re.compile(r'<script\b[^>]*/rellax\.min\.js[^>]*>[\s\S]*?</script>', re.IGNORECASE),
]


def retire_les_libs_sans_usage(html: str) -> tuple[str, int]:
    """Retire des libs JS chargées par le thème mais sans usage sur ce site.

    Chaque candidat est vérifié : plyr (pas de `<video>`/`<audio>`),
    flatpickr (pas de date picker : booking retiré), nouislider (pas de range
    slider), iframeResizer (le seul `<iframe>` est un GTM neutralisé), rellax
    (pas de `data-rellax`). Économise ~190 Ko réseau par première visite."""
    total = 0
    for pat in SCRIPTS_LIBS_SANS_USAGE:
        html, n = pat.subn("", html); total += n
    return html, total


SCRIPTS_WORDPRESS_MORTS = re.compile(
    r'<script\b[^>]*id="(?:wp-i18n-js|wp-hooks-js|wp-i18n-ltr-js|wp-i18n-js-after|wp-i18n-js-before)"[^>]*>[\s\S]*?</script>',
    re.IGNORECASE,
)


def retire_les_scripts_wordpress_morts(html: str) -> tuple[str, int]:
    """Retire les scripts WordPress restés dans le miroir mais sans usage.

    Trois familles concernées : `wp-i18n` (localisation UI PHP-only, aucun
    appel à `wp.i18n`/`__()` côté front), `wp-hooks` (filtres/actions PHP-only),
    et `i18n-ltr` (bascule LTR/RTL non utilisée : le site est mono-directionnel).
    Vérifié par grep sur `.mjs`/`.js` livrés — aucun code ne référence ces API.
    """
    return SCRIPTS_WORDPRESS_MORTS.subn("", html)


IMAGE_FALLBACK_META = "/assets/img/cotonou-1920x1080.webp"
DESCRIPTION_FALLBACK_META = (
    "Bienvenue au Bénin : plages du golfe de Guinée, palais royaux d'Abomey, "
    "cité lacustre de Ganvié et parcs du Nord inscrits au patrimoine mondial. "
    "Préparez votre voyage."
)

_YIELD_HEAD_IMAGE_OG = "@yield('headImageOg')"
_YIELD_HEAD_IMAGE_TW = "@yield('headImageTwitter')"
_DESC_PATTERNS = [
    ("description", "meta name"),
    ("og:description", "meta property"),
    ("twitter:description", "meta name"),
]


def assure_les_fallbacks_meta(html: str) -> tuple[str, int]:
    """Assure les meta SEO/OG de repli : og:image, twitter:image, description.

    Les pages du miroir déclarent parfois `@section('headImageOg')` (visite
    d'un lieu illustré), parfois pas (page utilitaire). Pour ne jamais servir
    de card Open Graph vide, on entoure les `@yield('headImage*')` d'un test
    `@hasSection(...)` avec image de repli, et on complète le bloc description
    par un `@else`. Idempotent : la première application y laisse une marque
    (`@hasSection('headImageOg')`) qui bloque la deuxième.
    """
    changes = 0
    if "@hasSection('headImageOg')" not in html:
        replacement = (
            "@hasSection('headImageOg')"
            + _YIELD_HEAD_IMAGE_OG
            + f'@else@verbatim<meta property="og:image" content="{IMAGE_FALLBACK_META}"/>@endverbatim@endif'
        )
        if _YIELD_HEAD_IMAGE_OG in html:
            html = html.replace(_YIELD_HEAD_IMAGE_OG, replacement, 1)
            changes += 1
    if "@hasSection('headImageTwitter')" not in html:
        replacement = (
            "@hasSection('headImageTwitter')"
            + _YIELD_HEAD_IMAGE_TW
            + f'@else@verbatim<meta property="twitter:image" content="{IMAGE_FALLBACK_META}"/>@endverbatim@endif'
        )
        if _YIELD_HEAD_IMAGE_TW in html:
            html = html.replace(_YIELD_HEAD_IMAGE_TW, replacement, 1)
            changes += 1
    for meta_name, kind in _DESC_PATTERNS:
        # match : @if($description ?? null)<KIND="NAME" content="{{ $description }}"/>@endif
        pat = re.compile(
            r'@if\(\$description \?\? null\)<'
            + kind
            + r'="' + re.escape(meta_name) + r'" content="\{\{ \$description \}\}"/>@endif'
        )
        rep = (
            f'@if($description ?? null)<{kind}="{meta_name}" content="{{{{ $description }}}}"/>'
            f'@else<{kind}="{meta_name}" content="{DESCRIPTION_FALLBACK_META}"/>@endif'
        )
        new, n = pat.subn(rep, html)
        if n:
            html = new
            changes += n
    return html, changes


BALISE_BODY = re.compile(r"<body\b[^>]*>")


def retire_les_marques(html: str) -> tuple[str, int]:
    """Retire du <body> les classes par lesquelles WordPress se nommait.

    Le retrait est borné à la balise ouvrante : ces mots apparaissent aussi
    ailleurs — dans le balisage schema.org, dans une charge JSON — et les ôter
    partout modifierait le <head>, ce qui n'est pas le propos.
    """
    compte = 0

    def sur_le_body(trouvé: re.Match) -> str:
        nonlocal compte
        balise = trouvé.group(0)
        for marque in MARQUES:
            balise, n = re.subn(rf"(?<=[\s\"]){re.escape(marque)}(?=[\s\"])\s?", "", balise)
            compte += n
        return balise

    return BALISE_BODY.sub(sur_le_body, html, count=1), compte


CAS = [
    (
        '<link href="/assets/woody.cloudly.space/app/dist/benin/css/main_chunk_1.css">',
        '<link href="/assets/site/css/main_chunk_1.css">',
        "une feuille de style du thème",
    ),
    (
        '"/assets/woody.cloudly.space/app/themes/woody-theme/src/js/static/angular-config.min.js"',
        '"/assets/site/theme/js/static/angular-config.min.js"',
        "un script du thème",
    ),
    (
        '"\\/assets\\/woody.cloudly.space\\/app\\/dist\\/benin\\/addons\\/lib.mjs"',
        '"\\/assets\\/site\\/addons\\/lib.mjs"',
        "une adresse échappée dans une charge JSON",
    ),
    (
        '<link href="/assets/api.cloudly.space/static/assets/fonts/raccourci-font.min.css">',
        '<link href="/assets/site/vendor/static/assets/fonts/raccourci-font.min.css">',
        "la police servie par le second domaine",
    ),
    (
        'src="/assets/img/ganvie-640x480.webp"',
        'src="/assets/img/ganvie-640x480.webp"',
        "les photographies du projet ne bougent pas",
    ),
    (
        'href="https://woody.cloudly.space/ailleurs"',
        'href="https://woody.cloudly.space/ailleurs"',
        "une adresse hors de /assets n'est pas touchée",
    ),
    (
        '<body class="home wp-singular page-template-default wp-theme-woody-theme prod menus-v2">',
        '<body class="home prod menus-v2">',
        "les marques de WordPress partent, prod et menus-v2 restent",
    ),
    (
        '<div class="woody-component-landswpr tpl_03">',
        '<div class="woody-component-landswpr tpl_03">',
        "le préfixe des classes du thème est conservé",
    ),
    (
        "<link href='/assets/fonts.googleapis.com/css_family=Montserrat:medium.css'/>",
        "<link href='/assets/fonts.googleapis.com/css_family=Montserrat_medium.css'/>",
        "les variantes Montserrat sont alignées sur les noms de fichier NTFS",
    ),
    (
        "<link href='/assets/fonts.googleapis.com/css_family=Montserrat:bold.css'/>",
        "<link href='/assets/fonts.googleapis.com/css_family=Montserrat_bold.css'/>",
        "la variante bold de Montserrat aussi",
    ),
    (
        "<link href='/assets/fonts.googleapis.com/css_family=Montserrat.css'/>",
        "<link href='/assets/fonts.googleapis.com/css_family=Montserrat.css'/>",
        "la police sans variante n'est pas touchée",
    ),
    (
        '<div class="a  b   c "></div>',
        '<div class="a b c"></div>',
        "les whitespace multiples dans class sont réduits",
    ),
    (
        '<div class="a"></div>',
        '<div class="a"></div>',
        "un attribut class propre n'est pas touché",
    ),
    (
        "<link rel='stylesheet' id='a-css' href='/assets/x.css'/>"
        "<link rel='stylesheet' id='b-css' href='/assets/x.css'/>",
        "<link rel='stylesheet' id='a-css' href='/assets/x.css'/>",
        "un stylesheet chargé deux fois pour la même URL est dédoublonné",
    ),
    (
        "<link rel='stylesheet' href='/assets/x.css'/>"
        "<link rel='stylesheet' href='/assets/y.css'/>",
        "<link rel='stylesheet' href='/assets/x.css'/>"
        "<link rel='stylesheet' href='/assets/y.css'/>",
        "deux stylesheets distincts ne sont pas touchés",
    ),
    (
        "@yield('headImageOg')",
        "@hasSection('headImageOg')@yield('headImageOg')@else@verbatim"
        "<meta property=\"og:image\" content=\"/assets/img/cotonou-1920x1080.webp\"/>"
        "@endverbatim@endif",
        "un @yield('headImageOg') sans fallback est entouré d'une image de repli",
    ),
    (
        "@hasSection('headImageOg')@yield('headImageOg')@else@endif",
        "@hasSection('headImageOg')@yield('headImageOg')@else@endif",
        "un @yield déjà entouré d'un @hasSection n'est plus modifié",
    ),
    (
        '<script defer src="/x/wp-i18n.js" id="wp-i18n-js"></script>OK',
        'OK',
        "le script WordPress wp-i18n est retiré",
    ),
    (
        '<script src="/x/main.js"></script>',
        '<script src="/x/main.js"></script>',
        "un script sans id WordPress n'est pas touché",
    ),
    (
        '<meta name="viewport" content="width=device-width"/>',
        '<meta name="viewport" content="width=device-width"/>'
        '<link rel="manifest" href="/site.webmanifest"/>'
        '<meta name="theme-color" content="#00a05d"/>',
        "le manifest PWA + theme-color sont insérés après le viewport",
    ),
    (
        '<meta name="viewport"/><link rel="manifest" href="/site.webmanifest"/>',
        '<meta name="viewport"/><link rel="manifest" href="/site.webmanifest"/>',
        "un head qui a déjà le manifest n'est pas dupliqué",
    ),
    (
        '<script src="/x/lazysizes.min.js"></script>',
        '<script src="/x/lazysizes.min.js"></script>',
        "lazysizes reste — utilisé pour le lazy-loading images",
    ),
]


def teste() -> int:
    échecs = 0
    for entrée, attendu, intitulé in CAS:
        obtenu, _ = desencastre(entrée)
        if obtenu != attendu:
            échecs += 1
            print(f"  ÉCHEC — {intitulé}\n    attendu : {attendu}\n    obtenu  : {obtenu}")
    print(f"{échecs} cas sur {len(CAS)} échouent." if échecs else f"Les {len(CAS)} cas de test passent.")
    return 1 if échecs else 0


def main() -> int:
    parser = argparse.ArgumentParser(description=__doc__)
    parser.add_argument("--deplacer", action="store_true", help="déplace les fichiers sur le disque")
    args = parser.parse_args()

    if not args.deplacer:
        return teste()

    bilan = deplace()
    for ligne in bilan["deplaces"]:
        print(f"  {ligne}")
    if bilan["absents"]:
        print(f"  déjà déplacés : {', '.join(bilan['absents'])}")
    return 0


if __name__ == "__main__":
    sys.exit(main())
