<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Crée le premier compte administrateur d'une installation neuve.
 *
 * Audit BUG-18 : ce seeder tournait à chaque déploiement avec un mot de passe
 * écrit en dur (« password ») et recréait le compte s'il avait été supprimé ou
 * renommé. Désormais :
 *   - il ne fait rien si un administrateur existe déjà ;
 *   - en production, il exige ADMIN_INITIAL_PASSWORD (12 caractères au moins)
 *     et refuse de créer un compte sans ;
 *   - il ne fait plus partie de tools/deploy.sh : on le lance à la main, une
 *     seule fois, à l'installation.
 */
final class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        if (User::query()->where('role', User::ROLE_ADMIN)->exists()) {
            $this->command?->info('Un administrateur existe déjà : aucun compte créé.');

            return;
        }

        $email = (string) env('ADMIN_INITIAL_EMAIL', 'admin@bonnearrivee.bj');
        $password = (string) env('ADMIN_INITIAL_PASSWORD', '');

        if ($password === '' && ! app()->environment('local', 'testing')) {
            $this->command?->error('ADMIN_INITIAL_PASSWORD manquant : aucun compte créé (obligatoire hors poste local).');

            return;
        }
        if ($password !== '' && mb_strlen($password) < 12) {
            $this->command?->error('ADMIN_INITIAL_PASSWORD trop court (12 caractères au moins) : aucun compte créé.');

            return;
        }

        User::query()->firstOrCreate(
            ['email' => $email],
            [
                'name' => 'Administrateur',
                // Sur un poste local sans mot de passe fourni : « password », à changer.
                'password' => Hash::make($password !== '' ? $password : 'password'),
                'role' => User::ROLE_ADMIN,
            ],
        );

        $this->command?->info("Compte administrateur créé : {$email}.");
    }
}
