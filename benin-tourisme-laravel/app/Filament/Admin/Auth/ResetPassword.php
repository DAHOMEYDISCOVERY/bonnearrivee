<?php

declare(strict_types=1);

namespace App\Filament\Admin\Auth;

use Filament\Pages\Auth\PasswordReset\ResetPassword as BaseResetPassword;

/**
 * Choix du nouveau mot de passe (lien reçu par e-mail) : même mise en page
 * que la connexion.
 */
class ResetPassword extends BaseResetPassword
{
    protected static string $layout = 'filament.admin.auth.layout';

    public function hasLogo(): bool
    {
        return false;
    }
}
