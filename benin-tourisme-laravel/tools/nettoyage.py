#!/usr/bin/env python3
"""
Retrait des services distants inaccessibles, et des identifiants qui les accompagnent.

Le site d'origine s'appuyait sur trois services extérieurs. Vérifié le 25 août 2026 :

    api.tourism-system.com    base touristique professionnelle française. Le point
                              d'entrée du site répond 404. Même accessible, elle
                              n'indexe que les données des offices de tourisme
                              français : rien du Bénin. Inutilisable, ici.
    touristicmaps.com         fonds de carte du même éditeur. Les tuiles répondent
                              404 sans la clé, que le site n'a pas.
    maps.googleapis.com       fonctionne parfaitement au Bénin — la clé du miroir
                              géocode Cotonou sans broncher. Mais cette clé
                              appartient à l'éditeur du site d'origine et elle est
                              active : chaque chargement de page lui est facturé.

Les deux premiers sont retirés parce qu'ils sont morts. Le troisième est retiré
parce que sa clé n'est pas la nôtre ; l'intégration se rétablira en une ligne avec
une clé du projet.

Les pages de liste — hôtels, restauration, opérateurs de safari, séjour sur le lac,
safari et randonnée — perdent leur bloc de résultats et gardent leur couverture,
leur titre, leur introduction et leurs cartes, en attendant que le back-office
devienne la source.

**Cette transformation s'applique aux deux côtés de la comparaison.** `convert.py`
la passe sur le miroir avant d'écrire les vues, `compare.py` la passe sur le miroir
avant de comparer : le contrôle reste donc strict sur tout le reste.
"""

from __future__ import annotations

import re

# Services dont le point d'entrée ne répond plus pour ce site.
HÔTES_MORTS = (
    "api.tourism-system.com",
    "medias.tourism-system.com",
    "api.touristicmaps.com",
    "tiles.touristicmaps.com",
)

# Service vivant, mais dont la clé appartient à l'éditeur du site d'origine.
HÔTE_EMPRUNTÉ = "maps.googleapis.com"

# Bloc de résultats alimenté par l'API morte.
LISTING = "page-playlist-wrapper"

# Bloc de configuration qui porte le mot de passe de l'API et son jeton.
IDENTIFIANTS = re.compile(
    r'<script id="hawwwai_angular_config_footer-js-after">.*?</script>', re.S
)

# Charge utile de la fiche, telle que l'API la livrait : nom, notes, coordonnées,
# code postal, réseaux sociaux. Les trois fiches du site portent encore celle de
# l'établissement mahorais dont elles sont issues — « HÔTEL TRÉVANI », code postal
# 97690, page Facebook comprise. Le service ne répond plus ; ces données n'ont plus
# d'usage et ne disent que le mauvais pays.
FICHE_DE_L_API = re.compile(r'<script type="text/javascript">\s*var Sheet = \{.*?</script>', re.S)


def bloc_équilibré(html: str, ouverture: re.Pattern) -> tuple[int, int] | None:
    """Les bornes du premier <div> équilibré ouvert par ce motif."""
    début = ouverture.search(html)
    if début is None:
        return None
    profondeur = 1
    for balise in re.finditer(r"<div\b|</div>", html[début.end():]):
        profondeur += 1 if balise.group(0) == "<div" else -1
        if profondeur == 0:
            return début.start(), début.end() + balise.end()
    return None


LISTING_OUVERTURE = re.compile(rf'<div class="{LISTING}"[^>]*>')


def vide_les_listes(html: str) -> tuple[str, int]:
    """Retire le bloc de résultats des pages de liste."""
    retirés = 0
    while (bornes := bloc_équilibré(html, LISTING_OUVERTURE)) is not None:
        début, fin = bornes
        retirés += fin - début
        html = html[:début] + html[fin:]
    return html, retirés


def retire_les_appels_morts(html: str) -> tuple[str, int]:
    """Retire les <script> et <link> qui pointent vers un service inaccessible."""
    hôtes = "|".join(re.escape(h) for h in (*HÔTES_MORTS, HÔTE_EMPRUNTÉ))
    balise = re.compile(rf'<script\b[^>]*(?:{hôtes})[^>]*>\s*</script>|<link\b[^>]*(?:{hôtes})[^>]*>')
    html, n = balise.subn("", html)
    return html, n


def retire_les_identifiants(html: str) -> tuple[str, int]:
    """Retire le bloc de configuration qui porte le mot de passe de l'API."""
    return IDENTIFIANTS.subn("", html)


def retire_les_fiches_de_l_api(html: str) -> tuple[str, int]:
    """Retire la charge utile que l'API livrait pour chaque fiche."""
    return FICHE_DE_L_API.subn("", html)


# Photographies servies par le même service mort. Leurs adresses portent les noms
# des établissements mahorais d'origine ; elles ne chargent rien.
IMAGE_DE_L_API = re.compile(r'"https?:\\?/\\?/medias\.tourism-system\.com[^"]*",?')


def retire_les_images_de_l_api(html: str) -> tuple[str, int]:
    """Retire les adresses d'images servies par le service mort."""
    return IMAGE_DE_L_API.subn("", html)


# Le retrait des <script src=…> ci-dessus supprime les APPELS aux services morts,
# mais leurs URLs traînent encore dans les charges de configuration en JSON
# (window.siteConfig, WoodyGlobals). Elles sont lues par le JS à la demande —
# quand un visiteur ouvre la recherche ou une carte. Elles déclenchent alors un
# appel 404 et laissent transparaître, dans la clé `login`, un identifiant qui
# appartient à l'éditeur d'origine. On vide les valeurs sans toucher aux clés :
# la structure JSON reste valide, le JS lit une chaîne vide et se tait.
URLS_DE_CONFIG_MORTES = (
    # window.siteConfig.{api_url,api_render_url}
    re.compile(r'("api(?:_render)?_url"\s*:\s*)"[^"]*api\.tourism-system\.com[^"]*"'),
    # WoodyGlobals ... "search":{"api":{"uri":"…tourism-system.com…"}}
    re.compile(r'("uri"\s*:\s*)"[^"]*api\.tourism-system\.com[^"]*"'),
    # WoodyGlobals ... "tmaps":{"url_api":"…", "url_api_lazy":"…"}
    re.compile(r'("url_api(?:_lazy)?"\s*:\s*)"[^"]*touristicmaps\.com[^"]*"'),
)
# Identifiant de compte hérité de l'éditeur d'origine, présent dans siteConfig.
IDENTIFIANT_HÉRITÉ = re.compile(r'("login"\s*:\s*)"public_[a-z0-9_]+"')


def vide_les_urls_de_config(html: str) -> tuple[str, int]:
    """Vide les URLs des services morts et l'identifiant hérité dans les charges JSON."""
    total = 0
    for motif in URLS_DE_CONFIG_MORTES:
        html, n = motif.subn(r'\1""', html)
        total += n
    html, n = IDENTIFIANT_HÉRITÉ.subn(r'\1""', html)
    total += n
    return html, total


def nettoie(html: str) -> tuple[str, dict]:
    """Applique les retraits et rend le compte de ce qui est parti."""
    html, octets = vide_les_listes(html)
    html, appels = retire_les_appels_morts(html)
    html, identifiants = retire_les_identifiants(html)
    avant = len(html)
    html, fiches = retire_les_fiches_de_l_api(html)
    html, images = retire_les_images_de_l_api(html)
    html, urls_config = vide_les_urls_de_config(html)
    fiches += images
    return html, {
        "octets_de_listes": octets + (avant - len(html)),
        "appels_morts": appels,
        "identifiants": identifiants + fiches + urls_config,
    }
