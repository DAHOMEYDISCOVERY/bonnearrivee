#!/usr/bin/env python3
"""
Fiche de relecture : les 89 pages, avec de quoi juger si leur contenu est complet.

Relire un site page par page demande de savoir par où commencer. Ce module dresse
la liste des pages servies, mesure ce qui s'y trouve, et signale ce qui manque.

Une page est signalée quand :

  · elle compte moins de 150 mots — trop peu pour renseigner un visiteur ;
  · elle n'a pas de description propre, ou reprend celle du site ;
  · elle n'a qu'une photographie, ou aucune ;
  · elle porte encore une mention de la France héritée du site d'origine ;
  · elle emploie un mot qui n'appartient qu'à Mayotte — un toponyme, un usage —
    laissé par la conversion ;
  · elle porte des coordonnées géographiques hors du Bénin ;
  · elle affiche des points à vérifier, signalés lors de la rédaction.

    python3 tools/relecture.py              # à l'écran
    python3 tools/relecture.py --fichier    # écrit RELECTURE.md
"""

from __future__ import annotations

import argparse
import re
import sys
from pathlib import Path

sys.path.insert(0, str(Path(__file__).resolve().parent))
from convert import PAGES_AUTONOMES, ROOT, Regions, SOURCE, load, route_of  # noqa: E402

MOTS_MINIMUM = 150

# La France a sa place dans ce contenu : elle est un pays émetteur d'avis aux
# voyageurs, une ancienne puissance coloniale, et une langue. Ce qu'il faut traquer,
# ce sont les phrases héritées du site de Mayotte, qui rattachent le Bénin à la
# France. Ces contextes-là sont donc tenus pour légitimes.
FRANCE_LÉGITIME = re.compile(
    r"forts? fran[çc]ais|comptoir|colonis|conqu[êe]te|protectorat|Dahomey fran"
    r"|langues? parl|francophone|minist[èe]re|avis aux voyageurs|d[ée]conseille"
    r"|recommande|ambassade|consulat|vol|compagnie|1892|br[ûu]l|trait[ée]"
    r"|visa|ressortissant|billet|euro",
    re.I,
)


# Mots qui n'appartiennent qu'à Mayotte : toponymes, usages, vocabulaire. Leur
# présence trahit un passage que la conversion n'a pas réécrit.
MAHORAIS = re.compile(
    r"\b(Dembéni|Mamoudzou|Dzaoudzi|Tsingoni|Koungou|Bandrélé|Bouéni|Sada"
    r"|M'Tsangamouji|Ouangani|Chirongui|Petite-Terre|Grande-Terre|Andriantsouli"
    r"|salouva|chatouilleuse|djarifa|msindzano|shimaore|mahorais\w*|Mayotte"
    r"|Trevani|Chissioua)\b",
    re.I,
)

# Le Bénin se tient entre 6,0 et 12,6 de latitude nord, 0,6 et 4,0 de longitude est.
COORDONNÉES = re.compile(r'"latitude"\s*:\s*"?(-?[\d.]+)"?\s*,\s*"longitude"\s*:\s*"?(-?[\d.]+)"?')


def hors_du_benin(html: str) -> list[str]:
    """Les coordonnées qui ne tombent pas dans le pays."""
    dehors = []
    for trouvé in COORDONNÉES.finditer(html):
        latitude, longitude = float(trouvé.group(1)), float(trouvé.group(2))
        if not (6.0 <= latitude <= 12.6 and 0.6 <= longitude <= 4.0):
            dehors.append(f"{latitude}, {longitude}")
    return dehors


def texte_visible(html: str) -> str:
    sans_script = re.sub(r"<(script|style)\b.*?</\1>", " ", html, flags=re.S)
    return re.sub(r"\s+", " ", re.sub(r"<[^>]+>", " ", sans_script)).strip()


def examine(relative: str) -> dict:
    """Mesure une page et relève ce qui lui manque."""
    html = load(relative)
    autonome = relative in PAGES_AUTONOMES
    main = html if autonome else Regions.parse(html).main
    mots = texte_visible(main).split()

    photos = {
        trouvé.group(1)
        for trouvé in re.finditer(r"/assets/img/([a-z0-9-]+?)(?:-\d+x\d+)?\.(?:jpg|png|webp)", main)
    }
    titre = re.search(r"<title>(.*?)</title>", html, re.S)
    description = re.search(r'<meta name="description"[^>]*content="([^"]*)"', html)

    france = [
        m.group(0)
        for m in re.finditer(r"[^.]{0,60}\b(France|fran[çc]ais\w*)\b[^.]{0,60}", texte_visible(main))
        if not FRANCE_LÉGITIME.search(m.group(0))
    ]

    mahorais = sorted({m.group(0) for m in MAHORAIS.finditer(texte_visible(main) + " " + html)})
    coordonnées = hors_du_benin(html)

    signaux = []
    if len(mots) < MOTS_MINIMUM:
        signaux.append(f"{len(mots)} mots")
    if not description:
        signaux.append("sans description")
    if len(photos) <= 1:
        signaux.append(f"{len(photos)} photo")
    if france:
        signaux.append(f"{len(france)} mention(s) de la France")
    if mahorais:
        signaux.append("vocabulaire de Mayotte : " + ", ".join(mahorais[:3]))
    if coordonnées:
        signaux.append(f"{len(coordonnées)} coordonnées hors du Bénin")
    if "à vérifier" in texte_visible(main).lower():
        signaux.append("points à vérifier")

    return {
        "route": route_of(relative),
        "titre": (titre.group(1).replace(" | Bénin tourisme", "") if titre else "?"),
        "mots": len(mots),
        "photos": len(photos),
        "signaux": signaux,
        "france": france[:2],
        "mahorais": mahorais,
        "coordonnées": coordonnées,
    }


def toutes_les_pages() -> list[dict]:
    pages = sorted(str(p.relative_to(SOURCE)) for p in SOURCE.rglob("*.html") if "assets" not in p.parts)
    return [examine(relative) for relative in pages]


def rapport(pages: list[dict], hôte: str) -> str:
    à_revoir = [p for p in pages if p["signaux"]]
    lignes = [
        "# Fiche de relecture — Bénin tourisme",
        "",
        f"{len(pages)} pages. **{len(à_revoir)} demandent un regard.**",
        "",
        "Une page est signalée quand elle compte moins de 150 mots, n'a pas de",
        "description propre, n'a qu'une photographie ou aucune, porte une mention de la",
        "France héritée du site d'origine, emploie un mot qui n'appartient qu'à Mayotte,",
        "affiche des coordonnées hors du Bénin, ou signale des points à vérifier.",
        "",
        "## Pages à revoir",
        "",
        "| Page | Mots | Photos | Ce qui manque |",
        "|---|---:|---:|---|",
    ]
    for page in sorted(à_revoir, key=lambda p: p["mots"]):
        lignes.append(
            f"| [{page['titre'][:44]}]({hôte}{page['route']}) | {page['mots']} | "
            f"{page['photos']} | {', '.join(page['signaux'])} |"
        )

    lignes += ["", "## Toutes les pages", "", "| Page | Mots | Photos |", "|---|---:|---:|"]
    for page in sorted(pages, key=lambda p: p["route"]):
        lignes.append(f"| [{page['titre'][:44]}]({hôte}{page['route']}) | {page['mots']} | {page['photos']} |")

    return "\n".join(lignes) + "\n"


def main() -> int:
    parser = argparse.ArgumentParser(description=__doc__, formatter_class=argparse.RawDescriptionHelpFormatter)
    parser.add_argument("--hote", default="http://127.0.0.1:8010", help="racine des liens")
    parser.add_argument("--fichier", action="store_true", help="écrit RELECTURE.md")
    args = parser.parse_args()

    pages = toutes_les_pages()
    à_revoir = [p for p in pages if p["signaux"]]

    print(f"{len(pages)} pages · {len(à_revoir)} demandent un regard\n")
    print(f"{'page':<46} {'mots':>6} {'photos':>7}   ce qui manque")
    for page in sorted(à_revoir, key=lambda p: p["mots"]):
        print(f"{page['route'][:45]:<46} {page['mots']:>6} {page['photos']:>7}   {', '.join(page['signaux'])}")

    if args.fichier:
        destination = ROOT / "RELECTURE.md"
        destination.write_text(rapport(pages, args.hote), encoding="utf-8")
        print(f"\nÉcrit : {destination}")
    return 0


if __name__ == "__main__":
    sys.exit(main())
