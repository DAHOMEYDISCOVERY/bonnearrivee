#!/usr/bin/env python3
"""
Carte interactive de l'architecture : `docs/atlas.html`.

Le relevé de `architecture.py` est exact mais illisible en colonnes. Ce module en
fait une page consultable : l'arbre à gauche, la fiche de la page à droite, et les
liens entrants et sortants cliquables pour circuler dans le graphe comme on
circulerait dans le site.

La page est régénérable : à chaque fois qu'une page est enrichie, relancer les deux
outils met la carte à jour.

    python3 tools/architecture.py --json && python3 tools/atlas.py
"""

from __future__ import annotations

import json
import sys
from pathlib import Path

RACINE = Path(__file__).resolve().parent.parent
OUTILS = Path(__file__).resolve().parent

# Le projet se partage en archive : celui qui la reçoit lance le serveur local, et
# les liens de la carte fonctionnent chez lui comme chez nous. Une seule page suffit.
GABARITS = ("atlas.html",)


def main() -> int:
    relevé = RACINE / "architecture.json"
    if not relevé.exists():
        print("Lancer d'abord : python3 tools/architecture.py --json", file=sys.stderr)
        return 1

    charge = json.dumps(
        json.loads(relevé.read_text(encoding="utf-8")), ensure_ascii=False, separators=(",", ":")
    )
    for nom in GABARITS:
        page = (OUTILS / nom).read_text(encoding="utf-8").replace("/*DONNÉES*/null", charge)
        destination = RACINE / "docs" / nom
        destination.write_text(page, encoding="utf-8")
        print(f"Écrit : {destination}  ({len(page):,} octets)".replace(",", " "))
    return 0


if __name__ == "__main__":
    sys.exit(main())
