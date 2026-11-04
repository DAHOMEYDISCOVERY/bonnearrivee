<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Ferme la session du back-office après une période sans activité.
 *
 * Sans cela, une session ouverte restait valable tant que le navigateur
 * gardait son cookie : en tapant /admin on arrivait directement dans le
 * tableau de bord, y compris sur un ordinateur partagé. Le délai se règle
 * avec ADMIN_IDLE_MINUTES (60 minutes par défaut, voir config/admin.php).
 *
 * « Se souvenir de moi » reste un choix explicite de l'utilisateur à la
 * connexion : il permet de revenir sans retaper son mot de passe.
 */
final class AdminIdleTimeout
{
    public const SESSION_KEY = 'ba_admin_last_activity';

    public function handle(Request $request, Closure $next): Response
    {
        $guard = Filament::auth();

        if ($guard->check()) {
            $minutes = max(1, (int) config('admin.idle_minutes', 60));
            $last = (int) $request->session()->get(self::SESSION_KEY, 0);
            // Connexion avec « Se souvenir de moi » : le cookie de rappel est là.
            $souvenir = method_exists($guard, 'getRecallerName')
                && $request->cookies->has($guard->getRecallerName());

            if ($last > 0 && (time() - $last) > $minutes * 60 && ! $souvenir) {
                $guard->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->to(Filament::getLoginUrl())
                    ->with('ba_admin_notice', 'Votre session a expiré après une période d’inactivité. Merci de vous reconnecter.');
            }

            $request->session()->put(self::SESSION_KEY, time());
        }

        return $next($request);
    }
}
