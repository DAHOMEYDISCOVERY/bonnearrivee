<?php

namespace App\Filament\Admin\Resources\RestaurantResource\Pages;

use App\Filament\Admin\Resources\RestaurantResource;
use App\Support\PublishGuard;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateRestaurant extends CreateRecord
{
    protected static string $resource = RestaurantResource::class;

    /**
     * Garde-fou : l'adresse n'est publiée que si la page Restaurants
     * s'affiche encore avec elle ; sinon elle est retirée et l'éditeur voit
     * pourquoi.
     */
    protected function afterCreate(): void
    {
        $problem = PublishGuard::problem('/sejourner/restaurants');
        if ($problem === null) {
            return;
        }

        $this->record->delete();

        Notification::make()
            ->title('Adresse non publiée')
            ->body($problem.' Vérifiez les champs de la fiche (photos, horaires, budget) puis réessayez.')
            ->danger()
            ->persistent()
            ->send();

        $this->halt();
    }
}
