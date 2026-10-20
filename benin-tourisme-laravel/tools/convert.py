#!/usr/bin/env python3
"""
Convertisseur : site statique « Bénin tourisme » → vues Blade.

Le miroir répète le même gabarit sur ses 89 pages. Ce script exploite cette
régularité : il découpe chaque page en régions, réécrit les URL relatives en
chemins absolus, et n'écrit qu'une fois ce qui est commun.

    python3 tools/convert.py            # écrit les vues
    python3 tools/convert.py --check    # affiche le plan sans rien écrire

Une contrainte gouverne tout le fichier : **le HTML servi doit être celui du
miroir**. Les liaisons irrégulières du gabarit — le <header> y referme deux <div>
ouverts hors de lui — sont transportées telles quelles, jamais redressées. Le
découpage se vérifie lui-même : `Regions.reassemble()` doit rendre le <body>
source au caractère près, sans quoi le script refuse d'écrire.

Ce que le miroir impose de savoir
---------------------------------
* **wget réécrit les liens vers la page courante en « index.html ».** Le menu
  d'une page contient donc des auto-liens. Résolus depuis le dossier de la page,
  ils désignent la même destination que sur l'accueil — c'est pourquoi le gabarit
  partagé est prélevé sur l'accueil, où aucun lien n'est ambigu.
* **Le logo de pied de page a échappé à cette relativisation** : « index.html »
  y figure sur les 89 pages, quelle que soit leur profondeur, si bien qu'il pointe
  sur la page courante au lieu de l'accueil. Le prélever sur l'accueil le répare.
* **Quatre profils de <head>** cohabitent, selon les ressources chargées :
  standard (78 pages), listing (5), fiche offre (3), et l'accueil (standard plus
  la feuille de l'atlas).
* **Deux pages n'utilisent pas le gabarit Woody** — `credits-photos.html` et
  `fiche-de-relecture.html`, écrites à la main. Elles sont converties telles quelles.
"""

from __future__ import annotations

import argparse
import difflib
import posixpath
import re
import sys
from dataclasses import dataclass
from pathlib import Path

sys.path.insert(0, str(Path(__file__).resolve().parent))
from card import componentise as componentise_cartes  # noqa: E402
from media import componentise as componentise_figures  # noqa: E402
from nettoyage import nettoie  # noqa: E402
from contenu import corrige_le_contenu, corrige_les_fiches  # noqa: E402
from photos import credite_les_ajouts, remplace_les_visuels  # noqa: E402
from ajouts import applique  # noqa: E402
from reparation import repare_les_titres
from desencastre import desencastre
from webp import passe_en_webp  # noqa: E402

SOURCE = Path("/Users/mac/Desktop/benin-tourisme-copie")
ROOT = Path(__file__).resolve().parent.parent
VIEWS = ROOT / "resources" / "views"

BANNER = "{{-- Généré par tools/convert.py — ne pas modifier à la main. --}}\n"

# Schémas d'URL à ne jamais réécrire : ils ne désignent pas une ressource locale.
ABSOLUTE_PREFIXES = (
    "http://", "https://", "//", "#", "mailto:", "tel:",
    "data:", "javascript:", "about:", "{", "/",
)

# Attributs susceptibles de porter une URL locale. « content » en fait partie :
# c'est lui qui porte les images sociales (og:image, twitter:image).
URL_ATTRS = ("src", "href", "data-src", "data-srcset", "srcset", "data-bg", "action", "content")

# Pages écrites à la main, hors gabarit Woody.
PAGES_AUTONOMES = ("credits-photos.html", "fiche-de-relecture.html")

# Sections de la page d'accueil, dans l'ordre où le gabarit les produit.
SECTIONS_ACCUEIL = ["atlas", "regions", "activites", "actualites", "newsletter", "socialwall"]


# --------------------------------------------------------------------------- #
# Routes
# --------------------------------------------------------------------------- #

def route_of(relative: str) -> str:
    """Chemin URL servi par Laravel pour un fichier du miroir."""
    if relative == "index.html":
        return "/"
    if relative.endswith("/index.html"):
        return "/" + relative[: -len("/index.html")]
    return "/" + relative[: -len(".html")]


def view_of(relative: str) -> str:
    """Nom de vue Blade correspondant, en notation pointée."""
    if relative == "index.html":
        return "pages.accueil"
    return "pages." + relative[: -len(".html")].replace("/", ".").rstrip(".")


# --------------------------------------------------------------------------- #
# Réécriture des URL
# --------------------------------------------------------------------------- #

class Rewriter:
    """Réécrit les URL d'une page du miroir en chemins absolus.

    Le miroir adresse ses ressources depuis la page courante (« ../assets/… ») et
    ses pages par leur fichier (« explorez/index.html »). Laravel sert tout depuis
    la racine, avec des URL sans extension. La résolution part donc du dossier de
    la page : sans cela, « le-littoral-et-les-lacs/index.html » lu depuis
    `explorez/index.html` donnerait « /le-littoral-et-les-lacs » au lieu de
    « /explorez/le-littoral-et-les-lacs ».
    """

    # Les attributs se rencontrent avec les deux styles de guillemets : WordPress
    # écrit ses feuilles de style en apostrophes et le reste en guillemets doubles.
    ATTRIBUTE = re.compile(rf"""\b({"|".join(URL_ATTRS)})=(["'])([^"']*)\2""", re.IGNORECASE)
    ESCAPED_ASSETS = re.compile(r"""(["'])(?:\.{1,2}\\/)*assets\\/""")

    def __init__(self, relative: str) -> None:
        self.relative = relative
        self.directory = posixpath.dirname(relative)
        self.route = route_of(relative)

    def url(self, value: str) -> str:
        if not value or value.startswith(ABSOLUTE_PREFIXES):
            return value

        path, _, anchor = value.partition("#")
        anchor = "#" + anchor if anchor or value.endswith("#") else ""
        if not path:
            return value

        resolved = posixpath.normpath(posixpath.join(self.directory, path))
        if resolved.startswith("assets/"):
            return "/" + resolved + anchor
        if not resolved.endswith(".html"):
            return value

        route = route_of(resolved)
        # « index.html# » est le lien d'évitement : il vise la page courante.
        return anchor if (route == self.route and anchor) else route + anchor

    def srcset(self, value: str) -> str:
        """Réécrit chaque URL d'un srcset en conservant ses descripteurs de largeur."""
        candidates = []
        for candidate in filter(None, (c.strip() for c in value.split(","))):
            url, _, descriptor = candidate.partition(" ")
            candidates.append(" ".join(filter(None, [self.url(url), descriptor.strip()])))
        return ", ".join(candidates)

    def __call__(self, html: str) -> str:
        if "@endverbatim" in html:
            raise SystemExit(f"{self.relative} contient « @endverbatim » : la protection serait rompue.")

        def replace(match: re.Match) -> str:
            attr, quote, value = match.group(1), match.group(2), match.group(3)
            rewritten = self.srcset(value) if "srcset" in attr.lower() else self.url(value)
            return f"{attr}={quote}{rewritten}{quote}"

        html = self.ATTRIBUTE.sub(replace, html)
        # Les blocs JSON inline (importmap, WoodyMainJsModules) échappent leurs
        # barres obliques et ne sont donc pas des attributs.
        return self.ESCAPED_ASSETS.sub(r"\1\\/assets\\/", html)


# --------------------------------------------------------------------------- #
# Découpage d'une page
# --------------------------------------------------------------------------- #

@dataclass(frozen=True)
class Regions:
    """Une page du miroir découpée en régions dont la concaténation la reconstitue.

    Les champs « glue_* » portent les liaisons du gabarit — sauts de ligne,
    indentation et <div> intermédiaires. Ils paraissent négligeables et ne le sont
    pas : ce sont eux qui rendent la reconstitution exacte.
    """

    head: str
    body_open: str
    glue_body: str
    wrapper_open: str
    glue_wrapper: str
    headnavs: str
    header: str
    glue_header: str
    main_open: str
    main: str
    glue_main: str
    footer: str
    glue_footer: str
    overlays: str

    @classmethod
    def parse(cls, html: str) -> "Regions":
        cut = _Cutter(html)
        header_at = cut.at(r'<header role="banner"')
        header_end = cut.after(r"</header>", header_at)
        return cls(
            head=cut.between("<head>", "</head>"),
            body_open=cut.match(r"<body[^>]*>"),
            glue_body=cut.slice(cut.after(r"<body[^>]*>"), cut.at(r'<div class="main-wrapper')),
            wrapper_open=cut.match(r'<div class="main-wrapper[^"]*">'),
            glue_wrapper=cut.slice(
                cut.after(r'<div class="main-wrapper[^"]*">'),
                cut.at(r'<div class="woody-component-headnavs">'),
            ),
            headnavs=cut.slice(cut.at(r'<div class="woody-component-headnavs">'), header_at),
            header=cut.slice(header_at, header_end),
            glue_header=cut.slice(header_end, cut.at(r"<main\b")),
            main_open=cut.match(r"<main[^>]*>"),
            main=cut.slice(cut.after(r"<main[^>]*>"), cut.at(r"</main>")),
            glue_main=cut.slice(cut.after(r"</main>"), cut.at(r'<footer role="contentinfo"')),
            footer=cut.slice(cut.at(r'<footer role="contentinfo"'), cut.after(r"</footer>")),
            glue_footer=cut.slice(cut.after(r"</footer>"), cut.at(r"<woody-search-engine")),
            overlays=cut.slice(cut.at(r"<woody-search-engine"), cut.at(r"</body>")),
        )

    def reassemble(self) -> str:
        """Reconstitue le <body> source. Test de non-régression du découpage."""
        return "".join([
            self.body_open, self.glue_body, self.wrapper_open, self.glue_wrapper,
            self.headnavs, self.header, self.glue_header, self.main_open, self.main,
            "</main>", self.glue_main, self.footer, self.glue_footer, self.overlays,
        ])

    def check(self, html: str, relative: str) -> None:
        source_body = html[html.index("<body") : html.index("</body>")]
        if self.reassemble() != source_body:
            raise SystemExit(f"{relative} : le découpage ne reconstitue pas le <body> source.")

    @property
    def body_class(self) -> str:
        found = re.search(r'class="([^"]*)"', self.body_open)
        return found.group(1) if found else ""



class _Cutter:
    """Découpe par expression régulière, en positions absolues."""

    def __init__(self, html: str) -> None:
        self.html = html

    def at(self, pattern: str, start: int = 0) -> int:
        return re.compile(pattern).search(self.html, start).start()

    def after(self, pattern: str, start: int = 0) -> int:
        return re.compile(pattern).search(self.html, start).end()

    def match(self, pattern: str) -> str:
        return re.compile(pattern).search(self.html).group(0)

    def slice(self, a: int, b: int) -> str:
        return self.html[a:b]

    def between(self, start: str, end: str) -> str:
        a = self.html.index(start) + len(start)
        return self.html[a : self.html.index(end, a)]


# --------------------------------------------------------------------------- #
# Protection du HTML importé
# --------------------------------------------------------------------------- #

SENTINEL = "\x00"


def protect(html: str) -> str:
    """Rend le HTML importé opaque à Blade.

    Le gabarit contient des séquences que Blade compilerait : « @context » et
    « @type » du JSON-LD sont des directives depuis Laravel 11, « jquery@ananas »
    en est une aux yeux du compilateur, et les gabarits Twig des pages de liste
    contiennent des « {{ } } ». L'échappement « @@ » ne les couvre pas toutes —
    Blade ne déséchappe pas « @@ananas.min.mjs », suivi d'un point. @verbatim, lui,
    met le bloc entier hors de portée du compilateur.

    Les fragments dynamiques, encadrés au préalable de sentinelles, restent hors
    des blocs protégés. Un bloc @verbatim se terminant par un « @ », un fragment
    qui commencerait par « { » ou « @ » formerait « @{{ » ou « @@ » — un échappement
    d'écho ou de directive. Un saut de ligne l'en sépare alors ; il tombe entre deux
    balises, là où le blanc est sans effet.
    """
    parts = html.split(SENTINEL)
    rendered = []
    for index, part in enumerate(parts):
        if index % 2:
            rendered.append(("\n" + part) if part[:1] in "{@" else part)
        elif part:
            rendered.append(f"@verbatim{part}@endverbatim")
    return "".join(rendered)


def dynamic(fragment: str) -> str:
    """Marque un fragment comme dynamique, pour que `protect` l'épargne."""
    return SENTINEL + fragment + SENTINEL


# --------------------------------------------------------------------------- #
# Le <head>
# --------------------------------------------------------------------------- #

# Balises du <head> propres à chaque page. La balise entière est remplacée, pas
# seulement sa valeur : voir la note d'adjacence dans `protect`.
HEAD_BINDINGS: list[tuple[str, str, bool]] = [
    (r"<title>.*?</title>", "<title>{{ $title }}</title>", True),
    (r'<meta name="description" property="og:description" content="[^"]*"\s*/?>',
     '@if($description ?? null)<meta name="description" property="og:description" '
     'content="{{ $description }}"/>@endif', False),
    (r'<meta name="description" content="[^"]*"\s*/?>',
     '@if($description ?? null)<meta name="description" content="{{ $description }}"/>@endif', False),
    (r'<meta property="og:description" content="[^"]*"\s*/?>',
     '@if($description ?? null)<meta property="og:description" content="{{ $description }}"/>@endif', False),
    (r'<meta name="twitter:description" content="[^"]*"\s*/?>',
     '@if($description ?? null)<meta name="twitter:description" content="{{ $description }}"/>@endif', False),
    (r'<meta property="og:title" content="[^"]*"\s*/?>',
     '<meta property="og:title" content="{{ $title }}"/>', True),
    (r'<meta name="twitter:title" content="[^"]*"\s*/?>',
     '<meta name="twitter:title" content="{{ $title }}"/>', True),
    (r'<meta property="og:url" content="[^"]*"\s*/?>',
     '<meta property="og:url" content="{{ url()->current() }}"/>', True),
    (r'<link rel="canonical" href="[^"]*"\s*/?>',
     '<link rel="canonical" href="{{ url()->current() }}"/>', True),
]

# Blocs entiers du <head> propres à chaque page : le gabarit les remplace par un
# point d'insertion, et chaque vue de page les réémet tels quels.
#
# Les deux derniers portent des clés d'API Google Maps en clair, héritées du site
# d'origine et différentes d'une page à l'autre. Elles sont transportées telles
# quelles — les retirer est une décision d'exploitation, pas de conversion.
PAGE_HEAD_BLOCKS: list[tuple[str, str]] = [
    ("headGlobals", r"<script>window\.globals=.*?</script>"),
    ("headDataLayer", r"<script>window\.dataLayer=.*?</script>"),
    # La classe de ce bloc est renommée par `desencastre` : le motif accepte les deux
    # noms, sans quoi le balisage cesserait d'être vu comme variable et deux pages
    # d'un même profil paraîtraient avoir des <head> différents.
    ("headSchema", r'<script type="application/ld\+json" class="(?:woody|benin)-schema-graph">.*?</script>'),
    ("headPagination", r'<link[^>]*rel="next"[^>]*>'),
    ("headMapsApi", r'<script[^>]*id="gg_maps-js"[^>]*></script>'),
    ("headMapsPlaylist", r'<script[^>]*id="hawwwai_playlist_map-js"[^>]*></script>'),
    ("headAlternate", r'<link[^>]*rel="alternate"[^>]*>'),
    # Les visuels sociaux ne se lient pas à une variable unique : sur les fiches
    # offre, og:image, twitter:image et thumbnail désignent trois images distinctes.
    ("headImageOg", r'<meta (?:property|name)="og:image" content="[^"]*"\s*/?>'),
    ("headImageTwitter", r'<meta (?:property|name)="twitter:image" content="[^"]*"\s*/?>'),
    ("headThumbnail", r'<meta name="thumbnail" content="[^"]*"\s*/?>'),
    # Le miroir a capturé deux versions de ce script selon les pages (3.6.17 et
    # 3.6.18) ; les deux fichiers existent, on sert à chaque page la sienne.
    ("headNgInit", r'<script[^>]*id="hawwwai_ng_init-js"[^>]*></script>'),
]

# Tous les points d'insertion du <head> : les blocs ci-dessus, plus la feuille de
# style propre à la page. Le <head> partagé d'un profil doit tous les porter.
INSERTION_POINTS = [nom for nom, _ in PAGE_HEAD_BLOCKS] + ["styles"]

# Emplacement où l'accueil insère la feuille de l'atlas, seule ressource qui
# distingue son profil du profil standard.
ATLAS_STYLESHEET = re.compile(r"<link[^>]*\bid=['\"]benin-atlas-css['\"][^>]*>")


def parametrise_head(head: str, relative: str) -> str:
    """Rend dynamiques les balises du <head> qui varient d'une page à l'autre."""
    for pattern, replacement, required in HEAD_BINDINGS:
        head, count = re.subn(pattern, dynamic(replacement), head, flags=re.S)
        if count > 1:
            raise SystemExit(f"{relative} : « {pattern[:40]} » trouvé {count} fois.")
        if count == 0 and required:
            raise SystemExit(f"{relative} : « {pattern[:40]} » introuvable dans le <head>.")
    for nom, pattern in PAGE_HEAD_BLOCKS:
        head = re.sub(pattern, dynamic(f"@yield('{nom}')"), head, flags=re.S)
    return ATLAS_STYLESHEET.sub(dynamic("@yield('styles')"), head)


def without_dynamic(head: str) -> str:
    """Retire les fragments dynamiques, pour comparer deux <head> sur leur seule part fixe.

    Une page peut légitimement ne pas porter une balise facultative — l'accueil n'a
    pas d'image sociale, les pages hors liste n'ont pas de lien de pagination. Ces
    absences ne doivent pas faire croire à deux profils différents.
    """
    return re.sub(f"{SENTINEL}[^{SENTINEL}]*{SENTINEL}", "", head)


def merge_head(base: str, donneur: str, nom: str) -> str:
    """Ajoute au <head> de référence un point d'insertion qu'il ne porte pas.

    Aucune page ne porte à elle seule tous les points d'insertion de son profil :
    l'accueil a la feuille de l'atlas mais pas d'image sociale, les autres l'inverse.
    Le <head> partagé doit pourtant les porter tous, sans quoi les pages qui en ont
    besoin les perdraient. On repère donc, chez une page qui l'a, le texte fixe qui
    précède le point d'insertion, et on le retrouve dans la référence.
    """
    marqueur = SENTINEL + f"@yield('{nom}')" + SENTINEL
    if marqueur in base:
        return base
    if marqueur not in donneur:
        raise SystemExit(f"Le point d'insertion « {nom} » est introuvable chez la page donneuse.")

    ancre = without_dynamic(donneur[: donneur.index(marqueur)])[-ANCRE:]
    fixe, positions = strip_dynamic_with_map(base)
    if fixe.count(ancre) != 1:
        raise SystemExit(
            f"L'ancre du point d'insertion « {nom} » apparaît {fixe.count(ancre)} fois dans "
            "le <head> de référence : impossible de le placer sans ambiguïté."
        )

    # positions[i] donne l'index, dans `base`, du i-ième caractère fixe. L'ancre se
    # terminant au caractère `fin`, le point d'insertion se glisse juste après lui.
    fin = fixe.index(ancre) + len(ancre)
    coupe = positions[fin] if fin < len(positions) else len(base)
    return base[:coupe] + marqueur + base[coupe:]


def strip_dynamic_with_map(head: str) -> tuple[str, list[int]]:
    """Retourne le texte fixe du <head> et, pour chaque caractère, sa position d'origine."""
    fixe, positions, dans_fragment = [], [], False
    for index, caractere in enumerate(head):
        if caractere == SENTINEL:
            dans_fragment = not dans_fragment
            continue
        if not dans_fragment:
            fixe.append(caractere)
            positions.append(index)
    return "".join(fixe), positions


ANCRE = 120


def head_profile(head: str) -> str:
    """Nomme le profil d'une page d'après les ressources que son <head> charge."""
    resources = set(re.findall(r"<(?:script|link)\b[^>]*\bid=['\"]([^'\"]+)['\"]", head))
    # Les pages de liste chargent en plus les filtres (chosen, nouislider, picker) ;
    # les fiches offre chargent le module de fiche sans ces filtres. Les deux
    # discriminants survivent au retrait des services inaccessibles — c'est la
    # condition pour qu'ils restent utilisables.
    if "jsdelivr_chosen-js" in resources:
        return "listing"
    if "hawwwai_ng_init-js" in resources:
        return "offre"
    return "standard"


# --------------------------------------------------------------------------- #
# Le gabarit partagé
# --------------------------------------------------------------------------- #

MENU_ITEM = re.compile(r'<li class="menu-item is-submenu-parent(?: is-active)?"([^>]*)>')
BRAND_LOGO = re.compile(r'<div class="is-brand-logo(?: is-active)?( *)">')
SPECULATION = '<script type="speculationrules">'


def split_overlays(overlays: str) -> tuple[str, str]:
    """Sépare les modales, communes à toutes les pages, des scripts de fin qui leur sont propres.

    Le moteur de recherche et les modales sont identiques partout ; ce qui suit le
    bloc « speculationrules » varie — l'accueil y charge l'atlas, les fiches offre et
    les pages de liste y chargent leur configuration Angular.
    """
    debut = overlays.index(SPECULATION)
    fin = overlays.index("</script>", debut) + len("</script>")
    return overlays[:fin], overlays[fin:]


def parametrise_header(header: str) -> str:
    """Rend conditionnel l'état actif du menu, seule chose qui varie d'une page à l'autre.

    Le gabarit marque la rubrique courante par « is-active », sur l'entrée de menu
    et — pour l'accueil — sur le logo. Ces deux marques sont ici rattachées à une
    variable, ce qui permet de n'écrire le menu qu'une fois pour les 89 pages.
    """
    header = BRAND_LOGO.sub(
        lambda m: dynamic("<div class=\"is-brand-logo {{ $activeMenu === 'accueil' ? 'is-active' : '' }}"
                          + m.group(1) + '">'),
        header,
    )

    def menu_item(match: re.Match) -> str:
        # L'identifiant de la rubrique se lit sur le lien qui suit l'ouverture du <li>.
        suite = header[match.end() : match.end() + 900]
        post_id = re.search(r'data-post-id="(\d+)"', suite)
        if not post_id:
            return match.group(0)
        condition = f"{{{{ $activeMenu === '{post_id.group(1)}' ? ' is-active' : '' }}}}"
        return dynamic(f'<li class="menu-item is-submenu-parent{condition}"{match.group(1)}>')

    return MENU_ITEM.sub(menu_item, header)


def active_menu(header: str) -> str | None:
    """Lit dans le header d'une page quelle rubrique y est marquée active."""
    if "is-brand-logo is-active" in header:
        return "accueil"
    found = re.search(r'<li class="menu-item is-submenu-parent is-active"[^>]*>(.{0,900})', header, re.S)
    if not found:
        return None
    post_id = re.search(r'data-post-id="(\d+)"', found.group(1))
    return post_id.group(1) if post_id else None


LAYOUT = """{{-- Généré par tools/convert.py — ne pas modifier à la main. --}}
{{--
    Gabarit commun aux 87 pages issues du thème Woody.

    Les liaisons ci-dessous sont reprises telles quelles du miroir WordPress, y
    compris ses irrégularités : le <header> y referme deux <div> ouverts avant lui,
    si bien que le document compte deux </div> de plus que de <div>. Les navigateurs
    l'admettent, et le rendu en dépend. Ne pas « corriger » sans revalider avec
    tools/compare.py.

    Quatre points d'insertion sont ouverts aux pages :
      mainAttributes — attributs du <main> ; les fiches offre n'ont ni role ni id
      styles       — feuille propre à la page, dans le <head>
      aside        — colonne latérale, après le <main> (fiches offre)
      afterFooter  — scripts après le pied de page (fiches offre)
      scripts      — scripts de fin de document
--}}
<!doctype html>
<html class="no-js" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>@include('partials.head.' . $headProfile)</head>
<body class="@yield('bodyClass')">{GLUE_BODY}{WRAPPER_OPEN}{GLUE_WRAPPER}@include('partials.headnavs')
@include('partials.header'){GLUE_HEADER}{MAIN_OPEN}@yield('content')</main>{GLUE_MAIN_AVANT}@yield('aside'){GLUE_MAIN_APRES}@include('partials.footer'){GLUE_FOOTER}@yield('afterFooter')
@include('partials.overlays')
@yield('scripts')
</body>
</html>
"""


FOOTER_CONTAINER = '<div class="site-footer-container">'


def before_footer_container(glue: str) -> str:
    return glue[: glue.index(FOOTER_CONTAINER)]


def footer_container_opening(glue: str) -> str:
    return glue[glue.index(FOOTER_CONTAINER) :]


def extra_relative_to(base: str, page: str) -> str:
    """Ce que cette page ajoute au gabarit commun, à cet endroit précis.

    Les liaisons du gabarit sont presque identiques d'une page à l'autre : seules
    les fiches offre y glissent une colonne latérale et un script. Plutôt que de
    répéter la liaison entière dans chaque vue, on n'en retient que l'ajout.
    """
    ops = difflib.SequenceMatcher(None, base, page, autojunk=False).get_opcodes()
    return "".join(page[j1:j2] for tag, _, _, j1, j2 in ops if tag in ("insert", "replace"))


def build_layout(regions: Regions) -> str:
    main_open = "<main @yield('mainAttributes')>"
    return (
        LAYOUT.replace("{GLUE_BODY}", protect(regions.glue_body))
        .replace("{WRAPPER_OPEN}", regions.wrapper_open)
        .replace("{GLUE_WRAPPER}", protect(regions.glue_wrapper))
        .replace("{GLUE_HEADER}", protect(regions.glue_header))
        .replace("{MAIN_OPEN}", main_open)
        .replace("{GLUE_MAIN_AVANT}", protect(before_footer_container(regions.glue_main)))
        .replace("{GLUE_MAIN_APRES}", protect(footer_container_opening(regions.glue_main)))
        .replace("{GLUE_FOOTER}", protect(regions.glue_footer))
    )


# --------------------------------------------------------------------------- #
# Description d'une page convertie
# --------------------------------------------------------------------------- #

@dataclass
class Page:
    source: str
    route: str
    view: str
    title: str
    description: str | None
    body_class: str = ""
    active_menu: str | None = None
    head_profile: str = "standard"
    standalone: bool = False


def extract(head: str, pattern: str) -> str | None:
    found = re.search(pattern, head, re.S)
    return found.group(1) if found else None


# --------------------------------------------------------------------------- #
# Écriture
# --------------------------------------------------------------------------- #

class Writer:
    def __init__(self, *, check: bool) -> None:
        self.check = check
        self.written = 0
        self.bytes = 0

    def __call__(self, path: Path, content: str, *, quiet: bool = False) -> None:
        self.written += 1
        self.bytes += len(content.encode())
        if not quiet:
            size = f"{len(content.encode()):,}".replace(",", " ")
            print(f"  {'[check]' if self.check else '[écrit]'} {path.relative_to(ROOT)}  ({size} o)")
        if not self.check:
            path.parent.mkdir(parents=True, exist_ok=True)
            path.write_text(content, encoding="utf-8")


def split_home(main: str) -> list[tuple[str, str]]:
    """Sépare le <main> de l'accueil en sections nommées.

    L'accueil est la seule page dont le contenu justifie un découpage : six blocs
    hétérogènes, dont l'atlas et ses 129 Ko de SVG. Les autres pages tiennent en
    une vue.
    """
    starts = [m.start() for m in re.finditer(r'<div class="page-section page-section-\d+"', main)]
    if not starts:
        return [("contenu", main)]
    bounds = starts + [len(main)]
    sections = [("hero", main[: starts[0]])]
    for index, (a, b) in enumerate(zip(bounds, bounds[1:])):
        name = SECTIONS_ACCUEIL[index] if index < len(SECTIONS_ACCUEIL) else f"section-{index}"
        sections.append((name, main[a:b]))
    return sections


# Gabarit d'une vue de page.
#
# Les attributs du <main> passent par une section en bloc, et non par la forme en
# ligne « @section('nom', 'valeur') » : Laravel échappe la valeur passée en ligne,
# ce qui transformerait les guillemets des attributs en entités.
PAGE_TEMPLATE = """{{{{-- Généré par tools/convert.py — ne pas modifier à la main. --}}}}
@extends('layouts.app')

@section('bodyClass', {body_class})
@section('mainAttributes'){main_attributes}@endsection

{head_blocks}{extras}
@section('content')
{content}
@endsection
"""


def php_string(value: str) -> str:
    return "'" + value.replace("\\", "\\\\").replace("'", "\\'") + "'"


# Compté par page : `load` est appelée deux fois pour chacune — une fois pour
# établir son profil de <head>, une fois pour écrire sa vue.
RETRAITS: dict[str, dict] = {}


def load(relative: str) -> str:
    """Lit une page du miroir, réécrit ses URL et retire les services inaccessibles.

    Le nettoyage est décrit dans `tools/nettoyage.py` et le passage des
    photographies en WebP dans `tools/webp.py` ; `compare.py` applique les deux au
    miroir avant de comparer, si bien que le contrôle reste strict sur tout le reste.
    """
    brut = Rewriter(relative)((SOURCE / relative).read_text(encoding="utf-8", errors="ignore"))
    réparé, titres = repare_les_titres(brut)
    illustré, visuels = remplace_les_visuels(réparé, relative)
    illustré, crédits = credite_les_ajouts(illustré, relative)
    visuels += crédits
    relu, chapôs = corrige_le_contenu(illustré)
    relu, fiches = corrige_les_fiches(relu, relative)
    chapôs += fiches
    propre, retirés = nettoie(relu)
    allégé, webp = passe_en_webp(propre)
    délié, chemins = desencastre(allégé)
    complet, posés = applique(délié)
    RETRAITS[relative] = {**retirés, "titres_réparés": titres, "visuels_remplacés": visuels,
                          "chapôs_corrigés": chapôs, "images_en_webp": webp,
                          "chemins_déliés": chemins, **posés}
    return complet


def section(nom: str, markup: str) -> str:
    """Émet une section Blade, ou rien du tout si le fragment est vide."""
    return f"\n@section('{nom}'){protect(markup)}@endsection" if markup.strip() else ""


# --------------------------------------------------------------------------- #
# Le gabarit partagé
# --------------------------------------------------------------------------- #

def write_shared_template(home: Regions, write: Writer) -> None:
    """Écrit le gabarit et ses partiels, prélevés sur la page d'accueil.

    L'accueil sert de source parce que wget y laisse les liens sans ambiguïté :
    ailleurs, les liens vers la page courante y ont été réécrits en « index.html ».
    """
    print("Gabarit commun :")
    write(VIEWS / "layouts/app.blade.php", build_layout(home))
    write(VIEWS / "partials/headnavs.blade.php", BANNER + protect(home.headnavs))
    write(VIEWS / "partials/header.blade.php", BANNER + protect(parametrise_header(home.header)))
    write(VIEWS / "partials/footer.blade.php", BANNER + protect(home.footer))
    write(VIEWS / "partials/overlays.blade.php", BANNER + protect(split_overlays(home.overlays)[0]))


# --------------------------------------------------------------------------- #
# Les profils de <head>
# --------------------------------------------------------------------------- #

def build_head_profiles(pages: list[str]) -> dict[str, tuple[str, list[str]]]:
    """Regroupe les pages par profil de <head> et construit le <head> de chaque profil.

    Deux garde-fous : deux pages d'un même profil doivent avoir le même <head> une
    fois leurs parts variables neutralisées, et le <head> retenu doit porter l'union
    des points d'insertion de ses pages — aucune n'ayant à elle seule.
    """
    profils: dict[str, str] = {}
    membres: dict[str, list[str]] = {}

    for relative in pages:
        head = Regions.parse(load(relative)).head
        nom = head_profile(head)
        membres.setdefault(nom, []).append(relative)
        rendu = parametrise_head(head, relative)

        if nom not in profils:
            profils[nom] = rendu
            continue
        if without_dynamic(profils[nom]) != without_dynamic(rendu):
            raise SystemExit(
                f"{relative} est classée dans le profil « {nom} » mais son <head> en diffère.\n"
                "Le discriminant de head_profile() ne sépare pas correctement les gabarits."
            )
        for bloc in INSERTION_POINTS:
            if SENTINEL + f"@yield('{bloc}')" + SENTINEL in rendu:
                profils[nom] = merge_head(profils[nom], rendu, bloc)

    return {nom: (profils[nom], membres[nom]) for nom in profils}


def write_head_profiles(profils: dict[str, tuple[str, list[str]]], write: Writer) -> None:
    print("\nProfils de <head> :")
    for nom, (head, pages) in sorted(profils.items()):
        write(VIEWS / f"partials/head/{nom}.blade.php", BANNER + protect(head))
        print(f"           → {len(pages)} pages")


# --------------------------------------------------------------------------- #
# Les pages
# --------------------------------------------------------------------------- #

def describe(relative: str, regions: Regions) -> Page:
    """Lit dans une page les métadonnées que le registre exposera."""
    head = regions.head
    return Page(
        source=relative,
        route=route_of(relative),
        view=view_of(relative),
        title=extract(head, r"<title>(.*?)</title>") or "Bonne Arrivée",
        description=extract(head, r'<meta name="description"(?:[^>]*?)content="([^"]*)"'),
        body_class=regions.body_class,
        active_menu=active_menu(regions.header),
        head_profile=head_profile(head),
    )


def template_sections(regions: Regions, home: Regions) -> str:
    """Ce que cette page ajoute au gabarit commun, aux points d'insertion prévus."""
    return "".join([
        section("aside", extra_relative_to(before_footer_container(home.glue_main),
                                           before_footer_container(regions.glue_main))),
        section("afterFooter", extra_relative_to(home.glue_footer, regions.glue_footer)),
        section("scripts", split_overlays(regions.overlays)[1]),
    ])


def head_sections(head: str) -> str:
    """Les blocs du <head> propres à cette page."""
    return "".join(section(nom, find(head, motif)) for nom, motif in PAGE_HEAD_BLOCKS)


def home_content(main: str, head: str, write: Writer) -> tuple[str, str]:
    """Écrit les sections de l'accueil et retourne leur composition et sa feuille propre.

    L'accueil est la seule page dont le contenu justifie un découpage : six blocs
    hétérogènes, dont l'atlas et ses 129 Ko de SVG. Les autres tiennent en une vue.
    """
    sections = split_home(main)
    for nom, markup in sections:
        write(VIEWS / f"components/accueil/{nom}.blade.php", BANNER + protect(markup), quiet=True)
    print(f"  [{'check' if write.check else 'écrit'}] resources/views/components/accueil/  "
          f"({len(sections)} sections)")
    composition = "\n".join(f"    <x-accueil.{nom} />" for nom, _ in sections)
    return composition, section("styles", find(head, ATLAS_STYLESHEET.pattern))


def emit_page(relative: str, regions: Regions, home: Regions, write: Writer) -> tuple[Page, int, int]:
    page = describe(relative, regions)
    extras = template_sections(regions, home)

    # Les cartes d'abord : chacune emporte sa figure. Les figures restantes ensuite.
    # Le HTML qu'aucun composant ne prend encore en charge est transporté tel quel.
    main, cartes = componentise_cartes(regions.main)
    main, figures = componentise_figures(main)

    if relative == "index.html":
        contenu, feuille = home_content(main, regions.head, write)
        extras += feuille
    else:
        contenu = protect(main)

    write(
        VIEWS / (page.view.replace(".", "/") + ".blade.php"),
        PAGE_TEMPLATE.format(
            body_class=php_string(page.body_class),
            main_attributes=regions.main_open[len("<main ") : -1].strip(),
            head_blocks=head_sections(regions.head),
            extras=extras,
            content=contenu,
        ),
        quiet=True,
    )
    return page, cartes, figures


def emit_standalone(relative: str, write: Writer) -> Page:
    """Convertit une page écrite à la main, qui n'utilise pas le gabarit Woody."""
    html = load(relative)
    page = Page(
        source=relative,
        route=route_of(relative),
        view=view_of(relative),
        title=extract(html, r"<title>(.*?)</title>") or "Bonne Arrivée",
        description=None,
        standalone=True,
    )
    write(VIEWS / (page.view.replace(".", "/") + ".blade.php"),
          STANDALONE_TEMPLATE.format(content=protect(html)))
    return page


def emit_all_pages(woody: list[str], home: Regions, write: Writer) -> list[Page]:
    print(f"\nPages ({len(woody)} sous gabarit Woody, {len(PAGES_AUTONOMES)} autonomes) :")
    pages, cartes, figures = [], 0, 0
    for relative in woody:
        html = load(relative)
        regions = Regions.parse(html)
        page, n_cartes, n_figures = emit_page(relative, regions, home, write)
        pages.append(page)
        cartes += n_cartes
        figures += n_figures
    pages.extend(emit_standalone(relative, write) for relative in PAGES_AUTONOMES)
    print(f"           → {cartes} cartes rendues par x-card")
    print(f"           → {figures} figures rendues par x-media.figure")
    return pages


def find(html: str, pattern: str) -> str:
    """Le premier fragment correspondant au motif, ou une chaîne vide."""
    found = re.search(pattern, html, re.S)
    return found.group(0) if found else ""


# --------------------------------------------------------------------------- #
# Le registre
# --------------------------------------------------------------------------- #

STANDALONE_TEMPLATE = """{{{{-- Généré par tools/convert.py — ne pas modifier à la main. --}}}}
{{{{--
    Page autonome : elle n'utilise pas le gabarit Woody. Écrite à la main lors de
    la rédaction du site, elle porte son propre <head> et ses propres styles.
--}}}}
{content}
"""

REGISTRY_TEMPLATE = """<?php

declare(strict_types=1);

/*
 * Registre des pages du site — généré par tools/convert.py.
 *
 * Chaque entrée décrit une page : la vue qui la rend, ses métadonnées et la
 * rubrique de menu à marquer active. Ce fichier tiendra lieu de base de données
 * jusqu'à ce que le back-office prenne la main sur le contenu ; la signature
 * qu'il expose — un tableau indexé par chemin d'URL — ne changera pas.
 */

return [
{entries}
];
"""


def build_registry(pages: list[Page]) -> str:
    entrées = []
    for page in sorted(pages, key=lambda p: p.route):
        champs = [f"'view' => {php_string(page.view)}", f"'title' => {php_string(page.title)}"]
        if page.description is not None:
            champs.append(f"'description' => {php_string(page.description)}")
        if page.active_menu is not None:
            champs.append(f"'activeMenu' => {php_string(page.active_menu)}")
        champs.append("'standalone' => true" if page.standalone
                      else f"'headProfile' => {php_string(page.head_profile)}")
        corps = ",\n        ".join(champs)
        entrées.append(f"    {php_string(page.route)} => [\n        {corps},\n    ],")
    return REGISTRY_TEMPLATE.format(entries="\n".join(entrées))


# --------------------------------------------------------------------------- #
# Orchestration
# --------------------------------------------------------------------------- #

def convert() -> None:
    parser = argparse.ArgumentParser(description=__doc__, formatter_class=argparse.RawDescriptionHelpFormatter)
    parser.add_argument("--check", action="store_true", help="affiche le plan sans rien écrire")
    write = Writer(check=parser.parse_args().check)

    sources = sorted(str(p.relative_to(SOURCE)) for p in SOURCE.rglob("*.html") if "assets" not in p.parts)
    woody = [s for s in sources if s not in PAGES_AUTONOMES]

    home_html = load("index.html")
    home = Regions.parse(home_html)
    home.check(home_html, "index.html")
    print("Découpage vérifié : la concaténation des régions rend le <body> au caractère près.\n")

    write_shared_template(home, write)
    write_head_profiles(build_head_profiles(woody), write)
    pages = emit_all_pages(woody, home, write)

    print("\nRegistre :")
    write(ROOT / "resources/pages.php", build_registry(pages))

    somme = lambda clé: sum(r[clé] for r in RETRAITS.values())
    listes = f"{somme('octets_de_listes'):,}".replace(",", " ")
    pages_vidées = sum(1 for r in RETRAITS.values() if r["octets_de_listes"])
    print("\nServices inaccessibles retirés :")
    print(f"  {listes} o de résultats alimentés par une API morte, sur {pages_vidées} pages")
    print(f"  {somme('appels_morts')} appels à un service injoignable ou à une clé d'emprunt")
    print(f"  {somme('identifiants')} blocs de configuration porteurs d'identifiants")
    print(f"\nTitres de carte réparés : {somme('titres_réparés')} occurrences")
    print(f"Script du projet ajouté sur {somme('scripts_ajoutés')} pages")
    print(f"URL d'assets versionnées : {somme('assets_versionnés')}")
    pages_illustrées = sum(1 for r in RETRAITS.values() if r["visuels_remplacés"])
    print(f"Visuels hors sujet remplacés : {somme('visuels_remplacés')} références sur {pages_illustrées} pages")
    print(f"Chapôs fautifs corrigés : {somme('chapôs_corrigés')} pages")

    total = f"{write.bytes:,}".replace(",", " ")
    print(f"\n{write.written} fichiers, {total} octets.")


if __name__ == "__main__":
    convert()
