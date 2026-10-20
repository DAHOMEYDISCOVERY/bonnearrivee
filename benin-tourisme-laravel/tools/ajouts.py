#!/usr/bin/env python3
"""
Ajouts du projet au gabarit d'origine.

Le miroir ne charge que les ressources du thème. Le projet en ajoute une :
`benin-carrousels.js`, qui rend le carrousel d'accroche continu et fait défiler
les carrousels tout seuls — deux réglages que le thème ne permet pas d'exprimer
autrement, son `loop: false` étant écrit en dur.

Ce module pose aussi une **empreinte de version** sur les fichiers qui appartiennent
au projet. Leurs URL n'en portaient aucune : un navigateur qui avait déjà chargé
`benin-overrides.css` continuait de servir son ancienne version, et les corrections
restaient invisibles. L'empreinte est calculée sur le contenu du fichier — elle
change quand le fichier change, et seulement à ce moment-là.

Comme le retrait des services inaccessibles et la réparation des titres, ces
transformations sont **déclarées et appliquées aux deux côtés de la comparaison** :
`convert.py` les passe sur le miroir avant d'écrire les vues, `compare.py` avant de
comparer. Le contrôle des 89 pages reste donc strict sur tout le reste.

    python3 tools/ajouts.py     # les cas de test
"""

from __future__ import annotations

import hashlib
import re
import sys
from pathlib import Path

SCRIPT = '<script src="/assets/benin-carrousels.js"></script>'

# Fichiers du projet, par opposition à ceux du thème : eux seuls sont versionnés.
ASSETS_DU_PROJET = (
    "benin-overrides.css",
    "benin-carrousels.js",
    "benin-atlas.css",
    "benin-atlas.js",
)

PUBLIC = Path(__file__).resolve().parent.parent / "public"

# Le script se place juste avant la fermeture du <body>, après ceux du thème :
# il a besoin que les carrousels soient initialisés pour les reprendre.
ANCRE = "</body>"


def ajoute_les_scripts(html: str) -> tuple[str, int]:
    """Insère les scripts du projet avant la fermeture du <body>."""
    if SCRIPT in html or ANCRE not in html:
        return html, 0

    coupe = html.rindex(ANCRE)
    return html[:coupe] + f"    {SCRIPT}\n" + html[coupe:], 1


def empreinte(nom: str) -> str:
    """Huit caractères tirés du contenu du fichier."""
    fichier = PUBLIC / "assets" / nom
    if not fichier.exists():
        return ""
    return hashlib.sha1(fichier.read_bytes()).hexdigest()[:8]


def versionne_les_assets(html: str) -> tuple[str, int]:
    """Ajoute une empreinte de version aux URL des fichiers du projet.

    Sans elle, un navigateur qui a déjà chargé la feuille de style continue de
    servir son ancienne version : les corrections restent invisibles.
    """
    posées = 0
    for nom in ASSETS_DU_PROJET:
        version = empreinte(nom)
        if not version:
            continue
        motif = re.compile(rf'(/assets/{re.escape(nom)})(?![?\w])')
        html, n = motif.subn(rf'\1?v={version}', html)
        posées += n
    return html, posées


def applique(html: str) -> tuple[str, dict]:
    """Pose les ajouts du projet : les scripts d'abord, puis leurs versions."""
    html, scripts = ajoute_les_scripts(html)
    html, versions = versionne_les_assets(html)
    return html, {"scripts_ajoutés": scripts, "assets_versionnés": versions}


CAS_DE_TEST = [
    (
        "<html><body><p>x</p></body></html>",
        lambda r, c: c["scripts_ajoutés"] == 1 and "benin-carrousels.js?v=" in r,
        "script ajouté et versionné",
    ),
    (
        "<html><body><p>x</p>" + SCRIPT + "</body></html>",
        lambda r, c: c["scripts_ajoutés"] == 0 and r.count("benin-carrousels.js") == 1,
        "script déjà présent, pas de doublon",
    ),
    (
        "<div>fragment sans body</div>",
        lambda r, c: c["scripts_ajoutés"] == 0,
        "fragment sans <body>",
    ),
    (
        "<link href='/assets/benin-overrides.css'>",
        lambda r, c: c["assets_versionnés"] == 1 and "?v=" in r,
        "feuille du projet versionnée",
    ),
    (
        "<link href='/assets/benin-overrides.css?v=deadbeef'>",
        lambda r, c: c["assets_versionnés"] == 0,
        "version déjà posée, laissée telle quelle",
    ),
    (
        '<link href="/assets/woody.cloudly.space/app/dist/benin/css/main_chunk_1.css">',
        lambda r, c: c["assets_versionnés"] == 0,
        "fichier du thème, pas versionné",
    ),
]


def main() -> int:
    échecs = 0
    for source, vérifie, libellé in CAS_DE_TEST:
        résultat, compte = applique(source)
        conforme = vérifie(résultat, compte)
        print(f"  {'✓' if conforme else '✗'} {libellé}")
        échecs += 0 if conforme else 1

    print()
    if échecs:
        print(f"{échecs} cas de test en échec.")
        return 1
    print(f"Les {len(CAS_DE_TEST)} cas de test passent.")
    return 0


if __name__ == "__main__":
    sys.exit(main())
