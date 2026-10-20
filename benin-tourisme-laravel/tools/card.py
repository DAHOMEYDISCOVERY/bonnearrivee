#!/usr/bin/env python3
"""
Reconnaissance des cartes du miroir et extraction de leurs propriétés.

La carte est le composant le plus répandu du site : 613 dans les pages. Toutes
partagent la même ossature — un conteneur, un lien, un média, un bloc de textes,
parfois un bouton — et ne diffèrent que par leurs classes et par les éléments de
texte qu'elles portent.

Ce module lit ces valeurs. `tools/roundtrip.php` vérifie que le composant `x-card`
les rend à l'équivalent, et `tools/convert.py` ne remplace que les cartes dont la
vérification passe.

    python3 tools/card.py           # mesure la couverture sans rien écrire
"""

from __future__ import annotations

import html
import json
import re
import subprocess
import sys
from pathlib import Path

sys.path.insert(0, str(Path(__file__).resolve().parent))
from media import FIGURE, appel as appel_figure, attribut_blade, props as props_figure  # noqa: E402

OUVERTURE = re.compile(r'<div class="card [^"]*"[^>]*>')

# Ordre dans lequel le thème dispose les éléments de texte d'une carte.
ÉLÉMENTS = ["pretitle", "title", "type", "subtitle", "description", "town"]

DÉFAUTS = {
    "sheetId": None, "postId": None, "cardPath": None, "displayOptions": None,
    "href": None, "target": None, "carte": None, "unavailable": False,
    "linkClasses": "card-link", "wrapMedia": False, "sectionClasses": None,
    "equalize": False, "titlesClasses": "card-titles", "textsClasses": None,
    "pretitle": None, "title": None, "titleTag": "span", "type": None,
    "subtitle": None, "description": None, "town": None, "button": None,
}


def blocs(fragment: str):
    """Rend chaque carte du fragment, avec ses bornes, en équilibrant les <div>."""
    for début in OUVERTURE.finditer(fragment):
        profondeur = 1
        for balise in re.finditer(r"<div\b|</div>", fragment[début.end():]):
            profondeur += 1 if balise.group(0) == "<div" else -1
            if profondeur == 0:
                yield début.start(), début.end() + balise.end(), fragment[début.start(): début.end() + balise.end()]
                break


def attribut(fragment: str, nom: str) -> str | None:
    trouvé = re.search(rf'\b{re.escape(nom)}="([^"]*)"', fragment)
    return html.unescape(trouvé.group(1)) if trouvé else None


def brut(fragment: str, nom: str) -> str | None:
    """La valeur d'un attribut, sans toucher à son échappement."""
    trouvé = re.search(rf'\b{re.escape(nom)}="([^"]*)"', fragment)
    return trouvé.group(1) if trouvé else None


def contenu(carte: str, motif: str) -> str | None:
    trouvé = re.search(motif, carte, re.S)
    return trouvé.group(1) if trouvé else None


def hors_modèle(carte: str) -> str | None:
    """Dit pourquoi une carte échappe au composant, ou None si elle y entre.

    Mieux vaut une conversion partielle qu'une conversion approximative : ce qui
    n'entre pas dans le modèle reste tel quel dans la vue.
    """
    if "{%" in carte:
        return "gabarit Twig du moteur de recherche"

    # La carte scindée dispose son média et ses textes dans une grille : une autre
    # ossature, qui appelle son propre composant. À faire.
    if "splitCard" in carte[: carte.index(">") + 1]:
        return "carte scindée, ossature en grille"

    if re.search(r'<a class="card-link[^"]*"[^>]*>', carte) is None:
        return "carte sans lien"

    # Quelques cartes du miroir portent deux titres : le vrai, et la légende de
    # leur image qui s'y est glissée. Impossible de trancher lequel garder sans
    # décider à la place de l'auteur — la carte reste telle quelle.
    if len(re.findall(r'<span class="card-title block-span">', carte)) > 1:
        return "deux titres concurrents dans le miroir"

    figure = FIGURE.search(carte)
    if figure is None or props_figure(figure.group(0)) is None:
        return "média illisible"

    return None


def props(carte: str) -> tuple[dict, str] | None:
    """Les propriétés d'une carte et son média, ou None si elle sort du modèle."""
    if hors_modèle(carte) is not None:
        return None

    lien = re.search(r'<a class="card-link[^"]*"[^>]*>', carte).group(0)
    média = appel_figure(props_figure(FIGURE.search(carte).group(0)))

    return {**conteneur(carte), **destination(lien), **corps(carte), **textes(carte)}, média


def conteneur(carte: str) -> dict:
    """Ce que porte le <div> de la carte."""
    ouverture = carte[: carte.index(">") + 1]
    return {
        "classes": attribut(ouverture, "class"),
        "sheetId": attribut(ouverture, "data-sheetid"),
        "postId": attribut(ouverture, "data-postid"),
        "cardPath": attribut(ouverture, "data-card-path"),
        # Conservées telles quelles : c'est du JSON déjà échappé, que le composant
        # rend verbatim plutôt que de le ré-échapper.
        "displayOptions": brut(ouverture, "data-card-display-options"),
    }


def destination(lien: str) -> dict:
    """Où mène la carte — ou pourquoi elle ne mène nulle part."""
    return {
        "href": attribut(lien, "href"),
        "target": attribut(lien, "target"),
        "carte": attribut(lien, "data-carte"),
        "unavailable": 'data-fiche="indisponible"' in lien,
        "linkClasses": attribut(lien, "class"),
    }


def corps(carte: str) -> dict:
    """La mise en page interne : conteneurs et alignement."""
    section = re.search(r'<div class="(card-section[^"]*)"([^>]*)>', carte)
    textes = re.search(r'<div class="(card-texts[^"]*)">', carte)
    return {
        "wrapMedia": '<div class="cardMediaWrapper">' in carte,
        "sectionClasses": section.group(1) if section else None,
        "equalize": bool(section) and "data-equalizer-watch" in section.group(2),
        "titlesClasses": attribut(carte[carte.index("card-titles") - 20:], "class") or "card-titles",
        "textsClasses": textes.group(1) if textes else None,
    }


def textes(carte: str) -> dict:
    """Les éléments de texte, dans l'ordre où le thème les dispose."""
    titre_h3 = re.search(r'<h3 class="card-title">(.*?)</h3>', carte, re.S)
    lus = {
        "pretitle": contenu(carte, r'<span class="card-pretitle block-span">(.*?)</span>'),
        "title": titre_h3.group(1) if titre_h3 else contenu(carte, r'<span class="card-title block-span">(.*?)</span>'),
        "titleTag": "h3" if titre_h3 else "span",
        "type": contenu(carte, r'<span class="card-type block-span">(.*?)</span>'),
        "subtitle": contenu(carte, r'<span class="card-subtitle block-span">(.*?)</span>'),
        "description": contenu(carte, r'<div class="card-description">(.*?)</div>(?=\s*(?:<span class="card-town|</div>))'),
        "town": contenu(carte, r'<span class="card-town block-span"><span class="wicon wicon-039-pin"></span><small>(.*?)</small></span>'),
        "button": contenu(carte, r'<span class="button-label" data-label="[^"]*">(.*?)</span>'),
    }
    # La description garde sa mise en forme ; les autres sont du texte brut.
    for clé in ("pretitle", "title", "type", "subtitle", "town", "button"):
        if lus[clé] is not None:
            lus[clé] = html.unescape(lus[clé])
    return lus


def appel(extrait: dict, média: str) -> str:
    """Sérialise une carte en appel du composant."""
    attributs = []
    for nom, valeur in extrait.items():
        if nom in DÉFAUTS and valeur == DÉFAUTS[nom]:
            continue
        attributs.append(attribut_blade(nom, valeur))
    corps = "\n    ".join(attributs)
    return f"<x-card\n    {corps}\n>\n    {média}\n</x-card>"


def componentise(fragment: str) -> tuple[str, int]:
    """Remplace les cartes d'un fragment par des appels au composant."""
    from convert import dynamic

    morceaux, curseur, remplacées = [], 0, 0
    for début, fin, carte in blocs(fragment):
        if début < curseur:  # carte imbriquée dans une autre, déjà traitée
            continue
        lu = props(carte)
        if lu is None:
            continue
        morceaux.append(fragment[curseur:début])
        morceaux.append(dynamic(appel(*lu)))
        curseur = fin
        remplacées += 1
    morceaux.append(fragment[curseur:])
    return "".join(morceaux), remplacées


def cartes_du_miroir():
    from convert import PAGES_AUTONOMES, Regions, Rewriter, SOURCE

    pages = sorted(str(p.relative_to(SOURCE)) for p in SOURCE.rglob("*.html") if "assets" not in p.parts)
    for relative in pages:
        if relative in PAGES_AUTONOMES:
            continue
        from reparation import repare_les_titres

        brut = Rewriter(relative)((SOURCE / relative).read_text(encoding="utf-8", errors="ignore"))
        page, _ = repare_les_titres(brut)
        try:
            main = Regions.parse(page).main
        except Exception:  # noqa: BLE001 — page hors gabarit
            continue
        curseur = 0
        for début, fin, carte in blocs(main):
            if début < curseur:
                continue
            curseur = fin
            yield relative, carte


def famille(extrait: dict) -> str:
    forme = re.search(r"(basicCard|overlayedCard|splitCard)", extrait["classes"])
    gabarit = re.search(r"tpl_\d+", extrait["classes"])
    return f"{forme.group(1) if forme else '?'} {gabarit.group(0) if gabarit else '?'}"


def main() -> int:
    from convert import ROOT

    cas = []
    for relative, carte in cartes_du_miroir():
        lu = props(carte)
        if lu is None:
            continue
        extrait, média = lu
        cas.append({
            "family": famille(extrait),
            "template": appel(extrait, média),
            "expected": carte,
            "page": relative,
        })

    print(f"{len(cas)} cartes candidates\n")
    fichier = ROOT / "storage" / "roundtrip-card.json"
    fichier.write_text(json.dumps(cas, ensure_ascii=False), encoding="utf-8")
    subprocess.run(["php", str(ROOT / "tools" / "roundtrip.php"), str(fichier)], check=True)
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
