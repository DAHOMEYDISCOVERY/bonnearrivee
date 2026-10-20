"""
Contenus de remplacement pour les blocs mahorais retirés.

Chaque bloc retiré de la vue est remplacé par un bloc de même architecture (un
composant `woody-component`), rempli de contenu factuel béninois. Les faits
avancés sont vérifiables sur des sources publiques :

  · Palais royaux d'Abomey : UNESCO patrimoine mondial depuis 1985.
  · Complexe W-Arly-Pendjari : UNESCO depuis 2017, partagé avec Burkina Faso et Niger.
  · Chaîne de l'Atacora : massif du nord-ouest, ~400 km, sommet Mont Sokbaro (658 m).
  · Chutes de Tanougou : cascade permanente au pied de l'Atacora, près de Tanguiéta.
  · Tata somba : maisons-tours du pays Betammaribé (Boukombé), architecture UNESCO
    (inscrite au patrimoine mondial en 2004 côté Togo, en cours d'inscription au Bénin).
  · Anacarde : le Bénin est le 4ᵉ producteur africain (~180 000 t/an), zones de
    Bembèrèkè, Nikki, Parakou.

Les remplacements gardent la classe Woody `woody-component-custom-text tpl_02`,
qui est le composant texte le plus simple et polyvalent du thème — un h2 + p
dans un grid-container. Ils héritent ainsi de tout le style existant sans
introduire un nouveau composant à débuguer.
"""

# ------------------------------------------------------------------
# Grand Nord
# ------------------------------------------------------------------

G1_ATACORA = """<div class="woody-component woody-component-custom-text tpl_02 flex-container flex-dir-column align-center no-sessions" data-equalizer="data-equalizer"><header class="section-header-wrapper grid-container grid-default container-min"><div class="section-header text-center"><div class="section-titles flex-container flex-dir-column align-middle align-center text-center"><div class="section-texts has-title has-subtitle"><h2 class="section-title">La chaîne de l'Atacora</h2><span class="section-subtitle block-span">le toit du Bénin</span></div></div></div></header><div class="grid-container"><div class="grid-x grid-padding-x"><div class="cell"><p>La <strong>chaîne de l'Atacora</strong> court sur près de <strong>quatre cents kilomètres</strong> au nord-ouest du pays, du Togo au Burkina Faso. Ses crêtes gréseuses culminent au <strong>mont Sokbaro</strong>, à 658 mètres — plus haut sommet du Bénin.</p><p>Elle abrite les <strong>chutes de Tanougou</strong>, la <strong>Pendjari</strong> et sa faune, les <strong>tata somba</strong> des Betammaribé, et une mosaïque de villages qui vivent au rythme des cultures pluviales : mil, sorgho, igname, arachide. C'est la région du Bénin la plus verte en saison des pluies, la plus rude en saison sèche.</p></div></div></div></div>"""

G2_ANACARDE = """<div class="woody-component woody-component-custom-text tpl_02 bg-primary bg-gradient bg-filigrane isRel padd-all-md no-sessions" data-equalizer="data-equalizer"><div class="grid-container"><div class="grid-x grid-padding-x align-center"><div class="cell large-8 text-center"><h2 class="section-title">L'anacarde du Nord</h2><p>Le Bénin est le <strong>quatrième producteur africain d'anacarde</strong>, avec près de <strong>180 000 tonnes par an</strong>. La ceinture de production court de <strong>Bembèrèkè</strong> à <strong>Nikki</strong> en passant par <strong>Parakou</strong>. En avril, les vergers se couvrent de pommes rouges et jaunes ; sous chacune, la noix — celle qu'on rôtit, décortique, puis exporte vers l'Inde et le Vietnam.</p><p>La transformation commence à s'installer : coopératives à Tchaourou, Sinendé, Kalalé. Une visite dans une petite unité artisanale apprend en une heure ce qui distingue une noix de qualité, comment se casse une coque sans blesser l'amande, et pourquoi la couleur d'une pomme se lit comme sa saison.</p></div></div></div></div>"""

G3_CASCADES = """<div class="woody-component woody-component-custom-text tpl_02 bg-primary bg-gradient bg-filigrane isRel padd-all-md no-sessions" data-equalizer="data-equalizer"><div class="grid-container"><div class="grid-x grid-padding-x align-center"><div class="cell large-8 text-center"><h2 class="section-title">Les cascades pour se ressourcer</h2><p>Le Nord béninois est le pays des chutes d'eau. Les <strong>chutes de Tanougou</strong>, à quinze kilomètres de Tanguiéta, dévalent le grès de l'Atacora sur trente mètres et forment un bassin où l'on peut se baigner presque toute l'année. Les <strong>chutes de Kota</strong>, plus discrètes, se méritent après une marche d'une heure depuis Natitingou. Plus au sud, la <strong>Kaboua</strong> et le <strong>saut du Roc</strong>, près de Savalou, achèvent le circuit.</p><p>Toutes se visitent avec un guide local : la végétation cache les sentiers pendant la saison des pluies, et la profondeur des vasques change d'une année à l'autre.</p></div></div></div></div>"""

G4_TATA_SOMBA = """<div class="page-section page-section-3" id="pageSection-3"><section class="section bg-motif padd-top-md padd-bottom-md marg-top-none marg-bottom-none"><header class="section-header-wrapper grid-container grid-default container-min"><div class="section-header text-center"><div class="section-titles flex-container flex-dir-column align-middle align-center text-center"><div class="section-texts has-title has-subtitle"><h2 class="section-title">Les tata somba</h2><span class="section-subtitle block-span">forteresses de terre du pays Betammaribé</span></div></div></div></header><article class="section-layout-wrapper grid-container grid-default"><div class="woody-component woody-component-custom-text tpl_02"><div class="grid-x grid-padding-x align-center"><div class="cell large-10"><p>Autour de <strong>Boukombé</strong>, les <strong>tata somba</strong> se dressent seules dans la savane. Ces maisons-tours en banco, deux étages sous une terrasse de terre battue, sont l'œuvre des <strong>Betammaribé</strong>. Chaque tata abrite une famille — hommes et bétail au rez-de-chaussée, greniers et couchages à l'étage, autel des ancêtres à l'entrée.</p><p>L'architecture parle d'histoire : ce sont d'anciennes fortifications, adoptées quand les razzias esclavagistes du <strong>XVIIIᵉ siècle</strong> menaçaient la région. Elles se visitent avec un habitant, qui explique la répartition des pièces, la fabrication du banco et les rituels d'inauguration — chaque tata neuve est un événement dans le village. Le pays Betammaribé est <strong>inscrit au patrimoine mondial de l'UNESCO côté togolais</strong> depuis 2004 ; l'inscription du côté béninois est portée par les autorités.</p></div></div></div></article></section></div>"""

G5_FOCUS_PENDJARI = """<div class="page-section page-section-4" id="pageSection-4"><section class="section padd-top-md padd-bottom-md marg-top-lg marg-bottom-lg"><div class="woody-component woody-component-custom-text tpl_02 bg-primary bg-gradient isRel padd-all-md no-sessions"><div class="grid-container"><div class="grid-x grid-padding-x align-center align-middle"><div class="cell large-8 text-center"><span class="focus-block-pretitle block-span">Préparer mon excursion</span><h2 class="focus-block-title">Vers la Pendjari et le pays somba</h2><div class="focus-block-description"><p>Le <strong>parc national de la Pendjari</strong> et son voisin le <strong>parc du W</strong> forment, avec le Burkina Faso et le Niger, le <strong>complexe W-Arly-Pendjari</strong> — inscrit au patrimoine mondial de l'UNESCO en 2017. On y observe éléphants, buffles, hippotragues, cobes, et l'un des derniers noyaux de lions d'Afrique de l'Ouest.</p><p>Le <strong>pays Betammaribé</strong>, autour de Boukombé et Natitingou, complète l'itinéraire : villages de tata, marchés de Kouaba et Perma, chutes de Tanougou. Compter trois à cinq jours pour l'un et l'autre, un guide agréé, une saison sèche (novembre à mai).</p><p><a class="woody-component-button button primary normal" href="/explorez/naturellement-sauvage/le-parc-de-la-pendjari"><span class="button-label">Découvrir la Pendjari</span></a></p></div></div></div></div></div></section></div>"""

# ------------------------------------------------------------------
# Plateau Royal
# ------------------------------------------------------------------

P1_PALAIS = """<div class="woody-component woody-component-custom-text tpl_02 flex-container flex-dir-column align-center no-sessions" data-equalizer="data-equalizer"><header class="section-header-wrapper grid-container grid-default container-min"><div class="section-header text-center"><div class="section-titles flex-container flex-dir-column align-middle align-center text-center"><div class="section-texts has-title has-subtitle"><h2 class="section-title">Les palais royaux d'Abomey</h2><span class="section-subtitle block-span">deux siècles de royaume du Dahomey</span></div></div></div></header><div class="grid-container"><div class="grid-x grid-padding-x"><div class="cell"><p>Douze rois se sont succédé à <strong>Abomey</strong> entre 1625 et 1900, et chacun a fait bâtir son propre palais dans le même enclos royal. Ce sont ces <strong>palais royaux d'Abomey</strong> qui sont aujourd'hui <strong>inscrits au patrimoine mondial de l'UNESCO</strong> depuis 1985 — quarante-quatre hectares de murs en banco, de trônes, de bas-reliefs polychromes, et une part continue de la mémoire dahoméenne.</p><p>La visite s'articule autour des palais du roi <strong>Ghezo</strong> et du roi <strong>Glélé</strong>. On y lit, panneau par panneau, la manière dont le royaume racontait sa propre histoire : victoires militaires, alliances, insignes de fonction. Le trône du roi Ghezo, monté sur quatre crânes ennemis, y est toujours exposé.</p></div></div></div></div>"""

P2_DAHOMEY = """<div class="page-section page-section-3" id="pageSection-3"><section class="section bg-motif padd-top-md padd-bottom-md marg-top-none marg-bottom-none"><header class="section-header-wrapper grid-container grid-default container-min"><div class="section-header text-center"><div class="section-titles flex-container flex-dir-column align-middle align-center text-center"><div class="section-texts has-title has-subtitle"><h2 class="section-title">Le royaume du Dahomey</h2><span class="section-subtitle block-span">d'Abomey à la mer</span></div></div></div></header><article class="section-layout-wrapper grid-container grid-default"><div class="grid_2col_tpl_01 grid-x large-up-2 align-center align-stretch grid-padding-x grid-padding-y"><div class="cell"><div class="woody-component woody-component-custom-text tpl_02"><div class="grid-x"><div class="cell"><h3>Une puissance ouest-africaine</h3><p>Fondé au début du <strong>XVIIᵉ siècle</strong> par le roi <strong>Houégbadja</strong>, le royaume du Dahomey s'est agrandi sous <strong>Agadja</strong>, qui prit Ouidah en 1727 et ouvrit l'accès à l'Atlantique. Ses successeurs — <strong>Kpengla</strong>, <strong>Ghezo</strong>, <strong>Glélé</strong>, <strong>Béhanzin</strong> — ont administré un État militarisé, structuré, redoutable. Il tombera en 1894 face à l'expédition française.</p></div></div></div></div><div class="cell"><div class="woody-component woody-component-custom-text tpl_02"><div class="grid-x"><div class="cell"><h3>Les agojie, femmes soldats</h3><p>Une des institutions du royaume qui fascine le plus : les <strong>agojie</strong>, un corps de <strong>femmes soldats</strong> attaché à la personne du roi. Rigoureusement entraînées, elles servaient à la fois de garde royale et d'unité de choc — leur mémoire est encore vive à Abomey, et le film <em>The Woman King</em> (2022) leur a rendu une visibilité internationale.</p></div></div></div></div></div></article></section></div>"""

P3_HISTOIRE = """<div class="woody-component woody-component-custom-text tpl_01 bg-primary bg-gradient bg-filigrane isRel padd-all-md no-sessions" data-equalizer="data-equalizer"><div class="grid-container"><div class="grid-x grid-padding-x align-center"><div class="cell large-8 text-center"><h2 class="section-title">Pour ceux qui aiment l'histoire</h2><p>Le Plateau royal est le cœur historique du Bénin. En trois jours au départ d'Abomey, on peut enchaîner le <strong>musée historique</strong> installé dans les palais royaux, la <strong>place Goho</strong> qui commémore Béhanzin, la <strong>maison du roi Agoli-Agbo</strong>, les <strong>ateliers de tenture appliquée</strong> — cet art hérité de la cour qui raconte les règnes en tissu — et le <strong>marché d'Abomey</strong>, où l'on trouve encore les tissus, poteries et forges de la ville.</p><p>Prolonger vers <strong>Ouidah</strong>, à deux heures de route, permet de suivre la Route de l'Esclave jusqu'à la Porte du Non-Retour, et de visiter le temple des Pythons. Un guide local à Abomey ouvre les portes moins connues.</p></div></div></div></div>"""


# ------------------------------------------------------------------
# nous-contacter : remplace le bloc « Nos bureaux d'informations » qui liste
# deux bureaux étrangers absurdes (« 16 rue de Saint-Pétersbourg, 75008 Paris »
# et « 19 rue Sainte-Anne, 97400 Saint-Denis / le Togo » — le 97400 est le
# code postal de La Réunion ; Dahomey Discovery n'a pas d'antenne à Paris ni à Saint-Denis
# du Togo). Le nouveau bloc porte les coordonnées de Dahomey Discovery à Cotonou, telles
# qu'elles apparaissent déjà dans contenu.py (ADRESSE_CORRIGÉE).
# ------------------------------------------------------------------
CONTACT_ANPT = """<div class="woody-component woody-component-custom-text tpl_01 no-sessions" data-equalizer="data-equalizer"><div class="grid-x grid-padding-x"><div class="cell"><div class="generic-block-header marg-bottom-md"><div class="generic-block-titles flex-container flex-dir-column align-center align-middle text-center"><div class="generic-block-texts has-title"><h2 class="generic-block-title">Nos coordonnées</h2></div></div></div><p class="h4">Dahomey Discovery, éditeur du portail Bonne Arrivée</p><p>Zongo, immeuble FAWAZ<br />Rue des Cheminots<br />08 BP 1134 Tri-Postal<br />COTONOU, BÉNIN<br />Tél : +229 21 30 91 44<br />Mail : <a href="mailto:contact@bonnearrivee.bj">contact@bonnearrivee.bj</a></p><p>Ouvert du lundi au vendredi, de 8&nbsp;h à 17&nbsp;h. En dehors de ces horaires, écrivez-nous par courriel ; nous répondons sous deux jours ouvrés.</p></div></div></div>"""


# ------------------------------------------------------------------
# Pages ultra-vides à remplir : chapô + rien. On ajoute plusieurs blocs de
# contenu factuel après le chapô existant. Faits vérifiables sur sources
# publiques (parc de la Pendjari, W-Arly-Pendjari UNESCO 2017, chaînes
# hôtelières présentes à Cotonou en 2024-2025).
# ------------------------------------------------------------------

# Fabrique un composant custom-text sobre à insérer sans dépendre d'une
# structure page-section : rendu autonome dans le content-wrapper.
def _bloc(h2, paragraphes, bg=""):
    ps = "".join(f"<p>{p}</p>" for p in paragraphes)
    bg_class = f" bg-primary bg-gradient bg-filigrane padd-all-md" if bg else ""
    return (f'<div class="woody-component woody-component-custom-text tpl_02{bg_class} '
            f'no-sessions" data-equalizer="data-equalizer">'
            f'<div class="grid-container padd-top-md padd-bottom-md">'
            f'<div class="grid-x grid-padding-x align-center">'
            f'<div class="cell large-10">'
            f'<h2 class="section-title">{h2}</h2>{ps}'
            f'</div></div></div></div>')


FICHE_INDISPONIBLE = ('<div class="woody-component woody-component-custom-text tpl_02 bg-primary '
                      'bg-gradient bg-filigrane padd-all-md no-sessions" data-equalizer="data-equalizer">'
                      '<div class="grid-container padd-top-md padd-bottom-md">'
                      '<div class="grid-x grid-padding-x align-center">'
                      '<div class="cell large-8 text-center">'
                      '<h2 class="section-title">Fiche en cours de mise à jour</h2>'
                      "<p>Le détail de cette offre — coordonnées, horaires, photographies, avis — "
                      "était fourni par un service extérieur qui n'est plus disponible pour ce site. "
                      "Nous reconstruisons progressivement l'annuaire des <strong>établissements "
                      "béninois</strong> à partir de nos propres sources.</p>"
                      "<p>En attendant, pour obtenir des informations à jour sur cet établissement "
                      "ou une recommandation adaptée à votre séjour, contactez l'"
                      '<a href="/sejourner/toutes-les-infos-pratiques/nous-contacter"><strong>'
                      "Agence Nationale de Promotion des Patrimoines et du Développement du "
                      "Tourisme (Dahomey Discovery)</strong></a> — nous vous répondrons sous deux jours ouvrés.</p>"
                      '</div></div></div></div>')


REMPLISSAGES_PAGES_VIDES = {
    "activites/safari-et-randonnee/index.blade.php": [
        _bloc("Où faire un safari au Bénin", [
            "Deux parcs nationaux, au nord du pays : le <strong>parc national de la Pendjari</strong> "
            "(2 755 km²) et le <strong>parc national du W</strong> (part béninoise du complexe partagé "
            "avec le Burkina Faso et le Niger). Les deux sont inscrits au <strong>patrimoine mondial "
            "de l'UNESCO</strong> depuis 2017 sous le nom de complexe <em>W-Arly-Pendjari</em>. C'est "
            "l'un des derniers grands ensembles sauvages d'Afrique de l'Ouest.",
            "La Pendjari est la plus visitée : mieux équipée en pistes, campements et postes de "
            "concession. Le W béninois est plus retiré, plus sauvage, plus difficile d'accès. Les "
            "deux se visitent depuis <strong>Natitingou</strong> ou <strong>Tanguiéta</strong>, "
            "porte d'entrée pratique pour le nord."]),
        _bloc("Ce qu'on peut y voir", [
            "Éléphants, buffles, hippopotames, cobes de Buffon, hippotragues, ourébis, phacochères, "
            "babouins, cynocéphales… La Pendjari abrite l'un des derniers noyaux de <strong>lions "
            "d'Afrique de l'Ouest</strong> (sous-espèce en danger critique) — leur observation reste "
            "rare mais possible. Côté oiseaux, plus de <strong>460 espèces</strong> recensées, dont "
            "de nombreux migrateurs paléarctiques en saison sèche.",
            "Les guépards, jadis présents, ont disparu du complexe ; les lycaons y sont "
            "occasionnellement signalés. La faune s'observe à l'aube et en fin d'après-midi, autour "
            "des mares résiduelles."], bg="1"),
        _bloc("Randonner dans l'Atacora", [
            "Au-delà des safaris, la <strong>chaîne de l'Atacora</strong> se prête à la marche : "
            "sentiers en escaliers autour des <strong>chutes de Tanougou</strong>, ascension des "
            "<strong>collines de Boukombé</strong> pour les couchers de soleil sur le pays "
            "Betammaribé, boucles autour de <strong>Perma</strong> et <strong>Kouaba</strong> à la "
            "rencontre des tata somba. Compter deux à quatre heures pour la plupart des sentiers, "
            "avec un guide local qui connaît les protocoles d'entrée dans les villages."]),
        _bloc("Quand y aller, comment se préparer", [
            "La <strong>saison sèche</strong>, de <strong>novembre à mai</strong>, est la seule "
            "praticable : les pistes sont ouvertes, la végétation basse rend la faune visible, et "
            "les températures nocturnes tombent (bienvenu sous une tente). En saison des pluies, "
            "les parcs sont <strong>fermés</strong>.",
            "Prévoir : véhicule 4×4 (loué avec chauffeur), permis d'accès au parc (à prendre à "
            "l'entrée ou via un opérateur), vêtements couvrants aux couleurs neutres, chaussures "
            "fermées, jumelles, moustiquaire imprégnée pour les nuits en brousse, et un traitement "
            "antipaludéen à jour."]),
    ],

    "activites/les-operateurs-de-safari/index.blade.php": [
        _bloc("Ce qu'un opérateur prend en charge", [
            "Un opérateur de safari organise l'ensemble du séjour : <strong>véhicule 4×4</strong> "
            "avec <strong>chauffeur-guide</strong>, <strong>permis d'entrée</strong> dans le parc, "
            "<strong>hébergement</strong> (campement, lodge ou tente sous étoiles), repas, et "
            "sorties d'observation aux heures propices. Certains proposent aussi la <strong>location "
            "de matériel</strong> — jumelles, tentes, groupes électrogènes — utile pour les nuits "
            "loin des postes équipés."]),
        _bloc("Comment choisir un opérateur agréé", [
            "Vérifier trois choses avant de réserver. Un, l'<strong>agrément</strong> délivré par "
            "l'autorité de gestion du parc — la Pendjari est gérée depuis 2017 par l'ONG "
            "<strong>African Parks</strong>, qui liste ses partenaires sur son site. Deux, un "
            "<strong>chauffeur-guide francophone</strong> connaissant la faune : les opérateurs "
            "sérieux emploient des guides formés à Kandi ou à Cotonou. Trois, la couverture "
            "<strong>assurance</strong> et l'entretien du parc automobile, particulièrement sur "
            "des pistes qui n'aident pas les mécaniques.",
            "Les tarifs varient sensiblement : compter, en 2025, entre <strong>150 000 et 350 000 "
            "F&nbsp;CFA par jour</strong> (~230-530&nbsp;€) selon le confort et la taille du "
            "groupe."], bg="1"),
        _bloc("Où trouver les opérateurs", [
            "Les principaux opérateurs sont installés à <strong>Natitingou</strong> et "
            "<strong>Tanguiéta</strong>, aux portes des parcs. Certains disposent d'un bureau à "
            "<strong>Cotonou</strong> qui organise le transfert depuis la capitale (une journée de "
            "route). L'<strong>Dahomey Discovery</strong> tient à jour un annuaire des opérateurs sérieux — "
            "n'hésitez pas à nous contacter pour un rendez-vous ou une recommandation adaptée à "
            "vos dates et à la composition de votre groupe."]),
    ],

    "activites/sejour-sur-le-lac/index.blade.php": [
        _bloc("Ganvié, sur le lac Nokoué", [
            "À trente minutes de pirogue depuis Abomey-Calavi, <strong>Ganvié</strong> se lève des "
            "eaux du <strong>lac Nokoué</strong> : environ trente mille habitants vivent dans des "
            "maisons montées sur pilotis, entre l'école, la mosquée, l'église et le marché — tous "
            "flottants ou perchés à la même hauteur. C'est la <strong>plus grande cité lacustre "
            "d'Afrique</strong>, et un site inscrit à la liste indicative du patrimoine mondial de "
            "l'UNESCO depuis 1996.",
            "La pêche s'y pratique aux <strong>acadja</strong>, ces enclos de branchages qui piègent "
            "poissons et crevettes ; ils dessinent, vus du ciel, un damier sur le lac. La visite dure "
            "une demi-journée avec un piroguier local — départs toutes les heures depuis "
            "l'embarcadère d'Abomey-Calavi."]),
        _bloc("Le lac Ahémé et la lagune côtière", [
            "Plus à l'ouest, le <strong>lac Ahémé</strong> forme un long bras d'eau entre "
            "Comè et Grand-Popo. Ses berges accueillent les villages de <strong>Possotomé</strong> "
            "— thermalisme et sources d'eau minérale — et <strong>Kpomassè</strong>, où l'on visite "
            "les ateliers de tissage. Les marchés flottants de Possotomé, le mercredi, rassemblent "
            "pêcheurs et paysans venus des deux rives."]),
        _bloc("La Bouche du Roy", [
            "À l'embouchure du fleuve <strong>Mono</strong>, la <strong>Bouche du Roy</strong> "
            "sépare les mangroves de l'océan Atlantique. C'est une réserve de biosphère (programme "
            "MAB de l'UNESCO depuis 2020), refuge des <strong>lamantins</strong>, des "
            "<strong>tortues marines</strong> et de plus de 200 espèces d'oiseaux migrateurs. La "
            "sortie s'organise depuis Grand-Popo, en pirogue à moteur, au petit jour pour "
            "l'observation de la faune."], bg="1"),
        _bloc("Bien s'organiser", [
            "Compter une <strong>demi-journée pour Ganvié</strong>, une <strong>journée complète</strong> "
            "pour le Ahémé ou la Bouche du Roy avec les temps de route. Prévoyez chapeau, crème "
            "solaire, eau, appareil photo protégé (spray d'embruns garanti), et un vêtement "
            "couvrant pour le retour au coucher du soleil, quand les moustiques sortent. Les "
            "piroguiers officient toute l'année ; la meilleure lumière est en <strong>saison "
            "sèche</strong>, de novembre à mai."]),
    ],

    "sejourner/toutes-les-infos-pratiques/index.blade.php": [
        _bloc("Ce qu'il faut préparer", [
            "Le Bénin accepte les voyageurs de la plupart des nationalités avec un <strong>e-visa</strong> "
            "délivré en ligne, valable de 30 à 90 jours. Le passeport doit être valide six mois "
            "après la date d'entrée. Le <strong>vaccin contre la fièvre jaune</strong> est "
            "obligatoire, la prophylaxie <strong>antipaludéenne</strong> vivement recommandée toute "
            "l'année.",
            "La monnaie est le <strong>franc CFA</strong> (F CFA), parité fixe avec l'euro "
            "(1&nbsp;€ = 655,957&nbsp;F&nbsp;CFA). On paie en carte à Cotonou dans les grands "
            "établissements ; ailleurs, le liquide est roi. Retraits possibles aux distributeurs "
            "BOA, Ecobank, UBA et Orabank."]),
        _bloc("Bon à savoir sur place", [
            "L'aéroport international <strong>Cardinal Bernardin Gantin</strong> à Cotonou est la "
            "principale porte d'entrée. Depuis l'Europe, comptez six heures de vol depuis Paris "
            "(vols directs Air France, Air Sénégal, Brussels Airlines). Sur place, "
            "<strong>taxis</strong> à Cotonou, <strong>zémidjans</strong> (moto-taxis) partout, "
            "et <strong>bus interurbains</strong> vers Parakou, Natitingou, Porto-Novo et "
            "Abomey.",
            "La <strong>saison sèche</strong>, de novembre à mars, est la plus favorable au "
            "voyage — températures autour de 30&nbsp;°C le jour, 22&nbsp;°C la nuit, pluies rares. "
            "Le harmattan (vent sec du Sahel) souffle de décembre à février et donne un ciel "
            "brumeux, particulièrement au nord."], bg="1"),
    ],

    "sejourner/hebergements/hotels/index.blade.php": [
        _bloc("Cotonou : l'offre internationale", [
            "L'hôtellerie 4 et 5 étoiles se concentre à <strong>Cotonou</strong>, en particulier "
            "dans les quartiers de <strong>Cadjèhoun</strong>, <strong>Haie Vive</strong> et le "
            "long du <strong>boulevard de la Marina</strong>. On y trouve les enseignes "
            "internationales — Novotel, Golden Tulip, Azalaï, Marina — ainsi que plusieurs hôtels "
            "indépendants comparables (Bénin Marina Hôtel, Livingstone).",
            "Comptez, en 2025, entre <strong>70 000 et 180 000 F&nbsp;CFA la nuit</strong> "
            "(~105-275&nbsp;€) selon la catégorie, petit déjeuner inclus. Toutes ces adresses "
            "disposent de wifi, climatisation, restaurant et souvent piscine ; la sécurité est "
            "assurée sur site."]),
        _bloc("En dehors de Cotonou", [
            "L'hôtellerie de charme se trouve à <strong>Grand-Popo</strong> (Villa Karo, Awalé "
            "Plage, Bel Azur), à <strong>Ouidah</strong> (Casa del Papa, Kanhoun), à "
            "<strong>Porto-Novo</strong> et à <strong>Abomey</strong>. Ce sont souvent des "
            "établissements à taille humaine — 10 à 30 chambres — installés dans des maisons "
            "coloniales rénovées ou des lodges les pieds dans le sable.",
            "Au nord, à <strong>Natitingou</strong> et près de la <strong>Pendjari</strong>, les "
            "<strong>campements</strong> et <strong>écolodges</strong> remplacent l'hôtel "
            "classique : bungalows, cases somba modernisées, ou tentes sur plateforme pour les "
            "safaris. Le confort y est plus rustique mais l'expérience unique."], bg="1"),
        _bloc("Ce qu'il faut savoir avant de réserver", [
            "Les <strong>coupures d'électricité</strong> restent fréquentes hors des grandes "
            "villes : les hôtels sérieux disposent d'un groupe électrogène ; vérifiez sa présence "
            "avant réservation. Le <strong>wifi</strong> est correct à Cotonou, plus aléatoire "
            "ailleurs — prévoyez une carte SIM locale (MTN ou Moov Africa).",
            "En saison des pluies (juin-octobre), demandez une chambre bien ventilée et "
            "systématiquement équipée d'une <strong>moustiquaire imprégnée</strong>. Les paiements "
            "en carte bancaire sont acceptés dans les grands hôtels ; ailleurs, prévoyez du "
            "liquide (F&nbsp;CFA), retiré aux distributeurs BOA, Ecobank ou UBA."]),
    ],

    # Les 3 fiches /offres/ sont des « culs-de-sac depuis que leur API a fermé »
    # (voir README). Plutôt qu'inventer les détails d'établissements dont nous
    # n'avons pas de source vérifiable, on affiche un bandeau explicatif honnête
    # qui renvoie vers Dahomey Discovery pour une information à jour.
    "offres/pendjari-safari-tours-natitingou-bj-3313618/index.blade.php": [FICHE_INDISPONIBLE],
    "offres/hotel-du-lac-nokoue-cotonou-bj-3332266/index.blade.php": [FICHE_INDISPONIBLE],
    "offres/le-maquis-royal-abomey-bj-3329842/index.blade.php": [FICHE_INDISPONIBLE],
}



REMPLACEMENTS_BLOCS_HÉRITAGE = [
    # Grand Nord
    ("woody-component-interactive_gallery", "Wana Issa", "galerie « mangroves du Nord »", G1_ATACORA),
    ("woody-component-feature", "Les oranges de M'tsamboro", "feature « oranges M'tsamboro »", G2_ANACARDE),
    ("woody-component-feature", "Des îlots pour se ressourcer", "feature « îlots pour se ressourcer »", G3_CASCADES),
    ("page-section-3", "Les îlots du Nord", "section « îlots du Nord rois du littoral »", G4_TATA_SOMBA),
    ("page-section-4", "Vers les îlots ou sur le littoral", "focus « vers les îlots »", G5_FOCUS_PENDJARI),
    # Plateau Royal
    ("woody-component-interactive_gallery", "djinns", "galerie « cascade Tanougou + djinns »", P1_PALAIS),
    ("page-section-3", "Mosquée de Bohicon", "section entière « Bohicon » (mosquée, sultan, plage)", P2_DAHOMEY),
    ("woody-component-custom-text", "Pour ceux qui ont le pied marin", "bloc « pied marin, kayak, faune marine »", P3_HISTOIRE),
    # nous-contacter
    ("woody-component-custom-text", "Nos bureaux d'informations",
     "bloc « bureaux Paris (16 rue de Saint-Pétersbourg) + Saint-Denis du Togo (97400) » "
     "— Dahomey Discovery n'a pas d'antenne à Paris ni à Saint-Denis du Togo, les codes postaux et "
     "les numéros de téléphone sont mahorais/réunionnais",
     CONTACT_ANPT),
]
