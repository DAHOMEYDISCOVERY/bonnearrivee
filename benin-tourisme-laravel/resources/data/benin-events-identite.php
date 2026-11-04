<?php

/*
 * Identité propre de chaque fiche « Bénin en Fête ».
 *
 * Les cinq fiches partageaient le même gabarit, dans le même ordre, avec les
 * mêmes titres de sections (« L'esprit de l'événement », « Ce qui vous
 * attend », « Les lieux emblématiques »…) et plusieurs photos communes : elles
 * se ressemblaient toutes. Ce fichier donne à chaque événement :
 *
 *   - ordre      l'ordre de ses sections (le récit ne commence pas au même
 *                endroit : les divinités pour les Vodun Days, la plage pour
 *                Weloveya, l'histoire pour la Gaani…) ;
 *   - titres     ses propres sur-titres, titres et chapeaux de sections ;
 *   - signature  une section qui n'existe que sur sa page, avec sa mise en
 *                forme (cartes, frise, glossaire, carnet) ;
 *   - remplace   des valeurs de resources/data/benin-events.php remplacées
 *                pour cette page (titres des blocs, photos propres au lieu
 *                montré). Rien n'est retiré de benin-events.php : sans ce
 *                fichier, les fiches s'affichent exactement comme avant.
 *
 * Tous ces textes restent modifiables au back-office, page par page, dans
 * « Textes et images ».
 */

return [

    'vodun-days' => [
        // Couleur des titres et repères de la page, lisible sur fond blanc.
        'accent' => '#c8102e',
        'ordre' => ['signature', 'blocks', 'stats', 'lieux', 'tempsForts', 'programme', 'ambiance', 'tips', 'histoire', 'surPlace', 'autour', 'faq', 'combine'],
        'signature' => [
            'style' => 'cartes',
            'eyebrow' => 'Le panthéon',
            'titre' => 'Six divinités à connaître avant Ouidah',
            'lead' => 'Pendant la semaine, on les entend nommer dans les chants, on reconnaît leurs couleurs et leurs autels. Quelques repères pour suivre les cérémonies.',
            'items' => [
                ['t' => 'Legba', 'd' => 'Gardien des seuils et messager des autres divinités. Ses statuettes de terre veillent à l\'entrée des maisons et des villages : c\'est lui qu\'on salue en premier.'],
                ['t' => 'Dan', 'd' => 'Le serpent arc-en-ciel, symbole de continuité et de prospérité. Au Temple des Pythons, face à la basilique de Ouidah, on le vénère depuis des siècles.'],
                ['t' => 'Hêviosso', 'd' => 'Maître de la foudre et du tonnerre, il punit le mensonge et l\'injustice. Ses adeptes portent souvent le rouge et le blanc.'],
                ['t' => 'Sakpata', 'd' => 'Divinité de la terre, on l\'invoque contre les maladies. Ses fidèles l\'appellent avec respect par des noms détournés plutôt que par son nom.'],
                ['t' => 'Mami Wata', 'd' => 'Esprit des eaux, associée à la mer, à la beauté et à la richesse. Ses cérémonies se tiennent volontiers au bord de l\'océan.'],
                ['t' => 'Fa', 'd' => 'L\'art divinatoire. Le bokonon lit les signes du Fa pour éclairer une décision ou un choix de vie — une pratique encore très consultée.'],
            ],
        ],
        'titres' => [
            'lieux' => ['eyebrow' => 'Le parcours', 'titre' => 'Les hauts lieux de la mémoire', 'lead' => 'De l\'ancien fort à la plage, Ouidah se lit à pied. Ces quatre étapes donnent le fil des cérémonies.'],
            'tempsForts' => ['eyebrow' => 'Jour après jour', 'titre' => 'Sept jours, cinq moments à ne pas manquer', 'lead' => 'De l\'ouverture à la journée nationale du 10 janvier, la ville change de rythme chaque jour.'],
            'programme' => ['eyebrow' => 'Votre semaine', 'titre' => 'Une semaine type à Ouidah', 'lead' => 'Le déroulé change d\'une édition à l\'autre : vérifiez les dates sur le site officiel avant de réserver.'],
            'ambiance' => ['eyebrow' => 'Dans les rues'],
            'tips' => ['eyebrow' => 'Respect des cultes', 'titre' => 'Avant d\'entrer dans une cérémonie', 'lead' => 'Le vodun est une religion vivante : quelques gestes simples ouvrent les portes plutôt qu\'ils ne les ferment.'],
            'histoire' => ['eyebrow' => 'Pourquoi le 10 janvier'],
            'surPlace' => ['eyebrow' => 'À Ouidah'],
            'autour' => ['eyebrow' => 'Après la semaine', 'lead' => 'La côte entre Cotonou et Grand-Popo prolonge naturellement la semaine vodun.'],
            'faq' => ['eyebrow' => 'Avant de partir', 'titre' => 'Vos questions sur les Vodun Days'],
            'combine' => ['eyebrow' => 'La même saison', 'titre' => 'Janvier au Bénin, à combiner'],
        ],
        'remplace' => [
            'blocks' => [
                ['titre' => 'Une religion, pas un spectacle'],
                ['titre' => 'Une semaine de cérémonies publiques'],
                ['titre' => 'Rejoindre Ouidah'],
            ],
            'histoire' => ['titre' => 'Du 10 janvier aux Vodun Days'],
            'autour' => ['titre' => 'Ouidah et la côte, en plus'],
            'surPlace' => ['titre' => 'Sur place à Ouidah'],
            'lieux' => [
                ['photo' => '/assets/img/ouidah-musee-640x480.webp'],
                ['photo' => '/assets/img/ouidah-route-640x480.webp'],
                ['photo' => '/assets/img/ouidah-porte-640x480.webp'],
                ['photo' => '/assets/img/foret-kpasse-640x480.webp'],
            ],
            'ambiance' => ['photos' => [
                '/assets/img/arene-de-ouidah-1024x576.jpg',
                '/assets/img/ouidah-pythons-1920x960.webp',
                '/assets/img/ouidah-musee-1200x600.webp',
            ]],
        ],
    ],

    'weloveya' => [
        // Couleur des titres et repères de la page, lisible sur fond blanc.
        'accent' => '#8f6200',
        // Photo d'en-tête : le fichier d'origine (weloveeya-1024x683.jpg) est abîmé — seul le
        // haut de l'image s'affiche, le reste est une bande unie. Il n'est pas
        // supprimé ; remplacez cette ligne par la vraie photo quand vous l'avez.
        'hero' => '/assets/img/plage-fidjrosse-1920x960.webp',
        'ordre' => ['ambiance', 'programme', 'signature', 'lieux', 'tips', 'surPlace', 'blocks', 'stats', 'tempsForts', 'histoire', 'autour', 'faq', 'combine'],
        'signature' => [
            'style' => 'frise',
            'eyebrow' => 'La bande-son',
            'titre' => 'Les sons du Bénin à guetter',
            'lead' => 'Entre deux têtes d\'affiche, sur les scènes découvertes ou dans les maquis de Cotonou, ces musiques racontent le pays.',
            'items' => [
                ['t' => 'Les fanfares de Cotonou', 'd' => 'Cuivres et percussions vodun : le Gangbé Brass Band a fait connaître ce son dans le monde entier. On le retrouve dans les cortèges et les fêtes de quartier.'],
                ['t' => 'Le zinli d\'Abomey', 'd' => 'Rythme lent et grave né des cérémonies royales d\'Abomey, porté par de grandes voix. Beaucoup d\'artistes actuels s\'en inspirent.'],
                ['t' => 'Le tchink system', 'd' => 'Le style mis au point par Sagbohan Danialou, qui mêle rythmes traditionnels et instruments modernes. Un classique des fêtes béninoises.'],
                ['t' => 'L\'héritage Poly-Rythmo', 'd' => 'Dans les années 1970, l\'Orchestre Poly-Rythmo de Cotonou fusionne funk, afrobeat et rythmes vodun. Angélique Kidjo a porté ces racines sur les plus grandes scènes.'],
                ['t' => 'La nouvelle scène', 'd' => 'Rap en fon et en français, afro-pop, sons urbains : la jeune génération béninoise se découvre en fin d\'après-midi, avant les grands noms.'],
            ],
        ],
        'titres' => [
            'ambiance' => ['eyebrow' => 'Pieds dans le sable'],
            'programme' => ['eyebrow' => 'Du coucher au lever du soleil', 'titre' => 'Une nuit à Weloveya, heure par heure', 'lead' => 'Portes ouvertes en fin d\'après-midi, dernière note au petit matin. La programmation complète est annoncée sur le site officiel.'],
            'lieux' => ['eyebrow' => 'Le site', 'titre' => 'De la plage aux scènes', 'lead' => 'Un site en bord d\'océan, organisé en espaces bien distincts : savoir où se placer, c\'est profiter de tout.'],
            'tips' => ['eyebrow' => 'Survivre à la nuit', 'titre' => 'Les conseils pour tenir jusqu\'à l\'aube', 'lead' => 'Billet, chaussures, retour : ce qu\'il faut avoir réglé avant d\'arriver sur le sable.'],
            'surPlace' => ['eyebrow' => 'Côté pratique'],
            'tempsForts' => ['eyebrow' => 'Le fil de la soirée', 'titre' => 'Cinq temps, du coucher du soleil à l\'aube', 'lead' => 'La soirée monte en intensité, puis redescend doucement jusqu\'au lever du jour.'],
            'histoire' => ['eyebrow' => 'L\'histoire du festival'],
            'autour' => ['eyebrow' => 'Le lendemain', 'lead' => 'Cotonou se visite très bien après une nuit blanche : marchés, art contemporain et lagune.'],
            'faq' => ['eyebrow' => 'Avant d\'acheter votre billet', 'titre' => 'Vos questions sur Weloveya'],
            'combine' => ['eyebrow' => 'D\'autres fêtes', 'titre' => 'Prolonger l\'ambiance festival'],
        ],
        'remplace' => [
            'blocks' => [
                ['titre' => 'Un festival tourné vers l\'océan'],
                ['titre' => 'Deux nuits, deux scènes'],
                ['titre' => 'Venir à Fidjrossè'],
            ],
            'histoire' => ['titre' => 'D\'une idée de Cotonou à une scène panafricaine'],
            'autour' => ['titre' => 'Cotonou, le jour d\'après'],
            'surPlace' => ['titre' => 'Sur le site du festival'],
            'lieux' => [
                ['photo' => '/assets/img/plage-fidjrosse-640x480.webp'],
                ['photo' => '/assets/img/danse-640x480.webp'],
                ['photo' => '/assets/img/cotonou-640x480.webp'],
                ['photo' => '/assets/img/sunset-640x480.webp'],
            ],
            'ambiance' => ['photos' => [
                '/assets/img/danse-1200x600.webp',
                '/assets/img/sunset-1200x600.webp',
                '/assets/img/cotonou-1200x675.webp',
            ]],
        ],
    ],

    'festival-des-masques' => [
        // Couleur des titres et repères de la page, lisible sur fond blanc.
        'accent' => '#a2582a',
        // Photo d'en-tête : le fichier d'origine (festivaldesmasques-1024x723.jpg) est abîmé — seul le
        // haut de l'image s'affiche, le reste est une bande unie. Il n'est pas
        // supprimé ; remplacez cette ligne par la vraie photo quand vous l'avez.
        'hero' => '/assets/img/egungun-1920x960.webp',
        'ordre' => ['signature', 'tips', 'blocks', 'programme', 'lieux', 'ambiance', 'tempsForts', 'histoire', 'stats', 'surPlace', 'autour', 'faq', 'combine'],
        'signature' => [
            'style' => 'glossaire',
            'eyebrow' => 'Savoir les reconnaître',
            'titre' => 'Quatre familles de masques',
            'lead' => 'Chaque masque a son rôle, son heure de sortie et ses règles. Les reconnaître, c\'est comprendre ce qui se joue devant vous.',
            'items' => [
                ['t' => 'Gèlèdè', 'd' => 'Masques de bois sculptés et peints, dansés le jour pour honorer les mères et la puissance féminine. Leur tradition orale est reconnue par l\'UNESCO comme patrimoine immatériel.'],
                ['t' => 'Egungun', 'd' => 'Les revenants : des costumes de tissus superposés, sans visage visible, qui incarnent les ancêtres revenus parmi les vivants. On ne les touche pas.'],
                ['t' => 'Zangbéto', 'd' => 'Les gardiens de la nuit, grands cônes de raphia qui tournoient sur eux-mêmes. Ils veillent sur le quartier et sortent surtout après le coucher du soleil.'],
                ['t' => 'Kaléta', 'd' => 'Masques joyeux et colorés, portés par les jeunes, hérités des familles revenues du Brésil. Ils animent les rues en fin d\'année et au Nouvel An.'],
            ],
        ],
        'titres' => [
            'tips' => ['eyebrow' => 'Avant tout', 'titre' => 'Les règles face aux masques', 'lead' => 'Un masque n\'est pas un costume : il incarne une force. Ces règles se respectent sans exception, même pour une photo.'],
            'programme' => ['eyebrow' => 'Le week-end', 'titre' => 'Du vendredi soir au dimanche', 'lead' => 'Le festival tient en un long week-end. Les horaires précis sont confirmés par les organisateurs quelques semaines avant.'],
            'lieux' => ['eyebrow' => 'Porto-Novo', 'titre' => 'Là où sortent les masques', 'lead' => 'De la place royale aux concessions familiales, la capitale devient une scène à ciel ouvert.'],
            'ambiance' => ['eyebrow' => 'Tambours et parades'],
            'tempsForts' => ['eyebrow' => 'À ne pas manquer', 'titre' => 'Cinq sorties qui font le festival', 'lead' => 'Chaque famille de masques a son heure : le gèlèdè au grand jour, le zangbéto à la nuit tombée.'],
            'histoire' => ['eyebrow' => 'Aux origines'],
            'surPlace' => ['eyebrow' => 'Dans la capitale'],
            'autour' => ['eyebrow' => 'Porto-Novo à pied', 'lead' => 'Mosquée afro-brésilienne, musées, jardin des plantes : la capitale se découvre en une journée de plus.'],
            'faq' => ['eyebrow' => 'Avant le festival', 'titre' => 'Vos questions sur les masques'],
            'combine' => ['eyebrow' => 'Masques et traditions', 'titre' => 'D\'autres rendez-vous de traditions'],
        ],
        'remplace' => [
            'blocks' => [
                ['titre' => 'Des masques qui incarnent des forces'],
                ['titre' => 'Un week-end de sorties'],
                ['titre' => 'Rejoindre Porto-Novo'],
            ],
            'histoire' => ['titre' => 'Porto-Novo, capitale des masques'],
            'autour' => ['titre' => 'Porto-Novo, au-delà du festival'],
            'surPlace' => ['titre' => 'Sur place à Porto-Novo'],
            'lieux' => [
                1 => ['photo' => '/assets/img/cathedrale-porto-novo.jpg'],
                3 => ['photo' => '/assets/img/zangbeto-640x480.webp'],
            ],
            'ambiance' => ['photos' => [
                '/assets/img/gelede-1200x675.webp',
                '/assets/img/porto-novo-1920x960.webp',
                '/assets/img/vodun-1200x675.webp',
            ]],
        ],
    ],

    'jistna' => [
        // Couleur des titres et repères de la page, lisible sur fond blanc.
        'accent' => '#3f7f22',
        'ordre' => ['blocks', 'signature', 'programme', 'lieux', 'tempsForts', 'ambiance', 'tips', 'histoire', 'stats', 'autour', 'surPlace', 'faq', 'combine'],
        'signature' => [
            'style' => 'carnet',
            'eyebrow' => 'Le carnet du visiteur',
            'titre' => 'Ce que l\'on vient apprendre',
            'lead' => 'Les JISTNA ne se regardent pas, elles s\'écoutent. Quatre sujets qui reviennent dans les échanges avec les sages et les tradipraticiens.',
            'items' => [
                ['t' => 'Les plantes qui soignent', 'd' => 'Feuilles, écorces et racines : la pharmacopée traditionnelle reste le premier recours de nombreuses familles. Les herboristes expliquent comment ils les reconnaissent et les préparent.'],
                ['t' => 'Le kinkéliba', 'd' => 'La tisane la plus bue d\'Afrique de l\'Ouest, prise au petit-déjeuner. Un bon point de départ pour parler des usages quotidiens des plantes.'],
                ['t' => 'La mangrove du Mono', 'd' => 'Autour de la Bouche du Roy, palétuviers et lagunes abritent poissons, crabes et oiseaux. Les communautés locales racontent comment elles la protègent.'],
                ['t' => 'La transmission', 'd' => 'Ces savoirs se transmettent oralement, d\'une génération à l\'autre. Les journées sont aussi un lieu où les jeunes viennent les recueillir.'],
            ],
        ],
        'titres' => [
            'programme' => ['eyebrow' => 'Deux journées', 'titre' => 'Le déroulé des deux jours', 'lead' => 'Tables rondes, démonstrations et marché : les horaires sont précisés par les organisateurs à l\'approche des journées.'],
            'lieux' => ['eyebrow' => 'Grand-Popo', 'titre' => 'Entre plage, salles et mangrove', 'lead' => 'Les rencontres se tiennent à Grand-Popo, entre l\'océan et l\'embouchure du Mono.'],
            'tempsForts' => ['eyebrow' => 'À ne pas manquer', 'titre' => 'Cinq moments pour comprendre', 'lead' => 'Des panels du premier jour au marché des simples, chaque séquence apporte un regard différent sur les savoirs traditionnels.'],
            'ambiance' => ['eyebrow' => 'Au bord de l\'océan'],
            'tips' => ['eyebrow' => 'Bien préparer sa venue', 'titre' => 'Les conseils pour les deux journées', 'lead' => 'Badge, espèces, baignade : quelques repères pour suivre les rencontres sans contretemps.'],
            'histoire' => ['eyebrow' => 'Pourquoi ces journées'],
            'autour' => ['eyebrow' => 'Le Mono à découvrir', 'lead' => 'Grand-Popo est une bonne base pour explorer l\'embouchure, les villages lacustres et la route côtière.'],
            'surPlace' => ['eyebrow' => 'À Grand-Popo'],
            'faq' => ['eyebrow' => 'Avant de venir', 'titre' => 'Vos questions sur les JISTNA'],
            'combine' => ['eyebrow' => 'Même esprit', 'titre' => 'D\'autres rendez-vous autour des traditions'],
        ],
        'remplace' => [
            'blocks' => [
                ['titre' => 'Des savoirs qu\'on vient écouter'],
                ['titre' => 'Rencontres, ateliers et marché'],
                ['titre' => 'Rejoindre Grand-Popo'],
            ],
            'histoire' => ['titre' => 'Donner la parole aux gardiens des savoirs'],
            'autour' => ['titre' => 'Autour de Grand-Popo'],
            'surPlace' => ['titre' => 'Sur place à Grand-Popo'],
            'lieux' => [
                1 => ['photo' => '/assets/img/grand-popo2-640x480.webp'],
                3 => ['photo' => '/assets/img/bouche-du-roy-640x480.webp'],
            ],
            'ambiance' => ['photos' => [
                '/assets/img/jistna-ceremonie-1024x682.webp',
                '/assets/img/bouche-du-roy-1200x600.webp',
                '/assets/img/mangrove-1200x600.webp',
            ]],
        ],
    ],

    'la-gaani' => [
        // Couleur des titres et repères de la page, lisible sur fond blanc.
        'accent' => '#b0232a',
        'ordre' => ['histoire', 'signature', 'tempsForts', 'ambiance', 'lieux', 'programme', 'blocks', 'stats', 'tips', 'surPlace', 'autour', 'faq', 'combine'],
        'signature' => [
            'style' => 'glossaire',
            'eyebrow' => 'Le vocabulaire de la fête',
            'titre' => 'Les mots de la Gaani',
            'lead' => 'Quelques mots entendus partout à Nikki pendant la fête, pour suivre les discours, les chants et les défilés.',
            'items' => [
                ['t' => 'Baatombu', 'd' => 'Le peuple bariba, majoritaire dans le Borgou, dont la Gaani est la grande fête. Sa langue est le baatonum.'],
                ['t' => 'Sinaboko', 'd' => 'Le titre du roi de Nikki, autour duquel s\'organise toute la fête : on vient lui rendre hommage et renouveler les liens avec lui.'],
                ['t' => 'Wasangari', 'd' => 'Les nobles cavaliers bariba. Ce sont eux qui paradent à cheval, en grande tenue, au moment le plus attendu de la Gaani.'],
                ['t' => 'Gesere', 'd' => 'Les griots, gardiens de la mémoire. Ils chantent les généalogies et les hauts faits des familles, au son des tambours.'],
                ['t' => 'Les chevaux', 'd' => 'Harnachés, parés de couleurs, ils sont au cœur de la fête. Leur présence rappelle le passé guerrier du royaume.'],
            ],
        ],
        'titres' => [
            'histoire' => ['eyebrow' => 'Pour commencer'],
            'tempsForts' => ['eyebrow' => 'Trois jours à Nikki', 'titre' => 'Des prières au grand défilé', 'lead' => 'La fête monte jusqu\'au défilé des cavaliers, puis se prolonge en banquets, démonstrations et marché.'],
            'ambiance' => ['eyebrow' => 'Au cœur du Borgou'],
            'lieux' => ['eyebrow' => 'Nikki', 'titre' => 'La ville royale en fête', 'lead' => 'Place centrale, palais, mosquée et marché : en trois jours, la fête passe par tous les lieux du pouvoir et de la vie de Nikki.'],
            'programme' => ['eyebrow' => 'Jour après jour', 'titre' => 'Trois jours de Gaani', 'lead' => 'La date suit le calendrier lunaire et change chaque année : renseignez-vous bien avant de prendre la route.'],
            'tips' => ['eyebrow' => 'Partir dans le Nord', 'titre' => 'Préparer la route vers Nikki', 'lead' => 'Longue route, chaleur, peu d\'hébergements : la Gaani se prépare plus que les fêtes du Sud.'],
            'surPlace' => ['eyebrow' => 'À Nikki'],
            'autour' => ['eyebrow' => 'Le Nord à découvrir', 'lead' => 'Parakou, la Pendjari, les chutes : la Gaani est la bonne occasion de découvrir le Nord du pays.'],
            'faq' => ['eyebrow' => 'Avant de partir', 'titre' => 'Vos questions sur la Gaani'],
            'combine' => ['eyebrow' => 'Prolonger dans le Nord', 'titre' => 'À combiner avec la Gaani'],
        ],
        'remplace' => [
            'blocks' => [
                ['titre' => 'La fête d\'un royaume'],
                ['titre' => 'Cavaliers, griots et banquets'],
                ['titre' => 'Rejoindre Nikki'],
            ],
            'histoire' => ['titre' => 'Une victoire devenue fête'],
            'autour' => ['titre' => 'Le Borgou et le Nord'],
            'surPlace' => ['titre' => 'Sur place à Nikki'],
            'lieux' => [
                ['photo' => '/assets/img/la-gaani-1024x682.jpg'],
                ['photo' => '/assets/img/nikki-640x480.webp'],
                ['photo' => '/assets/img/nikki-640x640.webp'],
                ['photo' => '/assets/img/marche-rue-640x480.webp'],
            ],
            'ambiance' => ['photos' => [
                '/assets/img/la-gaani-1024x682.jpg',
                '/assets/img/nikki-1200x600.webp',
                '/assets/img/marche-rue-1200x600.webp',
            ]],
        ],
    ],

];
