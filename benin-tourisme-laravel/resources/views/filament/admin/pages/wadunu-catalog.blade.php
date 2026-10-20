<x-filament-panels::page>
    <div class="space-y-6">
        <div class="fi-section rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <h2 class="text-lg font-semibold text-gray-950 dark:text-white">PDF actuellement servi aux abonnés</h2>
            @if ($currentExists)
                <div class="mt-3 flex flex-wrap items-center gap-4">
                    <span class="inline-flex items-center gap-2 rounded-lg bg-green-50 px-3 py-1.5 text-sm font-medium text-green-800 dark:bg-green-950/50 dark:text-green-200">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        En ligne
                    </span>
                    <span class="text-sm text-gray-600 dark:text-gray-400">Taille : <strong>{{ $currentSize }}</strong></span>
                    <span class="text-sm text-gray-600 dark:text-gray-400">Dernière MAJ : <strong>{{ $currentUpdatedAt }}</strong></span>
                </div>
                <p class="mt-3 text-sm text-gray-600 dark:text-gray-400">
                    Uploader un nouveau PDF ci-dessous remplacera immédiatement celui-ci pour tous les abonnés actifs.
                </p>
            @else
                <div class="mt-3 rounded-lg bg-amber-50 p-4 text-sm text-amber-900 dark:bg-amber-950/50 dark:text-amber-200">
                    <strong>Aucun PDF chargé.</strong> Les abonnés qui suivent leur lien magique verront un message d'attente. Uploadez le PDF ci-dessous pour activer le catalogue.
                </div>
            @endif
        </div>

        <form wire:submit="save">
            {{ $this->form }}
            <div class="mt-6 flex justify-end">
                <x-filament::button type="submit" size="lg" color="primary">
                    Publier ce PDF
                </x-filament::button>
            </div>
        </form>
    </div>
</x-filament-panels::page>
