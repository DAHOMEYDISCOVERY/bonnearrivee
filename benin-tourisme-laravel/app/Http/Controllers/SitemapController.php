<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Support\PageRepository;
use Illuminate\Http\Response;

/**
 * Génère /sitemap.xml à partir du registre des pages.
 *
 * Sortie conforme au protocole sitemaps.org 0.9 : une <url> par page,
 * chaque URL construite en absolu à partir de APP_URL. Pas de lastmod
 * (les entrées du registre n'ont pas de date de modification stable)
 * ni de priority (Google les ignore).
 */
final class SitemapController extends Controller
{
    public function __construct(private readonly PageRepository $pages) {}

    public function xml(): Response
    {
        $base = rtrim(config('app.url', 'http://127.0.0.1:8010'), '/');
        // Pages internes (rédaction, débogage) que Google ne doit pas indexer.
        // Elles portent déjà `<meta robots="noindex">`, on les retire aussi ici
        // pour ne pas gaspiller le crawl budget.
        $excluded = ['/fiche-de-relecture'];

        $urls = [];
        foreach ($this->pages->all() as $path => $_page) {
            if (in_array($path, $excluded, true)) {
                continue;
            }
            $absolute = $base.($path === '/' ? '/' : $path);
            $urls[] = '  <url><loc>'.htmlspecialchars($absolute, ENT_XML1 | ENT_QUOTES, 'UTF-8').'</loc></url>';
        }
        // Routes publiées en dehors du registre (voir routes/web.php).
        foreach ([
            '/carte-du-benin', '/benin-en-fete', '/a-propos',
            '/explorez/la-vie-semerveille-au-benin',
            '/benin-en-fete/vodun-days',
            '/benin-en-fete/weloveya',
            '/benin-en-fete/festival-des-masques',
            '/benin-en-fete/jistna',
            '/benin-en-fete/la-gaani',
        ] as $extra) {
            $urls[] = '  <url><loc>'.htmlspecialchars($base.$extra, ENT_XML1 | ENT_QUOTES, 'UTF-8').'</loc></url>';
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n"
             .'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n"
             .implode("\n", $urls)."\n"
             .'</urlset>'."\n";

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }
}
