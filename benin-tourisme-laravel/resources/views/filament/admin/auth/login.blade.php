{{-- Formulaire de connexion : celui de Filament, sans le cadre gris. --}}
<x-filament-panels::page.simple>
    @if (session('ba_admin_notice'))
        <p class="ba-login__notice" role="status">{{ session('ba_admin_notice') }}</p>
    @endif

    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE, scopes: $this->getRenderHookScopes()) }}

    <x-filament-panels::form id="form" wire:submit="authenticate">
        {{ $this->form }}

        <x-filament-panels::form.actions
            :actions="$this->getCachedFormActions()"
            :full-width="$this->hasFullWidthFormActions()"
        />
    </x-filament-panels::form>

    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_AFTER, scopes: $this->getRenderHookScopes()) }}

    <p class="ba-login__note">
        Accès réservé à l’équipe de Bonne Arrivée. Les tentatives sont limitées et
        la session se ferme après une période d’inactivité.
    </p>
</x-filament-panels::page.simple>
