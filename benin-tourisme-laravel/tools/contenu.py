#!/usr/bin/env python3
"""
Corrections éditoriales déclarées.

La conversion Mayotte → Bénin a remplacé les toponymes mais laissé des affirmations
qui ne tiennent plus. La plus visible occupe le chapô de 49 pages :

    « Le Réserve de biosphère du Bénin est le premier parc de France.
      D'une superficie de 68 400 km²… »

Trois erreurs en une phrase. Le Bénin n'est pas en France. Les 68 400 km² sont ceux
du parc naturel marin de **Mayotte** — la Pendjari fait environ 4 800 km², la part
béninoise du parc W environ 5 630. Et « Le Réserve » est fautif.

Ce chapô est un texte par défaut : le même sur les 49 pages, quel que soit leur
sujet. Il est remplacé par un texte vrai, de même longueur et de même forme, qui
dit le pays plutôt qu'un parc. **Chaque page mériterait le sien** ; c'est le travail
de relecture, page par page, que `tools/relecture.py` prépare.

Faits avancés dans le remplacement, tous vérifiables :
  · le Bénin s'étend du golfe de Guinée à la chaîne de l'Atacora ;
  · les palais royaux d'Abomey sont inscrits au patrimoine mondial depuis 1985 ;
  · Ganvié est une cité lacustre du lac Nokoué ;
  · le complexe W-Arly-Pendjari, partagé avec le Burkina Faso et le Niger, est
    inscrit au patrimoine mondial depuis juillet 2017.

Le module porte aussi les corrections **factuelles** : l'adresse de l'agence, que
les mentions légales donnaient à Mamoudzou avec un indicatif de Mayotte, et les
coordonnées des trois fiches, qui plaçaient les établissements dans l'océan Indien.

Toutes les mentions de la France ne sont pas des scories, et celles qui restent sont
volontaires : le français **est** la langue officielle du Bénin, France Diplomatie
**est** la source des avis aux voyageurs cités, et la conquête de 1892 a eu lieu.
Les retirer rendrait le contenu faux.

Comme les autres transformations du projet, celles-ci s'appliquent **aux deux côtés
de la comparaison** : le contrôle des 89 pages reste strict sur tout le reste.

    python3 tools/contenu.py     # les cas de test
"""

from __future__ import annotations

import json
import re
import sys

# Le chapô fautif et son remplacement, en texte nu. Le miroir les décline sous trois
# formes — enrichie de <strong> dans la page, nue dans la méta-description, échappée
# dans le balisage schema.org — que ce module engendre plutôt que de les recopier.
FAUTIF = (
    "Le Réserve de biosphère du Bénin est le premier parc de France. "
    "D’une superficie de 68 400 km2, il est riche de sa biodiversité et de son "
    "écosystème. Surtout, cet espace est l’un des trésors du pays, qu’il faut protéger."
)

CORRIGÉ = (
    "Le Bénin s’étire du golfe de Guinée aux collines de l’Atacora. "
    "Palais royaux d’Abomey, cité lacustre de Ganvié, parcs du Nord inscrits au "
    "patrimoine mondial : le pays réunit sur un territoire étroit une diversité "
    "rare en Afrique de l’Ouest."
)

# Mots mis en gras dans la page, dans l'ordre où ils y apparaissent.
GRAS_FAUTIF = ["Réserve de biosphère du Bénin", "France", "biodiversité", "écosystème", "trésors"]
GRAS_CORRIGÉ = ["Bénin", "Atacora", "patrimoine mondial", "diversité"]

# La méta-description et le balisage schema.org s'arrêtent à la deuxième phrase.
TRONQUÉ_FAUTIF = FAUTIF[: FAUTIF.index("écosystème.") + len("écosystème.")]
TRONQUÉ_CORRIGÉ = CORRIGÉ[: CORRIGÉ.index("patrimoine mondial") + len("patrimoine mondial")] + "."


def enrichis(texte: str, gras: list[str]) -> str:
    """Rend la forme affichée dans la page, avec ses mots en gras."""
    for mot in gras:
        texte = texte.replace(mot, f"<strong>{mot}</strong>", 1)
    return f"<p>{texte}</p>"


def échappe(texte: str) -> str:
    """Rend une chaîne utilisable dans un bloc JSON, accents échappés."""
    return json.dumps(texte, ensure_ascii=True)[1:-1]


# L'adresse de l'éditeur du portail (Dahomey Discovery), relevée sur dahomeydiscovery.com.
# Le miroir portait celle de Mamoudzou (Mayotte) puis, dans une itération intermédiaire,
# une adresse fictive ANPT à Zongo. Elle est remplacée par l'adresse réelle de l'agence
# Dahomey Discovery à Cotonou — c'est l'entreprise qui édite ce site.
ADRESSE_FAUTIVE = (
    "<p>Front de mer<br />BP 1169<br />97 600 MAMOUDZOU<br />BÉNIN<br />"
    "Tel : 02 69 61 09 09<br />Contact : "
)
ADRESSE_CORRIGÉE = (
    "<p>Rond-point Akpakpa, Ciné Concorde<br />COTONOU<br />BÉNIN<br />"
    "Tél : +229 01 67 11 02 20<br />Contact : "
)

# Coordonnées des trois fiches. Elles pointaient toutes vers Mayotte — océan Indien,
# cinq mille kilomètres du Bénin. Remplacées par celles de la ville que la fiche
# nomme, relevées sur OpenStreetMap.
COORDONNÉES = [
    (('-12.7313', '45.19462'), ('6.3677', '2.4253'), "hôtel du lac Nokoué → Cotonou"),
    (('-12.8622222', '45.11'), ('7.1820', '1.9936'), "maquis royal → Abomey"),
    (('-12.926757', '45.101265'), ('10.3052', '1.3809'), "safari tours → Natitingou"),
]

# Adresses d'établissements mahorais restées dans le balisage schema.org.
LIENS_MAHORAIS = [
    ("hotel-trevani-koungou-fr-3332266", "hotel-du-lac-nokoue-cotonou-bj-3332266"),
    ("le-chissioua-restaurant-sada-fr-3329842", "le-maquis-royal-abomey-bj-3329842"),
    ("abalone-plongee-boueni-fr-3313618", "pendjari-safari-tours-natitingou-bj-3313618"),
]

# Coordonnées portées en attributs par le composant de carte, en plus du balisage
# schema.org. Les établissements des fiches sont fictifs ; on place le repère sur la
# ville que leur titre nomme, faute de mieux — c'est déjà cinq mille kilomètres plus
# juste que l'océan Indien.
REPÈRES = [
    ("latitude=-12.7313\n                longitude=45.19462",
     "latitude=6.3677\n                longitude=2.4253", "repère → Cotonou"),
    ("latitude=-12.8622222\n                longitude=45.11",
     "latitude=7.1820\n                longitude=1.9936", "repère → Abomey"),
    ("latitude=-12.926757\n                longitude=45.101265",
     "latitude=10.3052\n                longitude=1.3809", "repère → Natitingou"),
]

# Traces de l'établissement mahorais dont chaque fiche est issue : sa page Facebook,
# son site, son code postal. Elles sont retirées plutôt que remplacées — inventer un
# compte ou un site à un établissement fictif serait pire que de n'en pas mettre.
TRACES = [
    ('<a class="social-icon" href=https://www.facebook.com/HotelLeTrevani/ '
     'target="_blank" rel="noindex,nofollow"></a>', "", "page Facebook mahoraise"),
    ('<a data-type="URL" class=\'nowrap\' href="https://www.abaloneplongee.com/" '
     'target="_blank">www.abaloneplongee.com</a>', "", "site mahorais"),
    ("MARCHE DE MAMOUDZOU", "MARCHÉ DE COTONOU", "titre d'image mahorais"),
    ('"streetAddress": "BP 245Plage de Tr\\u00e9vani"',
     '"streetAddress": "BP 245, plage d\'Avl\\u00e9k\\u00e9t\\u00e9"',
     "adresse mahoraise dans le balisage schema.org"),
    ('"postalCode": "97690",\n        ', "", "code postal de Mayotte, balisage"),
]

# Passages entièrement hérités de Mayotte : ils ne disent rien de vrai sur le Bénin.
# Ils sont retirés, non réécrits — supprimer une fausseté ne demande d'inventer rien,
# alors qu'y substituer un texte demanderait des sources.
PASSAGES_FAUX = [
    ("<p><strong>Cotonou</strong> et <strong>Dembéni</strong> abritent elles aussi des "
     "vestiges de l’<strong>histoire du Bénin</strong>. Vous y trouvez notamment les "
     "traces d’<strong>usines sucrières</strong>, mais aussi le <strong>tombeau du "
     "sultan Andriantsouli</strong>. C’est ce <strong>sultan</strong> qui fît de "
     "<strong>Bénin</strong> un <strong>territoire français</strong>.</p>", "",
     "Dembéni, les usines sucrières et le sultan de Mayotte"),
    ("<p>En dehors de son minaret, la <strong>Mosquée de Bohicon</strong> est la plus "
     "vieille <strong>mosquée</strong> en activité en France. Sa splendeur a fait d’elle "
     "la <strong>mosquée royale</strong> à l’époque où<strong>\xa0Bohicon</strong> était "
     "la capitale de <strong>Bénin</strong>.</p>", "",
     "la plus vieille mosquée de France, à Bohicon"),
]

# Toponymes mahorais restés dans du texte par ailleurs juste. La retouche est
# minimale : on retire le nom, on ne réécrit pas la phrase.
TOPONYMES = [
    ("STAR PIZZA Petite-terre", "STAR PIZZA", "nom d'établissement"),
    # La vignette montre en réalité des danseurs béninois : seule sa légende parlait
    # d'une pêche mahoraise. On lui rend la légende de sa propre photographie.
    ("La pêche au Djarifa est une coutume ancestrale du Bénin, transmise de mère en fille",
     "Danseurs traditionnels béninois", "légende d'une vignette"),
    ("se cantonnent à la Petite-terre et Cotonou", "se cantonnent à Cotonou",
     "les taxis urbains desservaient la Petite-Terre"),
    # « Communauté d'agglomération » est une forme administrative française, et
    # Dembéni est à Mayotte. La page emploie ailleurs « Le Grand Cotonou » : on
    # reprend sa propre formulation plutôt que d'en inventer une.
    ("c’est à la <strong>communauté d’agglomération de Dembéni-Cotonou</strong> que vous "
     "trouvez", "c’est au <strong>Grand Cotonou</strong> que vous trouvez",
     "instances administratives de la communauté d'agglomération"),
    ("Entre <strong>Cotonou</strong> et <strong>Dembéni</strong>, le",
     "Entre <strong>Cotonou</strong> et <strong>Porto-Novo</strong>, le",
     "Dembéni, dans un couple de villes"),
    ("La <strong>Communauté d’Agglomération</strong> de <strong>Dembéni-Cotonou</strong> "
     "est le cœur du Bénin.", "Le <strong>Grand Cotonou</strong> est le cœur "
     "économique du Bénin.", "communauté d'agglomération Dembéni-Cotonou"),
]

# Contresens géographiques sur les pages du Nord — l'Atacora, à l'intérieur du
# pays, n'a ni littoral, ni mangrove, ni îlot. Les phrases par ailleurs justes où
# seul un toponyme est fautif sont corrigées ici en substitution littérale ; les
# blocs entièrement mahorais sont retirés plus bas (BLOCS_HÉRITAGE).
NORD_META = (
    'ses baies et ses \\u00eelots sont un panel de biodiversit\\u00e9',
    'ses savanes et ses parcs sont un panel de biodiversit\\u00e9',
    "meta description du Nord : baies et îlots → savanes et parcs",
)
NORD_MANGROVES = (
    'Les <strong>mangroves</strong>, dont celles de <strong>Dzoumogné</strong> '
    'et <strong>Bouyoni</strong>, sont le refuge de nombreuses espèces du pays.',
    'Les <strong>réserves forestières</strong>, notamment la forêt classée de '
    '<strong>Wari-Maro</strong> et le parc de la <strong>Pendjari</strong>, '
    'sont le refuge de nombreuses espèces du pays.',
    "mangroves du Nord (Dzoumogné, Bouyoni) → réserves forestières béninoises (Wari-Maro, Pendjari)",
)
NORD_VILLES = (
    '<strong>Handréma</strong>, <strong>Parakou</strong>, <strong>Djougou</strong> '
    'ou encore <strong>Mtsangadoua</strong> et <strong>Mtsangaboua</strong>',
    '<strong>Parakou</strong>, <strong>Djougou</strong>, <strong>Boukombé</strong> '
    'ou encore <strong>Natitingou</strong> et <strong>Kandi</strong>',
    "liste de villes du Nord : Handréma/Mtsangadoua/Mtsangaboua → Boukombé/Natitingou/Kandi",
)
NORD_TITRE = (
    'Vers l\'infini des mangroves...',
    'Vers l\'infini des paysages...',
    "titre de section : « mangroves » → « paysages » (le Nord est enclavé)",
)
NORD_LITTORAL_PHRASE = (
    ', donnant sur le littoral',
    '',
    "retrait de la mention « donnant sur le littoral » (le Nord n'y donne pas)",
)
NORD_CTA_MANGROVE = (
    'Explorez la mangrove',
    'Explorez le Nord',
    "CTA « Explorez la mangrove » → « Explorez le Nord »",
)

CONTRESENS_DU_NORD = [
    NORD_META, NORD_MANGROVES, NORD_VILLES,
    NORD_TITRE, NORD_LITTORAL_PHRASE, NORD_CTA_MANGROVE,
]


# Contresens géologique et géographique sur la page « Les Collines » (le Centre
# Mystique). Le Bénin n'est pas volcanique — Mayotte l'est. Les Collines de
# Dassa sont un massif granitique cristallin. Et l'idée d'une « côte est »
# protégée par le Conservatoire du Littoral, avec des plages nommées Badamiers
# ou Papani, ne peut désigner qu'un archipel océanique, pas les Collines.
CENTRE_VOLCANIQUE = (
    'volcanique</strong> de <strong>Bénin</strong>',
    'granitique</strong> du <strong>Bénin</strong>',
    "les Collines : « volcanique » → « granitique » (le Bénin est cristallin)",
)
CENTRE_COTE = (
    '<p>Quant à sa côte est, protégée par le <strong>Conservatoire du Littoral'
    '</strong>, et offre de nombreux points de vue, en pleine nature. Les plages '
    'ne sont pas non plus en reste : Petit Djègbadji, Papani, Poudjou ou les '
    'Badamiers sont autant de spots à votre disposition pour un moment de '
    'détente.</p>',
    '<p>Autour de <strong>Dassa-Zoumè</strong>, les <strong>rochers de granit'
    '</strong> sculptent le paysage : les <strong>sept collines</strong> qui '
    'donnent leur nom au département, la <strong>grotte mariale d\'Arigbo'
    '</strong> — plus grand lieu de pèlerinage catholique du pays — et les '
    '<strong>grottes de Ouessè</strong>. Autant de points de vue pour une '
    'randonnée de quelques heures, en pleine nature, à quatre-vingts kilomètres '
    'de Bohicon.</p>',
    "Collines : paragraphe « côte est, plages, Badamiers » → rochers, sanctuaire d'Arigbo, grottes de Ouessè",
)

CENTRE_PANORAMA = (
    'Vos yeux se régalent du <a href="/explorez/naturellement-sauvage/les-incontournables" '
    'target="_blank" rel="noopener">lac Nokoué</a>, et vos oreilles des <em><strong>'
    'Halé Halélé</strong></em>, les légendes locales, jusqu’au <strong>point de vue de la '
    'Vigie</strong>, culminant à 202 mètres.',
    'Vos yeux se régalent des <strong>sept collines de Dassa</strong>, et vos oreilles '
    'des <strong>récits idaasha</strong>, transmis par les anciens, jusqu’au <strong>'
    'sommet d’Ita-Djèbou</strong>, à cinq cents mètres.',
    "panorama des Collines : lac Nokoué (300 km au sud) + Halé Halélé + Vigie mahoraise "
    "→ collines de Dassa + récits idaasha + Ita-Djèbou",
)
CENTRE_VASIERE = (
    'Après avoir eu les pieds sur terre, vous pouvez arpenter un petit sentier, découvert '
    'à marée basse, pour vous rendre jusqu’à la <strong>vasière des Badamiers</strong>, '
    'les pieds dans l’eau.',
    'Après une pause à l’ombre d’un baobab, poursuivez jusqu’à la <strong>grotte mariale '
    'd’Arigbo</strong>, sanctuaire creusé dans le granit qui attire chaque août des '
    'dizaines de milliers de pèlerins.',
    "vasière des Badamiers (marée, Mayotte) → grotte mariale d'Arigbo (Dassa-Zoumè)",
)
CENTRE_ACTIVITES = (
    '<strong>Safari</strong>, <strong>sorties en circuit mémoriel,</strong> ou encore '
    'simplement <strong>sorties en bateau</strong>… Suivez les guides, ils ont tout à '
    'vous apprendre !',
    'escalade sur les rochers, traversées du massif à pied, visites guidées des '
    '<strong>grottes de Ouessè</strong>… Suivez les guides, ils ont tout à vous '
    'apprendre !',
    "activités : safari + bateau → escalade rochers, traversées massif, grottes Ouessè",
)

CONTRESENS_DU_CENTRE = [CENTRE_VOLCANIQUE, CENTRE_COTE, CENTRE_PANORAMA, CENTRE_VASIERE, CENTRE_ACTIVITES]


# Nom d'affichage du site. Le miroir portait « Bénin tourisme », qui reprenait le
# nom d'origine (« Mayotte tourisme »). Le projet le remplace par « Bénin
# Forever ». Deux règles :
#   · seule la forme à « t » minuscule est visée — la forme « Bénin Tourisme »
#     (T majuscule) désigne Dahomey Discovery, l'Agence Nationale du Tourisme, entité
#     officielle qu'on n'a pas à renommer ;
#   · le remplacement est appliqué sous ses trois formes utiles : texte nu,
#     JSON échappé (« Bénin tourisme »), et un slug en URL n'est pas touché
#     (l'archive et les chemins conservent leur identifiant).
NOM_DU_SITE = [
    ('Bénin tourisme', 'Bonne Arrivée', 'nom du site en texte'),
    ('B\\u00e9nin tourisme', 'Bonne Arriv\\u00e9e', 'nom du site en JSON échappé'),
    # Résidus d'un renommage transitoire par « Bénin Forever ». On les rattrape
    # ici pour rester idempotent si le pipeline est ré-appliqué sur un miroir
    # partiellement traité.
    ('Bénin Forever', 'Bonne Arrivée', 'résidu transitoire Bénin Forever'),
    ('BÉNIN FOREVER', 'BONNE ARRIVÉE', 'résidu transitoire majuscules'),
    ('BENIN FOREVER', 'BONNE ARRIVÉE', 'résidu transitoire majuscules sans accent'),
    ('B\\u00e9nin Forever', 'Bonne Arriv\\u00e9e', 'résidu transitoire JSON échappé'),
    ('#beninforever', '#bonnearrivee', 'hashtag socialwall'),
]


# Blocs entiers hérités de Mayotte, décrits par leur classe englobante et un
# marqueur textuel unique. Le bloc est retiré depuis son <div>/<section> ouvrant
# le plus proche qui porte la classe indiquée, jusqu'à sa balise fermante
# équilibrée. Motifs suffisamment spécifiques (mot mahorais unique) pour ne pas
# happer par erreur un bloc semblable sur une autre page.
from contenu_remplacements import REMPLACEMENTS_BLOCS_HÉRITAGE as BLOCS_HÉRITAGE  # noqa: E402


def substitue_bloc_équilibré(html: str, class_hint: str, marker: str,
                             remplacement: str = "") -> tuple[str, int]:
    """Remplace le <div>/<section> le plus proche qui contient marker et dont la
    classe porte class_hint. Si remplacement est vide, le bloc est retiré ; sinon
    il est remplacé par remplacement. La fermeture se calcule par équilibre des
    balises. Chaque occurrence du marker est traitée, tant qu'un container
    convenable est trouvé.
    """
    balise = re.compile(r'<(/?)(div|section)\b([^>]*)>')
    classe = re.compile(r'class="([^"]*)"')
    compte = 0
    curseur = 0
    while True:
        pos = html.find(marker, curseur)
        if pos < 0:
            break
        # Balises jusqu'à pos ; pile des ouvertures en pilant les fermetures.
        stack: list[tuple[int, int, str]] = []
        for m in balise.finditer(html[:pos + 1]):
            if m.group(1) == '/':
                if stack:
                    stack.pop()
            else:
                cls_m = classe.search(m.group(3))
                stack.append((m.start(), m.end(), cls_m.group(1) if cls_m else ""))
        # Le parent le plus proche qui porte class_hint dans sa classe.
        début = fin_ouverture = None
        for st_start, st_end, cls in reversed(stack):
            if class_hint in cls:
                début, fin_ouverture = st_start, st_end
                break
        if début is None:
            # Marqueur présent mais pas de container attendu : avancer pour éviter
            # de boucler sur un marqueur qui reste après remplacement.
            curseur = pos + len(marker)
            continue
        # Descendre pour trouver la fermeture équilibrée.
        profondeur = 1
        fin = None
        for m in balise.finditer(html[fin_ouverture:]):
            profondeur += -1 if m.group(1) == '/' else 1
            if profondeur == 0:
                fin = fin_ouverture + m.end()
                break
        if fin is None:
            break
        html = html[:début] + remplacement + html[fin:]
        compte += 1
        # Reprend après le remplacement pour éviter une boucle infinie si le
        # remplacement contient encore le marker.
        curseur = début + len(remplacement)
    return html, compte


# Rétrocompatibilité : le nom d'origine reste, se contente d'un remplacement vide.
def retire_bloc_équilibré(html: str, class_hint: str, marker: str) -> tuple[str, int]:
    return substitue_bloc_équilibré(html, class_hint, marker, "")


def retire_les_blocs_hérités(html: str) -> tuple[str, int]:
    """Applique tous les retraits/substitutions de BLOCS_HÉRITAGE."""
    total = 0
    for entrée in BLOCS_HÉRITAGE:
        # Compatibilité : 3-tuple (retrait) ou 4-tuple (substitution)
        if len(entrée) == 3:
            class_hint, marker, _raison = entrée
            remplacement = ""
        else:
            class_hint, marker, _raison, remplacement = entrée
        html, n = substitue_bloc_équilibré(html, class_hint, marker, remplacement)
        total += n
    return html, total


# Blocs entiers hérités de Mayotte, décrits par leurs bornes plutôt que recopiés.
# Ils sont retirés, non réécrits : supprimer une fausseté ne demande d'inventer rien,
# alors qu'y substituer un texte demanderait des sources sur le Bénin.
BLOCS_FAUX = [
    (re.compile(r'<div class="generic-block-texts has-title"><h2[^>]*>La pêche au djarifa'
                r'</h2>.*?h[ée]ritage</strong>\.</p>', re.S),
     "la pêche au djarifa — pratique mahoraise, sur une page du Nord béninois, "
     "qui n'a pas de littoral"),
    (re.compile(r'<p class="h2">Carte d’identité</p><ul>(?:(?!</ul>).)*?'
                r'(?:Dzaoudzi|Dembéni|Mamoudzou|Petite-Terre|Bandrélé|Koungou|Sada)'
                r'(?:(?!</ul>).)*?</ul>', re.S),
     "cartes d'identité chiffrées sur Mayotte : 10,95 km² pour Petite-Terre, "
     "80,68 km² pour Mamoudzou-Dembéni"),
    (re.compile(r'Passage obligé de votre séjour, la communauté d’agglomération '
                r'<strong>\s*Dembéni-Cotonou</strong> allie affaires et détentes…\s*', re.S),
     "la « communauté d'agglomération Dembéni-Cotonou » — Dembéni est à Mayotte, et "
     "cette forme administrative n'existe pas au Bénin"),
    (re.compile(r'<p>Du côté de <strong>Cotonou</strong>, rendez-vous au <strong>jardin '
                r'botanique de la pointe Mahabou</strong>.*?</p>', re.S),
     "le jardin botanique de la pointe Mahabou, le tombeau du sultan et la plage d'Iloni"),
]


def retire_les_blocs_faux(html: str) -> tuple[str, int]:
    """Retire les blocs que la conversion a laissés tels quels."""
    retirés = 0
    for motif, _raison in BLOCS_FAUX:
        html, n = motif.subn("", html)
        retirés += n
    return html, retirés


# Formes littérales : la page, sa méta-description, l'adresse, les liens.
CORRECTIONS = [
    (enrichis(FAUTIF, GRAS_FAUTIF), enrichis(CORRIGÉ, GRAS_CORRIGÉ), "chapô de la page"),
    (TRONQUÉ_FAUTIF, TRONQUÉ_CORRIGÉ, "méta-description"),
    (ADRESSE_FAUTIVE, ADRESSE_CORRIGÉE, "adresse de l'agence"),
    *[(ancien, nouveau, "lien d'établissement") for ancien, nouveau in LIENS_MAHORAIS],
    *[(f'"latitude": "{a}",\n        "longitude": "{b}"',
       f'"latitude": "{c}",\n        "longitude": "{d}"', libellé)
      for (a, b), (c, d), libellé in COORDONNÉES],
    *REPÈRES,
    *TRACES,
    *PASSAGES_FAUX,
    *TOPONYMES,
    *CONTRESENS_DU_NORD,
    *CONTRESENS_DU_CENTRE,
    *NOM_DU_SITE,
]

# Le balisage schema.org, lui, n'échappe qu'une partie de ses accents — « Bénin »
# reste lisible quand « écosystème » devient « \u00e9cosyst\u00e8me ». Reproduire
# cette incohérence serait fragile : on vise la description par son motif.
DESCRIPTION_JSON = re.compile(
    r'("description":")((?:[^"\\]|\\.)*premier parc de France(?:[^"\\]|\\.)*)(")'
)


# Villes que les trois fiches nomment, et leurs coordonnées relevées sur
# OpenStreetMap. Les établissements sont fictifs : le repère se pose sur la ville.
VILLES_DES_FICHES = {
    "offres/hotel-du-lac-nokoue-cotonou-bj-3332266/index.html": ("6.3677", "2.4253"),
    "offres/le-maquis-royal-abomey-bj-3329842/index.html": ("7.1820", "1.9936"),
    "offres/pendjari-safari-tours-natitingou-bj-3313618/index.html": ("10.3052", "1.3809"),
}

# Coordonnées de contact des établissements mahorais dont les fiches sont issues.
# 0269 et 0639 sont les préfixes de Mayotte, 976xx ses codes postaux. Ce sont les
# numéros de vrais commerces : on les retire, on n'en invente pas d'autres.
TÉLÉPHONE_MAHORAIS = re.compile(r'"0(?:269|639)[\s\d]{8,14}",?\s*')
TÉLÉPHONE_AFFICHÉ = re.compile(r'\b0(?:269|639)(?:[\s.]?\d{2}){3}\b')
CODE_POSTAL_MAHORAIS = re.compile(r'\s*"postalCode":\s*"976\d\d",?')
CODE_POSTAL_AFFICHÉ = re.compile(r'<span>976\d\d</span>')
GÉO_JSON = re.compile(r'("latitude":\s*")(-?[\d.]+)("[,\s\n]*"longitude":\s*")([\d.]+)(")')

# Vignettes que le thème déclare dans ses métadonnées. Elles pointent vers des
# fichiers que le miroir n'a jamais rapatriés — toutes répondent 404 — et portent
# les noms d'origine, « mayott-ngouja-palétuvier » compris. On vide la valeur
# plutôt que de la remplacer : ce qu'elle désignait n'existe pas.
VIGNETTE_MORTE = re.compile(r'("post_image":")[^"]*uploads[^"]*(")')

# Mêmes fichiers, cités cette fois dans les tableaux « image » du balisage
# schema.org — celui que les moteurs lisent. Une adresse morte au nom mahorais y
# vaut moins que pas d'adresse du tout : on retire l'entrée.
IMAGE_MORTE = re.compile(r'"\\?/assets\\?/woody\.cloudly\.space\\?/app\\?/uploads[^"]*",?')


def corrige_les_fiches(html: str, relative: str) -> tuple[str, int]:
    """Rend aux trois fiches des coordonnées béninoises, et leur retire celles d'autrui."""
    if relative not in VILLES_DES_FICHES:
        return html, 0

    latitude, longitude = VILLES_DES_FICHES[relative]
    corrigés = 0

    html, n = GÉO_JSON.subn(
        lambda m: m.group(1) + latitude + m.group(3) + longitude + m.group(5), html
    )
    corrigés += n

    for motif in (TÉLÉPHONE_MAHORAIS, CODE_POSTAL_MAHORAIS,
                  TÉLÉPHONE_AFFICHÉ, CODE_POSTAL_AFFICHÉ):
        html, n = motif.subn("", html)
        corrigés += n

    return html, corrigés


def corrige_le_contenu(html: str) -> tuple[str, int]:
    """Applique les corrections éditoriales déclarées, sous toutes leurs formes."""
    corrigés = 0
    for fautif, corrigé, _libellé in CORRECTIONS:
        occurrences = html.count(fautif)
        if occurrences:
            html = html.replace(fautif, corrigé)
            corrigés += occurrences

    html, n = DESCRIPTION_JSON.subn(
        lambda m: m.group(1) + échappe(TRONQUÉ_CORRIGÉ) + m.group(3), html
    )
    html, blocs = retire_les_blocs_faux(html)
    html, hérités = retire_les_blocs_hérités(html)
    html, vignettes = VIGNETTE_MORTE.subn(r"\1\2", html)
    html, images = IMAGE_MORTE.subn("", html)
    return html, corrigés + n + blocs + hérités + vignettes + images


CAS_DE_TEST = [
    (f"<div>{enrichis(FAUTIF, GRAS_FAUTIF)}</div>", 1, "chapô de la page"),
    ('"description":"Le Réserve de biosphère du Bénin est le premier parc de France. '
     'D\\u2019une superficie de 68 400 km2, il est riche de sa biodiversit\\u00e9."', 1,
     "balisage schema.org, à l'échappement partiel"),
    (f'<meta name="description" content="{TRONQUÉ_FAUTIF}">', 1, "méta-description"),
    (f"<div>{enrichis(CORRIGÉ, GRAS_CORRIGÉ)}</div>", 0, "chapô déjà correct, laissé tel quel"),
    ("<div><p>Un autre texte.</p></div>", 0, "texte sans rapport, intact"),
    ('<meta content="ses baies et ses \\u00eelots sont un panel de biodiversit\\u00e9"/>',
     1, "meta description du Nord : baies et îlots → savanes et parcs"),
    ('<div class="woody-component woody-component-feature tpl_01">'
     '<span>Les oranges de M\'tsamboro sont réputées.</span></div>',
     1, "bloc feature entier retiré autour de « oranges de M\\'tsamboro »"),
    ('<div class="page-section page-section-3" id="pageSection-3">'
     '<h2>Les îlots du Nord</h2><p>Faux.</p></div>',
     1, "page-section-3 retirée pour « Les îlots du Nord »"),
]


def main() -> int:
    échecs = 0
    for source, attendu, libellé in CAS_DE_TEST:
        résultat, corrigés = corrige_le_contenu(source)
        conforme = corrigés == attendu and "premier parc de" not in résultat
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
