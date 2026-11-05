<?php

namespace App\Filament\Admin\Resources\RestaurantResource\Pages;

use App\Filament\Admin\Resources\RestaurantResource;
use App\Support\PublishGuard;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditRestaurant extends EditRecord
{
    protected static string $resource = RestaurantResource::class;

    /** Fiche avant l'enregistrement, pour l'annuler si besoin. */
    private array $avantEnregistrement = [];

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }

    protected function beforeSave(): void
    {
        $this->avantEnregistrement = $this->record->getRawOriginal();
    }

    /**
     * Garde-fou : la modification n'est gardée que si la page Restaurants
     * s'affiche encore ; sinon la fiche reprend sa version précédente.
     */
    protected function afterSave(): void
    {
        $problem = PublishGuard::problem('/sejourner/restaurants');
        if ($problem === null) {
            return;
        }

        $this->record->newQuery()->whereKey($this->record->getKey())->update(
            array_diff_key($this->avantEnregistrement, array_flip([$this->record->getKeyName(), 'created_at']))
        );
        $this->record->refresh();

        Notification::make()
            ->title('Modification non publiée')
            ->body($problem.' La fiche garde sa version précédente.')
            ->danger()
            ->persistent()
            ->send();

        $this->halt();
    }
}
