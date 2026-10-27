<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

/**
 * Ressource Filament pour gérer les comptes d'administration.
 *
 * Le mot de passe n'est demandé qu'à la création — laissé vide en édition,
 * il n'est pas touché. On l'affiche jamais dans la table.
 */
final class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Administrateurs';

    protected static ?string $modelLabel = 'administrateur';

    protected static ?string $pluralModelLabel = 'administrateurs';

    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Identité')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Nom')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('email')
                        ->label('Email')
                        ->email()
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),
                ]),

            Forms\Components\Section::make('Rôle et permissions')
                ->description("L'administrateur a accès à tout. L'éditeur ne peut modifier que les pages du site. "
                    ."Le lecteur peut seulement consulter.")
                ->schema([
                    Forms\Components\Select::make('role')
                        ->label('Rôle')
                        ->options(User::ROLES)
                        ->required()
                        ->default(User::ROLE_EDITOR)
                        ->native(false),
                ]),

            Forms\Components\Section::make('Mot de passe')
                ->description('Laisser vide en modification ne change pas le mot de passe existant.')
                ->schema([
                    Forms\Components\TextInput::make('password')
                        ->label('Nouveau mot de passe')
                        ->password()
                        ->revealable()
                        ->minLength(8)
                        ->required(fn (string $operation): bool => $operation === 'create')
                        ->dehydrated(fn (?string $state): bool => filled($state))
                        ->dehydrateStateUsing(fn (string $state): string => Hash::make($state)),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nom')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('role')
                    ->label('Rôle')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => User::ROLES[$state] ?? $state)
                    ->color(fn (string $state): string => match ($state) {
                        User::ROLE_ADMIN  => 'danger',
                        User::ROLE_EDITOR => 'success',
                        User::ROLE_VIEWER => 'gray',
                        default           => 'secondary',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créé')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Modifié')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('name')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function (User $record): void {
                        // On ne se supprime pas soi-même — sinon plus personne
                        // ne peut se connecter au panneau.
                        if (auth()->id() === $record->id) {
                            throw new \RuntimeException("Impossible de supprimer votre propre compte.");
                        }
                    }),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    /** Seuls les administrateurs peuvent gerer les utilisateurs. */
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
