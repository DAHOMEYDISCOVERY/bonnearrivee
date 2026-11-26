<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Support\PageRepository;
use Illuminate\Http\Response;

/**
 * Génère /sitemap.xml à partir du registre des pages.
 *
 * Sortie conforme au protocole sitemaps.org 0.9 : une <url> par page et par
 * langue (fr, en, es, pt), avec ses versions alternées (hreflang), chaque URL
 * construite en absolu à partir de APP_URL. Pas de lastmod
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

        $paths = [];
        foreach ($this->pages->all() as $path => $_page) {
            if (in_array($path, $excluded, true)) {
                continue;
            }
            $paths[] = $path === '' ? '/' : $path;
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
            $paths[] = $extra;
        }

        // Une <url> par langue (fr, en, es, pt), chacune listant toutes ses
        // versions (xhtml:link hreflang) : format multilingue recommandé par Google.
        $esc = static fn (string $u): string => htmlspecialchars($u, ENT_XML1 | ENT_QUOTES, 'UTF-8');
        $urls = [];
        foreach (array_unique($paths) as $path) {
            $alts = '';
            foreach (\App\Support\Locales::ALL as $code => $l) {
                $alts .= "\n    <xhtml:link rel=\"alternate\" hreflang=\"{$l['hreflang']}\" href=\"".$esc($base.\App\Support\Locales::path($code, $path))."\"/>";
            }
            $alts .= "\n    <xhtml:link rel=\"alternate\" hreflang=\"x-default\" href=\"".$esc($base.$path)."\"/>";
            foreach (\App\Support\Locales::codes() as $code) {
                $urls[] = '  <url><loc>'.$esc($base.\App\Support\Locales::path($code, $path)).'</loc>'.$alts."\n  </url>";
            }
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n"
             .'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">'."\n"
             .implode("\n", $urls)."\n"
             .'</urlset>'."\n";

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }
}
