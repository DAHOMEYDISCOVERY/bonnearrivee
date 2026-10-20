#!/usr/bin/env python3
"""
Compare le rendu Laravel au miroir statique dont il est issu.

C'est le garde-fou de la migration : tant qu'il passe, la conversion n'a rien
changé au HTML servi au navigateur.

    python3 tools/compare.py                          # les 89 pages
    python3 tools/compare.py --page explorez/index.html --diff
    python3 tools/compare.py --laravel http://…       # autre hôte

La comparaison se fait à deux profondeurs :

1. **Structure** — nombre de balises par type. Un écart signale un élément perdu
   ou dupliqué : le défaut le plus grave et le plus facile à manquer à l'œil.
2. **Contenu** — HTML normalisé, blanc inter-balises neutralisé, balises
   volontairement dynamiques exclues. Chaque écart restant est classé ; les
   classes attendues sont décrites dans ÉCARTS_ATTENDUS et proviennent toutes
   de défauts du miroir que la conversion répare.

Un écart inclassable fait échouer la comparaison : c'est ce qu'on cherche.
"""

from __future__ import annotations

import argparse
import difflib
import re
import sys
import urllib.error
import urllib.parse
import urllib.request
from collections import Counter
from pathlib import Path

sys.path.insert(0, str(Path(__file__).resolve().parent))
from convert import PAGES_AUTONOMES, Regions, Rewriter, SOURCE, route_of  # noqa: E402
from equivalence import balises  # noqa: E402
from nettoyage import nettoie  # noqa: E402
from contenu import corrige_le_contenu, corrige_les_fiches  # noqa: E402
from photos import credite_les_ajouts, remplace_les_visuels  # noqa: E402
from ajouts import applique  # noqa: E402
from reparation import repare_les_titres  # noqa: E402
from desencastre import desencastre  # noqa: E402
from webp import passe_en_webp  # noqa: E402

# Balises que la conversion rend dynamiques à dessein : neutralisées avant
# comparaison, sans quoi elles masqueraient les écarts réels.
DYNAMIQUES = [
    r"<title>.*?</title>",
    r'<meta name="description"[^>]*>',
    r'<meta (?:name|property)="twitter:(?:title|description)"[^>]*>',
    r'<meta (?:name|property)="og:(?:title|description|url)"[^>]*>',
    r'<link rel="canonical"[^>]*>',
]

# Écarts connus, tous dus au miroir. Les motifs ne présument pas de la position des
# attributs : le contrat d'équivalence les réordonne. Ils ne sont tolérés que hors du <main> : le
# contenu propre à chaque page, lui, doit être identique. Chaque motif reconnaît
# le fragment tel qu'il apparaît côté miroir ; le rendu Laravel porte à la place
# la valeur prélevée sur l'accueil.
ÉCARTS_ATTENDUS = [
    (r'class="brand-logo isRel"',
     "logo de pied de page : le miroir le laisse pointer sur la page courante"),
    (r'class="card-link flex-container flex-dir-column"',
     "carte modèle du moteur de recherche : même défaut de lien"),
    (r'alt="[^"]*"',
     "texte alternatif du gabarit, repris de l'accueil"),
    (r'imageObject-(?:description|caption|author)',
     "légende des cartes d'attente du moteur de recherche, reprise de l'accueil"),
    (r'<img[^>]*no-image[^>]*>',
     "visuel d'attente du moteur de recherche, repris de l'accueil"),
    (r'src="/assets/img/[^"]*"',
     "vignette du menu reprise de l'accueil (le miroir y met l'image de la page)"),
    (r'class="button primary"',
     "lien du pied de page rétabli (le miroir en perd sur certaines pages)"),
    (r'class="card-link isRel bg-black"|card-t(?:itles|exts|itle)',
     "carte du menu déroulant reprise de l'accueil"),
]

BALISES = (
    "div", "section", "article", "header", "footer", "main", "nav", "aside",
    "a", "img", "figure", "figcaption", "picture", "source", "svg", "path",
    "script", "link", "style", "ul", "li", "button", "form", "input",
    "h1", "h2", "h3", "h4", "span", "p", "table",
)

VERT, ROUGE, JAUNE, GRIS, RESET = "\033[32m", "\033[31m", "\033[33m", "\033[90m", "\033[0m"


def fetch(url: str) -> str:
    try:
        with urllib.request.urlopen(url, timeout=60) as response:
            return response.read().decode("utf-8", errors="ignore")
    except urllib.error.HTTPError as error:
        raise Divergence(f"{url} a répondu {error.code}") from error
    except urllib.error.URLError as error:
        raise SystemExit(
            f"Impossible d'atteindre {url} ({error}).\n"
            "Le serveur est-il lancé ?  php artisan serve --port=8010"
        ) from error


class Divergence(Exception):
    """Un écart qu'on ne sait pas expliquer."""


def neutralise(document: str) -> list[str]:
    """Réduit un document à sa suite de balises comparables.

    Le contrat d'équivalence est défini dans `tools/equivalence.py`, avec ses cas de
    test. Comparer des listes de balises plutôt que des chaînes de 300 Ko change
    aussi l'ordre de grandeur du travail de difflib.
    """
    for motif in DYNAMIQUES:
        document = re.sub(motif, "", document, flags=re.S | re.I)
    return balises(document)


def classe(fragment: str) -> str | None:
    """Rattache un écart à l'une des classes connues, ou None s'il est inédit."""
    for motif, libellé in ÉCARTS_ATTENDUS:
        if re.search(motif, fragment):
            return libellé
    return None


def vérifie_le_contenu(attendu: str, obtenu: str, *, montrer_diff: bool) -> None:
    """Le <main> — le contenu propre à la page — doit être identique, sans tolérance.

    Structure d'abord : un élément perdu ou dupliqué est le défaut le plus grave et
    le plus facile à manquer à l'œil. Contenu ensuite, balise par balise.
    """
    écarts = [
        (balise, len(re.findall(rf"<{balise}\b", attendu, re.I)), len(re.findall(rf"<{balise}\b", obtenu, re.I)))
        for balise in BALISES
    ]
    écarts = [(b, a, o) for b, a, o in écarts if a != o]
    if écarts:
        détail = ", ".join(f"<{b}> {a}→{o}" for b, a, o in écarts[:4])
        raise Divergence(f"structure du <main> : {détail}")

    gauche, droite = neutralise(attendu), neutralise(obtenu)
    if gauche == droite:
        return

    commun = min(len(gauche), len(droite))
    rang = next((i for i in range(commun) if gauche[i] != droite[i]), commun)
    if montrer_diff:
        print(f"      <main> miroir  : …{'><'.join(gauche[rang:rang + 2])[:170]}")
        print(f"      <main> Laravel : …{'><'.join(droite[rang:rang + 2])[:170]}")
    raise Divergence(f"le contenu du <main> diffère (balise {rang})")


def classe_les_écarts(attendu: str, obtenu: str, *, montrer_diff: bool) -> Counter:
    """Rattache chaque écart du gabarit à une classe connue.

    Un écart inclassable fait échouer la comparaison : c'est ce qu'on cherche.
    """
    gauche, droite = neutralise(attendu), neutralise(obtenu)
    écarts: Counter = Counter()
    inédits = []

    for tag, i1, i2, j1, j2 in difflib.SequenceMatcher(None, gauche, droite, autojunk=False).get_opcodes():
        if tag == "equal":
            continue
        côté_miroir = "><".join(gauche[i1:i2])
        côté_laravel = "><".join(droite[j1:j2])
        # Un ajout n'a pas de contrepartie côté miroir : on le classe sur ce que
        # Laravel produit, sans quoi le fragment vide échapperait à tout motif.
        libellé = classe(côté_miroir) or classe(côté_laravel)
        if libellé:
            écarts[libellé] += 1
        else:
            inédits.append((côté_miroir, côté_laravel))

    if inédits:
        if montrer_diff:
            for miroir, laravel in inédits[:5]:
                print(f"      miroir  : …{miroir[:170]}")
                print(f"      Laravel : …{laravel[:170]}")
        raise Divergence(f"{len(inédits)} écart(s) inexpliqué(s)")

    return écarts


def compare_page(relative: str, base: str, *, montrer_diff: bool) -> Counter:
    """Compare une page à son équivalent du miroir. Lève Divergence si elle en diffère."""
    # Le miroir subit les mêmes transformations volontaires que la conversion —
    # réparation des titres, retrait des services inaccessibles — sans quoi on
    # comparerait le rendu à un état qu'on a délibérément quitté.
    source = Rewriter(relative)((SOURCE / relative).read_text(encoding="utf-8", errors="ignore"))
    réparé, _ = repare_les_titres(source)
    illustré, _ = remplace_les_visuels(réparé, relative)
    illustré, _ = credite_les_ajouts(illustré, relative)
    relu, _ = corrige_le_contenu(illustré)
    relu, _ = corrige_les_fiches(relu, relative)
    nettoyé, _ = nettoie(relu)
    nettoyé, _ = passe_en_webp(nettoyé)
    nettoyé, _ = desencastre(nettoyé)
    attendu, _ = applique(nettoyé)
    obtenu = fetch(urllib.parse.urljoin(base, route_of(relative).lstrip("/")))

    if relative not in PAGES_AUTONOMES:
        vérifie_le_contenu(Regions.parse(attendu).main, Regions.parse(obtenu).main,
                           montrer_diff=montrer_diff)

    return classe_les_écarts(attendu, obtenu, montrer_diff=montrer_diff)


def main() -> int:
    parser = argparse.ArgumentParser(description=__doc__, formatter_class=argparse.RawDescriptionHelpFormatter)
    parser.add_argument("--laravel", default="http://127.0.0.1:8010/", help="racine du site servi par Laravel")
    parser.add_argument("--page", help="ne comparer qu'une page du miroir")
    parser.add_argument("--diff", action="store_true", help="affiche le détail des écarts inexpliqués")
    args = parser.parse_args()

    if args.page:
        pages = [args.page]
    else:
        pages = sorted(str(p.relative_to(SOURCE)) for p in SOURCE.rglob("*.html") if "assets" not in p.parts)

    print(f"Miroir  : {SOURCE}")
    print(f"Laravel : {args.laravel}")
    print(f"Pages   : {len(pages)}\n")

    échecs, total_écarts = [], Counter()
    for relative in pages:
        étiquette = relative if relative in PAGES_AUTONOMES else route_of(relative)
        try:
            écarts = compare_page(relative, args.laravel, montrer_diff=args.diff)
        except Divergence as erreur:
            échecs.append((étiquette, str(erreur)))
            print(f"  {ROUGE}✗{RESET} {étiquette}  —  {erreur}")
            continue
        total_écarts.update(écarts)
        suffixe = f"  {GRIS}({sum(écarts.values())} écart(s) connu(s)){RESET}" if écarts else ""
        print(f"  {VERT}✓{RESET} {étiquette}{suffixe}")

    print()
    if total_écarts:
        print("Écarts connus, tous imputables au miroir :")
        for libellé, nombre in total_écarts.most_common():
            print(f"  {JAUNE}·{RESET} {nombre:>4}  {libellé}")
        print()

    if échecs:
        print(f"{ROUGE}{len(échecs)} page(s) divergent du miroir.{RESET}")
        return 1
    print(f"{VERT}Les {len(pages)} pages sont équivalentes au miroir.{RESET}")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
