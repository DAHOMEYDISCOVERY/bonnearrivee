<?php

namespace App\Filament\Admin\Resources\SiteFragmentResource\Pages;

use App\Filament\Admin\Resources\SiteFragmentResource;
use App\Models\SiteFragment;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListSiteFragments extends ListRecords
{
    protected static string $resource = SiteFragmentResource::class;

    /**
     * A l'ouverture, on s'assure que header + footer sont presents en base
     * (seed depuis le HTML des partials actuels s'ils manquent). Idempotent.
     */
    public function mount(): void
    {
        parent::mount();
        SiteFragment::seedFromPartials();
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('resyncFromPartials')
                ->label('Resynchroniser depuis les partials')
                ->icon('heroicon-o-arrow-path')
                ->color('gray')
                ->requiresConfirmation()
                ->modalHeading('Ecraser les blocs avec le HTML des partials ?')
                ->modalDescription('Les contenus actuellement en base seront remplaces par le HTML extrait des fichiers views/partials/*.blade.php. Toute modification faite depuis le back-office sera perdue.')
                ->action(function () {
                    $r = SiteFragment::seedFromPartials(force: true);
                    Notification::make()
                        ->title('Blocs resynchronises')
                        ->body("Rechargement du HTML original pour {$r['total']} bloc(s).")
                        ->success()
                        ->send();
                }),
        ];
    }
}
