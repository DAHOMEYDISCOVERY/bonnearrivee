<?php

namespace App\Filament\Admin\Resources\SiteFragmentResource\Pages;

use App\Filament\Admin\Resources\SiteFragmentResource;
use App\Models\Page;
use App\Models\SiteFragment;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditSiteFragment extends EditRecord
{
    protected static string $resource = SiteFragmentResource::class;

    /**
     * Nettoie AUTOMATIQUEMENT le HTML des directives Blade residuelles avant
     * save. Un copier/coller depuis le partial d'origine aurait souvent du
     * @if/@foreach/{{ $x }} dedans — on les retire pour que l'affichage du
     * fragment par SiteFragment::html() ne plante jamais et que header/footer
     * ne cassent jamais toute la page.
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $html = (string) ($data['content'] ?? '');
        $data['content'] = Page::stripBladeArtefacts($html);
        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('restoreFromPartial')
                ->label('Restaurer depuis le partial d\'origine')
                ->icon('heroicon-o-arrow-uturn-left')
                ->color('gray')
                ->requiresConfirmation()
                ->modalHeading('Recharger le HTML depuis le partial d\'origine ?')
                ->modalDescription("Le contenu actuellement rédigé sera remplacé par le HTML du partial Blade sur disque. L'enregistrement en base n'est pas sauvegardé tant que vous ne cliquez pas sur « Save changes ».")
                ->action(function () {
                    $slug = $this->record->slug;
                    $path = resource_path("views/partials/{$slug}.blade.php");
                    if (!is_file($path)) {
                        Notification::make()
                            ->title('Partial introuvable')
                            ->body("Aucun fichier views/partials/{$slug}.blade.php sur disque.")
                            ->warning()
                            ->send();
                        return;
                    }
                    $html = (string) file_get_contents($path);
                    $html = preg_replace('/\{\{--.*?--\}\}/s', '', $html) ?? $html;
                    $html = str_replace(['@verbatim', '@endverbatim'], '', $html);
                    $html = trim($html);
                    $this->form->fill(array_merge($this->form->getState(), [
                        'content' => $html,
                    ]));
                    Notification::make()
                        ->title('HTML rechargé')
                        ->body('Le contenu du partial a été réinjecté dans l\'éditeur.')
                        ->success()
                        ->send();
                }),
        ];
    }
}
