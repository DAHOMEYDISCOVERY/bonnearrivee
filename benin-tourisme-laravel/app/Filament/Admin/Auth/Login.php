<?php

declare(strict_types=1);

namespace App\Filament\Admin\Auth;

use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Contracts\Support\Htmlable;

/**
 * Page de connexion du back-office, aux couleurs du site.
 *
 * Même formulaire, même contrôle que la page Filament d'origine (limite de
 * cinq essais par minute, « Se souvenir de moi », redirection vers la page
 * demandée) : seule la présentation change — photo du Bénin d'un côté,
 * formulaire de l'autre, textes en français.
 */
class Login extends BaseLogin
{
    protected static string $layout = 'filament.admin.auth.layout';

    protected static string $view = 'filament.admin.auth.login';

    public function getTitle(): string | Htmlable
    {
        return 'Connexion';
    }

    public function getHeading(): string | Htmlable
    {
        return 'Espace d’administration';
    }

    public function getSubheading(): string | Htmlable | null
    {
        return 'Connectez-vous pour gérer les pages, l’en-tête, le pied de page et les restaurants du site.';
    }

    public function hasLogo(): bool
    {
        return false;
    }
}
