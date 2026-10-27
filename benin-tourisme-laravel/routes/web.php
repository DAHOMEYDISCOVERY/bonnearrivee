<?php

declare(strict_types=1);

use App\Http\Controllers\PageController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

/*
 * L'admin est entierement gere par Filament via AdminPanelProvider :
 *  - /admin -> dashboard
 *  - /admin/login -> page de login native Filament (filament.admin.auth.login)
 *  - /admin/* -> ressources Filament (restaurants, pages, utilisateurs...)
 * Les anciennes routes custom (AuthController, PageAdminController) sont
 * supprimees pour eviter le conflit avec les routes Filament.
 */

/*
 * Le sitemap est publié avant la route attrape-tout, pour ne pas être avalé
 * par elle. Il liste toutes les pages du registre en une seule requête.
 */
Route::get('/sitemap.xml', [SitemapController::class, 'xml'])->name('sitemap');

/*
 * Recherche globale (option B) : moteur Laravel natif qui remplace l'ancien
 * composant web `<woody-search-engine>` (dependait d'une API Woody externe).
 * Interroge restaurants, pages editoriales et evenements.
 */
Route::get('/recherche', [SearchController::class, 'index'])->name('recherche');

/*
 * Vestige WordPress : le script `eskalad-default.js` (analytics du thème) appelle
 * `/wp-json/woody/cookie-toolbox` en dur. Sans cet endpoint, le catch-all
 * répond 404 et Eskalad log « Unable to create cookie toolbox ». On lui rend un
 * JSON vide pour qu'il se taise sans avoir à retirer le script.
 */
Route::get('/wp-json/woody/cookie-toolbox', fn () => response()->json([
    // Format attendu par eskalad-default.js : `s.options.customize` doit
    // exister, `s.toolbox` doit être une chaîne HTML (vide = pas de bandeau).
    'options' => [
        'customize' => (object) [],
        'services'  => [],
        'consent'   => (object) [],
    ],
    'toolbox' => '',
]));

/*
 * Page dédiée à la carte interactive du Bénin (4 niveaux : monde, Afrique
 * de l'Ouest, golfe de Guinée, 12 départements). Publiée en route explicite
 * plutôt qu'ajoutée au registre pour que le fichier fourni reste isolé
 * — un simple retrait de la route et du fichier vue suffit à rollback.
 */
Route::view('/carte-du-benin', 'pages.carte-du-benin', [
    'title' => 'Carte interactive du Bénin',
    'description' => 'Explorez le Bénin en quatre niveaux : les vols directs depuis Cotonou, l\'Afrique de l\'Ouest, le golfe de Guinée, et les 12 départements.',
    'activeMenu' => null,
    'headProfile' => 'standard',
    'customContent' => null,
])->name('carte-du-benin');

/*
 * Page « Bénin en Fête » : agenda des grands événements culturels
 * (Vodun Days, Festival des Masques, JISTNA, La Gaani, Weloveya).
 * Route explicite parce que le contenu vient d'un fichier vue fourni
 * hors du registre — même modèle que /carte-du-benin.
 */
Route::view('/benin-en-fete', 'pages.benin-en-fete.index', [
    'title' => 'Bénin en Fête | Bonne Arrivée',
    'description' => 'L\'agenda des grands rendez-vous culturels du Bénin : Vodun Days, Festival des Masques, La Gaani, JISTNA, Weloveya.',
    'activeMenu' => null,
    'headProfile' => 'standard',
    'customContent' => null,
])->name('benin-en-fete');

/*
 * Fiches détaillées d'un événement de « Bénin en Fête ». Le slug est
 * résolu par le contrôleur qui charge le tableau `resources/data/events.php`.
 */
Route::get('/benin-en-fete/{slug}', function (string $slug) {
    $events = require resource_path('data/benin-events.php');
    abort_unless(isset($events[$slug]), 404, "Aucun événement pour « {$slug} ».");
    $e = $events[$slug];

    return view('pages.benin-en-fete.event', [
        'title' => $e['title'] . ' | Bonne Arrivée',
        'description' => $e['description'],
        'activeMenu' => null,
        'headProfile' => 'standard',
        'customContent' => null,
        'event' => $e,
        'slug' => $slug,
    ]);
})->where('slug', '[a-z0-9-]+')->name('benin-en-fete.event');

/*
 * Page « À propos » — présentation de Dahomey Discovery et du portail
 * Bonne Arrivée. Remplace l'ancien /a-propos-de-lanpt (nom hérité).
 */
/*
 * Page listing thématique « La vie s'émerveille au Bénin » — équivalent du
 * « La vie s'invente à Mayotte » de l'original. Onze escales illustrées vers
 * les fiches éditoriales existantes. Route explicite pour rester hors du
 * registre (fichier de vue autonome).
 */
Route::view('/explorez/la-vie-semerveille-au-benin', 'pages.explorez.la-vie-semerveille-au-benin', [
    'title' => 'La vie s\'émerveille au Bénin | Bonne Arrivée',
    'description' => 'Onze escales pour comprendre le Bénin : Pendjari, Ganvié, Abomey, Ouidah, Atacora, Tanougou, vodun, betammaribé, cuisine du terroir.',
    'activeMenu' => null,
    'headProfile' => 'standard',
    'customContent' => null,
])->name('la-vie-semerveille');

Route::get('/sejourner/restaurants', function () {
    return view('pages.sejourner.restaurants', [
        'title' => 'Où manger au Bénin — Catalogue restaurants | Bonne Arrivée',
        'description' => 'Un annuaire éditorial vivant des meilleures adresses gastronomiques, maquis, cafés et bars du Bénin. Accès abonné, mises à jour automatiques, lien personnel.',
        'activeMenu' => null,
        'headProfile' => 'standard',
        'customContent' => null,
        // Aperçu : 6 coups de cœur (ou 6 premiers si moins de coups de cœur)
        'previewRestos' => \App\Models\Restaurant::published()
            ->orderByDesc('coup_de_coeur')
            ->orderBy('sort_order')
            ->take(6)
            ->get(),
        'totalRestos'    => \App\Models\Restaurant::published()->count(),
        'totalVilles'    => \App\Models\Restaurant::published()->distinct('ville')->count('ville'),
        'previewNames'   => \App\Models\Restaurant::published()
            ->orderBy('sort_order')
            ->skip(6)->take(30)
            ->pluck('nom')
            ->toArray(),
    ]);
})->name('restaurants');

// Wa Dùnù — flow commande + magic link + catalogue prive
Route::post('/restaurants/order', [\App\Http\Controllers\WadunuController::class, 'createOrder'])
    ->name('wadunu.order.create');
Route::post('/restaurants/paid/{order}', [\App\Http\Controllers\WadunuController::class, 'paidCallback'])
    ->name('wadunu.paid.callback');
Route::get('/mes-restos/{token}', [\App\Http\Controllers\WadunuController::class, 'catalog'])
    ->name('wadunu.catalog');
Route::get('/mes-restos/{token}/pdf', [\App\Http\Controllers\WadunuController::class, 'catalogPdf'])
    ->name('wadunu.catalog.pdf');

Route::view('/quand-partir', 'pages.quand-partir', [
    'title' => 'Quand partir au Bénin ? Climat et saisons | Bonne Arrivée',
    'description' => 'Grille climat 12 mois × 5 régions du Bénin. Saison sèche, saison des pluies, harmattan : choisir la meilleure période selon votre destination.',
    'activeMenu' => null,
    'headProfile' => 'standard',
    'customContent' => null,
])->name('quand-partir');

Route::view('/guide-conversation', 'pages.guide-conversation', [
    'title' => 'Guide de conversation Fon / Yoruba | Bonne Arrivée',
    'description' => 'Les phrases utiles en fon et en yoruba : bonjour, merci, au marché, se repérer. Vingt-cinq expressions pour dialoguer avec les Béninois.',
    'activeMenu' => null,
    'headProfile' => 'standard',
    'customContent' => null,
])->name('guide-conversation');

Route::view('/timeline-benin', 'pages.timeline-benin', [
    'title' => 'Timeline historique du Bénin | Bonne Arrivée',
    'description' => 'Une brève histoire du Bénin en trente-cinq dates : des royaumes fon d\'Abomey aux Vodun Days d\'aujourd\'hui.',
    'activeMenu' => null,
    'headProfile' => 'standard',
    'customContent' => null,
])->name('timeline-benin');

Route::view('/convertisseur', 'pages.convertisseur', [
    'title' => 'Convertisseur XOF ↔ EUR / USD | Bonne Arrivée',
    'description' => 'Convertissez le franc CFA en euro, dollar ou livre sterling. Parité XOF/EUR fixe, repères de prix courants au Bénin.',
    'activeMenu' => null,
    'headProfile' => 'standard',
    'customContent' => null,
])->name('convertisseur');

Route::view('/agenda', 'pages.agenda', [
    'title' => 'Agenda annuel du Bénin | Bonne Arrivée',
    'description' => 'Douze mois de festivals, fêtes coutumières et temps forts culturels au Bénin. Vodun Days, JISTNA, Weloveya, Festival des Masques, La Gaani.',
    'activeMenu' => null,
    'headProfile' => 'standard',
    'customContent' => null,
])->name('agenda');

Route::view('/checklist', 'pages.checklist', [
    'title' => 'Checklist voyage au Bénin | Bonne Arrivée',
    'description' => 'Trente-cinq points cochables pour préparer votre séjour : documents, santé, argent, bagages. Sauvegarde automatique sur votre appareil.',
    'activeMenu' => null,
    'headProfile' => 'standard',
    'customContent' => null,
])->name('checklist');

Route::view('/a-propos', 'pages.a-propos.index', [
    'title' => 'À propos — Bonne Arrivée',
    'description' => 'Dahomey Discovery, agence réceptive au Bénin fondée en 2018 par Zoulfati HASSAN DERBY, éditeur du portail Bonne Arrivée.',
    'activeMenu' => null,
    'headProfile' => 'standard',
    'customContent' => null,
])->name('a-propos');

/*
 * Redirection permanente de l'ancien URL /a-propos-de-lanpt vers /a-propos.
 * Préserve les backlinks externes (moteurs, réseaux sociaux).
 */
Route::redirect('/a-propos-de-lanpt', '/a-propos', 301);

/*
 * Le site est entièrement éditorial : une route unique résout n'importe quel
 * chemin auprès du registre des pages. Les URL du miroir sont conservées à
 * l'identique, sans « index.html » ni extension.
 */
Route::get('/{path?}', [PageController::class, 'show'])
    ->where('path', '.*')
    ->name('page');
