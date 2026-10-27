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
     * A l'ouverture de l'ecran, on importe automatiquement toutes les
     * pages du registre `resources/pages.php` dans la table. firstOrCreate
     * garantit qu'on ne touche jamais aux rows existantes (overrides, titres
     * modifies). Resultat : le back-office liste directement les ~89 pages
     * du site sans seeder manuel.
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
                    $r = Page::syncFromRegistry();
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
