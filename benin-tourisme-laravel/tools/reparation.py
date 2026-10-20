#!/usr/bin/env python3
"""
Réparation des titres de carte que le miroir a perdus.

Le miroir compte 223 endroits où le titre d'une carte s'écrit ainsi :

    <div class="card-titles">Gîtes, lodges et écolodges</span>

au lieu de :

    <div class="card-titles">
        <div class="card-texts has-title">
            <span class="card-title block-span">Gîtes, lodges et écolodges</span>
        </div>
    </div>

Deux **ouvertures** ont disparu — celle du conteneur `card-texts` et celle de
l'enveloppe du titre — en laissant leurs fermetures derrière elles. Mesuré sur les
51 cartes concernées : après le titre nu viennent trois `</div>` sans ouverture
correspondante — `card-texts`, `card-titles`, `card-section` — et un `</span>`
orphelin. Ces fermetures sont la preuve que les éléments étaient bien là.

La réparation n'ajoute donc que les ouvertures. Ajouter aussi les fermetures
refermerait la carte trop tôt et sortirait le lien de son conteneur.

Conséquence à l'écran : le navigateur ignore la balise orpheline et le titre
s'affiche en 16 px, sans le style des titres de carte, sur des vignettes qui font
parfois 1 300 px de haut.

Les classes de `card-texts` se déduisent de ce que la carte porte, comme le thème
les compose ailleurs.

**Elle s'applique aux deux côtés de la comparaison** : `convert.py` la passe sur le
miroir avant d'écrire les vues, `compare.py` la passe avant de comparer. Le contrôle
reste donc strict sur tout le reste.

    python3 tools/reparation.py     # les cas de test
"""

from __future__ import annotations

import re
import sys

# Le titre nu, suivi de la fermeture orpheline qui trahit l'enveloppe disparue.
MALFORMÉ = re.compile(r'<div class="(card-titles[^"]*)">([^<]+)</span>')

# Éléments dont la présence décide des classes de `card-texts`, dans l'ordre où le
# thème les nomme.
TRAITS = [
    ("card-pretitle", "has-pretitle"),
    (None, "has-title"),
    ("card-subtitle", "has-subtitle"),
    ("card-description", "has-desc"),
]


def fin_des_textes(html: str, depuis: int) -> int:
    """Position du `</div>` orphelin qui referme `card-texts`.

    On part d'une profondeur nulle : la première fermeture rencontrée sans
    ouverture correspondante est celle que l'on cherche.
    """
    profondeur = 0
    for balise in re.finditer(r"<div\b|</div>", html[depuis:]):
        if balise.group(0) == "<div":
            profondeur += 1
        elif profondeur == 0:
            return depuis + balise.start()
        else:
            profondeur -= 1
    return len(html)


def classes_des_textes(contenu: str) -> str:
    """Les classes de `card-texts`, déduites de ce que la carte porte."""
    traits = [nom for marqueur, nom in TRAITS if marqueur is None or marqueur in contenu]
    return " ".join(["card-texts", *traits])


def repare_les_titres(html: str) -> tuple[str, int]:
    """Restitue les ouvertures perdues par le miroir.

    On répare de la fin vers le début : les insertions ne décalent alors pas les
    positions restant à traiter.
    """
    trouvés = list(MALFORMÉ.finditer(html))
    for trouvé in reversed(trouvés):
        conteneur, titre = trouvé.group(1), trouvé.group(2)
        contenu = html[trouvé.end(): fin_des_textes(html, trouvé.end())]
        ouverture = (
            f'<div class="{conteneur}">'
            f'<div class="{classes_des_textes(contenu)}">'
            f'<span class="card-title block-span">{titre}</span>'
        )
        html = html[:trouvé.start()] + ouverture + html[trouvé.end():]

    return html, len(trouvés)


CAS_DE_TEST = [
    (
        '<div class="card-titles">Le karité</span></div></div>',
        '<div class="card-titles"><div class="card-texts has-title">'
        '<span class="card-title block-span">Le karité</span></div></div>',
        "titre seul",
    ),
    (
        '<div class="card-titles">Le karité</span>'
        '<span class="card-subtitle block-span">Beurre</span></div></div>',
        '<div class="card-titles"><div class="card-texts has-title has-subtitle">'
        '<span class="card-title block-span">Le karité</span>'
        '<span class="card-subtitle block-span">Beurre</span></div></div>',
        "titre et sous-titre",
    ),
    (
        '<div class="card-titles">Le karité</span>'
        '<div class="card-description">Un arbre.</div></div></div>',
        '<div class="card-titles"><div class="card-texts has-title has-desc">'
        '<span class="card-title block-span">Le karité</span>'
        '<div class="card-description">Un arbre.</div></div></div>',
        "titre et description, la description ne clôt pas card-texts",
    ),
    (
        '<div class="card-titles"><div class="card-texts has-title">'
        '<span class="card-title block-span">Déjà correct</span></div></div>',
        '<div class="card-titles"><div class="card-texts has-title">'
        '<span class="card-title block-span">Déjà correct</span></div></div>',
        "carte déjà bien formée, laissée intacte",
    ),
]


def main() -> int:
    échecs = 0
    for source, attendu, libellé in CAS_DE_TEST:
        obtenu, _ = repare_les_titres(source)
        if obtenu != attendu:
            échecs += 1
            print(f"  ✗ {libellé}")
            print(f"      attendu : {attendu}")
            print(f"      obtenu  : {obtenu}")
        else:
            print(f"  ✓ {libellé}")

    print()
    if échecs:
        print(f"{échecs} cas de test en échec.")
        return 1
    print(f"Les {len(CAS_DE_TEST)} cas de test passent.")
    return 0


if __name__ == "__main__":
    sys.exit(main())
