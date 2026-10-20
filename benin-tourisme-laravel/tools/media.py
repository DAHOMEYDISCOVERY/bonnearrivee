#!/usr/bin/env python3
"""
Reconnaissance des figures du miroir et extraction de leurs propriétés.

Le bloc `<figure class="imageObject">` apparaît 931 fois dans les pages. Il s'y
présente sous dix formes, qui ne diffèrent que par la stratégie de chargement,
la présence d'une légende et quelques attributs d'accessibilité — autrement dit,
par des valeurs, pas par une structure.

Ce module lit ces valeurs. `tools/roundtrip.php` vérifie ensuite que le composant
`x-media.figure` les rend au caractère près, et `tools/convert.py` ne remplace que
les figures dont la vérification passe.

    python3 tools/media.py          # mesure la couverture sans rien écrire
"""

from __future__ import annotations

import html
import json
import re
import subprocess
import sys
from pathlib import Path

sys.path.insert(0, str(Path(__file__).resolve().parent))

FIGURE = re.compile(r'<figure\b[^>]*class="[^"]*imageObject[^"]*"[^>]*>.*?</figure>', re.S)


def attribut(fragment: str, nom: str) -> str | None:
    """La valeur d'un attribut, ramenée en texte brut."""
    trouvé = re.search(rf'\b{nom}="([^"]*)"', fragment)
    return html.unescape(trouvé.group(1)) if trouvé else None


def texte(fragment: str, motif: str) -> str | None:
    """Le contenu textuel du premier élément correspondant, ramené en texte brut."""
    trouvé = re.search(motif, fragment, re.S)
    return html.unescape(trouvé.group(1)) if trouvé else None


def props(figure: str) -> dict | None:
    """Les propriétés d'une figure, ou None si elle ne relève pas du composant.

    Sont écartés les gabarits Twig du moteur de recherche, qui portent des jetons
    « {% … %} » au lieu de valeurs.
    """
    if "{%" in figure:
        return None

    images = re.findall(r"<img[^>]*>", figure)
    if not images:
        return None
    visible = images[-1]

    if 'loading="lazy"' in visible:
        chargement, src, placeholder = "native", attribut(visible, "src"), None
    elif "lazyload" in visible:
        chargement = "lazy"
        src, placeholder = attribut(visible, "data-src"), attribut(visible, "src")
    elif "fetchpriority" in visible:
        chargement, src, placeholder = "eager", attribut(visible, "src"), None
    else:
        return None

    ouverture = figure[: figure.index(">") + 1]
    légende = re.search(r"<figcaption[^>]*>", figure)

    return {
        "src": src,
        "alt": attribut(visible, "alt") or "",
        "loading": chargement,
        "placeholder": placeholder,
        "srcset": attribut(visible, "data-srcset") or attribut(visible, "srcset"),
        "classes": attribut(ouverture, "class") or "isRel imageObject",
        "ariaHidden": 'aria-hidden="true"' in ouverture,
        "focusable": "tabindex=" in ouverture,
        "published": attribut(figure, "content") if "datePublished" in figure else None,
        "description": texte(figure, r'<p class="hide imageObject-description[^"]*"[^>]*>(.*?)</p>'),
        "caption": texte(figure, r'imageObject-description" itemprop="caption">(.*?)</p>'),
        "author": texte(figure, r'imageObject-author" itemprop="copyrightHolder">(.*?)</p>'),
        "captionHidden": bool(légende) and " hide" in (attribut(légende.group(0), "class") or ""),
        "nosnippet": bool(légende) and "data-nosnippet" in légende.group(0),
        "instagram": "wicon-011-instagram" in figure,
    }


# Valeurs par défaut du composant : une propriété qui les reprend n'est pas écrite.
DÉFAUTS = {
    "alt": "", "loading": "lazy", "placeholder": None, "srcset": None,
    "classes": "isRel imageObject", "ariaHidden": False, "focusable": False,
    "published": None, "description": None, "caption": None, "author": None,
    "captionHidden": False, "nosnippet": False, "instagram": False,
}


def attribut_blade(nom: str, valeur) -> str:
    """Sérialise une propriété en attribut Blade.

    Blade ne décode pas les entités des attributs de composant : la valeur arrive
    telle qu'elle est écrite. Un guillemet double y est donc échappé, et le
    composant qui le reçoit doit le rendre verbatim.
    """
    if valeur is True:
        return f':{nom}="true"'
    return f'{nom}="' + str(valeur).replace('"', "&quot;") + '"'


def appel(props_: dict) -> str:
    """Sérialise les propriétés en un appel du composant.

    Les propriétés laissées à leur valeur par défaut ne sont pas écrites : l'appel
    dit ce qui distingue cette image, pas ce qu'elle a en commun avec les 930 autres.
    """
    attributs = []
    for nom, valeur in props_.items():
        if nom in DÉFAUTS and valeur == DÉFAUTS[nom]:
            continue
        attributs.append(attribut_blade(nom, valeur))

    if len(attributs) <= 2:
        return f"<x-media.figure {' '.join(attributs)} />"
    corps = "\n    ".join(attributs)
    return f"<x-media.figure\n    {corps}\n/>"


def componentise(html_fragment: str) -> tuple[str, int]:
    """Remplace les figures d'un fragment par des appels au composant.

    Les figures que l'extracteur ne sait pas lire — les gabarits Twig du moteur de
    recherche — sont laissées telles quelles : mieux vaut une conversion partielle
    qu'une conversion approximative.

    Retourne le fragment et le nombre de figures remplacées.
    """
    from convert import dynamic

    remplacées = 0

    def substitue(trouvé: re.Match) -> str:
        nonlocal remplacées
        extrait = props(trouvé.group(0))
        if extrait is None:
            return trouvé.group(0)
        remplacées += 1
        return dynamic(appel(extrait))

    return FIGURE.sub(substitue, html_fragment), remplacées


def figures_du_miroir():
    """Parcourt les <main> du miroir et rend chaque figure convertible."""
    from convert import PAGES_AUTONOMES, Regions, Rewriter, SOURCE

    pages = sorted(str(p.relative_to(SOURCE)) for p in SOURCE.rglob("*.html") if "assets" not in p.parts)
    for relative in pages:
        if relative in PAGES_AUTONOMES:
            continue
        from reparation import repare_les_titres

        brut = Rewriter(relative)((SOURCE / relative).read_text(encoding="utf-8", errors="ignore"))
        html_page, _ = repare_les_titres(brut)
        try:
            main = Regions.parse(html_page).main
        except Exception:  # noqa: BLE001 — page hors gabarit, ignorée ici
            continue
        for trouvé in FIGURE.finditer(main):
            yield relative, trouvé.group(0)


def famille(props_: dict) -> str:
    """Regroupe les figures par forme, pour lire la couverture par famille."""
    traits = [props_["loading"]]
    if props_["instagram"]:
        traits.append("instagram")
    if props_["caption"] is not None:
        traits.append("légende")
    if props_["author"] is not None and props_["caption"] is None:
        traits.append("crédit seul")
    if props_["description"] is not None:
        traits.append("description")
    if props_["ariaHidden"]:
        traits.append("aria-hidden")
    return " · ".join(traits)


def vérifie(cas: list[dict]) -> list[bool]:
    """Fait rendre chaque cas par Blade et retourne les verdicts."""
    from convert import ROOT

    fichier = ROOT / "storage" / "roundtrip-media.json"
    fichier.write_text(json.dumps(cas, ensure_ascii=False), encoding="utf-8")
    subprocess.run(["php", str(ROOT / "tools" / "roundtrip.php"), str(fichier)], check=True)
    verdicts = json.loads((fichier.with_suffix(".json.verdict.json")).read_text(encoding="utf-8"))
    return [verdicts[str(i)] if isinstance(verdicts, dict) else verdicts[i] for i in range(len(cas))]


def main() -> int:
    cas = []
    for relative, figure in figures_du_miroir():
        extrait = props(figure)
        if extrait is None:
            continue
        cas.append({
            "family": famille(extrait),
            "template": appel(extrait),
            "expected": figure,
            "page": relative,
        })

    print(f"{len(cas)} figures candidates\n")
    vérifie(cas)
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
