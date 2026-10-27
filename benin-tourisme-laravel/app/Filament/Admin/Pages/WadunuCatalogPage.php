<?php

namespace App\Filament\Admin\Pages;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Storage;

/**
 * Wa Dùnù — page admin de gestion du PDF du catalogue.
 *
 * Un seul fichier « courant » est stocké sur le disque privé `wadunu`,
 * sous `catalogue.pdf`. L'admin le remplace ici ; tous les abonnés
 * actifs voient la nouvelle version au prochain rafraîchissement du
 * viewer, sans qu'il y ait besoin de leur renvoyer un lien.
 */
class WadunuCatalogPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-arrow-up';
    protected static ?string $navigationLabel = 'Wa Dùnù · PDF';
    protected static ?string $navigationGroup = 'Wa Dùnù';
    protected static ?string $title = 'Catalogue Wa Dùnù — PDF courant';
    protected static ?int $navigationSort = 1;
    protected static string $view = 'filament.admin.pages.wadunu-catalog';

    /** Nom fixe du fichier servi. */
    public const FILENAME = 'catalogue.pdf';

    public ?array $data = [];

    /** Reserve aux administrateurs. */
    public static function canAccess(): bool
    {
        $u = \auth()->user();
        return $u instanceof \App\Models\User && $u->isAdmin();
    }

    public function mount(): void
    {
        $this->form->fill([]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('pdf')
                    ->label('Nouveau PDF du catalogue')
                    ->helperText('Fichier .pdf uniquement. Remplace l\'exemplaire courant. Tous les abonnés voient aussitôt la nouvelle version.')
                    ->acceptedFileTypes(['application/pdf'])
                    ->maxSize(200 * 1024) // 200 Mo
                    ->disk('wadunu')
                    ->directory('') // racine du disque wadunu
                    ->visibility('private')
                    ->preserveFilenames(false)
                    ->columnSpanFull(),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();
        $upload = $state['pdf'] ?? null;

        if (!$upload) {
            Notification::make()->title('Aucun fichier sélectionné')->warning()->send();
            return;
        }

        // FileUpload dépose le fichier avec un nom aléatoire dans wadunu/.
        // On le déplace sous le nom fixe catalogue.pdf.
        $stored = is_array($upload) ? reset($upload) : $upload;
        $disk = Storage::disk('wadunu');

        if (!$disk->exists($stored)) {
            Notification::make()->title('Erreur : fichier introuvable après upload')->danger()->send();
            return;
        }

        // Suppression de l'ancien PDF s'il existe
        if ($disk->exists(self::FILENAME)) {
            $disk->delete(self::FILENAME);
        }
        $disk->move($stored, self::FILENAME);

        // Reset du champ
        $this->form->fill([]);
        $this->dispatch('$refresh');

        Notification::make()
            ->title('Catalogue mis à jour')
            ->body('Le nouveau PDF est en ligne. Tous les abonnés le voient dès leur prochain accès.')
            ->success()
            ->send();
    }

    public function getViewData(): array
    {
        $disk = Storage::disk('wadunu');
        $exists = $disk->exists(self::FILENAME);
        return [
            'currentExists' => $exists,
            'currentSize'   => $exists ? $this->humanBytes($disk->size(self::FILENAME)) : null,
            'currentUpdatedAt' => $exists ? \Carbon\Carbon::createFromTimestamp($disk->lastModified(self::FILENAME))->format('d/m/Y à H\hi') : null,
        ];
    }

    private function humanBytes(int $bytes): string
    {
        $units = ['o', 'Ko', 'Mo', 'Go'];
        $i = 0;
        while ($bytes > 1024 && $i < count($units) - 1) { $bytes /= 1024; $i++; }
        return number_format($bytes, $i > 1 ? 1 : 0, ',', ' ') . ' ' . $units[$i];
    }
}
