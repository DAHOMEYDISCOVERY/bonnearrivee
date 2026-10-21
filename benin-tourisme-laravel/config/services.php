<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Resend, Postmark, AWS, and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    // Portail Bonne Arrivée — indexation des sites frères Dahomey Discovery.
    // Les clés WooCommerce sont optionnelles : sans elles, on lit tout ce
    // qui est déjà public via /wp-json/wp/v2/*. Avec elles, on récupère en
    // plus les prix et stocks via /wp-json/wc/v3/products.
    // KKiaPay — passerelle de paiement pour le catalogue Wa Dùnù.
    // Cartes internationales Visa/Mastercard + Mobile Money MTN/Moov Bénin.
    // Clés à recuperer sur https://kkiapay.me (compte marchand Dahomey Discovery).
    'kkiapay' => [
        'public_key'   => env('KKIAPAY_PUBLIC_KEY'),
        'private_key'  => env('KKIAPAY_PRIVATE_KEY'),
        'secret'       => env('KKIAPAY_SECRET'),
        'sandbox'      => env('KKIAPAY_SANDBOX', true),
        'catalog_amount_xof' => env('KKIAPAY_CATALOG_AMOUNT', 15000),
        'catalog_duration_months' => 6,
    ],

    // Wa Dùnù — catalogue PDF héberge sur Nextcloud (lien de partage prive).
    // Le serveur authentifie en back-end avec le mot de passe du share, puis
    // streame le PDF a l'abonne. L'abonne ne voit jamais le mot de passe.
    // En l'absence de ces variables, le controleur retombe sur le disque local
    // storage/app/private/wadunu/catalogue.pdf (mode dev).
    'wadunu' => [
        'pdf_url'      => env('WADUNU_PDF_URL'),
        'pdf_password' => env('WADUNU_PDF_PASSWORD'),
    ],

    'aggregator' => [
        // En dev local (WAMP), le bundle de certificats CA est souvent absent :
        // mettre AGG_HTTP_VERIFY=false dans .env pour désactiver la vérification.
        // En prod, laisser à true (défaut).
        'verify_ssl' => env('AGG_HTTP_VERIFY', true),
        'dahomey' => [
            'base_url'        => env('AGG_DAHOMEY_URL', 'https://dahomeydiscovery.com'),
            'consumer_key'    => env('AGG_DAHOMEY_WC_KEY'),
            'consumer_secret' => env('AGG_DAHOMEY_WC_SECRET'),
        ],
        'petit_train' => [
            'base_url'        => env('AGG_PETIT_TRAIN_URL', 'https://www.petit-train-cotonou.com'),
            'consumer_key'    => env('AGG_PETIT_TRAIN_WC_KEY'),
            'consumer_secret' => env('AGG_PETIT_TRAIN_WC_SECRET'),
        ],
    ],

];
