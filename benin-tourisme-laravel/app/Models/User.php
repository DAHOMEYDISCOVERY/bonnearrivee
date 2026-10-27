<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /** Role nominatifs stockes en base (colonne `role`). */
    public const ROLE_ADMIN  = 'admin';
    public const ROLE_EDITOR = 'editor';
    public const ROLE_VIEWER = 'viewer';

    public const ROLES = [
        self::ROLE_ADMIN  => 'Administrateur (acces total)',
        self::ROLE_EDITOR => 'Editeur (pages du site uniquement)',
        self::ROLE_VIEWER => 'Lecteur (lecture seule)',
    ];

    /**
     * Autorise tout compte ayant un role connu a acceder au panel.
     * Un compte sans role ou avec un role inconnu est refuse (403).
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return in_array($this->role, array_keys(self::ROLES), true);
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isEditor(): bool
    {
        return $this->role === self::ROLE_EDITOR || $this->isAdmin();
    }

    public function isViewer(): bool
    {
        return $this->role === self::ROLE_VIEWER;
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
