<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

/**
 * Affiche les utilisateurs admin + option pour promouvoir un email en admin.
 */
final class WhoAmI extends Command
{
    protected $signature = 'whoami {email? : Promouvoir ce user en admin}';

    protected $description = 'Liste les users admin, ou promeut un email en admin.';

    public function handle(): int
    {
        $email = $this->argument('email');

        if ($email) {
            $u = User::where('email', $email)->first();
            if (!$u) {
                $this->error("User {$email} introuvable.");
                return self::FAILURE;
            }
            $u->role = User::ROLE_ADMIN;
            $u->save();
            $this->info("User {$email} promu admin.");
        }

        $this->info("Users en base :");
        foreach (User::all() as $u) {
            $this->line("  {$u->email} -> role={$u->role} (admin=" . ($u->isAdmin() ? 'oui' : 'non') . ", editor=" . ($u->isEditor() ? 'oui' : 'non') . ")");
        }
        return self::SUCCESS;
    }
}
