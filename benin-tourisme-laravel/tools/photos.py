#!/usr/bin/env python3
"""
Recherche de photographies libres pour les sujets que le site n'illustre pas.

Le site tient sur 48 photographies pour 86 sujets de page. Certaines reviennent
plus de deux cents fois, et trois pages sont illustrées par un sujet qui n'est pas
le leur — le Parc W par un hippopotame de la Pendjari, la cuisine béninoise par une
plage. Ce module cherche des remplaçantes sur Wikimedia Commons.

Le risque, en cherchant des images par mots-clés, est d'en retenir une qui ne
montre pas le Bénin : le miroir en porte déjà la trace, une tata du Togo ayant servi
à illustrer le pays Betammaribé. Chaque candidate est donc **vérifiée** avant d'être
proposée :

* ses coordonnées tombent dans le rectangle du Bénin, ou
* elle appartient à une catégorie Commons qui nomme le Bénin.

Une candidate qui ne passe ni l'un ni l'autre est écartée, quelle que soit sa
qualité. Mieux vaut une page sans photo qu'une page qui ment.

    python3 tools/photos.py                 # la liste des candidates vérifiées
    python3 tools/photos.py --sujet cuisine
    python3 tools/photos.py --integre       # télécharge les retenues et crée les variantes

Le module porte aussi les **remplacements de visuels** décidés pour le site : les
pages illustrées par un sujet qui n'est pas le leur. Comme les autres corrections,
c'est une transformation déclarée, appliquée aux deux côtés de la comparaison.
"""

from __future__ import annotations

import argparse
import json
from html import escape as html_escape
import re
import subprocess
import time
import sys
import urllib.parse
from pathlib import Path

API = "https://commons.wikimedia.org/w/api.php"
AGENT = "benin-tourisme/1.0 (recherche de photographies libres)"

# Rectangle englobant du Bénin : latitude 6,2 à 12,5 N ; longitude 0,7 à 3,9 E.
BÉNIN = {"lat": (6.0, 12.6), "lon": (0.6, 4.0)}

LARGEUR_MINIMALE = 1600

# Licences acceptées : toutes libres, toutes compatibles avec une réutilisation
# commerciale, comme celles déjà employées par le site.
LICENCES = ("CC BY", "CC BY-SA", "CC0", "Public domain", "PD")

# Sujets à illustrer, et les termes qui les cherchent le mieux sur Commons.
SUJETS = {
    "cuisine": ("Arts culinaires du Bénin — la page est illustrée par une plage",
                ["Beninese cuisine", "Benin food", "Arts culinaire du Bénin"]),
    "dantokpa": ("Le marché Dantokpa — visuel générique",
                 ["Dantokpa", "Dantokpa market Cotonou"]),
    "parc-w": ("Le Parc W — illustré par un hippopotame de la Pendjari",
               ["W National Park Benin", "Parc national du W Bénin"]),
    "bouche-du-roy": ("La Bouche du Roy — aucune photo propre",
                      ["Bouche du Roy", "Mono River mouth Benin"]),
    "porto-novo": ("Porto-Novo", ["Porto-Novo", "Porto Novo Benin architecture"]),
    "hebergement": ("Hébergements", ["Hotel Benin", "Lodge Benin Pendjari"]),
    "koutammakou": ("Le Koutammakou béninois", ["Koutammakou Benin", "Tata Somba Boukoumbé"]),
    "zangbeto": ("Les Zangbeto", ["Zangbeto"]),
    "abomey": ("Les palais royaux d'Abomey", ["Royal Palaces of Abomey", "Musée historique d'Abomey"]),
    "ganvie": ("Ganvié", ["Ganvié", "Ganvie lake village"]),
}


# Photographies retenues pour les sujets qu'aucune image du site n'illustrait.
# Chacune est vérifiée : coordonnées dans le pays, ou catégorie qui le nomme.
TÉLÉCHARGEMENTS = {
    "varan": {
        "fichier": "Le Varan du Nil a la Pendjari.jpg",
        "auteur": "AMADOU BAHLEMAN FARID", "licence": "CC BY-SA 4.0",
        "légende": "Un varan du Nil",
    },
    "vodun-days": {
        "fichier": "10 Janvier 2023, Fête de vodoun à Ouidah 03.jpg",
        "auteur": "Dieu229", "licence": "CC BY-SA 4.0",
        "légende": "La fête du Vodun à Ouidah",
    },
    "piment": {
        "fichier": "Piment-vert-jaune-rouge.jpg",
        "auteur": "Richardspa", "licence": "CC0",
        "légende": "Piments sur un étal",
    },
    "gingembre": {
        "fichier": "Epices devant des vélos 02.jpg",
        "auteur": "Fawaz.tairou", "licence": "CC BY-SA 4.0",
        "légende": "Vendeurs de boisson au gingembre",
    },
    "hebergement": {
        "fichier": "Lodge dans le parc national de la Pendjari.jpg",
        "auteur": "Padonou Dotou", "licence": "CC BY-SA 4.0",
        "légende": "Un écolodge au Bénin",
    },
    "maquis": {
        "fichier": "Intérieur d'un bar Restaurant a Cotonou Bénin2.jpg",
        "auteur": "Adoscam", "licence": "CC BY-SA 4.0",
        "légende": "La terrasse d'un maquis",
    },
    "franc-cfa": {
        "fichier": "Billet de monnaie - Bénin.jpg",
        "auteur": "Houss 2020", "licence": "CC0",
        "légende": "Billets de franc CFA",
    },
    "aeroport": {
        "fichier": "Cadjehoun Airport 7822.jpg",
        "auteur": "GuillaumeG", "licence": "CC BY-SA 4.0",
        "légende": "L'aéroport de Cotonou",
    },
    "sante": {
        "fichier": "Centre de santé de Zogbodomey au Bénin 02.jpg",
        "auteur": "Adoscam", "licence": "CC BY-SA 4.0",
        "légende": "Un centre de santé",
    },
    "frontiere": {
        "fichier": "Benin entry stamp.jpg",
        "auteur": "Vitor Abdala", "licence": "Public domain",
        "légende": "Le tampon d'entrée au Bénin",
    },
    "gare-routiere": {
        "fichier": "Bus de la Fondation zinsou, Ouidah au Bénin.jpg",
        "auteur": "Saliousoft", "licence": "CC BY-SA 4.0",
        "légende": "Un car de transport interurbain",
    },
    "ouidah-musee": {
        "fichier": "Ouidah history museum.jpg",
        "auteur": "Francisco Anzola", "licence": "CC BY 2.0",
        "légende": "Le musée d'histoire de Ouidah",
    },
    "nikki": {
        "fichier": "Plaisir d'un équestre au Benin. 05.jpg",
        "auteur": "FiossifrejusduBénin", "licence": "CC BY-SA 4.0",
        "légende": "La Gaani à cheval, à Nikki",
    },
    "grand-popo2": {
        "fichier": "Mono river estuary in Avloh, Grand-Popo, Benin.jpg",
        "auteur": "Kulttuurinavigaattori", "licence": "CC BY-SA 4.0",
        "légende": "La plage de Grand-Popo",
    },
    "dassa-collines": {
        "fichier": "Vue de Dassa depuis la colline de Kamaté.jpg",
        "auteur": "Padonou Dotou", "licence": "CC BY-SA 4.0",
        "légende": "Les collines de Dassa-Zoumè",
    },
    "egungun": {
        "fichier": "Dans folklorique de Kouvitô (Egungun) au Bénin 03.jpg",
        "auteur": "DoussFrance", "licence": "CC BY-SA 4.0",
        "légende": "Des Egungun en procession",
    },
    "marche-rue": {
        "fichier": "Street market view in Come Benin Dec 2017.jpg",
        "auteur": "Kulttuurinavigaattori", "licence": "CC BY-SA 4.0",
        "légende": "Un marché de rue",
    },
    "coton": {
        "fichier": "Bénin-Champ de coton.jpg",
        "auteur": "Ji-Elle", "licence": "CC BY-SA 4.0",
        "légende": "Un champ de coton",
    },
    "acadja": {
        "fichier": "Cours du Mono au Bénin (14).jpg",
        "auteur": "Ji-Elle", "licence": "CC BY-SA 4.0",
        "légende": "Pêcheurs en pirogue sur la lagune",
    },
    "ecole": {
        "fichier": "DEGAN Gabin -( school children on their way to school ).jpg",
        "auteur": "DEGAN Gabin", "licence": "CC BY-SA 4.0",
        "légende": "Écoliers sur le chemin de l'école",
    },
    "foret-kpasse": {
        "fichier": "Forêt sacrée de Ouidah 12.jpg",
        "auteur": "Ivannovna", "licence": "CC BY-SA 4.0",
        "légende": "La forêt sacrée de Kpassè",
    },
    "parc-w": {
        "fichier": "Mekrou river in W-National Park MS 6380.JPG",
        "auteur": "Marco Schmidt", "licence": "CC BY-SA 3.0",
        "légende": "La Mékrou dans le parc national du W",
    },
    "dantokpa": {
        "fichier": "Marché DANTOKPA.jpg",
        "auteur": "jbdodane", "licence": "CC BY 2.0",
        "légende": "Le marché Dantokpa à Cotonou",
    },
    "bouche-du-roy": {
        "fichier": "Sunrise à l\'embouchure de la Bouche du Roy.jpg",
        "auteur": "Padonou Dotou", "licence": "CC BY-SA 4.0",
        "légende": "Le lever du jour sur la Bouche du Roy",
    },
}

# Pages dont la couverture ne montrait pas leur sujet. Chaque ligne dit pourquoi.
# Le remplacement porte sur toutes les variantes d'une photographie dans la page.
REMPLACEMENTS = {
    # --- Sujets qui n'avaient aucune photographie propre ---------------------
    "explorez/naturellement-sauvage/la-faune-emblematique/le-varan-du-nil/index.html":
        [("pendjari-elephant", "varan", "un éléphant illustrait le varan du Nil")],
    "explorez/terre-de-traditions/les-vodun-days/index.html":
        [("gelede", "vodun-days", "un masque gèlèdè illustrait les Vodun Days")],
    "explorez/terre-de-traditions/la-route-des-saveurs/le-piment/index.html":
        [("cuisine", "piment", "un plat générique illustrait le piment")],
    "explorez/terre-de-traditions/la-route-des-saveurs/le-gingembre/index.html":
        [("cuisine", "gingembre", "un plat générique illustrait le gingembre")],
    "explorez/terre-de-traditions/la-route-des-saveurs/index.html":
        [("pendjari-hippo", "coton", "un hippopotame illustrait la route des saveurs")],
    "sejourner/hebergements/index.html":
        [("plage-fidjrosse", "hebergement", "une plage illustrait les hébergements")],
    "sejourner/restauration/restaurants/index.html":
        [("peuple", "maquis", "un portrait de groupe illustrait les restaurants")],
    "sejourner/toutes-les-infos-pratiques/argent-et-paiements/index.html":
        [("plage-fidjrosse", "franc-cfa", "une plage illustrait l'argent et les paiements")],
    "sejourner/toutes-les-infos-pratiques/comment-venir/index.html":
        [("plage-fidjrosse", "aeroport", "une plage illustrait « comment venir »")],
    "sejourner/toutes-les-infos-pratiques/sante-et-vaccins/index.html":
        [("mangrove", "sante", "une mangrove illustrait la santé et les vaccins")],
    "sejourner/toutes-les-infos-pratiques/formalites-et-visa/index.html":
        [("plage-fidjrosse", "frontiere", "une plage illustrait les formalités d'entrée")],
    "sejourner/toutes-les-infos-pratiques/se-deplacer/index.html":
        [("paysage", "gare-routiere", "un paysage illustrait les déplacements")],
    "sejourner/toutes-les-infos-pratiques/vie-pratique/index.html":
        [("oiseau", "ecole", "un oiseau illustrait la vie pratique")],
    "activites/sur-les-traces-de-lhistoire/index.html":
        [("atacora", "ouidah-musee", "un paysage de l'Atacora illustrait l'Histoire")],
    "explorez/terre-de-traditions/les-traditions-locales/index.html":
        [("pendjari-hippo", "nikki", "un hippopotame illustrait les traditions locales")],
    "explorez/terre-de-traditions/a-faire-absolument/index.html":
        [("pendjari-hippo", "egungun", "un hippopotame illustrait « à faire absolument »")],
    "explorez/le-littoral-et-les-lacs/grand-popo/index.html":
        [("ouidah-route", "grand-popo2", "une rue de Ouidah illustrait Grand-Popo")],
    "explorez/les-cinq-benin/le-centre-mystique/index.html":
        [("plage-grand-popo", "dassa-collines", "une plage illustrait les Collines")],
    "explorez/le-littoral-et-les-lacs/peche-et-pirogue/index.html":
        [("plage-grand-popo", "acadja", "une plage illustrait la pêche et la pirogue")],
    "explorez/terre-de-traditions/ouidah-ville-du-vodun/index.html":
        [("danse", "foret-kpasse", "une danse illustrait Ouidah, ville du vodun")],
    "activites/les-operateurs-de-safari/index.html":
        [("pecheur", "marche-rue", "un pêcheur illustrait les opérateurs de safari")],

    # --- Corrections antérieures --------------------------------------------
    "explorez/terre-de-traditions/la-cuisine-beninoise-melange-de-saveurs/index.html":
        [("plage-fidjrosse", "cuisine", "une plage illustrait la cuisine béninoise")],
    "sejourner/restauration/index.html":
        [("plage-fidjrosse", "restaurant", "une plage illustrait la restauration")],
    "sejourner/hebergements/hotels/index.html":
        [("plage-grand-popo", "hotel", "une plage illustrait les hôtels")],
    "explorez/naturellement-sauvage/la-faune-emblematique/observer-les-elephants/index.html":
        [("pendjari-babouin", "pendjari-elephant", "un babouin illustrait les éléphants")],
    "explorez/le-littoral-et-les-lacs/index.html":
        [("pirogue", "lac-aheme", "le lac Ahémé dit mieux « le littoral et les lacs »")],
    "explorez/naturellement-sauvage/le-parc-w/index.html":
        [("pendjari-hippo", "parc-w", "un hippopotame de la Pendjari illustrait le parc W")],
    "explorez/terre-de-traditions/le-marche-dantokpa/index.html":
        [("marche", "dantokpa", "un marché générique illustrait Dantokpa")],
    "explorez/le-littoral-et-les-lacs/la-bouche-du-roy/index.html":
        [("pecheur", "bouche-du-roy", "un pêcheur illustrait la Bouche du Roy")],
    "offres/hotel-du-lac-nokoue-cotonou-bj-3332266/index.html":
        [("peuple", "hotel", "une scène de rue illustrait une fiche d'hôtel")],
    "offres/le-maquis-royal-abomey-bj-3329842/index.html":
        [("danse", "restaurant", "une danse illustrait une fiche de maquis")],
    "offres/pendjari-safari-tours-natitingou-bj-3313618/index.html":
        [("amazone", "pendjari-savane", "une statue illustrait un voyagiste de la Pendjari")],
}


# La page des crédits est écrite à la main et recense la photothèque. Les
# photographies ajoutées après sa rédaction n'y figuraient pas — or leurs licences
# CC BY et CC BY-SA imposent de nommer l'auteur. Le tableau est complété ici, à la
# même place que le reste, plutôt qu'à la main dans le fichier source.
CRÉDITS = "credits-photos.html"

LIGNE = (
    '<tr>\n      <td class="thumb"><img src="/assets/img/{sujet}-640x480.jpg" '
    'alt="{légende}" loading="lazy"></td>\n'
    '      <td><strong>{légende}</strong><br><span class="file">File:{fichier}</span></td>\n'
    '      <td>{auteur}</td>\n'
    '      <td><span class="lic">{licence}</span></td>\n'
    '      <td><a href="https://commons.wikimedia.org/wiki/File%3A{adresse}" '
    'rel="noopener">Commons</a></td>\n    </tr>\n    '
)


def credite_les_ajouts(html: str, relative: str) -> tuple[str, int]:
    """Inscrit les photographies ajoutées dans le tableau de la photothèque."""
    if relative != CRÉDITS:
        return html, 0

    manquantes = [(s, f) for s, f in TÉLÉCHARGEMENTS.items()
                  if f'/assets/img/{s}-640x480' not in html]
    if not manquantes:
        return html, 0

    lignes = "".join(
        LIGNE.format(
            sujet=sujet,
            légende=html_escape(fiche["légende"]),
            fichier=html_escape(fiche["fichier"]),
            auteur=html_escape(fiche["auteur"]),
            licence=fiche["licence"],
            adresse=urllib.parse.quote(fiche["fichier"].replace(" ", "_")),
        )
        for sujet, fiche in sorted(manquantes)
    )

    début = html.find("Photothèque du site")
    ancre = html.find("<tbody>", début) + len("<tbody>\n    ")
    html = html[:ancre] + lignes + html[ancre:]

    total = len(TÉLÉCHARGEMENTS) + 48
    html = html.replace("<strong>48 photographies</strong>", f"<strong>{total} photographies</strong>")
    html = html.replace("Photothèque du site — 48 visuels", f"Photothèque du site — {total} visuels")
    return html, len(manquantes)


def remplace_les_visuels(html: str, relative: str) -> tuple[str, int]:
    """Substitue les visuels hors sujet, page par page.

    Toutes les variantes d'une photographie sont visées d'un coup : le nom de base
    est suivi soit d'un tiret et de ses dimensions, soit directement de l'extension.
    """
    remplacés = 0
    for ancien, nouveau, _raison in REMPLACEMENTS.get(relative, []):
        motif = re.compile(rf"/assets/img/{re.escape(ancien)}(?=-\d+x\d+\.|\.)")
        html, n = motif.subn(f"/assets/img/{nouveau}", html)
        remplacés += n
    return html, remplacés


# Formats que le thème réclame. Ce sont ceux qu'au moins vingt photographies du
# site portent déjà : les couvrir suffit à servir tous les gabarits de carte.
FORMATS = [
    "360x90", "360x180", "360x203", "360x270", "360x360",
    "640x160", "640x320", "640x360", "640x480", "640x640", "640x854",
    "1200x300", "1200x600", "1200x675", "1200x900", "1200x1200",
    "1920x480", "1920x960", "1920x1080", "1920x1440",
]

SOURCE_IMG = Path(__file__).resolve().parent.parent / "public" / "assets" / "img"


def telecharge(sujet: str) -> Path | None:
    """Rapatrie l'original depuis Commons, sous le nom court du sujet."""
    fiche = TÉLÉCHARGEMENTS[sujet]
    données = interroge({
        "action": "query", "format": "json", "titles": "File:" + fiche["fichier"],
        "prop": "imageinfo", "iiprop": "url",
    })
    pages = ((données.get("query") or {}).get("pages") or {})
    info = next(iter(pages.values()), {}).get("imageinfo")
    if not info:
        return None

    destination = SOURCE_IMG / "_src" / f"{sujet}.jpg"
    destination.parent.mkdir(parents=True, exist_ok=True)

    # Les originaux de Commons montent à plusieurs dizaines de méga-octets et le
    # service est parfois lent : un délai court faisait échouer le téléchargement
    # au milieu de la série. On laisse le temps, on reprend où l'on s'est arrêté,
    # et on réessaie.
    for essai in range(3):
        rendu = subprocess.run(
            ["curl", "-sL", "-C", "-", "--max-time", "600", "--retry", "2",
             "-A", AGENT, "-o", str(destination), info[0]["url"]],
            capture_output=True,
        )
        if rendu.returncode == 0 and destination.exists() and destination.stat().st_size > 10_000:
            return destination
        time.sleep(2 * (essai + 1))
    return None


def genere_les_variantes(sujet: str) -> int:
    """Recadre l'original dans chacun des formats du thème.

    Le recadrage est centré : c'est ce que fait le thème, et c'est le seul choix
    qui ne demande pas de juger du sujet de chaque image.
    """
    from PIL import Image

    original = SOURCE_IMG / "_src" / f"{sujet}.jpg"
    with Image.open(original) as image:
        image = image.convert("RGB")
        for format_ in FORMATS:
            largeur, hauteur = (int(n) for n in format_.split("x"))
            vignette = recadre(image, largeur, hauteur)
            vignette.save(SOURCE_IMG / f"{sujet}-{format_}.jpg", quality=86, optimize=True)
    return len(FORMATS)


def carte_d_interet(image):
    """Où se trouve le sujet, en trois indices simples.

    Un recadrage centré décapite : sur une photo de personne, la tête occupe le
    tiers haut, et c'est justement ce tiers que le centre jette. Plutôt que de
    deviner, on note chaque zone de l'image sur trois critères :

      · la **structure** — mesurée sur une forte réduction, pour que le grain de
        la latérite et l'appareil des briques ne l'emportent pas sur un bâtiment.
        C'était l'erreur du premier essai : le sol, très texturé, attirait le
        cadrage vers le bas ;
      · la **peau** — pesée lourd, parce qu'un visage coupé est la faute qui se
        voit le plus ;
      · la **saturation** — pesée léger seulement, la terre rouge du Bénin étant
        elle-même très saturée.

    Le calcul se fait sur une réduction de l'image : la carte sert à choisir un
    cadrage, pas à être regardée.
    """
    import numpy as np
    from PIL import Image

    petite = image.resize((160, max(1, round(160 * image.height / image.width))), Image.BILINEAR)
    a = np.asarray(petite, dtype=float)
    gris = a @ (0.299, 0.587, 0.114)

    # La structure se lit sur une réduction plus forte encore : à cette échelle,
    # une façade ou une silhouette subsiste, le grain d'un sol disparaît.
    grosse = np.asarray(
        petite.convert("L").resize((40, max(1, petite.height * 40 // petite.width)), Image.BILINEAR),
        dtype=float,
    )
    structure = (
        np.abs(np.diff(grosse, axis=1, prepend=grosse[:, :1]))
        + np.abs(np.diff(grosse, axis=0, prepend=grosse[:1, :]))
    )
    if structure.max() > 0:
        structure /= structure.max()
    structure = np.asarray(
        Image.fromarray(structure * 255).resize((gris.shape[1], gris.shape[0]), Image.BILINEAR),
        dtype=float,
    ) / 255

    cr = (a[..., 0] - gris) * 0.713 + 128
    cb = (a[..., 2] - gris) * 0.564 + 128
    peau = ((cr > 135) & (cr < 180) & (cb > 85) & (cb < 135) & (gris > 60)).astype(float)

    haut, bas = a.max(axis=2), a.min(axis=2)
    saturation = np.where(haut > 0, (haut - bas) / np.maximum(haut, 1), 0)

    return structure + 4 * peau + 0.15 * saturation


def hauteur_privilegiee(lignes: int, sommet: float = 0.40, largeur: float = 0.34):
    """Le poids donné à chaque rangée selon sa hauteur dans l'image.

    Une courbe douce, maximale un peu au-dessus du milieu. Elle ne décide de rien
    à elle seule : elle départage, et empêche le cadrage de glisser vers un sol
    très texturé quand le sujet, lui, se tient plus haut.
    """
    import numpy as np

    y = np.linspace(0, 1, lignes)
    return np.exp(-0.5 * ((y - sommet) / largeur) ** 2)


def meilleure_fenetre(poids, longueur: int, biais_centre: float = 0.08) -> int:
    """Le début de la fenêtre de `longueur` qui retient le plus d'intérêt.

    Le recadrage ne glisse que sur un axe — l'autre est conservé en entier — si
    bien qu'une somme glissante à une dimension suffit. Un léger rappel vers le
    centre départage les cadrages de valeur égale, sans jamais l'emporter sur un
    sujet franchement décentré.
    """
    import numpy as np

    if longueur >= len(poids):
        return 0
    cumul = np.concatenate(([0.0], np.cumsum(poids)))
    scores = cumul[longueur:] - cumul[:-longueur]

    centre = (len(poids) - longueur) / 2
    départs = np.arange(len(scores))
    écart = np.abs(départs - centre) / max(centre, 1)
    return int(np.argmax(scores * (1 - biais_centre * écart)))


def recadre(image, largeur: int, hauteur: int, carte=None):
    """Recadre sur le sujet dans le rapport visé, puis redimensionne.

    Le rapport de l'original décide de l'axe qui est rogné ; l'autre est gardé en
    entier. La position du rognage vient de la carte d'intérêt, pas du centre.

    La carte ne dépend que de l'image : quand un même sujet est décliné en vingt
    formats, on la calcule une fois et on la passe.
    """
    from PIL import Image

    visé, source = largeur / hauteur, image.width / image.height
    if carte is None:
        carte = carte_d_interet(image)
    échelle = image.width / carte.shape[1]

    if source > visé:                       # l'original est plus large : on rogne les côtés
        garde = round(image.height * visé)
        colonnes = carte.sum(axis=0)
        # Rien ne distingue la gauche de la droite : le rappel au centre suffit.
        début = round(meilleure_fenetre(colonnes, max(1, round(garde / échelle))) * échelle)
        début = min(début, image.width - garde)
        boîte = (début, 0, début + garde, image.height)
    else:                                   # l'original est plus haut : on rogne en haut et en bas
        garde = round(image.width / visé)
        # Une photographie se compose vers le haut : sujets, visages et bâtiments
        # s'y tiennent, le sol occupe le bas. Ce rappel évite de cadrer la terre.
        lignes = carte.sum(axis=1) * hauteur_privilegiee(carte.shape[0])
        début = round(meilleure_fenetre(lignes, max(1, round(garde / échelle))) * échelle)
        début = min(début, image.height - garde)
        boîte = (0, début, image.width, début + garde)

    return image.crop(boîte).resize((largeur, hauteur), Image.LANCZOS)


def refais_toutes_les_decoupes() -> dict:
    """Refait chaque découpe servie depuis son original, au recadrage intelligent.

    Le miroir recadrait au centre, ce qui décapite : sur `peuple`, les têtes des
    enfants étaient coupées ; sur `gelede`, le masque était tronqué. Toutes les
    découpes d'un sujet sont refaites ensemble — en garder une partie au cadrage
    ancien ferait sauter le cadrage d'une taille d'écran à l'autre, puisque c'est
    le `srcset` qui choisit.
    """
    from PIL import Image

    servies = {}
    for fichier in SOURCE_IMG.glob("*.webp"):
        trouvé = re.match(r"(.+)-(\d+)x(\d+)$", fichier.stem)
        if trouvé:
            servies.setdefault(trouvé.group(1), []).append(
                (int(trouvé.group(2)), int(trouvé.group(3)))
            )

    refaites = agrandies = 0
    sans_original = []
    for sujet, formats in sorted(servies.items()):
        original = SOURCE_IMG / "_src" / f"{sujet}.jpg"
        if not original.exists():
            sans_original.append(sujet)
            continue
        with Image.open(original) as brut:
            image = brut.convert("RGB")
            carte = carte_d_interet(image)
            for largeur, hauteur in formats:
                if largeur > image.width or hauteur > image.height:
                    agrandies += 1
                recadre(image, largeur, hauteur, carte).save(
                    SOURCE_IMG / f"{sujet}-{largeur}x{hauteur}.webp",
                    "WEBP", quality=75, method=6,
                )
                refaites += 1
    return {"sujets": len(servies) - len(sans_original), "refaites": refaites,
            "agrandies": agrandies, "sans_original": sans_original}


def complete_les_decoupes(réclamées: set[str]) -> dict:
    """Fabrique les découpes qu'une page réclame et que le disque n'a pas.

    Le miroir en appelle huit qu'il n'a jamais possédées : la page les demande,
    le serveur répond 404. Tant que l'original est dans `_src`, la découpe se
    refait — au même recadrage centré que le thème.
    """
    from PIL import Image

    faites, sans_original = [], []
    for nom in sorted(réclamées):
        sujet, _, format_ = nom.removesuffix(".webp").rpartition("-")
        original = SOURCE_IMG / "_src" / f"{sujet}.jpg"
        if not original.exists():
            sans_original.append(nom)
            continue
        largeur, hauteur = (int(n) for n in format_.split("x"))
        with Image.open(original) as image:
            vignette = recadre(image.convert("RGB"), largeur, hauteur)
            vignette.save(SOURCE_IMG / nom, "WEBP", quality=75, method=6)
        faites.append(nom)
    return {"faites": faites, "sans_original": sans_original}


def met_a_jour_les_credits() -> int:
    """Inscrit les nouvelles photographies dans le fichier de crédits."""
    fichier = SOURCE_IMG / "_src" / "credits.json"
    crédits = json.loads(fichier.read_text(encoding="utf-8"))
    ajoutés = 0
    for sujet, fiche in TÉLÉCHARGEMENTS.items():
        entrée = {
            "file": f"{sujet}.jpg",
            "title": fiche["légende"],
            "author": fiche["auteur"],
            "license": fiche["licence"],
            "source": "https://commons.wikimedia.org/wiki/File:"
                      + urllib.parse.quote(fiche["fichier"].replace(" ", "_")),
        }
        if isinstance(crédits, list):
            if not any(c.get("file") == entrée["file"] for c in crédits):
                crédits.append(entrée)
                ajoutés += 1
        else:
            if sujet not in crédits:
                crédits[sujet] = entrée
                ajoutés += 1
    fichier.write_text(json.dumps(crédits, ensure_ascii=False, indent=2), encoding="utf-8")
    return ajoutés


def integre() -> int:
    """Télécharge les photographies retenues et fabrique leurs variantes."""
    for sujet in TÉLÉCHARGEMENTS:
        chemin = telecharge(sujet)
        if chemin is None:
            print(f"  ✗ {sujet} : téléchargement impossible")
            continue
        taille = chemin.stat().st_size / 1024 / 1024
        variantes = genere_les_variantes(sujet)
        print(f"  ✓ {sujet:<16} {taille:>5.1f} Mo · {variantes} variantes")
    print(f"\n  {met_a_jour_les_credits()} entrées ajoutées aux crédits.")
    return 0


class CommonsMuet(RuntimeError):
    """Commons n'a pas répondu, malgré les tentatives."""


def interroge(params: dict, tentatives: int = 4) -> dict:
    """Appelle l'API de Commons. Passe par curl : l'environnement n'a pas de racines TLS.

    Une recherche un peu large enchaîne des dizaines d'appels ; Commons finit par
    ralentir et renvoie autre chose que du JSON. La première version rendait alors
    un dictionnaire vide sans rien dire — et un sujet paraissait n'avoir aucune
    photographie alors que la même requête, lancée seule, en trouvait quinze.
    Chaque échec est donc réessayé, en patientant de plus en plus, et un silence
    persistant lève une erreur au lieu de se faire passer pour une absence.
    """
    url = API + "?" + urllib.parse.urlencode(params)
    for essai in range(tentatives):
        sortie = subprocess.run(
            ["curl", "-s", "--max-time", "45", "-A", AGENT, url],
            capture_output=True, text=True,
        ).stdout
        try:
            réponse = json.loads(sortie)
        except json.JSONDecodeError:
            réponse = None
        if réponse is not None and "error" not in réponse:
            time.sleep(0.4)          # on ne bouscule pas le service
            return réponse
        time.sleep(1.5 * (essai + 1))
    raise CommonsMuet(f"aucune réponse après {tentatives} tentatives : {params.get('gsrsearch', '')}")


def candidates(terme: str, limite: int = 20) -> list[dict]:
    """Les images d'un terme de recherche, avec ce qu'il faut pour les vérifier."""
    données = interroge({
        "action": "query", "format": "json", "generator": "search",
        "gsrsearch": terme, "gsrlimit": limite, "gsrnamespace": 6,
        "prop": "imageinfo|categories|coordinates",
        "iiprop": "url|size|extmetadata",
        "iiextmetadatafilter": "LicenseShortName|Artist|ImageDescription",
        "cllimit": "max", "coprop": "type",
    })
    trouvées = []
    for page in ((données.get("query") or {}).get("pages") or {}).values():
        info = (page.get("imageinfo") or [{}])[0]
        if info.get("width", 0) < LARGEUR_MINIMALE:
            continue
        méta = info.get("extmetadata", {})
        trouvées.append({
            "titre": page["title"].removeprefix("File:"),
            "largeur": info.get("width"), "hauteur": info.get("height"),
            "licence": (méta.get("LicenseShortName") or {}).get("value", "?"),
            "auteur": nettoie((méta.get("Artist") or {}).get("value", "")),
            "url": info.get("url", ""),
            "catégories": [c["title"].removeprefix("Category:") for c in page.get("categories", [])],
            "coordonnées": (page.get("coordinates") or [{}])[0],
        })
    return trouvées


def nettoie(html: str) -> str:
    """Retire le balisage des champs de Commons, qui arrivent en HTML."""
    return re.sub(r"<[^>]+>", "", html).strip()[:60]


def au_bénin(image: dict) -> str | None:
    """Dit ce qui rattache l'image au Bénin, ou None si rien ne l'y rattache."""
    point = image.get("coordonnées") or {}
    lat, lon = point.get("lat"), point.get("lon")
    if lat is not None and lon is not None:
        if BÉNIN["lat"][0] <= lat <= BÉNIN["lat"][1] and BÉNIN["lon"][0] <= lon <= BÉNIN["lon"][1]:
            return f"coordonnées {lat:.3f}, {lon:.3f}"
        return None

    for catégorie in image["catégories"]:
        if "benin" in catégorie.lower() or "bénin" in catégorie.lower():
            return f"catégorie « {catégorie[:44]} »"
    return None


def libre(image: dict) -> bool:
    return any(image["licence"].startswith(l) for l in LICENCES)


def retenues(sujet: str) -> list[dict]:
    """Les candidates vérifiées pour un sujet, les plus grandes d'abord."""
    _, termes = SUJETS[sujet]
    vues, gardées = set(), []
    for terme in termes:
        for image in candidates(terme):
            if image["titre"] in vues:
                continue
            vues.add(image["titre"])
            preuve = au_bénin(image)
            if preuve is None or not libre(image):
                continue
            gardées.append({**image, "preuve": preuve})
    return sorted(gardées, key=lambda i: -i["largeur"])


def main() -> int:
    parser = argparse.ArgumentParser(description=__doc__, formatter_class=argparse.RawDescriptionHelpFormatter)
    parser.add_argument("--sujet", choices=sorted(SUJETS), help="n'examiner qu'un sujet")
    parser.add_argument("--integre", action="store_true",
                        help="télécharge les photographies retenues et crée leurs variantes")
    args = parser.parse_args()

    if args.integre:
        return integre()

    sujets = [args.sujet] if args.sujet else sorted(SUJETS)
    total = 0
    for sujet in sujets:
        libellé, _ = SUJETS[sujet]
        gardées = retenues(sujet)
        total += len(gardées)
        print(f"\n=== {sujet} — {libellé}")
        if not gardées:
            print("    aucune candidate ne prouve son rattachement au Bénin.")
            continue
        for image in gardées[:4]:
            print(f"    {image['largeur']}×{image['hauteur']:<6} {image['licence']:<16} {image['titre'][:52]}")
            print(f"        {image['preuve']} · {image['auteur']}")

    print(f"\n{total} candidates vérifiées.")
    return 0


if __name__ == "__main__":
    sys.exit(main())
