
(function(){
'use strict';
/* ---------- familles de lieux ---------- */
const CATS = {
  plage:  {label:"Plages & lagunes",  k:"var(--ba-c-plage)"},
  culture:{label:"Culture & histoire",k:"var(--ba-c-culture)"},
  nature: {label:"Nature & parcs",    k:"var(--ba-c-nature)"},
  ville:  {label:"Villes & marchés",  k:"var(--ba-c-ville)"},
  arrivee:{label:"Arriver au pays",   k:"var(--ba-c-arrivee)"}
};
/* un glyphe par famille, dessiné dans un carré de 24 */
const GLYPHS = {
  plage:  {solid:false, d:"M2.5 14.5c1.9-2.3 3.8-2.3 5.7 0s3.8 2.3 5.7 0 3.8-2.3 5.7 0M2.5 9c1.9-2.3 3.8-2.3 5.7 0s3.8 2.3 5.7 0 3.8-2.3 5.7 0"},
  culture:{solid:true,  d:"M12 2.6 20.2 12 12 21.4 3.8 12 12 2.6Zm0 4.7L7.9 12l4.1 4.7 4.1-4.7L12 7.3Z"},
  nature: {solid:true,  d:"M12 2.4 18.6 13.6H5.4L12 2.4Zm0 4.3L9 11.6h6L12 6.7Zm-1.2 6.9h2.4V21h-2.4v-7.4Z"},
  ville:  {solid:false, d:"M2.6 20.6h18.8M5.4 20.6v-8.2h4v8.2M11.8 20.6V4.4h4v16.2M17.8 20.6v-5.4h2.6v5.4"},
  arrivee:{solid:true,  d:"M21.4 12.6 13.6 11.9 10.4 18.5H8.3l1.8-6.7-4.1-.4-1.6 2.2H3l1.2-3.6L3 6.4h1.4l1.6 2.2 4.1-.4L8.3 1.5h2.1l3.2 6.6 7.8-.7c.8 0 .8 5.2 0 5.2Z"}
};
/* quatre fils conducteurs, composés de lieux déjà présents sur la carte */
const ROUTES = [
  {id:"royaumes", cat:"culture", title:"Sur la route des royaumes", court:"Les royaumes",
   sub:"La mémoire, les palais, la Porte du Non-Retour.",
   ids:["ouidah","abomey","porto-novo","ketou","nikki"]},
  {id:"eau", cat:"plage", title:"L’eau, la lagune, le sel", court:"L’eau et le sel",
   sub:"Le cordon littoral, le lac Nokoué et le lac Ahémé — on s’y déplace en pirogue.",
   ids:["grand-popo","ganvie","aheme","cotonou"]},
  {id:"nord", cat:"nature", title:"Le grand Nord", court:"Le grand Nord",
   sub:"L’Atacora, les tata somba, les cascades et les deux parcs.",
   ids:["natitingou","boukoumbe","kota","tanougou","pendjari","parc-w"]},
  {id:"axe", cat:"ville", title:"L’axe, du port au fleuve", court:"Du port au fleuve",
   sub:"La route qui monte de Cotonou jusqu’au Niger, de marché en marché.",
   ids:["aeroport","port","cotonou","dassa","parakou","djougou","malanville"]}
];

const SITES  = [
{
"id": "grand-popo",
"name": "Grand-Popo",
"cat": "plage",
"dept": "Mono",
"x": 304.6,
"y": 1002.7,
"label": [
"Grand-Popo"
],
"cap": null,
"coord": "6°17′ N · 1°49′ E",
"desc": "Une longue plage de sable bordée de cocotiers, à l’endroit où le fleuve Mono rejoint l’océan. Le village garde l’empreinte afro-brésilienne du XIX<sup>e</sup> siècle, et la lagune juste derrière se parcourt en barque.",
"see": [
"La Bouche du Roy, l’embouchure du Mono et ses bancs de sable",
"Une sortie en pirogue dans la mangrove, tôt le matin",
"Le site de ponte des tortues marines, en saison"
],
"when": "Novembre à mars, quand la mer est la plus calme et l’air le plus sec."
},
{
"id": "ouidah",
"name": "Ouidah",
"cat": "culture",
"dept": "Atlantique",
"x": 346.0,
"y": 990.3,
"label": [
"Ouidah"
],
"cap": null,
"coord": "6°22′ N · 2°05′ E",
"desc": "La ville la plus chargée d’histoire du littoral : port négrier, capitale spirituelle du vodoun, et lieu de mémoire majeur. La Route des Esclaves relie sur près de quatre kilomètres la ville à la plage.",
"see": [
"La Porte du Non-Retour, au bout de la Route des Esclaves",
"Le Temple des Pythons, face à la basilique",
"Le musée installé dans l’ancien fort portugais",
"La forêt sacrée de Kpassè et ses statues"
],
"when": "Le 10 janvier pour la fête nationale du Vodoun ; toute l’année sinon."
},
{
"id": "cotonou",
"name": "Cotonou",
"cat": "ville",
"dept": "Littoral",
"x": 393.1,
"y": 989.1,
"label": [
"Cotonou"
],
"cap": "Capitale économique",
"coord": "6°22′ N · 2°23′ E",
"desc": "La capitale économique, bruyante et dense, coincée entre l’océan et le lac Nokoué. C’est le point d’entrée du pays : tout y arrive et tout en repart.",
"see": [
"Le marché Dantokpa, l’un des plus grands d’Afrique de l’Ouest",
"La Fondation Zinsou et la scène d’art contemporain",
"La plage de Fidjrossè et ses paillotes, le soir",
"La place de l’Amazone et son monument"
],
"when": "Toute l’année. Les soirées de saison sèche sont les plus agréables."
},
{
"id": "ganvie",
"name": "Ganvié",
"cat": "plage",
"dept": "Atlantique",
"x": 397.0,
"y": 974.0,
"label": [
"Ganvié"
],
"cap": null,
"coord": "6°28′ N · 2°25′ E",
"desc": "Une cité entièrement bâtie sur pilotis au milieu du lac Nokoué, fondée par les Tofinu pour échapper aux razzias. Des dizaines de milliers d’habitants y vivent, se déplacent et commercent en pirogue.",
"see": [
"Le marché flottant, en fin de matinée",
"Les enclos de pêche en branchages (acadja)",
"La traversée depuis l’embarcadère d’Abomey-Calavi"
],
"when": "Toute l’année ; partez tôt pour éviter la chaleur sur l’eau."
},
{
"id": "porto-novo",
"name": "Porto-Novo",
"cat": "culture",
"dept": "Ouémé",
"x": 429.7,
"y": 969.3,
"label": [
"Porto-Novo"
],
"cap": "Capitale politique",
"coord": "6°30′ N · 2°38′ E",
"desc": "La capitale politique, plus calme que Cotonou, avec un centre ancien où se croisent architecture afro-brésilienne, palais royal et maisons coloniales. C’est la ville des musées du pays.",
"see": [
"Le musée Honmé, ancien palais du roi Toffa",
"La Grande Mosquée, ancienne église de style afro-brésilien",
"Le musée Da Silva et le Jardin des plantes"
],
"when": "Toute l’année ; les musées ferment souvent en début d’après-midi."
},
{
"id": "aheme",
"name": "Lac Ahémé",
"cat": "plage",
"dept": "Mono",
"x": 330.3,
"y": 961.0,
"label": [
"Lac Ahémé",
"Possotomè"
],
"cap": null,
"coord": "6°33′ N · 1°59′ E",
"desc": "Un grand lac peu profond entouré de villages de pêcheurs. Possotomè, sur la rive est, est connue pour ses sources d’eau minérale et pour la lenteur générale des choses.",
"see": [
"Une sortie en pirogue au coucher du soleil",
"Les techniques de pêche traditionnelles des Pédah",
"Le marché de Bopa, sur l’autre rive"
],
"when": "Décembre à mars, hors des grandes pluies."
},
{
"id": "ketou",
"name": "Kétou",
"cat": "culture",
"dept": "Plateau",
"x": 425.3,
"y": 834.6,
"label": [
"Kétou"
],
"cap": null,
"coord": "7°22′ N · 2°36′ E",
"desc": "Une des plus anciennes cités yoruba de la région, longtemps ceinte de remparts. La ville reste le siège d’une royauté vivante et d’un calendrier de fêtes dense.",
"see": [
"La porte d’Akaba Idéna, vestige de l’enceinte fortifiée",
"Le palais de l’Alaketou",
"Les masques Guèlèdè, inscrits au patrimoine immatériel de l’UNESCO"
],
"when": "Pendant les fêtes traditionnelles, entre mars et juin."
},
{
"id": "lama",
"name": "Forêt de la Lama",
"cat": "nature",
"dept": "Zou",
"x": 352.9,
"y": 898.6,
"label": [
"Forêt",
"de la Lama"
],
"cap": null,
"coord": "6°57′ N · 2°08′ E",
"desc": "Une dépression argileuse entre Abomey et Cotonou qui abrite le dernier grand massif de forêt dense semi-décidue du sud du pays, protégé en son cœur.",
"see": [
"Le noyau central de forêt naturelle",
"Les singes à ventre rouge, endémiques de la région",
"Les plantations de teck qui ceinturent la réserve"
],
"when": "Saison sèche, quand les pistes restent praticables."
},
{
"id": "abomey",
"name": "Abomey",
"cat": "culture",
"dept": "Zou",
"x": 331.5,
"y": 861.7,
"label": [
"Abomey"
],
"cap": null,
"coord": "7°11′ N · 1°59′ E",
"desc": "Capitale du royaume du Dahomey pendant près de trois siècles. Douze rois s’y sont succédé, chacun ajoutant son palais de terre au même enclos — un ensemble inscrit au patrimoine mondial de l’UNESCO.",
"see": [
"Les palais royaux et le musée historique",
"Les bas-reliefs polychromes racontant les règnes",
"Le quartier des artisans : tentures appliquées, forge, tissage"
],
"when": "Toute l’année ; visite guidée vivement conseillée."
},
{
"id": "dassa",
"name": "Dassa-Zoumè",
"cat": "culture",
"dept": "Collines",
"x": 361.1,
"y": 773.7,
"label": [
"Dassa-Zoumè"
],
"cap": null,
"coord": "7°45′ N · 2°11′ E",
"desc": "Une ville posée au milieu d’un chaos de collines granitiques — quarante et une, dit-on. C’est aussi le grand lieu de pèlerinage catholique du pays.",
"see": [
"La grotte mariale d’Arigbo et son pèlerinage d’août",
"La montée sur les collines au lever du jour",
"Le palais du roi de Dassa"
],
"when": "Mi-août pour le pèlerinage national ; le reste de l’année pour le calme."
},
{
"id": "parakou",
"name": "Parakou",
"cat": "ville",
"dept": "Borgou",
"x": 427.9,
"y": 523.9,
"label": [
"Parakou"
],
"cap": null,
"coord": "9°21′ N · 2°37′ E",
"desc": "La grande ville du centre-nord et le carrefour commercial entre le sud du pays et le Sahel. Terminus historique du chemin de fer, elle vit au rythme des camions et des marchés.",
"see": [
"Le marché Arzèkè et ses tisserands",
"Le musée de plein air du Borgou",
"Les ateliers de teinture et de forge"
],
"when": "Toute l’année ; très chaud en mars-avril."
},
{
"id": "nikki",
"name": "Nikki",
"cat": "culture",
"dept": "Borgou",
"x": 519.3,
"y": 431.8,
"label": [
"Nikki"
],
"cap": null,
"coord": "9°56′ N · 3°13′ E",
"desc": "Capitale historique du royaume baatonou (bariba), célèbre pour sa cavalerie. La Gaani, sa grande fête, rassemble des centaines de cavaliers en tenue autour du roi.",
"see": [
"La fête de la Gaani et sa cavalcade",
"La cour royale et ses griots",
"Les selleries et l’artisanat du cuir"
],
"when": "À la Gaani, dont la date suit le calendrier lunaire islamique."
},
{
"id": "djougou",
"name": "Djougou",
"cat": "ville",
"dept": "Donga",
"x": 281.5,
"y": 469.3,
"label": [
"Djougou"
],
"cap": null,
"coord": "9°42′ N · 1°40′ E",
"desc": "Ville marchande de l’ouest, longtemps étape sur les routes caravanières entre le Sahel et la côte. L’artisanat y est resté un vrai métier, pas une démonstration.",
"see": [
"Les ateliers de tisserands et de forgerons",
"Le grand marché, tous les cinq jours",
"La vieille ville et ses mosquées"
],
"when": "Novembre à février, pendant la saison sèche."
},
{
"id": "kota",
"name": "Chutes de Kota",
"cat": "nature",
"dept": "Atacora",
"x": 250.7,
"y": 399.0,
"label": [
"Chutes de Kota"
],
"cap": null,
"coord": "10°09′ N · 1°28′ E",
"desc": "Une cascade encaissée dans les rochers à une vingtaine de kilomètres de Natitingou, au milieu d’une végétation dense. Bassin naturel en contrebas.",
"see": [
"Le bassin au pied de la chute",
"Le sentier d’approche à travers les rochers",
"Le point de vue sur la vallée depuis le haut"
],
"when": "Juillet à octobre pour le débit ; la piste est plus dure en pleine pluie."
},
{
"id": "boukoumbe",
"name": "Boukoumbé",
"cat": "culture",
"dept": "Atacora",
"x": 194.2,
"y": 393.8,
"label": [
"Boukoumbé",
"Tata Somba"
],
"cap": null,
"coord": "10°11′ N · 1°06′ E",
"desc": "Le pays des Betammaribe, où l’on construit les tata somba : des maisons-forteresses en terre à étage, avec greniers, terrasse et étable au rez-de-chaussée. L’aire culturelle du Koutammakou, inscrite à l’UNESCO, s’étend de part et d’autre de la frontière togolaise.",
"see": [
"Une tata somba habitée, visitée avec la famille",
"Le marché de Boukoumbé, tous les quatre jours",
"Les champs de fonio et de sorgho en terrasses"
],
"when": "Novembre à février ; le marché vaut à lui seul le détour."
},
{
"id": "natitingou",
"name": "Natitingou",
"cat": "ville",
"dept": "Atacora",
"x": 237.8,
"y": 375.6,
"label": [
"Natitingou"
],
"cap": null,
"coord": "10°18′ N · 1°23′ E",
"desc": "La porte d’entrée du Nord, adossée à la chaîne de l’Atacora. C’est de là que partent les circuits vers la Pendjari, les tata somba et les cascades.",
"see": [
"Le musée régional de l’Atacora",
"Le panorama sur la chaîne depuis les hauteurs",
"Le marché et les points de départ vers la Pendjari"
],
"when": "Décembre à mai, la saison des circuits dans le Nord."
},
{
"id": "tanougou",
"name": "Chutes de Tanougou",
"cat": "nature",
"dept": "Atacora",
"x": 245.5,
"y": 295.0,
"label": [
"Chutes de",
"Tanougou"
],
"cap": null,
"coord": "10°49′ N · 1°26′ E",
"desc": "Une chute d’eau à plusieurs paliers au pied de l’Atacora, sur la route de la Pendjari. Les bassins successifs se remontent à pied.",
"see": [
"Le grand bassin, baignade possible",
"La montée vers les paliers supérieurs",
"L’arrêt sur la route du parc, en fin de journée"
],
"when": "Août à novembre, quand la chute est pleine."
},
{
"id": "pendjari",
"name": "Parc de la Pendjari",
"cat": "nature",
"dept": "Atacora",
"x": 248.1,
"y": 227.3,
"label": [
"Parc de la",
"Pendjari"
],
"cap": null,
"coord": "11°15′ N · 1°27′ E",
"desc": "La grande réserve du pays, environ 2 750 km² de savane arborée au pied de l’Atacora. C’est l’un des derniers refuges du lion en Afrique de l’Ouest, et une réserve de biosphère de l’UNESCO.",
"see": [
"Les mares à hippopotames et les points d’eau à l’aube",
"Les troupeaux d’éléphants et de buffles",
"Plus de 450 espèces d’oiseaux",
"Les lions et les guépards, avec de la patience"
],
"when": "Décembre à mai. Le parc ferme en général pendant la saison des pluies."
},
{
"id": "parc-w",
"name": "Parc W du Bénin",
"cat": "nature",
"dept": "Alibori",
"x": 417.6,
"y": 125.8,
"label": [
"Parc W",
"du Bénin"
],
"cap": null,
"coord": "11°54′ N · 2°33′ E",
"desc": "La part béninoise du complexe transfrontalier W, partagé avec le Niger et le Burkina Faso, et inscrit au patrimoine mondial. Son nom vient du double méandre du fleuve Niger.",
"see": [
"Les rives du Niger et de la Mékrou",
"Éléphants, hippopotames, antilopes rouannes",
"L’avifaune des zones humides en saison"
],
"when": "Décembre à mai ; accès réglementé, guide obligatoire."
},
{
"id": "malanville",
"name": "Malanville",
"cat": "ville",
"dept": "Alibori",
"x": 545.4,
"y": 132.1,
"label": [
"Malanville",
"Fleuve Niger"
],
"cap": null,
"coord": "11°52′ N · 3°23′ E",
"desc": "La ville la plus au nord, sur la rive du fleuve Niger face au pays voisin. Son marché hebdomadaire draine des commerçants de toute la sous-région.",
"see": [
"Le marché du dimanche, l’un des plus grands du Nord",
"Les rizières irriguées de la vallée",
"Les berges du Niger au coucher du soleil"
],
"when": "Novembre à février, avant les grosses chaleurs."
},
{
"id": "allada",
"name": "Allada",
"cat": "culture",
"dept": "Atlantique",
"x": 356.1,
"y": 943.0,
"label": [
"Allada"
],
"cap": null,
"coord": "6°40′ N · 2°09′ E",
"desc": "Le berceau : c’est d’Allada que sont partis, au XVII<sup>e</sup> siècle, les fondateurs d’Abomey et de Porto-Novo. Le royaume adja-fon y a laissé un palais, des forêts sacrées et un lac où l’on vient encore déposer des offrandes.",
"see": [
"Le palais royal et la cour du roi d’Allada",
"Le lac sacré d’Awouto et ses légbas de bois",
"Le monument au père de Toussaint Louverture, né ici",
"Le grand marché, un jour sur cinq"
],
"when": "Toute l’année. Le 10 janvier, fête nationale du vodoun."
},
{
"id": "dogbo",
"name": "Dogbo",
"cat": "culture",
"dept": "Couffo",
"x": 299.0,
"y": 922.0,
"label": [
"Dogbo"
],
"cap": null,
"coord": "6°48′ N · 1°47′ E",
"desc": "Le site dit « des hommes à queue » : un réseau de galeries creusées dans la latérite, que la tradition attribue à un peuple de forgerons réfugié sous terre. Les traces d’extraction du fer sont encore lisibles sur les parois.",
"see": [
"Les galeries souterraines, avec un guide du village",
"Les scories de fer laissées par les fondeurs",
"La case d’accueil et le récit des anciens",
"Le marché de Dogbo et les palmeraies du Couffo"
],
"when": "Saison sèche, de novembre à mars : les galeries sont plus praticables."
},
{
"id": "savalou",
"name": "Savalou",
"cat": "culture",
"dept": "Collines",
"x": 329.1,
"y": 745.9,
"label": [
"Savalou"
],
"cap": null,
"coord": "7°56′ N · 1°59′ E",
"desc": "Capitale du royaume mahi, adossée à ses collines. La dynastie des Gbaguidi y règne depuis le XVIII<sup>e</sup> siècle, et la fête de l’igname du mois d’août est l’une des plus suivies du pays.",
"see": [
"Le palais royal et l’arbre généalogique des Gbaguidi",
"La fête de l’igname, à la mi-août",
"La grotte de Kpataba, refuge des habitants pendant les razzias",
"Le marché et ses huiles de palme"
],
"when": "Mi-août pour la fête de l’igname ; le reste de l’année, novembre à février."
},
{
"id": "save",
"name": "Savè",
"cat": "nature",
"dept": "Collines",
"x": 407.8,
"y": 729.3,
"label": [
"Savè"
],
"cap": null,
"coord": "8°02′ N · 2°29′ E",
"desc": "Des dômes de granit sortent de la plaine tout autour de la ville — les collines de Savè, devenues un petit terrain d’escalade. Le fleuve Ouémé passe juste à côté.",
"see": [
"Les dômes granitiques et la vue depuis le sommet",
"L’escalade, encadrée par les clubs locaux",
"Les berges de l’Ouémé",
"Le marché, sur l’axe Cotonou–Parakou"
],
"when": "Novembre à février : la roche est sèche et l’air plus clair."
},
{
"id": "tchaourou",
"name": "Tchaourou",
"cat": "ville",
"dept": "Borgou",
"x": 425.0,
"y": 596.2,
"label": [
"Tchaourou"
],
"cap": null,
"coord": "8°53′ N · 2°36′ E",
"desc": "La plus vaste commune du pays, étirée le long de la route du Nord. On s’y arrête pour le marché de bord de route, les rizières de Bétérou et les rives de l’Ouémé.",
"see": [
"Le marché de Tchaourou, au bord de la nationale",
"Bétérou et ses périmètres rizicoles",
"Les berges de l’Ouémé, spectaculaires en étiage",
"La forêt classée de l’Ouémé supérieur"
],
"when": "D’octobre à décembre, juste après les pluies : tout est vert."
},
{
"id": "taneka",
"name": "Tanéka-Béri",
"cat": "culture",
"dept": "Donga",
"x": 289.3,
"y": 448.5,
"label": [
"Tanéka-Béri"
],
"cap": null,
"coord": "9°50′ N · 1°43′ E",
"desc": "Un village accroché au flanc de la colline, fait de cases rondes en terre coiffées de chaume et surmontées d’un pot renversé. Les Taneka y ont gardé une organisation initiatique très structurée : on monte avec un guide du village.",
"see": [
"La montée dans le village et ses cases coniques",
"Les forgerons, caste à part et respectée",
"Les prêtres traditionnels et le bois sacré — accès encadré",
"La vue sur la plaine depuis le haut du village"
],
"when": "Novembre à février. Guide du village obligatoire, on ne visite pas seul."
},
{
"id": "tanguieta",
"name": "Tanguiéta",
"cat": "ville",
"dept": "Atacora",
"x": 219.9,
"y": 326.1,
"label": [
"Tanguiéta"
],
"cap": null,
"coord": "10°37′ N · 1°16′ E",
"desc": "La ville-carrefour au pied de l’Atacora, entre falaises et gorges. C’est d’ici qu’on entre dans la Pendjari, et la route qui y monte est déjà un spectacle en soi.",
"see": [
"La route des gorges, vers la Pendjari",
"Les chutes de Yéripao, à une vingtaine de kilomètres",
"Le marché, très animé le jour de foire",
"L’hôpital Saint-Jean-de-Dieu, connu dans toute la sous-région"
],
"when": "De décembre à avril, la saison des parcs."
},
{
"id": "aeroport",
"name": "Aéroport de Cotonou",
"cat": "arrivee",
"dept": "Littoral",
"x": 368.1,
"y": 986.1,
"label": [
"Aéroport"
],
"cap": null,
"coord": "6°21′ N · 2°23′ E",
"desc": "L’aéroport international Cardinal-Bernardin-Gantin est la porte d’entrée du pays. Il est posé dans Cotonou même, à quelques minutes de l’océan : pour beaucoup, le voyage commence ici, dans la chaleur qui tombe dessus dès la passerelle.",
"see": [
"Le change et la carte SIM, à régler avant de sortir du hall",
"Les taxis et les zémidjans à la sortie — la course se négocie avant de monter",
"La plage de Fidjrossè, à un quart d’heure de là"
],
"when": "Toute l’année. Les vols long-courriers partent surtout en soirée."
},
{
"id": "port",
"name": "Port autonome de Cotonou",
"cat": "arrivee",
"dept": "Littoral",
"x": 421.2,
"y": 998.4,
"label": [
"Port autonome"
],
"cap": null,
"coord": "6°21′ N · 2°26′ E",
"desc": "Le poumon économique du pays, et le débouché maritime de tout le Sahel : une grande part des marchandises du Niger et du Burkina Faso passe par ses quais. C’est aussi par là qu’arrivent les conteneurs envoyés depuis l’étranger.",
"see": [
"Les grues et les porte-conteneurs, vus depuis le boulevard de la Marina",
"Le port de pêche voisin, à l’aube, quand les barques rentrent",
"Le départ de la route qui monte vers le fleuve Niger"
],
"when": "Toute l’année. Le port ne se visite pas librement : il se regarde depuis la Marina."
}
];
const PHOTOS = {"grand-popo": {"src": "grand-popo.webp", "author": "Kulttuurinavigaattori", "license": "CC BY-SA 4.0", "page": "/assets/img/wikimedia/mono-river-estuary-in-avloh-grand-popo-benin.webp", "title": "Mono river estuary in Avloh, Grand-Popo, Benin.jpg", "ko": 31.9}, "ouidah": {"src": "ouidah.webp", "author": "Borisghost", "license": "CC0", "page": "/assets/img/wikimedia/porte-du-non-retour-au-benin.webp", "title": "Porte du non-retour au Benin.jpg", "ko": 42.5}, "cotonou": {"src": "cotonou.webp", "author": "Fawaz.tairou", "license": "CC BY-SA 4.0", "page": "/assets/img/wikimedia/lever-de-soleil-sur-l-esplanade-de-l-amazone-a-cotonou.webp", "title": "Lever de soleil sur l'Esplanade de l'amazone à Cotonou.jpg", "ko": 18.9}, "ganvie": {"src": "ganvie.webp", "author": "Dr. Ondřej Havelka (cestovatel)", "license": "CC BY-SA 4.0", "page": "/assets/img/wikimedia/the-village-of-ganvie-on-lake-nokoue.webp", "title": "The village of Ganvié on Lake Nokoué.jpg", "ko": 76.6}, "porto-novo": {"src": "porto-novo.webp", "author": "Gildaskiki", "license": "CC BY-SA 4.0", "page": "/assets/img/wikimedia/grande-mosquee-de-porto-novo-au-benin-01.webp", "title": "Grande Mosquée de Porto-Novo au Bénin 01.jpg", "ko": 55.8}, "aheme": {"src": "aheme.webp", "author": "Rachad sanoussi", "license": "CC BY-SA 4.0", "page": "/assets/img/wikimedia/lac-aheme-a-guezin.webp", "title": "Lac aheme à Guezin.jpg", "ko": 54.6}, "ketou": {"src": "ketou.webp", "author": "Jonathanbwilson1", "license": "CC BY-SA 4.0", "page": "/assets/img/wikimedia/gelede-masked-dancer-ketou-benin.webp", "title": "Gèlèdé masked dancer, Kétou, Benin.jpg", "ko": 61.9}, "lama": {"src": "lama.webp", "author": "Rolex2019", "license": "CC BY-SA 4.0", "page": "/assets/img/wikimedia/natural-forest-of-lama-in-benin.webp", "title": "Natural forest of Lama in Benin.jpg", "ko": 116.7}, "abomey": {"src": "abomey.webp", "author": "Karalyn Monteil", "license": "CC BY-SA 3.0 igo", "page": "/assets/img/wikimedia/royal-palaces-of-abomey-133471.webp", "title": "Royal Palaces of Abomey-133471.jpg", "ko": 36.5}, "dassa": {"src": "dassa.webp", "author": "Padonou Dotou", "license": "CC BY-SA 4.0", "page": "/assets/img/wikimedia/vue-de-dassa-depuis-la-colline-de-kamate.webp", "title": "Vue de Dassa depuis la colline de Kamaté.jpg", "ko": 45.1}, "parakou": {"src": "parakou.webp", "author": "UXrocks", "license": "CC BY 3.0", "page": "/assets/img/wikimedia/parakoumosque.webp", "title": "ParakouMosque.jpg", "ko": 46.3}, "nikki": {"src": "nikki.webp", "author": "Saliousoft", "license": "CC BY-SA 4.0", "page": "/assets/img/wikimedia/place-publique-pour-la-gaani-nikki-departement-du-borgou-benin.webp", "title": "Place publique pour la Gaani, Nikki, département du Borgou, Bénin.jpg", "ko": 70.8}, "djougou": {"src": "djougou.webp", "author": "Gregor Rom", "license": "CC BY-SA 4.0", "page": "/assets/img/wikimedia/stra-enbild-djougou.webp", "title": "Straßenbild djougou.JPG", "ko": 32.4}, "kota": {"src": "kota.webp", "author": "Ji-Elle", "license": "CC BY-SA 4.0", "page": "/assets/img/wikimedia/baignade-aux-chutes-de-kota.webp", "title": "Baignade aux chutes de Kota.jpg", "ko": 117.3}, "boukoumbe": {"src": "boukoumbe.webp", "author": "Justin ABADJAYE", "license": "CC BY-SA 4.0", "page": "/assets/img/wikimedia/tata-somba-la-maison-traditionnelle-a-etage.webp", "title": "Tata somba, la maison traditionnelle à étage.jpg", "ko": 69.5}, "natitingou": {"src": "natitingou.webp", "author": "Baliola", "license": "CC BY-SA 3.0", "page": "/assets/img/wikimedia/benin-natitingou.webp", "title": "Benin Natitingou.JPG", "ko": 55.6}, "tanougou": {"src": "tanougou.webp", "author": "Ji-Elle", "license": "CC BY-SA 4.0", "page": "/assets/img/wikimedia/chutes-de-tanougou-2.webp", "title": "Chutes de Tanougou (2).jpg", "ko": 102.9}, "pendjari": {"src": "pendjari.webp", "author": "Farid AMADOU BAHLEMAN", "license": "CC BY-SA 4.0", "page": "/assets/img/wikimedia/elephants-in-pendjari.webp", "title": "Eléphants in Pendjari.jpg", "ko": 90.1}, "parc-w": {"src": "parc-w.webp", "author": "Marco Schmidt", "license": "CC BY-SA 3.0", "page": "/assets/img/wikimedia/mekrou-river-in-w-national-park-ms-6380.webp", "title": "Mekrou river in W-National Park MS 6380.JPG", "ko": 87.6}, "malanville": {"src": "malanville.webp", "author": "Banasylve", "license": "CC BY-SA 4.0", "page": "/assets/img/wikimedia/on-the-edge-from-the-niger-river-malanville.webp", "title": "On the edge from the Niger river, Malanville.jpg", "ko": 55.9}, "allada": {"src": "allada.webp", "author": "Netho639", "license": "CC BY-SA 4.0", "page": "/assets/img/wikimedia/divinite-vodun-legba-lac-sacre-awouto-allada-benin.webp", "title": "Divinite-vodun-legba-lac-sacre-awouto-allada-benin.jpg", "ko": 53.0}, "dogbo": {"src": "dogbo.webp", "author": "Adoscam", "license": "CC BY-SA 4.0", "page": "/assets/img/wikimedia/salle-d-accueil-du-site-homme-a-queue-a-dogbo-benin.webp", "title": "Salle d'accueil du site homme à queue à Dogbo (Bénin).jpg", "ko": 84.8}, "savalou": {"src": "savalou.webp", "author": "MahugnonCam", "license": "CC BY-SA 4.0", "page": "/assets/img/wikimedia/monument-a-savalou.webp", "title": "Monument à Savalou.jpg", "ko": 30.1}, "save": {"src": "save.webp", "author": "OBA", "license": "CC BY-SA 3.0", "page": "/assets/img/wikimedia/les-collines-de-save.webp", "title": "Les Collines de save .jpg", "ko": 55.2}, "tchaourou": {"src": "tchaourou.webp", "author": "Saliousoft", "license": "CC BY-SA 4.0", "page": "/assets/img/wikimedia/scene-de-marche-commune-de-tchaourou-01.webp", "title": "Scène de marché, commune de Tchaourou 01.jpg", "ko": 27.9}, "taneka": {"src": "taneka.webp", "author": "Aimeabibis", "license": "CC BY-SA 4.0", "page": "/assets/img/wikimedia/l-entree-du-village-taneka-beri-1.webp", "title": "L'entrée du village Tanéka Béri 1.jpg", "ko": 98.3}, "tanguieta": {"src": "tanguieta.webp", "author": "Barke11", "license": "CC BY-SA 4.0", "page": "/assets/img/wikimedia/entree-de-la-ville-de-tanguieta.webp", "title": "Entrée de la ville de Tanguiéta.jpg", "ko": 68.8}, "aeroport": {"src": "aeroport.webp", "author": "Houss 2020", "license": "CC0", "page": "/assets/img/wikimedia/avion-royal-air-maroc-cotonou.webp", "title": "Avion de la Royal Air Maroc à Cotonou 03.jpg", "ko": 27.9}, "port": {"src": "port.webp", "author": "Fawaz.tairou", "license": "CC BY-SA 4.0", "page": "/assets/img/wikimedia/port-autonome-de-cotonou-vu-de-haut-1.webp", "title": "Port autonome de Cotonou vu de haut (1).jpg", "ko": 34.0}};

const SVGNS   = 'http://www.w3.org/2000/svg';
const svg     = document.getElementById('ba-map');
const gMark   = document.getElementById('ba-markers');
const plate   = document.getElementById('ba-plate');
const card    = document.getElementById('ba-placecard');
const legend  = document.getElementById('ba-legend');
const hint    = document.getElementById('ba-hint');
const routesEl= document.getElementById('ba-routes');
const track   = document.getElementById('ba-track');
const btnPrev = document.getElementById('ba-prev');
const btnNext = document.getElementById('ba-next');
const counter  = document.getElementById('ba-count');
const progress = document.getElementById('ba-progress');
const progFill = document.getElementById('ba-progressFill');

let selected = null;
let visible  = new Set(SITES.map(s => s.id));
let activeCats  = new Set(Object.keys(CATS));
let activeRoute = null;

function el(n, attrs, parent){
  const e = document.createElementNS(SVGNS, n);
  for(const k in attrs) e.setAttribute(k, attrs[k]);
  if(parent) parent.appendChild(e);
  return e;
}
function html(n, cls, parent){
  const e = document.createElement(n);
  if(cls) e.className = cls;
  if(parent) parent.appendChild(e);
  return e;
}
function glyphSVG(cat){
  const g = GLYPHS[cat];
  return `<svg class="glyph" viewBox="0 0 24 24" aria-hidden="true"><path d="${g.d}" `
       + (g.solid ? 'fill="currentColor"' :
          'fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"')
       + `/></svg>`;
}

/* ---------- les repères ---------- */
const R = 12.5;                       /* rayon de la tête du repère */
function pinPath(x, y, r){
  const cy = y - r - 1.5;
  return `M${x},${y} C${x-r*.416},${y-r*.512} ${x-r},${cy+r*0.62} ${x-r},${cy}`
       + ` a${r},${r} 0 1,1 ${r*2},0`
       + ` C${x+r},${cy+r*0.62} ${x+r*.416},${y-r*.512} ${x},${y} Z`;
}

/* Les lieux qui font venir : ils portent un repère pleine taille et un nom
   plus appuyé. Les autres s'effacent d'un cran — sans hiérarchie, vingt-sept
   pastilles identiques ne se lisent pas. */
/* la durée d'un aller, lue dans la feuille de style : changer la variable
   suffit, les décalages suivent */
/* ————————————————————————————————————————————————————————————
   OÙ SONT LES PHOTOS
   Seule ligne à changer quand la section est posée dans un projet :
   mettre le chemin public du dossier, par exemple '/assets/benin/'.
   (Sans effet sur la version tout-en-un, dont les images sont
   embarquées dans le fichier.)
   ———————————————————————————————————————————————————————————— */
const PHOTOS_BASE = 'assets/atlas-photos/';
const urlPhoto = ph => ph.src.startsWith('data:') ? ph.src : PHOTOS_BASE + ph.src;

const cycle = parseFloat(getComputedStyle(document.documentElement)
                .getPropertyValue('--ba-flot-vitesse')) || 2.4;

const MAJEURS = new Set(['abomey','pendjari','parc-w','boukoumbe','tanougou','dassa',
                         'parakou','ouidah','ganvie','cotonou','porto-novo']);

SITES.forEach((s, i) => {
  /* Dans l'encart du littoral tout est vu 2,4 fois plus gros : le repère y est
     donc dessiné plus petit, pour ne pas manger la côte. */
  s.r  = MAJEURS.has(s.id) ? R    : R*0.80;
  s.fk = MAJEURS.has(s.id) ? 1.06 : 0.92;

  const g = el('g', {class:'pin' + (MAJEURS.has(s.id) ? ' pin-maj' : ''),
                     'data-id':s.id, tabindex:'0', role:'button',
                     'aria-label':`${s.name}, ${CATS[s.cat].label}`}, gMark);
  g.style.setProperty('--ba-k', CATS[s.cat].k);
  g.style.animationDelay = (0.3 + i*0.03) + 's';
  // les phases se répartissent sur le cycle réglé en CSS, quel qu'il soit
  g.style.setProperty('--ba-phase', -((i * cycle / 7.7) % cycle).toFixed(2) + 's');
  g.addEventListener('animationend', () => { g.style.animationDelay = ''; }, {once:true});

  const tag  = el('g', {class:'tag'}, g);
  const txt  = el('text', {}, tag);
  s.label.forEach((line, j) => {
    const ts = el('tspan', {x:0, y:0}, txt);
    if(s.cap && j === 0) el('tspan', {class:'etoile'}, ts).textContent = '★ ';
    ts.appendChild(document.createTextNode(line));
  });

  /* Un groupe intermédiaire rien que pour l'échelle : sur mobile la carte
     tombe à 344 px et un repère y ferait 5 px de large, impossible à viser.
     On ne peut pas passer par une transform CSS, l'animation d'apparition et
     le survol occupent déjà celles de .pin et de .art. */
  const ech = el('g', {class:'ech'}, g);
  const r = s.r, k = r / R;
  el('ellipse', {class:'shade', cx:s.x, cy:s.y + 1.5, rx:6.6*k, ry:2.4*k}, ech);
  const art = el('g', {class:'art'}, ech);
  const flot = el('g', {class:'flot'}, art);
  const cy  = s.y - r - 1.5;
  el('circle', {class:'halo', cx:s.x, cy:cy, r:r+10*k}, flot);
  el('path',   {class:'body', d:pinPath(s.x, s.y, r)}, flot);
  el('circle', {class:'disc', cx:s.x, cy:cy, r:6.9*k}, flot);
  const gl = GLYPHS[s.cat], sc = 0.46*k;
  el('path', {class: gl.solid ? 'glyph-solid' : 'glyph-line', d:gl.d,
              transform:`translate(${s.x - 12*sc},${cy - 12*sc}) scale(${sc})`}, flot);
  el('circle', {class:'ring', cx:s.x, cy:cy, r:r+4*k}, flot);
  el('circle', {class:'hit',  cx:s.x, cy:cy, r:r+6*k}, ech);

  g.addEventListener('click', () => select(s.id));
  g.addEventListener('keydown', e => {
    if(e.key === 'Enter' || e.key === ' '){ e.preventDefault(); select(s.id); }
  });
  s._g = g; s._tag = {txt}; s._cy = cy; s._ech = ech;
});

/* ---------- placement automatique des noms ----------
   Chaque nom essaie huit positions autour de son repère et prend la première
   qui ne heurte rien : ni un autre repère, ni un nom déjà posé, ni un toponyme
   de la carte. À la main, vingt-sept noms se chevauchent toujours quelque part ;
   et la moindre retouche du fond remet tout par terre. */
const POSES = [
  {ax: 1, ay: 0, a:'start'},  {ax:-1, ay: 0, a:'end'},
  {ax: 1, ay:-1, a:'start'},  {ax:-1, ay:-1, a:'end'},
  {ax: 1, ay: 1, a:'start'},  {ax:-1, ay: 1, a:'end'},
  {ax: 0, ay:-1, a:'middle'}, {ax: 0, ay: 1, a:'middle'},
];

/* La boîte d'un élément une fois ses transformations appliquées, exprimée
   dans le repère du SVG. getBBox() seul rend la boîte AVANT rotation. */
function boiteReelle(n){
  const b = n.getBBox();
  const m = n.transform.baseVal.consolidate();
  if(!m) return {x:b.x, y:b.y, w:b.width, h:b.height};
  const t = m.matrix;
  const xs = [], ys = [];
  for(const [px, py] of [[b.x, b.y], [b.x + b.width, b.y],
                         [b.x, b.y + b.height], [b.x + b.width, b.y + b.height]]){
    xs.push(t.a * px + t.c * py + t.e);
    ys.push(t.b * px + t.d * py + t.f);
  }
  return {x:Math.min(...xs), y:Math.min(...ys),
          w:Math.max(...xs) - Math.min(...xs), h:Math.max(...ys) - Math.min(...ys)};
}

function chevauche(a, b, m){
  return a.x - m < b.x + b.w && a.x + a.w + m > b.x &&
         a.y - m < b.y + b.h && a.y + a.h + m > b.y;
}
function aire(a, b){
  const w = Math.min(a.x+a.w, b.x+b.w) - Math.max(a.x, b.x);
  const h = Math.min(a.y+a.h, b.y+b.h) - Math.max(a.y, b.y);
  return w > 0 && h > 0 ? w*h : 0;
}

function layoutTags(fs){
  const feuille = {x:6, y:6, w:svg.viewBox.baseVal.width-12, h:svg.viewBox.baseVal.height-12};

  /* Le fond de carte est un obstacle comme un autre : toponymes, reliefs,
     hydronymes, échelle. On les relève une fois. */
  const obstacles = [...svg.querySelectorAll(
      '.ville text,.oro,.hydro,.geo-label,.credits,.scale-t')].map(boiteReelle);

  /* ...et tous les repères, y compris ceux dont le nom n'est pas encore posé. */
  SITES.forEach(s => obstacles.push({x:s.x - s.r, y:s._cy - s.r,
                                     w:s.r*2, h:(s.y - s._cy) + s.r}));

  /* Les lieux majeurs choisissent leur place en premier : ce sont eux qui
     doivent tomber juste. */
  const ordre = [...SITES].sort((a, b) =>
    (MAJEURS.has(b.id) - MAJEURS.has(a.id)) || (a.y - b.y));

  ordre.forEach(s => {
    const {txt} = s._tag;
    const f = Math.round(fs * s.fk * 10) / 10;
    const lineH = f * 1.14, n = s.label.length;
    txt.setAttribute('font-size', f);
    txt.setAttribute('text-anchor', 'middle');
    [...txt.children].forEach((ts, j) => { ts.setAttribute('x', 0); ts.setAttribute('y', j*lineH); });
    const w = txt.getBBox().width, h = n*lineH;
    const marge = 2.2, borne = feuille;

    /* Huit directions, essayées à trois distances : quand le voisinage immédiat
       est pris, on écarte le nom d'un cran plutôt que de le laisser se
       superposer — c'est ce qui débloque les grappes comme Dogbo/Aplahoué. */
    let best = null, bestMal = Infinity;
    boucle:
    for(const ecart of [0, 9, 19, 32]){
      for(const p of POSES){
        // point d'accroche : le flanc de la tête, son sommet, ou la pointe
        const ox = s.x + p.ax * (s.r + 4 + (p.ay ? 0 : 2) + ecart);
        const oy = p.ay < 0 ? s._cy - s.r - 5 - ecart - h + f*0.82
                 : p.ay > 0 ? s.y + 4 + ecart + f*0.82
                 : s._cy - h/2 + f*0.82;
        const bx = p.a === 'start' ? ox : p.a === 'end' ? ox - w : ox - w/2;
        const box = {x:bx, y:oy - f*0.82, w, h};
        let mal = ecart * 0.7 + POSES.indexOf(p) * 0.5;   // à égalité : près, et à l'est
        if(box.x < borne.x || box.y < borne.y ||
           box.x + box.w > borne.x + borne.w || box.y + box.h > borne.y + borne.h) mal += 1e5;
        for(const o of obstacles) if(chevauche(box, o, marge)) mal += 100 + aire(box, o);
        if(mal < bestMal){ bestMal = mal; best = {p, ox, oy, box}; }
        if(mal < 10) break boucle;
      }
    }
    txt.setAttribute('text-anchor', best.p.a);
    [...txt.children].forEach((ts, j) => {
      ts.setAttribute('x', best.ox); ts.setAttribute('y', best.oy + j*lineH);
    });
    obstacles.push(best.box);
    s._pose = bestMal;
  });
}

let compact = null;
function responsive(){
  const w = svg.getBoundingClientRect().width;
  if(!w) return;
  const c = w < 560;
  if(c === compact) return;
  compact = c;
  plate.classList.toggle('compact', c);
  /* Sur petit écran on grossit les repères là où ils sont : sans ça ils font
     cinq pixels et le doigt ne peut pas les viser. */
  SITES.forEach(s => s._ech.setAttribute('transform', c
    ? `translate(${s.x},${s.y}) scale(1.75) translate(${-s.x},${-s.y})` : ''));
  hint.textContent = c ? 'Touchez un repère pour ouvrir la fiche du lieu'
                       : 'Cliquez un repère pour ouvrir la fiche du lieu';
  layoutTags(c ? 30 : 14);
  carState();
}

/* ---------- la couche des ressources ---------- */
const resBtn = document.getElementById('ba-resToggle');
resBtn.addEventListener('click', () => {
  const on = resBtn.getAttribute('aria-pressed') === 'true';
  resBtn.setAttribute('aria-pressed', !on);
  plate.classList.toggle('no-res', on);
});

/* ---------- filtres : les familles ---------- */
Object.keys(CATS).forEach(c => {
  const b = html('button', 'chip', legend);
  b.type = 'button';
  b.style.setProperty('--ba-k', CATS[c].k);
  b.setAttribute('aria-pressed', 'true');
  b.innerHTML = glyphSVG(c) + CATS[c].label;
  b.addEventListener('click', () => {
    if(activeRoute){ activeRoute = null; activeCats = new Set([c]); }
    else if(activeCats.has(c)) activeCats.delete(c);
    else activeCats.add(c);
    if(!activeCats.size) activeCats = new Set(Object.keys(CATS));
    apply();
  });
  CATS[c]._chip = b;
});

/* ---------- filtres : les quatre fils ---------- */
ROUTES.forEach(r => {
  const b = html('button', 'fil', routesEl);
  b.type = 'button';
  b.style.setProperty('--ba-k', CATS[r.cat].k);
  b.setAttribute('aria-pressed', 'false');
  b.title = r.title + " — " + r.sub;
  b.setAttribute('aria-label', r.title);
  b.innerHTML = glyphSVG(r.cat) + r.court;
  b.addEventListener('click', () => {
    activeRoute = (activeRoute === r.id) ? null : r.id;
    activeCats = new Set(Object.keys(CATS));
    apply();
  });
  r._btn = b;
});

/* ---------- le carrousel : une grande carte à la fois ---------- */
SITES.forEach(s => {
  const ph = PHOTOS[s.id];
  const b = html('button', 'pcard', track);
  b.type = 'button';
  b.dataset.id = s.id;
  b.style.setProperty('--ba-k', CATS[s.cat].k);
  b.setAttribute('aria-label', s.name + " — ouvrir la fiche sur la carte");
  b.innerHTML =
    `${ph ? `<span class="fig">
       <img src="${urlPhoto(ph)}" alt="${s.name} — ${ph.title.replace(/\.[a-z]+$/i,'')}" loading="lazy" decoding="async">
       <span class="fig-tag">${glyphSVG(s.cat)}${CATS[s.cat].label.split(' ')[0]}</span>
     </span>` : ''}
     <span class="hold">
       <span class="bar"></span>
       <span class="pcard-body">
         <span class="dept">${s.dept}</span>
         <span class="ttl">${s.name}</span>
         <span class="txt">${s.desc}</span>
         <span class="go">Voir sur la carte
           <svg viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M6 3l5 5-5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
         </span>
       </span>
     </span>`;
  b.addEventListener('click', () => select(s.id));
  s._card = b;
});

function slides(){ return [...track.querySelectorAll('.pcard:not(.hidden)')]; }
function stepW(){
  const a = slides()[0];
  return a ? a.offsetWidth + (parseFloat(getComputedStyle(track).columnGap) || 0) : 1;
}
function curIdx(){ return Math.round(track.scrollLeft / stepW()); }

/* `cible` est la diapositive visée. On ne la relit depuis la position réelle
   qu'une fois le défilement arrêté : sinon deux clics rapides sur la flèche
   se marcheraient dessus, chacun repartant d'une animation en cours. */
let cible = 0, viseeA = -1e9;
function goTo(i, smooth){
  const n = slides().length;
  cible = Math.max(0, Math.min(n - 1, i));
  viseeA = performance.now();
  track.scrollTo({left: cible * stepW(), behavior: (smooth && !reduceMotion) ? 'smooth' : 'instant'});
  carState();
}
btnPrev.addEventListener('click', () => goTo(cible - 1, true));
btnNext.addEventListener('click', () => goTo(cible + 1, true));
track.addEventListener('keydown', e => {
  if(e.key === 'ArrowRight'){ e.preventDefault(); goTo(cible + 1, true); }
  if(e.key === 'ArrowLeft'){  e.preventDefault(); goTo(cible - 1, true); }
});

function carState(){
  const n = slides().length;
  const i = cible = Math.max(0, Math.min(cible, n - 1));
  btnPrev.disabled = i <= 0;
  btnNext.disabled = i >= n - 1;
  counter.textContent = n ? String(i + 1).padStart(2,'0') + ' / ' + String(n).padStart(2,'0') : '—';
  progFill.style.width = n > 1 ? (i / (n - 1) * 100) + '%' : '100%';
  progress.setAttribute('aria-valuenow', i + 1);
  progress.setAttribute('aria-valuemax', Math.max(n, 1));
  progress.setAttribute('aria-valuetext', n ? `Fiche ${i + 1} sur ${n}` : 'aucune fiche');
}

/* La barre est aussi une commande : on peut y viser une fiche, glisser le
   long, ou la piloter aux flèches du clavier. */
function viserBarre(clientX){
  const n = slides().length;
  if(n < 2) return;
  const r = progress.getBoundingClientRect();
  const t = Math.max(0, Math.min(1, (clientX - r.left) / r.width));
  goTo(Math.round(t * (n - 1)), true);
}
let glisse = false;
progress.addEventListener('pointerdown', e => {
  glisse = true;
  try { progress.setPointerCapture(e.pointerId); } catch(_){}   // pointeur synthétique
  viserBarre(e.clientX);
});
progress.addEventListener('pointermove', e => { if(glisse) viserBarre(e.clientX); });
progress.addEventListener('pointerup',     () => { glisse = false; });
progress.addEventListener('pointercancel', () => { glisse = false; });
progress.addEventListener('keydown', e => {
  const pas = {ArrowLeft:-1, ArrowRight:1, ArrowUp:1, ArrowDown:-1}[e.key];
  if(pas){ e.preventDefault(); goTo(cible + pas, true); return; }
  if(e.key === 'Home'){ e.preventDefault(); goTo(0, true); }
  if(e.key === 'End'){ e.preventDefault(); goTo(slides().length - 1, true); }
});
/* la carte visible allume discrètement son repère sur le plan */
function spotlight(){
  const a = slides()[cible];
  const id = a ? a.dataset.id : null;
  SITES.forEach(x => x._g.classList.toggle('hov', x.id === id));
}
let settle;
track.addEventListener('scroll', () => {
  clearTimeout(settle);
  settle = setTimeout(() => {
    const i = curIdx();
    /* défilement à la main (molette, doigt, glissé) : la position fait foi */
    if(i !== cible && performance.now() - viseeA > 900) cible = i;
    /* une animation coupée par le clic suivant laisse le rail entre deux
       diapositives : on le recale toujours sur la diapositive visée */
    if(Math.abs(track.scrollLeft - cible * stepW()) > 6)
      track.scrollTo({left: cible * stepW(), behavior:'instant'});
    carState(); spotlight();
  }, 130);
}, {passive:true});

/* glisser-déposer à la souris, comme un vrai carrousel */
let dragging = false, startX = 0, startLeft = 0, moved = 0;
track.addEventListener('pointerdown', e => {
  if(e.pointerType === 'touch') return;              /* le tactile natif suffit */
  dragging = true; moved = 0; startX = e.clientX; startLeft = track.scrollLeft;
  track.classList.add('dragging'); track.setPointerCapture(e.pointerId);
});
track.addEventListener('pointermove', e => {
  if(!dragging) return;
  const d = e.clientX - startX;
  moved = Math.max(moved, Math.abs(d));
  track.scrollLeft = startLeft - d;
});
const endDrag = () => {
  if(!dragging) return;
  dragging = false; track.classList.remove('dragging');
  goTo(curIdx(), true);
};
track.addEventListener('pointerup', endDrag);
track.addEventListener('pointercancel', endDrag);
/* un glissé ne doit pas être pris pour un clic sur la carte */
track.addEventListener('click', e => { if(moved > 6){ e.preventDefault(); e.stopPropagation(); moved = 0; } }, true);

/* Le bouton « Tout voir » est désormais un lien direct vers
 * /explorez/la-vie-semerveille-au-benin — plus de reset in-place ici. */

function apply(){
  const route = ROUTES.find(r => r.id === activeRoute);
  visible = new Set(route ? route.ids : SITES.filter(s => activeCats.has(s.cat)).map(s => s.id));
  Object.keys(CATS).forEach(c =>
    CATS[c]._chip.setAttribute('aria-pressed', !route && activeCats.has(c)));
  ROUTES.forEach(r => r._btn.setAttribute('aria-pressed', r.id === activeRoute));
  SITES.forEach(s => {
    const vu = visible.has(s.id);
    s._g.classList.toggle('dim', !vu);
    s._g.setAttribute('tabindex', vu ? '0' : '-1');
    s._g.setAttribute('aria-hidden', vu ? 'false' : 'true');
    s._card.classList.toggle('hidden', !vu);
  });
  if(selected && !visible.has(selected)) select(null);
  const survivant = selected ? SITES.find(s => s.id === selected) : null;
  goTo(survivant ? Math.max(0, slides().indexOf(survivant._card)) : 0, false);
  requestAnimationFrame(() => { carState(); spotlight(); });
}

/* ---------- sélection : la fiche sort sur la carte ---------- */
const deps = [...document.querySelectorAll('.dep')];

function select(id){
  selected = id;
  const s = SITES.find(x => x.id === id);
  SITES.forEach(x => {
    x._g.classList.toggle('sel', x.id === id);
    x._card.setAttribute('aria-current', x.id === id);
  });
  deps.forEach(d => d.classList.toggle('on', !!s && d.dataset.name === s.dept));
  plate.classList.toggle('has-card', !!s);
  if(!s){ card.setAttribute('data-open', 'false'); return; }
  s._g.parentNode.appendChild(s._g);            /* le repère choisi passe au-dessus */

  /* la fiche se pose à l'opposé du repère, pour ne jamais le masquer */
  card.setAttribute('data-v', s.y < 556 ? 'bottom' : 'top');
  card.setAttribute('data-h', s.x > 380 ? 'left'   : 'right');
  card.style.setProperty('--ba-k', CATS[s.cat].k);
  const ph = PHOTOS[s.id];
  card.innerHTML =
    `<button class="pc-close" type="button" aria-label="Fermer la fiche">&times;</button>
     ${ph ? `<figure class="pc-fig"><img src="${urlPhoto(ph)}" alt="${s.name}"></figure>` : ''}
     <div class="pc-kicker">${glyphSVG(s.cat)}<span>${CATS[s.cat].label}</span></div>
     <h3>${s.name}</h3>
     <p class="pc-meta">${s.cap ? s.cap + ' · ' : ''}Département ${s.dept} · ${s.coord}</p>
     <p class="pc-desc">${s.desc}</p>
     <ul class="pc-see">${s.see.map(x => `<li>${x}</li>`).join('')}</ul>
     <p class="pc-when"><b>Quand&nbsp;:</b> ${s.when}</p>`;
  card.querySelector('.pc-close').addEventListener('click', () => select(null));
  card.setAttribute('data-open', 'true');
  /* le carrousel amène la carte du lieu choisi devant */
  const i = slides().indexOf(s._card);
  if(i >= 0) goTo(i, true);
}

svg.addEventListener('click', e => { if(!e.target.closest('.pin')) select(null); });
document.addEventListener('keydown', e => { if(e.key === 'Escape' && selected) select(null); });

const reduceMotion = matchMedia('(prefers-reduced-motion: reduce)').matches;

/* ---------- démarrage ---------- */
responsive();
new ResizeObserver(responsive).observe(plate);
if(document.fonts && document.fonts.ready)
  document.fonts.ready.then(() => layoutTags(compact ? 30 : 14));
apply();
addEventListener('load', () => { carState(); spotlight(); });
/* au redimensionnement la largeur d'une diapositive change : on recale dessus */
addEventListener('resize', () => goTo(cible, false));
})();
