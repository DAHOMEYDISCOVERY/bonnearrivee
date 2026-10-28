<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Support\PageRepository;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Sert les pages éditoriales du site.
 *
 * Une seule route couvre les 89 pages : leur arborescence est celle du registre,
 * pas celle du fichier de routes.
 */
final class PageController extends Controller
{
    public function __construct(private readonly PageRepository $pages) {}

    public function show(string $path = '/'): View
    {
        $page = $this->pages->find($path);

        if ($page === null) {
            throw new NotFoundHttpException("Aucune page ne répond à « {$path} ».");
        }

        // Le back-office peut fournir un contenu personnalisé qui remplace la
        // vue Blade historique : le dépôt le signale via `customView` et joint
        // le HTML sous `customContent`.
        $view = $page['customView'] ?? $page['view'];

        return view($view, [
            'title' => $page['title'],
            'description' => $page['description'] ?? null,
            'activeMenu' => $page['activeMenu'] ?? null,
            'headProfile' => $page['headProfile'] ?? 'standard',
            'customContent' => $page['customContent'] ?? null,
            // La vue 'editable-raw' en lit les sections critiques (headGlobals,
            // headSchema, styles, scripts) pour les reinjecter.
            'originalView' => $page['originalView'] ?? null,
        ]);
    }
}
