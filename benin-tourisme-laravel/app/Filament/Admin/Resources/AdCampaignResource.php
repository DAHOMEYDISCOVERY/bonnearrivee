<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\AdCampaignResource\Pages;
use App\Models\AdCampaign;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

/**
 * Gestion des campagnes publicitaires partenaires (carrousel Liens utiles).
 *
 * Espace commercial, distinct du répertoire officiel des agences agréées :
 * une campagne ne vaut pas agrément.
 */
class AdCampaignResource extends Resource
{
    protected static ?string $model = AdCampaign::class;
    protected static ?string $navigationIcon = 'heroicon-o-megaphone';
    protected static ?string $modelLabel = 'Campagne partenaire';
    protected static ?string $pluralModelLabel = 'Campagnes partenaires';
    protected static ?string $navigationGroup = 'Espace publicitaire';
    protected static ?int $navigationSort = 20;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Annonce')->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Titre de la campagne')->required()->maxLength(120),
                Forms\Components\TextInput::make('advertiser')
                    ->label('Annonceur / partenaire')->maxLength(120),
                Forms\Components\Textarea::make('description')
                    ->label('Texte descriptif')->rows(3)->maxLength(400)
                    ->helperText('Court : une ou deux phrases suffisent.')->columnSpanFull(),
            ])->columns(2),

            Forms\Components\Section::make('Visuel')->schema([
                Forms\Components\FileUpload::make('image_path')
                    ->label('Image / visuel publicitaire')
                    ->image()->imageEditor()
                    ->disk('public')->directory('campagnes')
                    ->required()
                    ->helperText('Format paysage large recommandé, au moins 1600 × 900 px.')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('image_alt')
                    ->label("Texte alternatif de l'image")
                    ->maxLength(160)
                    ->helperText('Décrit l’image pour l’accessibilité et le référencement.')
                    ->columnSpanFull(),
            ]),

            Forms\Components\Section::make('Action')->schema([
                Forms\Components\TextInput::make('button_label')
                    ->label('Libellé du bouton')->maxLength(40)
                    ->placeholder('Découvrir l’offre'),
                Forms\Components\TextInput::make('url')
                    ->label('URL de destination')->url()->maxLength(300)
                    ->placeholder('https://…'),
            ])->columns(2),

            Forms\Components\Section::make('Diffusion')->schema([
                Forms\Components\DatePicker::make('starts_at')
                    ->label('Début de diffusion')->native(false),
                Forms\Components\DatePicker::make('ends_at')
                    ->label('Fin de diffusion')->native(false)
                    ->after('starts_at')
                    ->helperText('Laisser vide pour une diffusion sans date de fin.'),
                Forms\Components\Toggle::make('is_active')
                    ->label('Active')->default(true),
                Forms\Components\TextInput::make('sort_order')
                    ->label('Ordre d’affichage')->numeric()->default(0),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')->label('Visuel')->disk('public'),
                Tables\Columns\TextColumn::make('title')->label('Titre')->searchable()->sortable()->wrap(),
                Tables\Columns\TextColumn::make('advertiser')->label('Annonceur')->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('starts_at')->label('Début')->date('d/m/Y')->placeholder('—'),
                Tables\Columns\TextColumn::make('ends_at')->label('Fin')->date('d/m/Y')->placeholder('—'),
                Tables\Columns\IconColumn::make('is_active')->label('Active')->boolean(),
                Tables\Columns\TextColumn::make('sort_order')->label('Ordre')->sortable(),
            ])
            ->defaultSort('sort_order')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')->label('Active'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index'  => Pages\ListAdCampaigns::route('/'),
            'create' => Pages\CreateAdCampaign::route('/create'),
            'edit'   => Pages\EditAdCampaign::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool { return self::isAdmin(); }
    public static function canCreate(): bool  { return self::isAdmin(); }
    public static function canEdit($record): bool   { return self::isAdmin(); }
    public static function canDelete($record): bool { return self::isAdmin(); }

    private static function isAdmin(): bool
    {
        $u = auth()->user();
        return $u instanceof User && $u->isAdmin();
    }
}
