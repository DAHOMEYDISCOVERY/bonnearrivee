<x-filament-panels::page>
    <div class="rounded-xl bg-white p-4 text-sm text-gray-700 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:text-gray-300 dark:ring-white/10">
        <p><strong>Ce qui se modifie ici :</strong> les textes, liens, images et coordonnées du menu principal et du pied de page. Cliquez sur « Enregistrer et publier » : le site est mis à jour immédiatement.</p>
        <p class="mt-2">
            @if (\App\Support\SiteLayout::hasSavedHeader() || \App\Support\SiteLayout::hasSavedFooter())
                Le site affiche actuellement <strong>la version enregistrée ici</strong>.
            @else
                Le site affiche actuellement <strong>les valeurs du code</strong> : rien n'a encore été enregistré ici.
            @endif
            La mise en page elle-même (couleurs, disposition) se règle dans le code.
        </p>
    </div>

    <form wire:submit="save" class="space-y-6">
        {{ $this->form }}

        <div class="flex justify-end">
            <x-filament::button type="submit" icon="heroicon-o-check">
                Enregistrer et publier
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
