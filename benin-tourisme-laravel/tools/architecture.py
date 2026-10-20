#!/usr/bin/env python3
"""
Architecture du site : l'arborescence, tous les liens, et qui mène où.

Le site est un gabarit repris d'ailleurs : les pages existent, mais leur câblage
n'a jamais été vérifié. Ce module relève trois choses que l'œil ne voit pas en
naviguant :

  · l'arborescence réelle, déduite des adresses ;
  · le graphe des liens du contenu — chaque page, ce qu'elle cite, ce qui la cite.
    Les liens du menu, de l'en-tête et du pied de page sont écartés : ils sont
    identiques partout et noieraient le signal ;
  · les anomalies de câblage : pages orphelines qu'aucune autre ne cite,
    culs-de-sac qui ne mènent nulle part, liens brisés, et vignettes du menu dont
    la légende ne correspond pas à leur destination.

    python3 tools/architecture.py            # à l'écran
    python3 tools/architecture.py --json     # pour la carte interactive
"""

from __future__ import annotations

import argparse
import json
import re
import sys
from collections import defaultdict
from pathlib import Path

sys.path.insert(0, str(Path(__file__).resolve().parent))
from convert import PAGES_AUTONOMES, ROOT, Regions, SOURCE, load, route_of  # noqa: E402

MOTS_MINIMUM = 150

# Vocabulaire maritime, pour repérer les pages de l'intérieur du pays qui
# décrivent encore le lagon d'où le gabarit vient.
MER = re.compile(
    r"\b(mangroves?|lagons?|îlots?|récifs?|coraux|corail|plages?|barrière de corail"
    r"|snorkeling|sable blanc|bancs? de sable)\b",
    re.I,
)

# Pages dont le sujet se situe loin de la côte : la mer n'y a rien à faire.
INTÉRIEUR = re.compile(
    r"atacora|pendjari|parc-w|tanougou|betammaribe|abomey|danxome|collines"
    r"|centre-mystique|plateau-royal|grand-nord|dassa|natitingou|parakou",
    re.I,
)


def texte(html: str) -> str:
    sans = re.sub(r"<(script|style)\b.*?</\1>", " ", html, flags=re.S)
    return re.sub(r"\s+", " ", re.sub(r"<[^>]+>", " ", sans)).strip()


def prose(html: str) -> str:
    """La prose seule : les paragraphes, sans les légendes ni le fil d'Ariane.

    Le texte brut d'une page mêle le crédit des photographies, le fil d'Ariane et
    les libellés des boutons. Pour juger si une page dit quelque chose, il faut
    lire ce qu'elle rédige — c'est-à-dire ses paragraphes.
    """
    sans_légendes = re.sub(r"<figcaption\b.*?</figcaption>", " ", html, flags=re.S)
    sans_fil = re.sub(r'<(nav|ol)\b[^>]*breadcrumb.*?</\1>', " ", sans_légendes, flags=re.S)
    morceaux = [texte(m) for m in re.findall(r"<p\b[^>]*>(.*?)</p>", sans_fil, re.S)]
    retenus = [m for m in morceaux if len(m) > 40 and "©" not in m]
    return " ".join(retenus)


def titre_de(html: str) -> str:
    trouvé = re.search(r"<title>(.*?)</title>", html, re.S)
    if not trouvé:
        return "?"
    return re.sub(r"\s+", " ", trouvé.group(1)).replace(" | Bénin tourisme", "").strip()


def liens_internes(fragment: str, connues: set[str]) -> tuple[set[str], list[str]]:
    """Les routes citées par ce fragment, et celles qui ne mènent nulle part."""
    valides, brisés = set(), []
    for href in re.findall(r'href="([^"#?]+)', fragment):
        if href.startswith(("http", "mailto:", "tel:", "javascript:", "#")):
            continue
        route = re.sub(r"/index\.html$", "", href) or "/"
        route = re.sub(r"\.html$", "", route)
        if not route.startswith("/"):
            route = "/" + route.lstrip("./")
        route = route.rstrip("/") or "/"
        if route in connues:
            valides.add(route)
        elif not route.startswith("/assets"):
            brisés.append(href)
    return valides, brisés


def vignettes_du_menu(html: str, connues: set[str]) -> list[dict]:
    """Les vignettes du méga-menu : leur légende, leur destination, leur cohérence.

    Le méga-menu vit dans l'en-tête. Chaque entrée porte une photographie et une
    légende ; la légende devrait annoncer la page visée. Elle est comparée au titre
    de la destination : deux libellés qui ne partagent aucun mot signalent une
    vignette héritée du gabarit, restée sur la mauvaise porte.
    """
    entête = Regions.parse(html).header
    vignettes = []
    for bloc in re.finditer(r'<a\b[^>]*?href="([^"]+)"[^>]*>(.*?)</a>', entête, re.S):
        cible, dedans = bloc.group(1), bloc.group(2)
        légende = texte(dedans)
        if not légende or cible.startswith(("http", "#", "mailto:", "tel:")):
            continue
        route = re.sub(r"/?index\.html$", "", cible).rstrip("/")
        route = ("/" + route.lstrip("./")).rstrip("/") or "/"
        vignettes.append({
            "legende": légende[:70],
            "cible": route,
            "connue": route in connues,
            "illustree": "<img" in dedans,
        })
    return vignettes


MOTS_VIDES = {"le", "la", "les", "de", "des", "du", "un", "une", "et", "au", "aux",
              "sur", "en", "dans", "pour", "à", "l", "d"}


def mots_forts(libellé: str) -> set[str]:
    return {m for m in re.findall(r"\w{3,}", libellé.lower()) if m not in MOTS_VIDES}


def confronte_les_vignettes(vignettes: list[dict], titres: dict[str, str]) -> None:
    """Marque les vignettes dont la légende ne dit pas où elles mènent."""
    for vignette in vignettes:
        titre = titres.get(vignette["cible"], "")
        vignette["titre_cible"] = titre
        vignette["coherente"] = bool(
            not vignette["illustree"]
            or not titre
            or mots_forts(vignette["legende"]) & (mots_forts(titre) | mots_forts(vignette["cible"]))
        )


def examine(relative: str, connues: set[str]) -> dict:
    html = load(relative)
    autonome = relative in PAGES_AUTONOMES
    régions = None if autonome else Regions.parse(html)
    contenu = html if autonome else régions.main

    corps = texte(contenu)
    rédaction = prose(contenu) or corps
    mots = len(corps.split())
    photos = {
        m.group(1)
        for m in re.finditer(r"/assets/img/([a-z0-9-]+?)(?:-\d+x\d+)?\.(?:jpg|png|webp)", contenu)
    }
    titres = [
        re.sub(r"\s+", " ", re.sub(r"<[^>]+>", "", t)).strip()
        for t in re.findall(r"<h2[^>]*>(.*?)</h2>", contenu, re.S)
    ]
    sortants, brisés = liens_internes(contenu, connues)
    route = route_of(relative)
    sortants.discard(route)

    description = re.search(r'<meta name="description"[^>]*content="([^"]*)"', html)
    mer = [m.group(0).lower() for m in MER.finditer(corps)] if INTÉRIEUR.search(route) else []

    manques = []
    if mots < MOTS_MINIMUM:
        manques.append(f"seulement {mots} mots")
    if len(photos) <= 1:
        manques.append(f"{len(photos)} illustration" + ("s" if len(photos) > 1 else ""))
    if not titres:
        manques.append("aucun sous-titre")
    if not description:
        manques.append("sans description")
    if mer:
        manques.append(f"{len(mer)} mots de mer sur une page de l'intérieur")
    if brisés:
        manques.append(f"{len(brisés)} lien(s) brisé(s)")

    return {
        "route": route,
        "titre": titre_de(html) or "?",
        "rubrique": route.strip("/").split("/")[0] or "accueil",
        "profondeur": 0 if route == "/" else route.strip("/").count("/") + 1,
        "mots": mots,
        "photos": len(photos),
        "titres": [t for t in titres if t],
        "sortants": sorted(sortants),
        "brises": brisés[:5],
        "mer": sorted(set(mer)),
        "manques": manques,
        "chapo": rédaction[:180],
        "extrait": rédaction[:620],
    }


def releve() -> dict:
    fichiers = sorted(
        str(p.relative_to(SOURCE)) for p in SOURCE.rglob("*.html") if "assets" not in p.parts
    )
    connues = {route_of(f) for f in fichiers}
    pages = [examine(f, connues) for f in fichiers]

    entrants = defaultdict(list)
    for page in pages:
        for cible in page["sortants"]:
            entrants[cible].append(page["route"])
    for page in pages:
        page["entrants"] = sorted(entrants[page["route"]])
        if not page["entrants"] and page["route"] != "/":
            page["manques"].insert(0, "orpheline : aucune page ne la cite")
        if not page["sortants"]:
            page["manques"].append("cul-de-sac : ne mène nulle part")

    accueil = next(f for f in fichiers if route_of(f) == "/")
    vignettes = vignettes_du_menu(load(accueil), connues)
    confronte_les_vignettes(vignettes, {p["route"]: p["titre"] for p in pages})
    return {"pages": pages, "vignettes": vignettes}


def affiche(données: dict) -> None:
    pages = données["pages"]
    par_rubrique = defaultdict(list)
    for page in pages:
        par_rubrique[page["rubrique"]].append(page)

    print(f"\n{len(pages)} pages, {sum(p['mots'] for p in pages):,} mots\n".replace(",", " "))
    for rubrique in sorted(par_rubrique, key=lambda r: -len(par_rubrique[r])):
        lot = sorted(par_rubrique[rubrique], key=lambda p: p["route"])
        print(f"━━ {rubrique.upper()} ({len(lot)})")
        for page in lot:
            marque = "⚠" if page["manques"] else " "
            indent = "  " * max(0, page["profondeur"] - 1)
            print(
                f" {marque} {indent}{page['route']:<58} "
                f"{page['mots']:>5}m {page['photos']:>2}i "
                f"→{len(page['sortants']):>2} ←{len(page['entrants']):>2}"
            )
            if page["manques"]:
                print(f"     {indent}  ↳ {' · '.join(page['manques'])}")
        print()

    mauvaises = [v for v in données["vignettes"] if not v["coherente"]]
    print(f"MÉGA-MENU — {len(mauvaises)} vignettes sur {len(données['vignettes'])} "
          f"annoncent autre chose que leur destination :")
    for v in mauvaises:
        print(f"  « {v['legende']} »")
        print(f"      mène à  {v['cible']}  ({v['titre_cible'] or 'page inconnue'})")
    print()
    orphelines = [p for p in pages if not p["entrants"] and p["route"] != "/"]
    culs = [p for p in pages if not p["sortants"]]
    print(f"Pages orphelines : {len(orphelines)} · culs-de-sac : {len(culs)} · "
          f"pages à revoir : {sum(1 for p in pages if p['manques'])}")


def main() -> int:
    parser = argparse.ArgumentParser(description=__doc__)
    parser.add_argument("--json", action="store_true", help="écrit architecture.json")
    args = parser.parse_args()

    données = releve()
    if args.json:
        destination = ROOT / "architecture.json"
        destination.write_text(json.dumps(données, ensure_ascii=False), encoding="utf-8")
        print(f"Écrit : {destination}")
    else:
        affiche(données)
    return 0


if __name__ == "__main__":
    sys.exit(main())
