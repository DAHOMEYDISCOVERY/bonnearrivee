<?php

namespace App\Filament\Admin\Resources\PageResource\Pages;

use App\Filament\Admin\Resources\PageResource;
use App\Models\Page;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListPages extends ListRecords
{
    protected static string $resource = PageResource::class;

    /**
     * A l'ouverture de l'ecran, on verifie que la table contient les pages
     * du registre. Le check est tres cheap : COUNT(*) + ne fait rien si
     * le compte correspond. Pas d'INSERT par ligne. Garantit que le back-
     * office voit toutes les pages sans ralentir le rendu.
     */
    public function mount(): void
    {
        parent::mount();
        Page::syncFromRegistry();
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('resync')
                ->label('Resynchroniser le registre')
                ->icon('heroicon-o-arrow-path')
                ->color('gray')
                ->action(function () {
                    $r = Page::syncFromRegistry(force: true);
                    Notification::make()
                        ->title('Registre synchronisé')
                        ->body("{$r['created']} nouvelle(s) page(s) ajoutée(s) sur {$r['total']}.")
                        ->success()
                        ->send();
                }),
            Actions\CreateAction::make(),
        ];
    }
}
