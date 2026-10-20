<?php

declare(strict_types=1);

/**
 * Fiches détaillées des grands rendez-vous culturels du Bénin.
 *
 * Contenu rédactionnel original rédigé à partir de faits publics :
 * dates traditionnelles, lieux, thématiques. Chaque fiche renvoie vers
 * le site officiel de l'événement pour la billetterie et le programme.
 */
return [

    'vodun-days' => [
        'title'        => 'Vodun Days',
        'subtitle'     => 'Le rendez-vous international du vodun',
        'lieu'         => 'Ouidah · Bénin',
        'dates'        => '02 → 09 janvier 2027',
        'themeCouleur' => '#e8112d',
        'hero'         => '/assets/img/arene-de-ouidah-1024x576.jpg',
        'siteOfficiel' => 'https://vodundays.bj/',
        'description'  => 'Les Vodun Days rassemblent chaque année à Ouidah les héritiers du culte vodun, des artistes venus de toute la diaspora, et des visiteurs du monde entier autour de la spiritualité, des arts et de la mémoire.',
        'chapo'        => 'Une semaine où la ville de Ouidah devient la capitale mondiale du vodun. Cérémonies, concerts, expositions, forums académiques et défilés se succèdent sur la Route de l\'Esclave, dans les temples, sur les plages et jusqu\'à la Porte du Non-Retour.',

        'blocks' => [
            [
                'titre' => 'L\'esprit de l\'événement',
                'texte' => 'Le vodun n\'est pas un folklore pour touristes. C\'est une religion vivante, pratiquée par une majorité de Béninois, et l\'un des piliers spirituels du golfe de Guinée. Les Vodun Days ont été créés pour redonner sa place à ce patrimoine — en le montrant tel qu\'il se vit, sans caricature, à travers ses gardiens et ses artistes contemporains.',
            ],
            [
                'titre' => 'Ce qui vous attend',
                'texte' => 'Cérémonies traditionnelles ouvertes au public, danses masquées zangbéto et gèlèdè, défilé de la flamme de la mémoire depuis le fort portugais jusqu\'à la plage, concerts en soirée, marché d\'artisans et de plantes rituelles, forums avec chercheurs et prêtres vodun. Chaque édition ajuste son programme autour du 10 janvier, journée nationale du vodun.',
            ],
            [
                'titre' => 'Y aller',
                'texte' => 'Ouidah est à 42 km à l\'ouest de Cotonou, soit 45 min à 1 h en voiture par la route côtière. Réservez votre hébergement dès l\'annonce du programme : les hôtels de Ouidah et Grand-Popo se remplissent très vite pendant la semaine. Dahomey Discovery organise des séjours en immersion pour toute la durée du festival.',
            ],
        ],

        'programme' => [
            ['jour' => 'Jour 1',   'titre' => 'Arrivées & Ouverture officielle', 'lieu' => 'Fort portugais',    'texte' => 'Cérémonie d\'ouverture avec les autorités traditionnelles, allocutions, allumage de la flamme de la mémoire.'],
            ['jour' => 'Jour 2-3', 'titre' => 'Sorties de masques',              'lieu' => 'Ville de Ouidah',    'texte' => 'Sorties gèlèdè le jour, zangbéto à la lumière des lampes-tempête la nuit. Codes rituels précis, guide local recommandé.'],
            ['jour' => 'Jour 4',   'titre' => 'Route de l\'Esclave',              'lieu' => 'Ouidah → plage',    'texte' => 'Défilé mémoriel sur les 4 km qui séparent le centre de la Porte du Non-Retour. Arrêts aux stations symboliques.'],
            ['jour' => 'Jour 5-6', 'titre' => 'Forums & concerts',               'lieu' => 'Places & scènes',   'texte' => 'Tables rondes en journée avec chercheurs et prêtres vodun, concerts en soirée mêlant musique traditionnelle et scènes actuelles.'],
            ['jour' => '10 janvier', 'titre' => 'Journée nationale du vodun',     'lieu' => 'Plage de Ouidah',   'texte' => 'Grande cérémonie de clôture. Bain rituel dans l\'océan pour ceux qui le souhaitent, offrande collective sur la plage.'],
        ],

        'tips' => [
            ['ic' => '👕', 'label' => 'Tenue',     'texte' => 'Blanc conseillé pour les cérémonies. Épaules couvertes dans les enceintes rituelles.'],
            ['ic' => '📸', 'label' => 'Photo',     'texte' => 'Toujours demander l\'autorisation avant de photographier une personne masquée ou en transe.'],
            ['ic' => '💵', 'label' => 'Paiement',  'texte' => 'Prévoir du liquide (F CFA). Peu de commerces acceptent la carte à Ouidah.'],
            ['ic' => '🌡️', 'label' => 'Météo',     'texte' => 'Saison sèche. 30 °C en journée, 22 °C la nuit. Chapeau et crème solaire obligatoires.'],
            ['ic' => '🚗', 'label' => 'Transport', 'texte' => 'Voiture avec chauffeur recommandée : les sites sont dispersés dans la ville et jusqu\'à la plage.'],
        ],

        'combinerAvec' => [
            ['slug' => 'festival-des-masques', 'titre' => 'Festival des Masques', 'note' => 'Continuité thématique sur les masques du golfe de Guinée.'],
            ['slug' => 'jistna',               'titre' => 'JISTNA',                 'note' => 'Pour approfondir le lien vodun-nature-guérison.'],
        ],

        'stats' => [
            ['nb' => '6-8', 'label' => 'jours de programme'],
            ['nb' => '2019', 'label' => 'année de création'],
            ['nb' => '40+', 'label' => 'pays représentés'],
            ['nb' => '10 janv.', 'label' => 'journée nationale'],
        ],

        'ambiance' => [
            'titre' => 'L\'atmosphère de Ouidah pendant la semaine',
            'texte' => 'Ouidah change de peau pour les Vodun Days. Les rues du centre historique se remplissent de percussions, les temples familiaux ouvrent aux visiteurs de bonne foi, l\'odeur de l\'encens et des feuilles brûlées flotte sur la ville. La nuit, la Route de l\'Esclave s\'illumine de torches, et sur la plage on voit briller au loin la silhouette de la Porte du Non-Retour.',
            'points' => [
                'Percussions et chants dès la fin d\'après-midi dans la ville basse',
                'Cérémonies familiales ouvertes aux respectueux, tenue blanche recommandée',
                'Défilé mémoriel de plusieurs kilomètres entre centre et plage',
                'Rassemblement final sur le sable — bain rituel pour ceux qui le souhaitent',
            ],
            'photos' => [
                '/assets/img/arene-de-ouidah-1024x576.jpg',
                '/assets/img/vodun-1200x675.webp',
                '/assets/img/ouidah-porte-1200x600.webp',
            ],
        ],

        'lieux' => [
            ['nom' => 'Fort portugais', 'desc' => 'Point de départ symbolique du défilé de la mémoire. Ancien poste négrier reconverti en musée.', 'photo' => '/assets/img/ouidah-porte-640x480.webp'],
            ['nom' => 'Route de l\'Esclave', 'desc' => 'Quatre kilomètres jalonnés de stations symboliques entre la ville et l\'océan.', 'photo' => '/assets/img/ouidah-porte-1200x600.webp'],
            ['nom' => 'Porte du Non-Retour', 'desc' => 'Monument sur la plage. Grand rassemblement de clôture face à l\'océan.', 'photo' => '/assets/img/plage-grand-popo-640x480.webp'],
            ['nom' => 'Forêt sacrée de Kpassè', 'desc' => 'Statues et arbres sacrés qui accueillent des cérémonies pendant la semaine.', 'photo' => '/assets/img/vodun-640x480.webp'],
        ],

        'tempsForts' => [
            ['titre' => 'Ouverture officielle', 'sous' => 'Vague 1 · lundi', 'texte' => 'Discours des autorités traditionnelles et de l\'État, salut aux délégations étrangères, allumage de la flamme de la mémoire.'],
            ['titre' => 'Danses et sorties de masques', 'sous' => 'Vague 2 · mardi & mercredi', 'texte' => 'Gèlèdè le jour, zangbéto la nuit. Chants codifiés, tambours qui se répondent à travers les quartiers.'],
            ['titre' => 'Défilé mémoriel', 'sous' => 'Vague 3 · jeudi', 'texte' => 'Marche silencieuse de la ville jusqu\'à la plage, avec haltes aux stations symboliques.'],
            ['titre' => 'Scènes musicales', 'sous' => 'Vague 4 · vendredi & samedi', 'texte' => 'Musiques traditionnelles revisitées, jazz d\'inspiration vodun, chœurs mixtes. Programmation entièrement dévoilée en amont sur le site officiel.'],
            ['titre' => 'Grande cérémonie de clôture', 'sous' => 'Vague 5 · 10 janvier', 'texte' => 'Rassemblement final sur la plage. Bain rituel possible, offrande collective, dernier chant des officiants.'],
        ],

        'histoire' => [
            'titre' => 'Comment est né l\'événement',
            'paragraphs' => [
                'Le vodun est reconnu religion nationale depuis 1997. La journée du 10 janvier devient férié dans la foulée, mais l\'événement reste longtemps circonscrit à Ouidah et à ses temples. Il fallait un cadre plus large pour montrer que le vodun est autre chose qu\'une image d\'Épinal — une pratique vivante, contemporaine, en dialogue avec les arts et la science.',
                'Les Vodun Days sont lancés en 2022 sur cette ambition. En trois éditions, la manifestation s\'est imposée comme le grand rendez-vous international du vodun, avec une programmation qui mêle rituels, forums académiques et créations d\'artistes contemporains issus du Bénin et de sa diaspora.',
            ],
        ],

        'autour' => [
            'titre' => 'Autour de l\'événement',
            'points' => [
                ['t' => 'Le musée de la Fondation Zinsou', 'd' => 'À une heure de route à Cotonou. Trois étages consacrés à l\'art contemporain africain — parfait complément à la semaine vodun.'],
                ['t' => 'Grand-Popo à 40 min', 'd' => 'Plage, mangrove, hôtels de charme. Idéal pour souffler entre deux journées à Ouidah.'],
                ['t' => 'Le musée d\'histoire de Ouidah', 'd' => 'Installé dans le fort portugais. Salle dédiée à la traite négrière et aux royaumes du golfe.'],
                ['t' => 'Le Temple des Pythons', 'd' => 'Face à la basilique — visite courte mais marquante, symbole vivant du culte vodun urbain.'],
            ],
        ],

        'surPlace' => [
            'titre' => 'Sur place',
            'points' => [
                ['ic' => '🍽️', 'label' => 'Manger', 'texte' => 'Maquis autour de la Place du Souvenir. Cuisine béninoise abordable, poisson braisé et akassa.'],
                ['ic' => '🩹', 'label' => 'Sécurité', 'texte' => 'Ouidah est calme mais les foules cachent parfois les pickpockets. Sac fermé, téléphone en poche intérieure.'],
                ['ic' => '♿', 'label' => 'Accessibilité', 'texte' => 'Sables et pistes rugueuses sur la Route de l\'Esclave. Fauteuil roulant peu adapté sans accompagnement.'],
                ['ic' => '💳', 'label' => 'Paiement', 'texte' => 'Cash en priorité (F CFA). Un distributeur BOA à l\'entrée de la ville, souvent en rupture pendant l\'événement.'],
            ],
        ],

        'faq' => [
            ['q' => 'Faut-il être invité pour assister aux cérémonies ?', 'r' => 'Non — la plupart des moments publics sont ouverts à tous. Seules certaines cérémonies d\'initiation restent réservées aux initiés.'],
            ['q' => 'Peut-on venir avec des enfants ?', 'r' => 'Oui, les journées se prêtent aux familles. Les sorties nocturnes de masques sont plus intenses — à réserver aux ados et adultes.'],
            ['q' => 'La photo est-elle autorisée ?', 'r' => 'Elle l\'est sur les scènes publiques et défilés. Elle est interdite ou déconseillée dans les temples et pendant les transes rituelles. Toujours demander.'],
            ['q' => 'Combien de jours prévoir ?', 'r' => 'Un séjour de 4 jours autour du 10 janvier permet de vivre les moments-clés. La semaine entière si vous voulez tout suivre.'],
            ['q' => 'Peut-on venir sans réserver ?', 'r' => 'Non. Ouidah affiche complet dès novembre. Réservez hébergement et guide dès l\'annonce du programme.'],
        ],
    ],

    'weloveya' => [
        'title'        => 'Weloveya',
        'subtitle'     => 'Le festival musique de la fin d\'année',
        'lieu'         => 'Cotonou · Bénin',
        'dates'        => '26 → 27 décembre 2026',
        'themeCouleur' => '#fcd116',
        'hero'         => '/assets/img/weloveeya-1024x683.jpg',
        'siteOfficiel' => 'https://www.weloveyafestival.com/',
        'description'  => 'Weloveya est le grand rassemblement musique et culture urbaine du Bénin — deux journées en fin d\'année qui réunissent têtes d\'affiche continentales et jeune scène béninoise sur la plage de Cotonou.',
        'chapo'        => 'Entre Noël et le Nouvel An, Cotonou vibre au rythme de Weloveya. Deux scènes, une programmation qui mixe afrobeat, coupé-décalé, hip-hop et musiques traditionnelles revisitées. Le public est jeune, festif, cosmopolite — un condensé de la nouvelle génération béninoise.',

        'blocks' => [
            [
                'titre' => 'L\'esprit de l\'événement',
                'texte' => 'Né de l\'envie d\'une scène béninoise professionnalisée et exportable, Weloveya s\'est imposé comme le moment de l\'année où la diaspora rentre au pays et où les artistes locaux mesurent leur audience. Le festival a lieu sur la plage de Cotonou, avec vue sur l\'océan.',
            ],
            [
                'titre' => 'Ce qui vous attend',
                'texte' => 'Programmation dévoilée en trois vagues à partir d\'octobre, ouverture des portes en fin d\'après-midi, dernier concert au petit matin. Espace restauration avec les meilleurs maquis de Cotonou, marché d\'artisans, zone chill au bord de l\'eau. Deux formules : pass 1 jour ou 2 jours, avec des billets VIP incluant carré premium et boissons.',
            ],
            [
                'titre' => 'Y aller',
                'texte' => 'La plage de Fidjrossè, à Cotonou, est desservie par tous les taxis et VTC (Yango, Gozem). Prévoyez une tenue confortable pour la marche dans le sable et une petite laine — les nuits en bord d\'océan restent fraîches en décembre. Le week-end du festival est le plus chargé de l\'année pour les hôtels de Cotonou, réservez tôt.',
            ],
        ],

        'programme' => [
            ['jour' => 'Après-midi', 'titre' => 'Ouverture des portes', 'lieu' => 'Site de Fidjrossè', 'texte' => 'Contrôle billetterie, marché d\'artisans, food-court avec les grandes tables de Cotonou, apéro au bord de l\'eau.'],
            ['jour' => 'Début soirée', 'titre' => 'Découvertes locales', 'lieu' => 'Scène A',          'texte' => 'Talents montants béninois et régionaux qui ouvrent le bal — jeunes signatures afro, rap, alternative.'],
            ['jour' => 'Soirée',       'titre' => 'Têtes d\'affiche',    'lieu' => 'Scène B',          'texte' => 'Le cœur du festival : DJ sets, live afrobeat, coupé-décalé, hits panafricains. Ambiance grand rassemblement.'],
            ['jour' => 'Nuit',         'titre' => 'After-scene',         'lieu' => 'Zone chill',       'texte' => 'DJ résident, moments plus lents, retour au calme avant la fin. Départ au petit matin ou after au centre-ville.'],
        ],

        'tips' => [
            ['ic' => '🎫', 'label' => 'Billets',    'texte' => 'Achetez le pass 2 jours en early bird — c\'est nettement moins cher.'],
            ['ic' => '👟', 'label' => 'Chaussures', 'texte' => 'Baskets fermées ou espadrilles. Le sable est vite fatigant en tongs.'],
            ['ic' => '🌡️', 'label' => 'Nuit',       'texte' => 'Chemise légère prévue : la brise océane fraîchit après minuit.'],
            ['ic' => '💵', 'label' => 'Argent',     'texte' => 'Peu d\'ATM sur site. Retirez du liquide en amont ou utilisez le paiement mobile.'],
            ['ic' => '🚕', 'label' => 'Retour',     'texte' => 'Booker un VTC au petit matin : la disponibilité s\'effondre à 4 h.'],
        ],

        'combinerAvec' => [
            ['slug' => 'vodun-days', 'titre' => 'Vodun Days', 'note' => 'Deux semaines plus tard à Ouidah — combiner urbain et spirituel.'],
        ],

        'stats' => [
            ['nb' => '2', 'label' => 'jours de concerts'],
            ['nb' => '20+', 'label' => 'artistes par édition'],
            ['nb' => '5 000+', 'label' => 'festivaliers attendus'],
            ['nb' => '2 scènes', 'label' => 'programmation parallèle'],
        ],

        'ambiance' => [
            'titre' => 'La plage de Fidjrossè, deux nuits qui ne dorment pas',
            'texte' => 'Weloveya prend possession du sable de Fidjrossè avec deux scènes qui se répondent, un food-court où se croisent les grandes tables de Cotonou, un carré chill éclairé aux lampions et une foule d\'habitués. Les têtes d\'affiche continentales s\'enchaînent avec la nouvelle scène béninoise, la diaspora rentre pour l\'occasion, et la vibration ne redescend qu\'au petit matin.',
            'points' => [
                'Deux scènes en parallèle, alternance rapide entre découvertes et têtes d\'affiche',
                'Programmation dévoilée en trois vagues à partir d\'octobre',
                'Food-court avec les grandes tables de Cotonou et vendeurs de rue',
                'Espace chill au bord de l\'eau — retour au calme jusqu\'au petit matin',
            ],
            'photos' => [
                '/assets/img/weloveeya-1024x683.jpg',
                '/assets/img/plage-grand-popo-1200x675.webp',
                '/assets/img/cotonou-1200x675.webp',
            ],
        ],

        'lieux' => [
            ['nom' => 'Fidjrossè', 'desc' => 'Plage principale du festival, à quelques minutes du centre-ville de Cotonou.', 'photo' => '/assets/img/plage-grand-popo-640x480.webp'],
            ['nom' => 'Scène principale', 'desc' => 'Grande scène qui accueille les têtes d\'affiche panafricaines.', 'photo' => '/assets/img/danse-640x480.webp'],
            ['nom' => 'Scène découvertes', 'desc' => 'Espace dédié à la nouvelle scène béninoise et régionale.', 'photo' => '/assets/img/danse-640x480.webp'],
            ['nom' => 'Espace VIP', 'desc' => 'Carré premium avec vue mer, service dédié, bar exclusif.', 'photo' => '/assets/img/cotonou-640x480.webp'],
        ],

        'tempsForts' => [
            ['titre' => 'Ouverture des portes', 'sous' => 'Vague 1 · fin d\'après-midi', 'texte' => 'Marché d\'artisans, food-court, apéro au bord de l\'eau. Le festival monte doucement en température.'],
            ['titre' => 'Découvertes locales', 'sous' => 'Vague 2 · début de soirée', 'texte' => 'La scène béninoise émergente ouvre le bal. Rap, afro-alternative, formations de fusion.'],
            ['titre' => 'Têtes d\'affiche panafricaines', 'sous' => 'Vague 3 · soirée', 'texte' => 'Le cœur du festival. Afrobeat, coupé-décalé, amapiano — la programmation phare qui rassemble la foule.'],
            ['titre' => 'DJ sets & after', 'sous' => 'Vague 4 · nuit', 'texte' => 'Résidents et invités enchaînent les sets jusqu\'à l\'aube. Zone chill pour ceux qui veulent souffler.'],
            ['titre' => 'Clôture au lever du soleil', 'sous' => 'Vague 5 · petit matin', 'texte' => 'Dernier set face à l\'océan, café sur le sable. Départ groupé au bon matin, retour VTC vers le centre.'],
        ],

        'histoire' => [
            'titre' => 'Comment est né l\'événement',
            'paragraphs' => [
                'Cotonou a longtemps compté sur des soirées d\'hôtel, des maquis animés et quelques concerts ponctuels pour faire vivre sa scène musicale. Ce qui manquait, c\'était un rendez-vous fédérateur — un festival capable de rassembler une nuit entière la scène béninoise, la diaspora rentrée pour les fêtes, et les têtes d\'affiche panafricaines qui font tourner le continent.',
                'Weloveya s\'inscrit dans cette logique de professionnalisation de la scène béninoise. Le festival choisit délibérément fin décembre, quand la diaspora est là, quand les grandes tournées africaines s\'arrêtent, et quand la plage de Fidjrossè offre le cadre idéal pour deux nuits en plein air.',
            ],
        ],

        'autour' => [
            'titre' => 'Autour de l\'événement',
            'points' => [
                ['t' => 'Marché Dantokpa', 'd' => 'Le plus grand marché d\'Afrique de l\'Ouest, à 15 min du site. Idéal pour respirer Cotonou de jour avant de festoyer le soir.'],
                ['t' => 'Fondation Zinsou', 'd' => 'Art contemporain africain, expositions temporaires. Ouverte en journée, entrée libre.'],
                ['t' => 'Plage de Fidjrossè', 'd' => 'Site du festival — mais aussi belle balade en journée. Paillotes et poisson braisé à toute heure.'],
                ['t' => 'Ganvié', 'd' => 'À 45 min, la cité lacustre. Excursion d\'une demi-journée pour compenser les nuits blanches.'],
            ],
        ],

        'surPlace' => [
            'titre' => 'Sur place',
            'points' => [
                ['ic' => '🍽️', 'label' => 'Manger', 'texte' => 'Food-court sur site avec les grandes tables de Cotonou. Poisson braisé, brochettes, tchoukoutou pression.'],
                ['ic' => '🩹', 'label' => 'Sécurité', 'texte' => 'Fouille à l\'entrée, agents de sécurité en nombre. Restez sur le site — l\'extérieur de la plage est moins bien couvert.'],
                ['ic' => '♿', 'label' => 'Accessibilité', 'texte' => 'Sable partout — fauteuil roulant compliqué. Zone bar accessible en dur, prévenez le staff à l\'accueil.'],
                ['ic' => '💳', 'label' => 'Paiement', 'texte' => 'Bar sans espèces — bracelets rechargeables en amont par carte ou mobile money.'],
            ],
        ],

        'faq' => [
            ['q' => 'Quel âge minimum pour entrer ?', 'r' => 'Officiellement 16 ans avec accompagnant. Certaines zones (bar VIP) sont strictement 18+.'],
            ['q' => 'Peut-on aller et venir entre les 2 jours ?', 'r' => 'Non — chaque billet est valable pour la nuit indiquée. Il faut un pass 2 jours pour circuler sur les deux dates.'],
            ['q' => 'Quel dress code ?', 'r' => 'Aucun. Tenue de plage, streetwear, ou sortie chic — tout se croise. Prévoyez juste des baskets et une veste pour la nuit.'],
            ['q' => 'Comment repartir à 4h du matin ?', 'r' => 'Yango et Gozem tournent toute la nuit, mais avec forte demande. Réservez à l\'avance ou groupez-vous.'],
            ['q' => 'Y a-t-il un espace pour se reposer ?', 'r' => 'Oui, une zone chill avec transats, éclairage tamisé et DJ résident au ralenti. Accès inclus dans tous les billets.'],
        ],
    ],

    'festival-des-masques' => [
        'title'        => 'Festival International des Masques',
        'subtitle'     => 'Les masques du golfe de Guinée réunis',
        'lieu'         => 'Porto-Novo · Bénin',
        'dates'        => '07 → 08 août 2027',
        'themeCouleur' => '#a2582a',
        'hero'         => '/assets/img/festivaldesmasques-1024x723.jpg',
        'siteOfficiel' => 'https://festivaldesmasques.bj/',
        'description'  => 'Deux journées qui rassemblent à Porto-Novo les grandes traditions masquées du Bénin et de la sous-région : gèlèdè, zangbéto, egungun, kokou, et leurs équivalents togolais et nigérians.',
        'chapo'        => 'Le Festival des Masques met sur la même scène des sociétés secrètes qui, d\'ordinaire, ne se croisent jamais. Un moment rare pour voir défiler côte à côte les grandes figures masquées de la région, avec leurs percussions, leurs chants et leur codification centenaire.',

        'blocks' => [
            [
                'titre' => 'L\'esprit de l\'événement',
                'texte' => 'Les masques du Bénin ne sont pas des costumes : ce sont des présences codifiées, activées par des chants et des tambours précis, réservées à des sociétés secrètes qui les gardent depuis des siècles. Réunir plusieurs d\'entre elles au même endroit demande une préparation diplomatique — chaque édition en négocie la représentation avec les gardiens de chaque tradition.',
            ],
            [
                'titre' => 'Ce qui vous attend',
                'texte' => 'Défilé d\'ouverture avec les masques gèlèdè (inscrits à l\'UNESCO), sortie nocturne des zangbéto qui n\'apparaissent qu\'à la lumière des lampes-tempête, présentations pédagogiques d\'universitaires spécialistes, marché d\'artisans (masques miniatures, tissus, sculptures), banquets communautaires.',
            ],
            [
                'titre' => 'Y aller',
                'texte' => 'Porto-Novo est la capitale politique du Bénin, à 30 km de Cotonou. Comptez 45 min à 1 h de route selon le trafic. La ville dispose de plusieurs guest houses et hôtels de charme dans le centre historique. Les masques évoluent dans plusieurs quartiers de la ville : un guide local est indispensable pour ne rien manquer et respecter les codes.',
            ],
        ],

        'programme' => [
            ['jour' => 'Vendredi soir',  'titre' => 'Veillée d\'ouverture',   'lieu' => 'Place royale',        'texte' => 'Salutations aux chefs traditionnels, allocutions, présentation des délégations béninoises et étrangères.'],
            ['jour' => 'Samedi matin',   'titre' => 'Défilé gèlèdè',           'lieu' => 'Centre historique',   'texte' => 'Sortie des masques gèlèdè (patrimoine immatériel UNESCO). Grande parade avec percussions et chants.'],
            ['jour' => 'Samedi après-midi', 'titre' => 'Forums culturels',     'lieu' => 'Musées de la ville',  'texte' => 'Tables rondes avec ethnologues et gardiens de tradition. Ouverture au public sur inscription.'],
            ['jour' => 'Samedi soir',    'titre' => 'Sortie zangbéto',         'lieu' => 'Quartiers de nuit',    'texte' => 'Apparition nocturne des masques zangbéto — moment rare qui exige de rester silencieux et à distance.'],
            ['jour' => 'Dimanche',       'titre' => 'Clôture & banquet',      'lieu' => 'Concessions royales', 'texte' => 'Prestations de tous les masques présents, banquet communautaire, remise des trophées aux troupes invitées.'],
        ],

        'tips' => [
            ['ic' => '🚫', 'label' => 'Interdits',   'texte' => 'Ne jamais toucher un masque en action. Distance de sécurité toujours respectée.'],
            ['ic' => '📸', 'label' => 'Photo',       'texte' => 'Certaines sorties (zangbéto de nuit) interdisent la photo. Demandez systématiquement au guide.'],
            ['ic' => '👂', 'label' => 'Silence',     'texte' => 'Les chants qui accompagnent les masques sont sacrés. On ne parle pas, on n\'interpelle pas.'],
            ['ic' => '👗', 'label' => 'Tenue',       'texte' => 'Sobre. Le blanc est apprécié pour les femmes. Chaussures fermées.'],
            ['ic' => '🎓', 'label' => 'Guide',       'texte' => 'Un guide local qui parle la langue des sociétés est indispensable pour lire ce que l\'on voit.'],
        ],

        'combinerAvec' => [
            ['slug' => 'vodun-days', 'titre' => 'Vodun Days',       'note' => 'Le même univers spirituel, six mois plus tôt.'],
            ['slug' => 'jistna',     'titre' => 'JISTNA',            'note' => 'Se tient dans la même quinzaine — deux temps forts en un voyage.'],
        ],

        'stats' => [
            ['nb' => '2', 'label' => 'jours intensifs'],
            ['nb' => '4+', 'label' => 'sociétés masquées'],
            ['nb' => '2008', 'label' => 'inscription UNESCO du gèlèdè'],
            ['nb' => '30 min', 'label' => 'depuis Cotonou'],
        ],

        'ambiance' => [
            'titre' => 'Porto-Novo devient le théâtre des masques',
            'texte' => 'La capitale politique se transforme le temps du festival. Les gèlèdè paradent dans les rues du centre en plein jour, les zangbéto sortent à la lampe-tempête après la tombée de la nuit. Chaque quartier accueille sa société, chaque société apporte ses tambours, ses chants et ses propres codes. Ce n\'est pas un spectacle qu\'on regarde de loin — c\'est une expérience qu\'on vit à hauteur d\'homme.',
            'points' => [
                'Défilé gèlèdè en plein jour dans les rues du centre historique',
                'Sorties zangbéto exclusivement de nuit, à la lueur des lampes-tempête',
                'Chaque quartier accueille une société masquée différente',
                'Guides locaux indispensables pour lire les codes et rester en sécurité',
            ],
            'photos' => [
                '/assets/img/festivaldesmasques-1024x723.jpg',
                '/assets/img/vodun-1200x675.webp',
                '/assets/img/danse-1200x600.webp',
            ],
        ],

        'lieux' => [
            ['nom' => 'Place royale', 'desc' => 'Lieu d\'ouverture, où les délégations sont accueillies par les chefs traditionnels.', 'photo' => '/assets/img/porto-novo-640x480.webp'],
            ['nom' => 'Centre historique', 'desc' => 'Rues et maisons afro-brésiliennes où se déroulent les grands défilés gèlèdè.', 'photo' => '/assets/img/porto-novo-640x480.webp'],
            ['nom' => 'Musée Honmé', 'desc' => 'Ancien palais du roi Toffa. Forums et présentations académiques.', 'photo' => '/assets/img/abomey-palais-640x480.webp'],
            ['nom' => 'Concessions traditionnelles', 'desc' => 'Cours privées ouvertes pour les banquets et démonstrations en clôture.', 'photo' => '/assets/img/vodun-640x480.webp'],
        ],

        'tempsForts' => [
            ['titre' => 'Veillée d\'ouverture', 'sous' => 'Vague 1 · vendredi soir', 'texte' => 'Accueil des délégations, allocutions, présentation des sociétés qui ont fait le déplacement.'],
            ['titre' => 'Défilé gèlèdè', 'sous' => 'Vague 2 · samedi matin', 'texte' => 'Grande parade des masques inscrits au patrimoine immatériel de l\'UNESCO. Percussions, chants, effigies.'],
            ['titre' => 'Forums culturels', 'sous' => 'Vague 3 · samedi après-midi', 'texte' => 'Tables rondes avec ethnologues et gardiens de tradition. Ouverture au public sur inscription.'],
            ['titre' => 'Sortie zangbéto', 'sous' => 'Vague 4 · samedi soir', 'texte' => 'Apparition nocturne exceptionnelle. Silence total exigé. Aucune photo autorisée.'],
            ['titre' => 'Clôture & banquet', 'sous' => 'Vague 5 · dimanche', 'texte' => 'Prestations de toutes les sociétés, banquet communautaire, remise des trophées aux troupes invitées.'],
        ],

        'histoire' => [
            'titre' => 'Comment est né l\'événement',
            'paragraphs' => [
                'Chaque société masquée du golfe de Guinée a ses lieux, ses saisons, ses codes. Certaines sortent une fois par an dans un village précis, d\'autres à l\'occasion d\'une naissance ou d\'un décès. Les voir toutes réunies n\'a longtemps été possible qu\'à travers des documentaires ou des collections de musée.',
                'Le Festival des Masques est venu combler ce manque. Porto-Novo, capitale politique et cité afro-brésilienne, offrait le cadre naturel : sociétés savantes, chefferies vivantes, hospitalité des concessions royales. Le rendez-vous s\'est imposé comme le seul moment de l\'année où l\'on peut voir défiler côte à côte plusieurs grandes traditions masquées de la sous-région.',
            ],
        ],

        'autour' => [
            'titre' => 'Autour de l\'événement',
            'points' => [
                ['t' => 'Musée Honmé', 'd' => 'Ancien palais du roi Toffa, aujourd\'hui musée d\'histoire de Porto-Novo. Visite guidée en 1 h.'],
                ['t' => 'Grande Mosquée', 'd' => 'Ancienne église afro-brésilienne convertie — l\'un des monuments les plus photogéniques de la ville.'],
                ['t' => 'Jardin des plantes', 'd' => 'Belle pause verte au centre — ombragée, calme, ouverte en semaine.'],
                ['t' => 'Marché d\'Adjara', 'd' => 'À 15 min de la ville : tissus, tissages, poteries. Idéal pour repartir avec de vrais souvenirs.'],
            ],
        ],

        'surPlace' => [
            'titre' => 'Sur place',
            'points' => [
                ['ic' => '🍽️', 'label' => 'Manger', 'texte' => 'Petits restaurants créoles autour de la place royale — cuisine afro-brésilienne, poissons frais.'],
                ['ic' => '🩹', 'label' => 'Sécurité', 'texte' => 'Ville tranquille, mais gardez vos distances avec les masques en action — jamais devant, jamais dans leur trajectoire.'],
                ['ic' => '♿', 'label' => 'Accessibilité', 'texte' => 'Centre historique en dur, praticable. Concessions traditionnelles avec seuils — accompagnement recommandé.'],
                ['ic' => '💳', 'label' => 'Paiement', 'texte' => 'Cash prioritaire au marché et dans les concessions. Distributeurs BOA et Ecobank sur l\'axe principal.'],
            ],
        ],

        'faq' => [
            ['q' => 'Puis-je m\'approcher d\'un masque ?', 'r' => 'Non. Respectez toujours 3-4 mètres. Les percussionnistes autour définissent une zone qu\'on ne franchit jamais.'],
            ['q' => 'La photo est-elle autorisée ?', 'r' => 'Gèlèdè de jour : oui. Zangbéto de nuit : interdiction totale. Toujours vérifier auprès de votre guide.'],
            ['q' => 'Faut-il parler une langue locale ?', 'r' => 'Non — le festival est en français. Un guide local aide à décoder les chants et les gestes, mais on peut vivre l\'ambiance sans.'],
            ['q' => 'Peut-on assister aux forums ?', 'r' => 'Oui, sur inscription en amont. Places limitées, universitaires en priorité mais public curieux accepté.'],
            ['q' => 'Y a-t-il une billetterie ?', 'r' => 'Les cérémonies dans l\'espace public sont gratuites. Les forums académiques et le banquet demandent une inscription.'],
        ],
    ],

    'jistna' => [
        'title'        => 'JISTNA',
        'subtitle'     => 'Journées Internationales des Savoirs Traditionnels et de la Nature en Afrique',
        'lieu'         => 'Grand-Popo · Bénin',
        'dates'        => '22 → 23 août 2027',
        'themeCouleur' => '#4c9a2a',
        'hero'         => '/assets/img/jistna-ceremonie-1024x682.webp',
        'siteOfficiel' => 'https://jistna.bj/',
        'description'  => 'Les JISTNA rassemblent à Grand-Popo tradipraticiens, herboristes, chercheurs, décideurs publics et curieux, pour valoriser les savoirs traditionnels africains liés à la santé, à la biodiversité et à l\'environnement.',
        'chapo'        => 'Deux jours à mi-chemin entre le colloque scientifique, le sommet culturel et le marché des plantes. Les JISTNA défendent l\'idée qu\'il n\'y a pas d\'écologie sérieuse en Afrique sans dialogue avec ceux qui pratiquent la médecine et la pharmacopée traditionnelles depuis des siècles.',

        'blocks' => [
            [
                'titre' => 'L\'esprit de l\'événement',
                'texte' => 'Les JISTNA sont nées d\'un constat : les grandes conférences internationales sur la biodiversité parlent souvent de l\'Afrique sans les Africains qui vivent la nature au quotidien. L\'événement de Grand-Popo remet les tradipraticiens, chefs de terre et herboristes au centre de la conversation, avec les scientifiques et les responsables publics comme partenaires.',
            ],
            [
                'titre' => 'Ce qui vous attend',
                'texte' => 'Cérémonie d\'ouverture officielle sur la plage, tables rondes thématiques (plantes médicinales, gestion communautaire des forêts, changement climatique), démonstrations de pharmacopée et de rituels de guérison, marché des simples avec des herboristes venus de tout le pays, moment culturel en soirée avec chants et danses traditionnels.',
            ],
            [
                'titre' => 'Y aller',
                'texte' => 'Grand-Popo est à 90 km à l\'ouest de Cotonou, environ 2 h de route côtière. Choisissez un hôtel les pieds dans le sable — plusieurs adresses à taille humaine sont disponibles. L\'événement se déroule en français, avec traduction anglaise pour les invités internationaux. Prévoyez du liquide pour le marché des herboristes.',
            ],
        ],

        'programme' => [
            ['jour' => 'Jour 1 matin',       'titre' => 'Ouverture officielle',    'lieu' => 'Plage de Grand-Popo', 'texte' => 'Rituel d\'ouverture par les chefs coutumiers, discours officiels, signature d\'engagements.'],
            ['jour' => 'Jour 1 après-midi',  'titre' => 'Tables rondes',           'lieu' => 'Salle centrale',      'texte' => 'Panels thématiques : plantes médicinales et brevetabilité, forêts sacrées, adaptation climatique.'],
            ['jour' => 'Jour 1 soirée',      'titre' => 'Soirée culturelle',       'lieu' => 'Plage',               'texte' => 'Chants d\'accueil, contes en langues locales avec traduction, banquet de spécialités du terroir.'],
            ['jour' => 'Jour 2 matin',       'titre' => 'Démonstrations',          'lieu' => 'Espace ateliers',    'texte' => 'Herboristes en pleine reconnaissance des plantes, préparation en direct de tisanes et onguents.'],
            ['jour' => 'Jour 2 après-midi',  'titre' => 'Marché des simples',      'lieu' => 'Marché principal',    'texte' => 'Vente de plantes séchées, écorces, huiles essentielles. Consultations libres avec les tradipraticiens.'],
        ],

        'tips' => [
            ['ic' => '📖', 'label' => 'Programme', 'texte' => 'Inscrivez-vous aux panels — les places sont limitées pour respecter la qualité des échanges.'],
            ['ic' => '🌿', 'label' => 'Marché',    'texte' => 'Achetez en petites quantités — certaines plantes sont mieux fraîches, d\'autres doivent sécher chez vous.'],
            ['ic' => '💵', 'label' => 'Cash',      'texte' => 'Aucun paiement carte au marché des simples. Prévoir du liquide en petites coupures.'],
            ['ic' => '🩺', 'label' => 'Santé',     'texte' => 'Une consultation avec un tradipraticien ne remplace pas votre médecin. Restez sur des remèdes courants (rhume, digestion).'],
            ['ic' => '🌊', 'label' => 'Baignade',  'texte' => 'La plage de Grand-Popo est belle mais les courants peuvent être forts. Baignez-vous dans les zones surveillées.'],
        ],

        'combinerAvec' => [
            ['slug' => 'festival-des-masques', 'titre' => 'Festival des Masques', 'note' => 'Même quinzaine, 2 h de route seulement.'],
            ['slug' => 'vodun-days',           'titre' => 'Vodun Days',            'note' => 'Autre grande célébration en lien avec la nature et le sacré.'],
        ],

        'stats' => [
            ['nb' => '2', 'label' => 'jours de programme'],
            ['nb' => '15+', 'label' => 'pays africains représentés'],
            ['nb' => '200+', 'label' => 'plantes médicinales exposées'],
            ['nb' => '90 km', 'label' => 'depuis Cotonou'],
        ],

        'ambiance' => [
            'titre' => 'Grand-Popo, deux jours qui redonnent la parole aux sages',
            'texte' => 'Les JISTNA changent le rythme de Grand-Popo. La plage se remplit de tentes où se croisent tradipraticiens venus de tout le pays, chercheurs universitaires, ministres, journalistes, et voyageurs curieux. On y écoute autant qu\'on y parle — l\'événement est structuré comme un dialogue entre traditions et institutions, avec un marché des simples qui ne désemplit pas.',
            'points' => [
                'Cérémonie d\'ouverture officielle sur la plage, allocutions bilingues',
                'Tables rondes thématiques accessibles au public sur inscription',
                'Marché des simples avec herboristes venus de tout le pays',
                'Soirée culturelle en clôture, chants et contes en langues locales',
            ],
            'photos' => [
                '/assets/img/jistna-ceremonie-1024x682.webp',
                '/assets/img/mangrove-1200x600.webp',
                '/assets/img/plage-grand-popo-1200x675.webp',
            ],
        ],

        'lieux' => [
            ['nom' => 'Plage de Grand-Popo', 'desc' => 'Cérémonie d\'ouverture et clôture au bord de l\'océan.', 'photo' => '/assets/img/plage-grand-popo-640x480.webp'],
            ['nom' => 'Salle centrale', 'desc' => 'Panels et tables rondes, jauge limitée pour préserver la qualité des échanges.', 'photo' => '/assets/img/cotonou-640x480.webp'],
            ['nom' => 'Marché des simples', 'desc' => 'Espace ouvert avec vendeurs de plantes, écorces et huiles essentielles.', 'photo' => '/assets/img/marche-640x480.webp'],
            ['nom' => 'Bouche du Roy', 'desc' => 'Site naturel à proximité, souvent visité en marge de l\'événement.', 'photo' => '/assets/img/mangrove-640x480.webp'],
        ],

        'tempsForts' => [
            ['titre' => 'Ouverture officielle', 'sous' => 'Vague 1 · jour 1 matin', 'texte' => 'Rituel d\'ouverture par les chefs coutumiers, discours des délégations, signature d\'engagements.'],
            ['titre' => 'Panels thématiques', 'sous' => 'Vague 2 · jour 1 après-midi', 'texte' => 'Plantes médicinales, forêts sacrées, adaptation climatique, brevetabilité des savoirs.'],
            ['titre' => 'Soirée culturelle', 'sous' => 'Vague 3 · jour 1 soir', 'texte' => 'Chants d\'accueil, contes en langues locales avec traduction, banquet de spécialités du terroir.'],
            ['titre' => 'Démonstrations & ateliers', 'sous' => 'Vague 4 · jour 2 matin', 'texte' => 'Reconnaissance de plantes en direct, préparation de tisanes et onguents, consultations libres.'],
            ['titre' => 'Marché des simples', 'sous' => 'Vague 5 · jour 2 après-midi', 'texte' => 'Vente de plantes, écorces, huiles essentielles. Consultations avec tradipraticiens de tout le pays.'],
        ],

        'histoire' => [
            'titre' => 'Comment est né l\'événement',
            'paragraphs' => [
                'Les conférences internationales sur la biodiversité rassemblent des scientifiques et des décideurs, mais laissent souvent sur le bord de la route ceux qui côtoient la nature au quotidien : herboristes, tradipraticiens, chefs de terre. Or c\'est chez eux que sont préservés une partie des savoirs pharmacologiques et écologiques que la science moderne redécouvre par étapes.',
                'Les JISTNA sont nées de la volonté de remettre ces gardiens au centre de la conversation. En choisissant Grand-Popo, un site naturel emblématique du littoral béninois, l\'événement crée un cadre propice au dialogue — entre langues, entre disciplines, entre générations.',
            ],
        ],

        'autour' => [
            'titre' => 'Autour de l\'événement',
            'points' => [
                ['t' => 'La Bouche du Roy', 'd' => 'Embouchure du fleuve Mono à 20 min. Site Ramsar, sortie en pirogue à réserver.'],
                ['t' => 'Village d\'Agonvé', 'd' => 'Culture de sel, techniques ancestrales. Rencontres avec les femmes du village.'],
                ['t' => 'Villa Karo', 'd' => 'Résidence d\'artistes finno-béninoise, expositions temporaires, bibliothèque ouverte.'],
                ['t' => 'Route côtière vers Ouidah', 'd' => 'À 50 min à l\'est : Route de l\'Esclave et Porte du Non-Retour à combiner en une journée.'],
            ],
        ],

        'surPlace' => [
            'titre' => 'Sur place',
            'points' => [
                ['ic' => '🍽️', 'label' => 'Manger', 'texte' => 'Restaurants d\'hôtel les pieds dans le sable — cuisine béninoise et internationale. Poissons du jour.'],
                ['ic' => '🩹', 'label' => 'Sécurité', 'texte' => 'Zone tranquille. Vigilance sur les courants marins : baignade uniquement dans les zones surveillées.'],
                ['ic' => '♿', 'label' => 'Accessibilité', 'texte' => 'Salle centrale en dur, praticable. Plage et sable moins accessibles — équipements sur demande.'],
                ['ic' => '💳', 'label' => 'Paiement', 'texte' => 'Cash indispensable pour le marché des simples. Hôtels acceptent la carte.'],
            ],
        ],

        'faq' => [
            ['q' => 'Faut-il un badge pour tout ?', 'r' => 'Les panels et le banquet demandent une accréditation. Le marché des simples et la soirée culturelle sont ouverts au public.'],
            ['q' => 'Peut-on consulter un tradipraticien ?', 'r' => 'Oui, des consultations libres sont proposées lors du marché des simples. Une contribution est bienvenue même quand aucun tarif n\'est affiché.'],
            ['q' => 'Peut-on rentrer avec des plantes en Europe ?', 'r' => 'Certaines espèces sont réglementées. Vérifiez la douane de votre pays avant d\'acheter. Les huiles essentielles passent en général sans souci.'],
            ['q' => 'La langue de travail est ?', 'r' => 'Le français majoritairement, avec traduction anglaise pour les invités internationaux. Les langues locales sont utilisées avec interprétation lors des rituels.'],
            ['q' => 'Peut-on venir en famille ?', 'r' => 'Oui, la soirée culturelle et le marché sont familiaux. Les panels s\'adressent à un public adulte.'],
        ],
    ],

    'la-gaani' => [
        'title'        => 'La Gaani',
        'subtitle'     => 'La grande fête royale du Borgou',
        'lieu'         => 'Nikki · Bénin',
        'dates'        => 'Août 2027',
        'themeCouleur' => '#c1272d',
        'hero'         => '/assets/img/la-gaani-1024x682.jpg',
        'siteOfficiel' => 'https://gaani.bj/',
        'description'  => 'La Gaani est la fête traditionnelle des Baribas, célébrée à Nikki dans le nord du Bénin. Rendez-vous des cavaliers du royaume, elle rassemble plusieurs milliers de personnes autour du sultan et de sa cour.',
        'chapo'        => 'Chaque année, Nikki accueille l\'un des spectacles les plus impressionnants d\'Afrique de l\'Ouest : la Gaani. Des centaines de cavaliers en tenue royale, sabres tirés, trompes en corne, poussière dorée dans la lumière du couchant. Une fenêtre unique sur la royauté baribara, vivante depuis huit siècles.',

        'blocks' => [
            [
                'titre' => 'L\'esprit de l\'événement',
                'texte' => 'La Gaani commémore une victoire militaire fondatrice du royaume de Nikki, et rejoue chaque année le serment de fidélité des chefs vassaux au sultan. Ce n\'est pas une reconstitution : c\'est une continuité vivante d\'un pouvoir royal qui n\'a jamais disparu, même sous l\'ère coloniale. Les cavaliers sont les descendants directs de ceux qu\'ils incarnent.',
            ],
            [
                'titre' => 'Ce qui vous attend',
                'texte' => 'Grand défilé équestre sur la place centrale de Nikki, arrivée du sultan sous le baldaquin royal, danses guerrières, concours de tir à l\'arc et de démonstration de sabre, banquets communautaires dans les concessions, marché aux tissus et bijoux du nord. La cérémonie principale dure trois jours, avec un pic le samedi.',
            ],
            [
                'titre' => 'Y aller',
                'texte' => 'Nikki est à 620 km au nord de Cotonou : compter 8 à 10 h de route, ou un vol régional jusqu\'à Parakou puis 2 h de piste. L\'hébergement est simple (guest houses, campements) et se remplit dès l\'annonce des dates. Dahomey Discovery organise chaque année un circuit combiné Gaani + Pendjari, la façon la plus confortable de découvrir le nord du pays.',
            ],
        ],

        'programme' => [
            ['jour' => 'Jour 1',   'titre' => 'Arrivées & prières',       'lieu' => 'Grande mosquée',      'texte' => 'Prières collectives, arrivée des délégations vassales, dépôt des présents au palais du sultan.'],
            ['jour' => 'Jour 2',   'titre' => 'Grand défilé équestre',    'lieu' => 'Place centrale',      'texte' => 'Sortie du sultan sous le baldaquin, cavaliers en tenues d\'apparat, chants des griots. Moment central de la fête.'],
            ['jour' => 'Jour 2 soir', 'titre' => 'Banquets & danses',    'lieu' => 'Concessions',         'texte' => 'Repas communautaires dans les foyers, veillées, danses guerrières et musique de trompes en corne.'],
            ['jour' => 'Jour 3',   'titre' => 'Démonstrations & marché',  'lieu' => 'Ville & marché',      'texte' => 'Concours de tir à l\'arc, dressage de chevaux, marché aux tissus, bijoux et cuirs du grand nord.'],
        ],

        'tips' => [
            ['ic' => '🚗', 'label' => 'Route',      'texte' => 'Comptez 2 jours de route depuis Cotonou avec étape à Parakou. Ou vol Cotonou-Parakou puis piste.'],
            ['ic' => '🏨', 'label' => 'Logement',   'texte' => 'Options limitées à Nikki. Réservation indispensable, parfois plusieurs mois à l\'avance.'],
            ['ic' => '👳', 'label' => 'Culture',    'texte' => 'Contexte majoritairement musulman. Tenue couvrante appréciée, alcool discret.'],
            ['ic' => '🌡️', 'label' => 'Chaleur',    'texte' => '35-38 °C en journée. Casquette, eau, protection solaire indispensables.'],
            ['ic' => '💵', 'label' => 'Espèces',    'texte' => 'Pas de distributeur à Nikki. Retirez du liquide à Parakou avant de continuer.'],
        ],

        'combinerAvec' => [
            ['slug' => 'jistna',     'titre' => 'JISTNA',                'note' => 'Se tient dans la même quinzaine — combiner sud et nord.'],
            ['slug' => 'vodun-days', 'titre' => 'Vodun Days',            'note' => 'Pour découvrir la spiritualité du sud en complément de la royauté du nord.'],
        ],

        'stats' => [
            ['nb' => '3', 'label' => 'jours de cérémonies'],
            ['nb' => '620 km', 'label' => 'de Cotonou'],
            ['nb' => 'XIIᵉ s.', 'label' => 'origine du royaume'],
            ['nb' => '5 000+', 'label' => 'cavaliers réunis'],
        ],

        'ambiance' => [
            'titre' => 'Nikki, la ville-royaume en tenue d\'apparat',
            'texte' => 'La Gaani transforme Nikki en carte postale animée. Poussière dorée soulevée par les sabots des chevaux, tuniques brodées des cavaliers, trompes en corne qui résonnent au-dessus des toits en tôle. Le sultan et ses chefs vassaux se retrouvent au centre du dispositif, entourés de griots qui déclament la généalogie du royaume. C\'est un condensé d\'histoire vivante — huit siècles de royauté baribara qu\'on peut littéralement toucher du regard.',
            'points' => [
                'Défilé équestre de plusieurs centaines de cavaliers en tenue royale',
                'Arrivée du sultan sous baldaquin, accompagnée des chefs vassaux',
                'Chants de griots qui déclament l\'histoire du royaume',
                'Marché aux tissus et bijoux du grand nord en marge des cérémonies',
            ],
            'photos' => [
                '/assets/img/la-gaani-1024x682.jpg',
                '/assets/img/atacora-1200x600.webp',
                '/assets/img/tata-somba-640x480.webp',
            ],
        ],

        'lieux' => [
            ['nom' => 'Place centrale', 'desc' => 'Grand défilé équestre et arrivée du sultan sous baldaquin royal.', 'photo' => '/assets/img/atacora-640x480.webp'],
            ['nom' => 'Palais du sultan', 'desc' => 'Cœur cérémoniel de la ville — dépôts des présents des chefs vassaux.', 'photo' => '/assets/img/abomey-palais-640x480.webp'],
            ['nom' => 'Grande mosquée', 'desc' => 'Prières collectives d\'ouverture. Contexte majoritairement musulman à respecter.', 'photo' => '/assets/img/porto-novo-640x480.webp'],
            ['nom' => 'Marché de Nikki', 'desc' => 'Tissus, bijoux et cuirs du nord. Étape à part entière du séjour.', 'photo' => '/assets/img/marche-640x480.webp'],
        ],

        'tempsForts' => [
            ['titre' => 'Prières & arrivées', 'sous' => 'Vague 1 · jour 1', 'texte' => 'Prières collectives à la grande mosquée, arrivée des délégations vassales, dépôt des présents au palais.'],
            ['titre' => 'Grand défilé équestre', 'sous' => 'Vague 2 · jour 2 après-midi', 'texte' => 'Sortie du sultan sous baldaquin, cavaliers en tenue d\'apparat, chants des griots. Moment central de la fête.'],
            ['titre' => 'Banquets & veillées', 'sous' => 'Vague 3 · jour 2 soir', 'texte' => 'Repas communautaires dans les concessions, danses guerrières, musique de trompes en corne jusqu\'au milieu de la nuit.'],
            ['titre' => 'Démonstrations martiales', 'sous' => 'Vague 4 · jour 3 matin', 'texte' => 'Concours de tir à l\'arc, dressage de chevaux, exercices de sabre. Public familial.'],
            ['titre' => 'Marché de clôture', 'sous' => 'Vague 5 · jour 3 après-midi', 'texte' => 'Grand marché aux tissus, bijoux et cuirs du grand nord. Dernier moment convivial avant le départ.'],
        ],

        'histoire' => [
            'titre' => 'Comment est née la fête',
            'paragraphs' => [
                'La Gaani commémore une victoire militaire fondatrice qui a scellé, au XIIᵉ siècle, la structure du royaume de Nikki : un sultan au sommet, des chefs vassaux qui prêtent serment de fidélité chaque année. Le cérémonial n\'a jamais été interrompu. Il a traversé le commerce transsaharien, la colonisation française, l\'indépendance du Bénin, sans perdre son sens ni sa forme.',
                'Ce qui distingue la Gaani des autres grandes fêtes d\'Afrique de l\'Ouest, c\'est cette continuité de huit siècles. Les cavaliers qu\'on voit défiler ne rejouent pas une tradition — ils la vivent. Leurs tuniques, leurs sabres, la couleur des étoffes qui recouvrent les chevaux, chaque détail encode une position dans la hiérarchie du royaume.',
            ],
        ],

        'autour' => [
            'titre' => 'Autour de l\'événement',
            'points' => [
                ['t' => 'Parc de la Pendjari', 'd' => 'À 3-4 h de piste de Nikki. Circuit combiné Gaani + safari, la meilleure façon d\'aborder le grand nord.'],
                ['t' => 'Parakou', 'd' => 'À 130 km, capitale régionale du Borgou. Marché central, musée régional, bonne base logistique.'],
                ['t' => 'Chutes de Kota', 'd' => 'À 2 h de Natitingou (via Parakou). Cascade toute l\'année dans l\'Atacora.'],
                ['t' => 'Ancien palais de Nikki', 'd' => 'Résidence historique du sultan, accessible sur demande — présence du chef parfois nécessaire.'],
            ],
        ],

        'surPlace' => [
            'titre' => 'Sur place',
            'points' => [
                ['ic' => '🍽️', 'label' => 'Manger', 'texte' => 'Wagashi (fromage peul), riz au wollof, brochettes. Cuisine simple, honnête, partagée à même le sol.'],
                ['ic' => '🩹', 'label' => 'Sécurité', 'texte' => 'Le contexte sécuritaire du Bénin nord évolue — consultez la page Sécurité du site et l\'avis officiel de votre ministère avant de partir.'],
                ['ic' => '♿', 'label' => 'Accessibilité', 'texte' => 'Route et pistes rugueuses, cérémonies en plein air sur sable rouge. Peu adapté aux mobilités réduites sans accompagnement.'],
                ['ic' => '💳', 'label' => 'Paiement', 'texte' => 'Aucun distributeur à Nikki. Retirez du liquide à Parakou (BOA, Ecobank) avant de continuer vers le nord.'],
            ],
        ],

        'faq' => [
            ['q' => 'Faut-il un permis spécial pour aller à Nikki ?', 'r' => 'Non — pas de zone réglementée, mais consultez les avis officiels de sécurité avant chaque déplacement dans le nord.'],
            ['q' => 'Peut-on approcher les cavaliers ?', 'r' => 'Non pendant le défilé. Après la cérémonie, certains acceptent les photos — toujours demander.'],
            ['q' => 'Peut-on assister aux prières ?', 'r' => 'La grande mosquée réserve l\'entrée aux musulmans. La cour extérieure est ouverte, tenue couvrante requise.'],
            ['q' => 'Y a-t-il un hôtel de bon confort ?', 'r' => 'Standards modestes à Nikki. Pour un confort supérieur, dormez à Parakou et faites Nikki en aller-retour à la journée.'],
            ['q' => 'Peut-on filmer ?', 'r' => 'Oui pour la partie publique du défilé et les danses. Toujours demander pour les portraits individuels et l\'intérieur des concessions.'],
        ],
    ],

];
