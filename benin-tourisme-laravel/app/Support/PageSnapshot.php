<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Facade;

/**
 * HTML d'une page du site tel que le code le produit, sans les modifications
 * « Textes et images » : c'est la référence à partir de laquelle le back-office
 * liste les textes, images et liens modifiables.
 *
 * La page est produite en appelant directement l'action de sa route (vue,
 * contrôleur ou fonction), sans repasser par la pile HTTP : la session et
 * l'authentification de la personne connectée au back-office ne sont pas
 * touchées.
 */
final class PageSnapshot
{
    public static function html(string $path): ?string
    {
        $path = ContentLayer::normalisePath($path);
        $original = app('request');

        $request = Request::create($path, 'GET', [], [], [], [
            'HTTP_HOST' => $original->getHttpHost(),
            'HTTPS' => $original->isSecure() ? 'on' : 'off',
            'HTTP_ACCEPT' => 'text/html',
        ]);
        if ($original->hasSession()) {
            $request->setLaravelSession($original->session());
        }

        try {
            /** @var Router $router */
            $router = app('router');
            $route = $router->getRoutes()->match($request);
            $request->setRouteResolver(static fn () => $route);

            app()->instance('request', $request);
            Facade::clearResolvedInstance('request');

            $response = Router::toResponse($request, $route->run());
            if ($response->getStatusCode() !== 200) {
                return null;
            }
            $html = (string) $response->getContent();

            return stripos($html, '<html') !== false ? $html : null;
        } catch (\Throwable $e) {
            report($e);

            return null;
        } finally {
            app()->instance('request', $original);
            Facade::clearResolvedInstance('request');
        }
    }
}
