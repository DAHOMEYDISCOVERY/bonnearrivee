<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Le back-office s'affiche en français, quelle que soit la langue réglée
 * dans .env.
 *
 * Une langue écrite « fr_FR » (format système) n'a pas de traductions chez
 * Filament, qui attend « fr » : l'écran de connexion affichait alors les clés
 * brutes « filament-panels::pages/auth/login.heading ». La langue de secours
 * est ramenée à l'anglais pour qu'une clé manquante ne s'affiche jamais.
 */
final class AdminLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        app()->setLocale('fr');
        app('translator')->setFallback('en');

        return $next($request);
    }
}
