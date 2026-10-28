<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PageResource\Pages;
use App\Models\Page;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

/**
 * Ressource Filament pour éditer les métadonnées des 89 pages du site.
 *
 * Le contenu HTML reste dans les vues Blade — cet écran ne touche qu'au titre,
 * à la meta-description, au profil de <head> et au menu actif. Une action
 * « Voir » ouvre la page rendue sur le site.
 */
final class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Pages du site';

    protected static ?string $modelLabel = 'page';

    protected static ?string $pluralModelLabel = 'pages';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Route et vue')
                ->description("Le chemin d'URL sert de clé — deux pages ne peuvent pas partager le même. "
                    ."La vue Blade rendue est référencée en notation pointée (« pages.accueil »).")
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('path')
                        ->label("Chemin d'URL")
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->prefix(url('/'))
                        ->placeholder('/exemple/page'),
                    Forms\Components\TextInput::make('view')
                        ->label('Vue Blade')
                        ->required()
                        ->placeholder('pages.accueil'),
                ]),

            Forms\Components\Section::make('Métadonnées')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->label('Titre HTML')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),
                    Forms\Components\Textarea::make('description')
                        ->label('Meta-description')
                        ->rows(3)
                        ->maxLength(500)
                        ->columnSpanFull(),
                    Forms\Components\TextInput::make('active_menu')
                        ->label('Menu actif')
                        ->helperText('Nom ou identifiant de la rubrique de menu à marquer active.'),
                ]),

            Forms\Components\Section::make('Rendu')
                ->columns(2)
                ->schema([
                    Forms\Components\Select::make('head_profile')
                        ->label('Profil de <head>')
                        ->options([
                            'standard' => 'Standard (79 pages)',
                            'listing' => 'Listing avec filtres et carte (5 pages)',
                            'offre' => 'Fiche établissement (3 pages)',
                        ])
                        ->required()
                        ->default('standard'),
                    Forms\Components\Toggle::make('standalone')
                        ->label('Hors gabarit Woody')
                        ->helperText('Page écrite à la main, sans le layout d\'application.')
                        ->default(false),
                ]),

            Forms\Components\Section::make('Contenu personnalisé')
                ->description("Réécrire le corps de la page depuis le back-office. Quand l'interrupteur "
                    ."ci-dessous est activé et que du contenu est rédigé, le site sert cette version "
                    ."à la place de la vue Blade d'origine — sans la perdre : il suffit de désactiver "
                    ."l'interrupteur pour retrouver le contenu historique.")
                ->collapsible()
                ->collapsed(fn ($record) => ! ($record && $record->use_override))
                ->schema([
                    Forms\Components\Toggle::make('use_override')
                        ->label('Utiliser le contenu personnalisé')
                        ->helperText('Décoché : le site sert la vue Blade historique. Coché : le site sert le contenu édité ci-dessous.')
                        ->default(false)
                        ->live(),
                    Forms\Components\Textarea::make('content_override')
                        ->label('Contenu HTML de la page')
                        ->helperText("HTML brut tel que rendu sur le site. Les classes CSS, structures (section, div), "
                            ."SVG et scripts sont préservés tels quels. Pré-rempli automatiquement avec le contenu "
                            ."actuel de la page à la première ouverture — à toi de le modifier.")
                        ->rows(28)
                        ->autosize(false)
                        ->extraInputAttributes([
                            'style' => 'font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace; '
                                     . 'font-size: 13px; line-height: 1.55; white-space: pre; '
                                     . 'overflow-x: auto; tab-size: 2;',
                            'spellcheck' => 'false',
                        ])
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('path')
                    ->label("URL")
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('title')
                    ->label('Titre')
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('head_profile')
                    ->label('Profil')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'standard' => 'gray',
                        'listing' => 'info',
                        'offre' => 'warning',
                        default => 'secondary',
                    }),
                Tables\Columns\TextColumn::make('active_menu')
                    ->label('Menu')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\IconColumn::make('standalone')
                    ->label('Autonome')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Modifiée')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('path')
            ->filters([
                Tables\Filters\SelectFilter::make('head_profile')
                    ->label('Profil')
                    ->options([
                        'standard' => 'Standard',
                        'listing' => 'Listing',
                        'offre' => 'Fiche établissement',
                    ]),
                Tables\Filters\TernaryFilter::make('standalone')
                    ->label('Hors gabarit'),
            ])
            ->actions([
                Tables\Actions\Action::make('voir')
                    ->label('Voir')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn (Page $record): string => url($record->path))
                    ->openUrlInNewTab(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }

    /**
     * Autorisation : admins et editeurs peuvent voir et editer les pages.
     * Seul un admin peut en creer ou en supprimer (sinon un editeur pourrait
     * casser le registre en ajoutant des rows parasites).
     */
    public static function canViewAny(): bool { return self::isEditor(); }
    public static function canEdit($record): bool { return self::isEditor(); }
    public static function canCreate(): bool { return self::isAdmin(); }
    public static function canDelete($record): bool { return self::isAdmin(); }

    private static function isAdmin(): bool
    {
        $u = auth()->user();
        return $u instanceof User && $u->isAdmin();
    }
    private static function isEditor(): bool
    {
        $u = auth()->user();
        return $u instanceof User && $u->isEditor();
    }
}
