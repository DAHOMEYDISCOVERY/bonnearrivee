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

    /**
     * Au Save : use_override est pilote DETERMINISTE par le contenu de
     * l'editeur. Jamais d'etat "use_override=true + contenu vide" en DB,
     * qui provoquait des pages blanches (header + footer visibles mais
     * <main> vide) quand la textarea etait videe par un sync Alpine rate
     * ou par un stripBladeArtefacts trop agressif.
     *
     * Regle : l'interrupteur use_override decide de la publication (voir
     * l'etape 3 ci-dessous). Un contenu vide (apres strip des commentaires
     * HTML et des balises vides) n'est jamais publie : use_override=false et
     * content_override=null, pour ne pas garder un fantome en DB.
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $html = (string) ($data['content_override'] ?? '');
        // 1) Nettoyage AUTOMATIQUE des directives Blade residuelles. Un copier
        //    /coller depuis une vue d'origine aurait du @if/@foreach/{{ $x }}
        //    dedans — on les retire pour que la compilation Blade ne plante
        //    jamais et que la page ne devienne jamais blanche.
        $html = Page::stripBladeArtefacts($html);
        // L'éditeur encode « => » en « =&gt; » dans les réglages des blocs :
        // on les rétablit avant d'enregistrer.
        $html = \App\Support\OverrideHtml::repairBoundAttributes($html);

        // 2) On considere un HTML "vide" s'il ne reste rien de visible apres
        //    suppression des commentaires HTML et des espaces.
        $cleaned = preg_replace('/<!--.*?-->/s', '', $html) ?? $html;
        $cleaned = trim(strip_tags($cleaned));
        // On autorise un HTML qui n'a que des images / medias (strip_tags
        // le viderait a tort).
        $hasMedia = (bool) preg_match('/<(img|video|iframe|svg|picture|source|audio)\b/i', $html);

        $hasContent = $cleaned !== '' || $hasMedia;

        // 3) L'interrupteur « Utiliser le contenu personnalisé » decide seul de
        //    la publication. Auparavant, tout contenu non vide activait
        //    use_override : comme l'editeur est pre-rempli avec le source de la
        //    vue, corriger un simple titre figeait la page en HTML brut.
        //    - interrupteur coche + contenu  -> publie
        //    - interrupteur coche sans contenu -> refuse (pas de page blanche)
        //    - interrupteur decoche          -> la vue d'origine reste servie ;
        //      le brouillon est conserve, sauf s'il n'est qu'une copie de la vue.
        $wantsOverride = (bool) ($data['use_override'] ?? false);

        // Garde-fou : une page construite par du code (listes de compagnies,
        // agenda…) ne peut pas être remplacée par une copie figée de son HTML.
        if ($wantsOverride && $hasContent && Page::isDynamicView((string) ($data['view'] ?? $this->record->view))) {
            Notification::make()
                ->title('Modification non publiée')
                ->body('Cette page est construite automatiquement (listes, calculs) : son contenu ne peut pas être remplacé en bloc ici, il s’afficherait en code. Modifiez ses textes et ses images dans « Textes et images ». Le site affiche toujours la version précédente.')
                ->danger()
                ->persistent()
                ->send();
            $this->halt();
        }

        if (! $hasContent) {
            $data['use_override'] = false;
            $data['content_override'] = null;
        } elseif ($wantsOverride) {
            $data['use_override'] = true;
            $data['content_override'] = $html;
        } else {
            $data['use_override'] = false;
            $data['content_override'] = \App\Support\OverrideHtml::isPristine($html, (string) ($data['view'] ?? $this->record->view))
                ? null
                : $html;
        }
        return $data;
    }

    /** État de la page avant l'enregistrement, pour l'annuler si besoin. */
    private array $avantEnregistrement = [];

    protected function beforeSave(): void
    {
        $this->avantEnregistrement = $this->record->only(['title', 'description', 'active_menu', 'head_profile', 'standalone', 'use_override', 'wrap_content', 'content_override']);
    }

    /**
     * Garde-fou : la page est affichée comme un visiteur la verrait. Si elle
     * ne s'affiche plus, l'enregistrement est annulé et l'éditeur voit
     * pourquoi ; le site garde la version d'avant.
     */
    protected function afterSave(): void
    {
        $problem = \App\Support\PublishGuard::problem((string) $this->record->path);
        if ($problem === null) {
            return;
        }

        $this->record->forceFill($this->avantEnregistrement)->save();

        Notification::make()
            ->title('Modification non publiée')
            ->body($problem.' Le site affiche toujours la version précédente. Corrigez le contenu, ou cliquez sur « Recharger depuis la vue d\'origine ».')
            ->danger()
            ->persistent()
            ->send();

        $this->halt();
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
