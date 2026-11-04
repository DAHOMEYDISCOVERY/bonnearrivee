<?php

declare(strict_types=1);

namespace App\Filament\Admin\Auth;

use Filament\Pages\Auth\PasswordReset\RequestPasswordReset as BaseRequestPasswordReset;

/**
 * « Mot de passe oublié ? » : même mise en page que la connexion.
 */
class RequestPasswordReset extends BaseRequestPasswordReset
{
    protected static string $layout = 'filament.admin.auth.layout';

    public function hasLogo(): bool
    {
        return false;
    }
}
