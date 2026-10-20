#!/usr/bin/env python3
"""
Passage des photographies en WebP.

Le site tient sur 51 photographies, que le thème d'origine découpe en autant de
formats qu'il a de gabarits — 722 fichiers au total, 183 Mo. C'est ce qui pèse sur
le chargement : la page d'accueil demande 2,9 Mo d'images pour 3,4 Mo au total.

Le WebP rend environ 30 % de ce poids — pas la moitié, car les JPEG du miroir sont
déjà compressés — et tous les navigateurs en service le lisent depuis 2020. La conversion se fait en deux temps :

  · sur le disque, `convertis()` produit un `.webp` à côté de chaque `.jpg` ;
  · dans les pages, `passe_en_webp()` réécrit les adresses.

**Cette réécriture s'applique aux deux côtés de la comparaison**, comme les autres
transformations déclarées : `convert.py` la passe sur le miroir avant d'écrire les
vues, `compare.py` la passe sur le miroir avant de comparer. Le contrôle des 89
pages reste donc strict sur tout le reste.

Les originaux restent dans `public/assets/img/_src`, hors du site servi : ce sont
eux qui permettront de tout refaire si la qualité choisie déçoit.

    python3 tools/webp.py                  # les cas de test
    python3 tools/webp.py --convertir      # produit les .webp et retire les .jpg
"""

from __future__ import annotations

import argparse
import re
import subprocess
import sys
from pathlib import Path

RACINE = Path(__file__).resolve().parent.parent
IMAGES = RACINE / "public" / "assets" / "img"

# Les JPEG du miroir sont déjà encodés autour de q65 : leur demander plus de
# qualité qu'ils n'en portent ne fait que conserver leurs défauts. Mesuré sur un
# échantillon de 25 découpes, q75 rend 30 % du poids pour un SSIM de 0,95 contre la
# source — au-dessous, les aplats de ciel se marbrent visiblement.
QUALITÉ = 75

# Les adresses servies par le site, toutes à la racine de `/assets/img/`. Les
# sous-dossiers `_src` et `_atlas` ne sont jamais appelés par les vues et restent
# hors du champ.
IMAGE_SERVIE = re.compile(r"(/assets/img/[\w.-]+)\.(?:jpe?g|png)\b")


def passe_en_webp(html: str) -> tuple[str, int]:
    """Réécrit en `.webp` les adresses des photographies servies."""
    return IMAGE_SERVIE.subn(r"\1.webp", html)


def convertis(qualité: int = QUALITÉ) -> dict:
    """Produit un `.webp` pour chaque photographie servie, et retire l'original.

    Les fichiers d'origine ne sont pas perdus : le miroir en garde 856 et
    `public/assets/img/_src` garde les 52 clichés dont tout le reste est découpé.
    """
    sources = sorted(p for p in IMAGES.glob("*.*") if p.suffix.lower() in (".jpg", ".jpeg", ".png"))
    avant = après = ratés = 0
    for source in sources:
        cible = source.with_suffix(".webp")
        rendu = subprocess.run(
            ["cwebp", "-q", str(qualité), "-m", "6", "-mt", str(source), "-o", str(cible)],
            capture_output=True,
        )
        if rendu.returncode != 0 or not cible.exists():
            ratés += 1
            continue
        avant += source.stat().st_size
        après += cible.stat().st_size
        source.unlink()
    return {"fichiers": len(sources) - ratés, "ratés": ratés, "avant": avant, "après": après}


CAS = [
    (
        'src="/assets/img/paysage-1920x1440.jpg"',
        'src="/assets/img/paysage-1920x1440.webp"',
        "une adresse simple",
    ),
    (
        'srcset="/assets/img/sunset-360x180.jpg 360w, /assets/img/sunset-640x320.jpg 640w"',
        'srcset="/assets/img/sunset-360x180.webp 360w, /assets/img/sunset-640x320.webp 640w"',
        "toutes les découpes d'un srcset",
    ),
    (
        '"image":["https://benin.test/assets/img/ganvie-1200x600.jpg"]',
        '"image":["https://benin.test/assets/img/ganvie-1200x600.webp"]',
        "les adresses du balisage schema.org",
    ),
    (
        '<link rel="icon" type="image/png" href="/assets/favicon.png">',
        '<link rel="icon" type="image/png" href="/assets/favicon.png">',
        "les favicons, hors du dossier des photographies, sont laissés",
    ),
    (
        'src="/assets/img/_src/parc-w.jpg"',
        'src="/assets/img/_src/parc-w.jpg"',
        "les originaux de `_src`, jamais servis, sont laissés",
    ),
    (
        'src="/assets/woody.cloudly.space/logo.png"',
        'src="/assets/woody.cloudly.space/logo.png"',
        "les images du thème sont laissées",
    ),
]


def teste() -> int:
    échecs = 0
    for entrée, attendu, intitulé in CAS:
        obtenu, _ = passe_en_webp(entrée)
        if obtenu != attendu:
            échecs += 1
            print(f"  ÉCHEC — {intitulé}\n    attendu : {attendu}\n    obtenu  : {obtenu}")
    if échecs:
        print(f"{échecs} cas sur {len(CAS)} échouent.")
    else:
        print(f"Les {len(CAS)} cas de test passent.")
    return 1 if échecs else 0


def main() -> int:
    parser = argparse.ArgumentParser(description=__doc__)
    parser.add_argument("--convertir", action="store_true", help="produit les .webp sur le disque")
    parser.add_argument("--qualite", type=int, default=QUALITÉ, help=f"qualité WebP (défaut {QUALITÉ})")
    args = parser.parse_args()

    if not args.convertir:
        return teste()

    if not subprocess.run(["which", "cwebp"], capture_output=True).stdout:
        print("cwebp est introuvable :  brew install webp", file=sys.stderr)
        return 1

    bilan = convertis(args.qualite)
    mo = lambda n: f"{n / 1024 / 1024:.0f} Mo"  # noqa: E731
    gain = 100 - bilan["après"] * 100 // max(1, bilan["avant"])
    print(f"  {bilan['fichiers']} photographies converties en qualité {args.qualite}")
    print(f"  {mo(bilan['avant'])} → {mo(bilan['après'])}   −{gain} %")
    if bilan["ratés"]:
        print(f"  {bilan['ratés']} échecs de conversion : originaux conservés")
    return 0


if __name__ == "__main__":
    sys.exit(main())
