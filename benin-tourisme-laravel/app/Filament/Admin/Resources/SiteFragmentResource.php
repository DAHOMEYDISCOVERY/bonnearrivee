<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\SiteFragmentResource\Pages;
use App\Models\SiteFragment;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

/**
 * Edition des morceaux de template partages par toutes les pages : header
 * (navigation + loupe) et footer (coordonnees + liens + carte). Chaque row
 * = un slug + le HTML qui sera rendu a la place du partial en dur.
 *
 * Zone a utiliser avec precaution — un header casse rend tout le site
 * inutilisable. L'ecran expose un bouton 'Restaurer depuis le partial' pour
 * recharger le HTML d'origine en cas de pepin.
 */
final class SiteFragmentResource extends Resource
{
    protected static ?string $model = SiteFragment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';

    protected static ?string $navigationLabel = 'Blocs du site';

    protected static ?string $modelLabel = 'bloc';

    protected static ?string $pluralModelLabel = 'blocs';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Identite du bloc')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('slug')
                        ->label('Slug')
                        ->required()
                        ->maxLength(60)
                        ->unique(ignoreRecord: true)
                        ->helperText("Identifiant technique : 'header', 'footer', etc. Doit correspondre a un partial du theme."),
                    Forms\Components\TextInput::make('label')
                        ->label('Libelle')
                        ->required()
                        ->maxLength(120),
                ]),

            Forms\Components\Section::make('Contenu HTML')
                ->schema([
                    Forms\Components\Textarea::make('content')
                        ->label('HTML du bloc')
                        ->helperText("HTML brut tel qu'injecte dans le site. Les classes CSS, structures, SVG et "
                            ."directives Blade (ex. {{ \$activeMenu }}) sont preserves. ATTENTION : un bloc casse "
                            ."peut rendre tout le site inutilisable — teste apres chaque modification.")
                        ->required()
                        ->rows(32)
                        ->autosize(false)
                        ->extraInputAttributes([
                            'style' => 'font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace; '
                                     . 'font-size: 13px; line-height: 1.55; white-space: pre; '
                                     . 'overflow-x: auto; tab-size: 2;',
                            'spellcheck' => 'false',
                        ])
                        ->columnSpanFull(),
                    Forms\Components\Textarea::make('notes')
                        ->label('Notes internes')
                        ->rows(2)
                        ->maxLength(500)
                        ->helperText("Visible seulement dans l'admin. Pour tracer les modifications ou documenter.")
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->badge()
                    ->color('gray')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('label')
                    ->label('Libelle')
                    ->searchable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Modifie')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('slug')
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSiteFragments::route('/'),
            'edit' => Pages\EditSiteFragment::route('/{record}/edit'),
        ];
    }

    /** Seuls les admins peuvent toucher aux blocs — trop critiques pour les editeurs. */
    public static function canViewAny(): bool { return self::isAdmin(); }
    public static function canEdit($record): bool { return self::isAdmin(); }
    public static function canCreate(): bool { return self::isAdmin(); }
    public static function canDelete($record): bool { return self::isAdmin(); }

    private static function isAdmin(): bool
    {
        $u = auth()->user();
        return $u instanceof User && $u->isAdmin();
    }
}
