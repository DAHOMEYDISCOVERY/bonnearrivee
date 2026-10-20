#!/usr/bin/env python3
"""
Le contrat d'équivalence entre le miroir et le rendu Laravel.

Migrer un site à l'identique demande de dire ce que « à l'identique » veut dire.
Comparer les octets serait trop sévère : le miroir est produit par deux générateurs
WordPress qui n'espacent pas leurs attributs de la même façon, et n'échappent pas
les apostrophes de la même façon non plus. Ces différences n'ont aucun effet sur ce
qu'un navigateur affiche.

Deux documents sont donc tenus pour équivalents lorsqu'ils ont :

* les mêmes balises, dans le même ordre, avec les mêmes attributs et les mêmes
  valeurs ;
* le même texte.

Sont ignorés, parce qu'un navigateur les ignore :

* le blanc entre deux attributs d'une même balise ;
* l'ordre des attributs d'une balise — ni le rendu ni les sélecteurs CSS ou JS n'en
  dépendent ;
* le blanc entre deux balises ;
* la barre oblique auto-fermante — « <img …/> » et « <img …> » sont la même balise
  en HTML5 ;
* l'encodage des entités — « l'Atacora » et « l&#039;Atacora » sont le même texte.

Tout le reste compte. Un double échappement reste détecté : « &amp;#039; » ne se
décode pas en apostrophe.

`tools/roundtrip.php` applique le même contrat côté PHP ; les deux implémentations
doivent rester d'accord, ce que vérifie `tools/equivalence.py --test`.
"""

from __future__ import annotations

import html
import re
import sys

# Une balise : pas de chevron dans son contenu, ce qui évite d'attraper les
# comparaisons « a < b » du JavaScript embarqué.
BALISE = re.compile(r"<[a-zA-Z/!][^<>]*>")


def normalise(document: str) -> str:
    """Ramène un document à sa forme comparable."""
    document = BALISE.sub(_normalise_balise, document)
    document = re.sub(r">\s+<", "><", document).strip()
    return html.unescape(document)


ATTRIBUT = re.compile(r'([a-zA-Z_:][-\w:.]*)(?:="([^"]*)")?')


def _normalise_balise(trouvé: re.Match) -> str:
    """Réordonne les attributs d'une balise et normalise sa fermeture."""
    balise = re.sub(r"\s*/?>$", "", trouvé.group(0))[1:]
    nom, _, reste = balise.partition(" ")
    if not reste.strip():
        return f"<{nom}>"

    attributs = sorted(
        f'{cle}="{valeur}"' if valeur is not None else cle
        for cle, valeur in ATTRIBUT.findall(reste)
    )
    return f"<{nom} {' '.join(attributs)}>"


def balises(document: str) -> list[str]:
    """Le document découpé en balises, pour situer un écart."""
    return normalise(document).split("><")


def équivalents(gauche: str, droite: str) -> bool:
    return normalise(gauche) == normalise(droite)


CAS_DE_TEST = [
    ('<a  href="/x"  id="y">T</a>', '<a href="/x" id="y">T</a>', True, "blanc entre attributs"),
    ('<figure class="c"\n    >T</figure>', '<figure class="c">T</figure>', True, "saut de ligne avant le chevron"),
    ("<p>l'Atacora</p>", "<p>l&#039;Atacora</p>", True, "encodage des entités"),
    ("<p>x</p>\n  <p>y</p>", "<p>x</p><p>y</p>", True, "blanc entre balises"),
    ('<p class="a b">T</p>', '<p class="b a">T</p>', False, "ordre des classes"),
    ("<p>l&amp;#039;x</p>", "<p>l'x</p>", False, "double échappement"),
    ('<a href="/x">T</a>', '<a href="/y">T</a>', False, "valeur d'attribut"),
    ("<p>T</p>", "<p>T</p><p>U</p>", False, "balise en trop"),
    ("<p>Bonjour</p>", "<p>Bonjour </p>", False, "espace dans le texte"),
    ('<img src="/x" />', '<img src="/x">', True, "barre oblique auto-fermante"),
    ("<br/>", "<br>", True, "balise vide auto-fermante"),
    ('<a href="/x/">T</a>', '<a href="/x">T</a>', False, "barre oblique dans une valeur"),
    ('<a href="/x" id="y">T</a>', '<a id="y" href="/x">T</a>', True, "ordre des attributs"),
    ("<input disabled required>", "<input required disabled>", True, "attributs sans valeur"),
    ('<a href="/x" id="y">T</a>', '<a id="y">T</a>', False, "attribut manquant"),
]


def main() -> int:
    échecs = 0
    for gauche, droite, attendu, libellé in CAS_DE_TEST:
        obtenu = équivalents(gauche, droite)
        if obtenu != attendu:
            échecs += 1
            print(f"  ✗ {libellé} : attendu {attendu}, obtenu {obtenu}")
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
