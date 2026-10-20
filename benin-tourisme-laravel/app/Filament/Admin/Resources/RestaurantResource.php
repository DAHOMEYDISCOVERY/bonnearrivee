<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\RestaurantResource\Pages;
use App\Models\Restaurant;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RestaurantResource extends Resource
{
    protected static ?string $model = Restaurant::class;
    protected static ?string $navigationIcon = 'heroicon-o-cake';
    protected static ?string $modelLabel = 'Restaurant';
    protected static ?string $pluralModelLabel = 'Catalogue Wa Dùnù';
    protected static ?string $navigationGroup = 'Wa Dùnù (restaurants)';
    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Identité')->schema([
                Forms\Components\TextInput::make('nom')->required()->maxLength(120),
                Forms\Components\TextInput::make('sous_titre')->maxLength(200)
                    ->helperText('Une phrase courte, ex : « Rooftop suspendu entre mer et ciel »'),
                Forms\Components\Select::make('categorie')
                    ->options(Restaurant::CATEGORIES)->required()->default('maquis'),
                Forms\Components\Toggle::make('coup_de_coeur')
                    ->label('Coup de cœur Dahomey Discovery'),
                Forms\Components\Toggle::make('published')->default(true),
            ])->columns(2),

            Forms\Components\Section::make('Localisation')->schema([
                Forms\Components\TextInput::make('ville')->default('Cotonou')->required(),
                Forms\Components\TextInput::make('quartier'),
                Forms\Components\Textarea::make('adresse')->rows(2)->columnSpanFull(),
                Forms\Components\TextInput::make('gps_lat')->numeric()->step(0.0000001),
                Forms\Components\TextInput::make('gps_lng')->numeric()->step(0.0000001),
            ])->columns(2),

            Forms\Components\Section::make('Infos pratiques')->schema([
                Forms\Components\TextInput::make('telephone')->tel(),
                Forms\Components\TextInput::make('horaires')->maxLength(200),
                Forms\Components\TextInput::make('budget_min')->numeric()->suffix('FCFA'),
                Forms\Components\TextInput::make('budget_max')->numeric()->suffix('FCFA'),
                Forms\Components\Textarea::make('specialite')->rows(2)->columnSpanFull(),
            ])->columns(2),

            Forms\Components\Section::make('Contenu éditorial')->schema([
                Forms\Components\Repeater::make('pourquoi_y_aller')
                    ->label('Pourquoi y aller ?')
                    ->schema([Forms\Components\TextInput::make('point')->required()])
                    ->simple(Forms\Components\TextInput::make('point'))
                    ->addActionLabel('Ajouter une raison')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('conseil_dahomey')
                    ->label('Conseil Dahomey Discovery')->rows(4)->columnSpanFull(),
            ]),

            Forms\Components\Section::make('Photos & tri')->schema([
                Forms\Components\FileUpload::make('photos')
                    ->image()->multiple()->reorderable()->maxFiles(6)
                    ->disk('public')->directory('restaurants')->columnSpanFull(),
                Forms\Components\TextInput::make('sort_order')->numeric()->default(0),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nom')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('ville')->sortable()->badge(),
                Tables\Columns\TextColumn::make('categorie')
                    ->formatStateUsing(fn ($state) => Restaurant::CATEGORIES[$state] ?? $state)
                    ->badge(),
                Tables\Columns\IconColumn::make('coup_de_coeur')->boolean(),
                Tables\Columns\IconColumn::make('published')->boolean(),
                Tables\Columns\TextColumn::make('sort_order')->sortable(),
            ])
            ->defaultSort('sort_order')
            ->filters([
                Tables\Filters\SelectFilter::make('ville'),
                Tables\Filters\SelectFilter::make('categorie')->options(Restaurant::CATEGORIES),
                Tables\Filters\TernaryFilter::make('coup_de_coeur'),
                Tables\Filters\TernaryFilter::make('published'),
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
            'index'  => Pages\ListRestaurants::route('/'),
            'create' => Pages\CreateRestaurant::route('/create'),
            'edit'   => Pages\EditRestaurant::route('/{record}/edit'),
        ];
    }
}
