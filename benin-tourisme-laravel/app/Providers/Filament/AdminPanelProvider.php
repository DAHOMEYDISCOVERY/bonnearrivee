<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Filament\Widgets;
use Illuminate\Support\Facades\Blade;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('admin')
            ->path('admin')
            // Connexion aux couleurs du site (photo + formulaire, en français) ;
            // « Mot de passe oublié ? » envoie un lien de réinitialisation.
            ->login(\App\Filament\Admin\Auth\Login::class)
            ->passwordReset(\App\Filament\Admin\Auth\RequestPasswordReset::class, \App\Filament\Admin\Auth\ResetPassword::class)
            ->brandName('Bonne Arrivée')
            ->brandLogo('/assets/site/divers/app/themes/benin/src/img/logo/logo.png')
            ->brandLogoHeight('2.75rem')
            ->favicon('/assets/favicon-carte-32.png')
            ->colors([
                'primary' => Color::hex('#00a05d'),
            ])
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\\Filament\\Admin\\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\\Filament\\Admin\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'App\\Filament\\Admin\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                // Français partout dans le back-office, session fermée après
                // inactivité, en-têtes de sécurité (pas d'iframe, pas d'index).
                \App\Http\Middleware\AdminLocale::class,
                \App\Http\Middleware\AdminIdleTimeout::class,
                \App\Http\Middleware\AdminSecurityHeaders::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            // Charge le JS du composant Alpine 'baHtmlEditor' avant tout DOM
            // qui en depend. Separe du Blade field pour ne pas se faire
            // echapper comme texte par le wrapper Filament/Livewire.
            ->renderHook(
                PanelsRenderHook::HEAD_START,
                fn (): string => '<meta name="robots" content="noindex, nofollow">',
            )
            ->renderHook(
                PanelsRenderHook::BODY_START,
                fn (): string => Blade::render('<script src="/assets/admin/ba-html-editor.js?v=7" defer></script>'),
            );
    }
}
