<?php

namespace App\Filament\Admin\Resources\PageResource\Pages;

use App\Filament\Admin\Resources\PageResource;
use App\Models\Page;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditPage extends EditRecord
{
    protected static string $resource = PageResource::class;

    /**
     * A l'ouverture de l'ecran d'edition, si le champ content_override est vide
     * (page jamais editee), on injecte le HTML extrait de la vue Blade source
     * pour que l'editeur affiche le contenu reel au lieu d'un champ vide.
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        if (empty($data['content_override']) && !empty($data['view'])) {
            $extracted = Page::extractContentFromView((string) $data['view']);
            if ($extracted !== null) {
                $data['content_override'] = $extracted;
            }
        }
        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('reloadFromBlade')
                ->label('Recharger depuis la vue d\'origine')
                ->icon('heroicon-o-arrow-uturn-left')
                ->color('gray')
                ->requiresConfirmation()
                ->modalHeading('Recharger le contenu depuis la vue Blade ?')
                ->modalDescription('Le contenu actuellement rédigé dans l\'éditeur sera remplacé par le HTML d\'origine de la vue Blade. L\'enregistrement n\'est pas sauvegardé tant que vous ne cliquez pas sur « Save changes ».')
                ->action(function () {
                    $view = $this->record->view;
                    $extracted = Page::extractContentFromView((string) $view);
                    if ($extracted === null) {
                        Notification::make()
                            ->title('Vue introuvable')
                            ->body("Impossible de localiser le fichier de la vue « {$view} ».")
                            ->warning()
                            ->send();
                        return;
                    }
                    $this->form->fill(array_merge($this->form->getState(), [
                        'content_override' => $extracted,
                    ]));
                    Notification::make()
                        ->title('Contenu rechargé')
                        ->body('Le HTML d\'origine a été réinjecté dans l\'éditeur.')
                        ->success()
                        ->send();
                }),
            Actions\DeleteAction::make(),
        ];
    }
}
