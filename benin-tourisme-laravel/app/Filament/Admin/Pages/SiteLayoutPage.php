<?php

declare(strict_types=1);

namespace App\Filament\Admin\Pages;

use App\Models\SiteSetting;
use App\Models\User;
use App\Support\LegacyFragmentImporter;
use App\Support\SiteLayout;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

/**
 * « En-tête et pied de page » : contenu du menu et du footer, éditable sans
 * toucher au HTML.
 *
 * Le formulaire n'édite que du contenu (libellés, liens, images, coordonnées) ;
 * le balisage reste dans resources/views/partials/header|footer.blade.php.
 * Enregistrer publie immédiatement. « Revenir aux valeurs du code » efface la
 * version enregistrée : le site affiche alors config/site_layout.php.
 */
class SiteLayoutPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-bars-3';
    protected static ?string $navigationLabel = 'En-tête et pied de page';
    protected static ?string $title = 'En-tête et pied de page';
    protected static ?int $navigationSort = 1;
    protected static string $view = 'filament.admin.pages.site-layout';

    private const IMAGE_PREFIX = '/assets/img/';

    public ?array $data = [];

    public static function canAccess(): bool
    {
        $u = auth()->user();

        return $u instanceof User && $u->isEditor();
    }

    public function mount(): void
    {
        $this->fillFromSite();
    }

    private function fillFromSite(): void
    {
        $header = SiteLayout::header();
        foreach ($header['menu'] as &$item) {
            foreach ($item['blocks'] as &$block) {
                foreach ($block['items'] as &$entry) {
                    if (($block['type'] ?? '') === 'cards') {
                        $entry['image_file'] = self::toDiskPath((string) ($entry['image'] ?? ''));
                    }
                }
            }
        }
        unset($item, $block, $entry);

        $this->form->fill([
            'header' => $header,
            'footer' => SiteLayout::footer(),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Tabs::make('layout')->tabs([
                    Tabs\Tab::make('Pied de page')->icon('heroicon-o-rectangle-stack')->schema([
                        Section::make('Coordonnées')->columns(2)->schema([
                            TextInput::make('footer.company')->label('Nom affiché')->maxLength(120),
                            TextInput::make('footer.tagline')->label('Accroche sous le logo')->maxLength(160),
                            Textarea::make('footer.address')->label('Adresse')->rows(2)
                                ->helperText('Une ligne par ligne d\'adresse.'),
                            TextInput::make('footer.phone')->label('Téléphone')->maxLength(40)
                                ->helperText('Tel qu\'il doit s\'afficher, ex. +229 01 67 11 02 20. Le lien d\'appel est calculé automatiquement.')
                                ->regex('/^\+?[\d\s.()-]{6,}$/'),
                            TextInput::make('footer.email')->label('E-mail')->email()->maxLength(120),
                        ]),
                        Repeater::make('footer.socials')->label('Réseaux sociaux')
                            ->schema([
                                Select::make('icon')->label('Réseau')->required()->options([
                                    'wicon-000-facebook' => 'Facebook',
                                    'wicon-011-instagram' => 'Instagram',
                                ]),
                                TextInput::make('url')->label('Adresse de la page')->url()->required(),
                                TextInput::make('label')->label('Texte pour les lecteurs d\'écran')
                                    ->helperText('Ex. « Dahomey Discovery sur Facebook (nouvel onglet) »'),
                            ])->columns(3)->reorderable()->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['url'] ?? null),
                        Repeater::make('footer.buttons')->label('Boutons')
                            ->schema([
                                TextInput::make('label')->label('Texte du bouton')->required()->maxLength(60),
                                TextInput::make('url')->label('Lien')->required()
                                    ->helperText('Une page du site (/a-propos) ou une adresse complète (https://…)'),
                                TextInput::make('title')->label('Infobulle')->maxLength(120),
                            ])->columns(3)->reorderable()->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['label'] ?? null),
                    ]),
                    Tabs\Tab::make('Menu principal')->icon('heroicon-o-bars-3')->schema([
                        Repeater::make('header.menu')->label('Entrées du menu')
                            ->schema([
                                TextInput::make('label')->label('Libellé')->required()->maxLength(40),
                                TextInput::make('url')->label('Lien')->required(),
                                Hidden::make('post_id'),
                                Repeater::make('blocks')->label('Contenu du sous-menu')
                                    ->schema([
                                        Select::make('type')->label('Type de bloc')->required()->live()->options([
                                            'cards' => 'Cartes avec image',
                                            'links' => 'Liste de liens',
                                            'buttons' => 'Boutons',
                                        ]),
                                        Hidden::make('position'),
                                        Hidden::make('columns'),
                                        Hidden::make('post_id'),
                                        TextInput::make('title')->label('Titre de la liste')
                                            ->visible(fn (Get $get) => $get('type') === 'links'),
                                        TextInput::make('url')->label('Lien du titre')
                                            ->visible(fn (Get $get) => $get('type') === 'links'),
                                        Repeater::make('items')->label('Éléments')
                                            ->schema(self::itemSchema())
                                            ->columns(2)->reorderable()->collapsible()
                                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? null),
                                    ])
                                    ->reorderable()->collapsible()->collapsed()
                                    ->itemLabel(fn (array $state): ?string => match ($state['type'] ?? null) {
                                        'cards' => 'Cartes',
                                        'links' => 'Liens'.(! empty($state['title']) ? ' · '.$state['title'] : ''),
                                        'buttons' => 'Boutons',
                                        default => 'Bloc',
                                    }),
                            ])
                            ->reorderable()->collapsible()->collapsed()
                            ->itemLabel(fn (array $state): ?string => $state['label'] ?? null),
                    ]),
                ])->persistTabInQueryString(),
            ]);
    }

    /**
     * Champs d'un élément de sous-menu. Le type du bloc parent (cartes, liens,
     * boutons) décide des champs visibles ; un champ masqué n'est ni validé
     * ni exigé.
     *
     * @return array<int, \Filament\Forms\Components\Component>
     */
    private static function itemSchema(): array
    {
        $is = static fn (string $type) => static fn (Get $get): bool => $get('../../type') === $type;

        return [
            TextInput::make('title')->label('Titre')->required()->maxLength(80),
            TextInput::make('url')->label('Lien')->required()
                ->helperText('Une page du site (/explorez) ou une adresse complète (https://…)'),
            Hidden::make('post_id'),
            FileUpload::make('image_file')->label('Image')
                ->disk('site_images')->directory('uploads/menu')->visibility('public')
                ->image()->maxSize(8192)->required()
                ->helperText('JPG, PNG ou WebP, format paysage 2:1 conseillé (ex. 1200 × 600).')
                ->visible($is('cards')),
            TextInput::make('alt')->label("Description de l'image")->required()->maxLength(160)
                ->helperText("Lue par les lecteurs d'écran et les moteurs de recherche.")
                ->visible($is('cards')),
            Hidden::make('image'),
            Hidden::make('published'),
            Hidden::make('description'),
            Hidden::make('caption'),
            Select::make('style')->label('Style')
                ->options(['primary' => 'Plein', 'secondary' => 'Contour'])->default('primary')
                ->visible($is('buttons')),
        ];
    }

    public function save(): void
    {
        $state = $this->form->getState();

        $header = $state['header'] ?? ['menu' => []];
        foreach ($header['menu'] as &$item) {
            $item['blocks'] = array_values($item['blocks'] ?? []);
            foreach ($item['blocks'] as &$block) {
                $block['items'] = array_values($block['items'] ?? []);
                foreach ($block['items'] as &$entry) {
                    if (($block['type'] ?? '') === 'cards') {
                        $file = $entry['image_file'] ?? null;
                        $file = is_array($file) ? (reset($file) ?: null) : $file;
                        if (is_string($file) && $file !== '') {
                            $newSrc = self::IMAGE_PREFIX.ltrim($file, '/');
                            if ($newSrc !== ($entry['image'] ?? null)) {
                                // Nouvelle image : les textes cachés suivent la description.
                                $entry['published'] = now()->format('Y-m-d H:i:s');
                            }
                            $entry['image'] = $newSrc;
                        }
                        $entry['description'] = ($entry['description'] ?? '') !== '' ? $entry['description'] : ($entry['alt'] ?? '');
                        $entry['caption'] = ($entry['caption'] ?? '') !== '' ? $entry['caption'] : ($entry['alt'] ?? '');
                        unset($entry['image_file']);
                    }
                    // Le libellé ARIA est calculé depuis le titre (SiteLayout::aria).
                    unset($entry['aria']);
                }
            }
        }
        unset($item, $block, $entry);
        $header['menu'] = array_values($header['menu']);

        $footer = $state['footer'] ?? [];
        $footer['socials'] = array_values($footer['socials'] ?? []);
        $footer['buttons'] = array_values($footer['buttons'] ?? []);

        // Garde-fou : publiés seulement si les pages s'affichent encore.
        $avantHeader = SiteSetting::read(SiteLayout::HEADER_KEY);
        $avantFooter = SiteSetting::read(SiteLayout::FOOTER_KEY);
        $problem = \App\Support\PublishGuard::attempt(
            function () use ($header, $footer): void {
                SiteSetting::write(SiteLayout::HEADER_KEY, $header);
                SiteSetting::write(SiteLayout::FOOTER_KEY, $footer);
            },
            function () use ($avantHeader, $avantFooter): void {
                is_array($avantHeader) ? SiteSetting::write(SiteLayout::HEADER_KEY, $avantHeader) : SiteSetting::forgetKey(SiteLayout::HEADER_KEY);
                is_array($avantFooter) ? SiteSetting::write(SiteLayout::FOOTER_KEY, $avantFooter) : SiteSetting::forgetKey(SiteLayout::FOOTER_KEY);
            },
            ['/', '/quand-partir'],
        );
        if ($problem !== null) {
            Notification::make()
                ->title('Modifications non publiées')
                ->body($problem.' Le site affiche toujours l’en-tête et le pied de page précédents.')
                ->danger()
                ->persistent()
                ->send();

            return;
        }

        Notification::make()
            ->title('En-tête et pied de page publiés')
            ->body('Les modifications sont visibles sur le site dès maintenant.')
            ->success()->send();

        $this->fillFromSite();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('reset')
                ->label('Revenir aux valeurs du code')
                ->icon('heroicon-o-arrow-uturn-left')
                ->color('gray')
                ->requiresConfirmation()
                ->modalDescription('Les modifications enregistrées ici seront effacées : le site affichera de nouveau le contenu défini dans le code (config/site_layout.php).')
                ->action(function (): void {
                    SiteSetting::forgetKey(SiteLayout::HEADER_KEY);
                    SiteSetting::forgetKey(SiteLayout::FOOTER_KEY);
                    $this->fillFromSite();
                    Notification::make()->title('Valeurs du code rétablies')->success()->send();
                }),
            Action::make('importLegacy')
                ->label('Reprendre l\'ancienne version')
                ->icon('heroicon-o-archive-box-arrow-down')
                ->color('gray')
                ->requiresConfirmation()
                ->modalDescription('Recharge le contenu depuis les anciens « Blocs du site » (HTML). Utile une seule fois, après la mise à jour.')
                ->action(function (): void {
                    $r = LegacyFragmentImporter::import(overwrite: true);
                    $this->fillFromSite();
                    Notification::make()
                        ->title($r['header'] || $r['footer'] ? 'Ancienne version reprise' : 'Rien à reprendre')
                        ->body($r['header'] || $r['footer']
                            ? 'Contenu repris : '.implode(', ', array_keys(array_filter($r))).'.'
                            : 'Les anciens blocs sont identiques aux valeurs du code, ou absents.')
                        ->success()->send();
                }),
        ];
    }

    private static function toDiskPath(string $src): ?string
    {
        return str_starts_with($src, self::IMAGE_PREFIX) ? substr($src, strlen(self::IMAGE_PREFIX)) : null;
    }
}
