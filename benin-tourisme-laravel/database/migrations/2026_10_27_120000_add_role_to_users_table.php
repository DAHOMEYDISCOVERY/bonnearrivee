<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Introduit un systeme de roles sur la table users :
 *   - admin   : acces total au back-office (pages, restaurants, utilisateurs)
 *   - editor  : peut editer les pages du site (pas les autres ressources)
 *   - viewer  : lecture seule (reserve, non utilise pour l'instant)
 * Par defaut un nouveau compte est 'editor' pour eviter d'elargir
 * accidentellement les privileges.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 20)->default('editor')->after('email');
        });

        // Toutes les lignes existantes etaient creees avant les roles :
        // on les considere comme administrateurs.
        \DB::table('users')->update(['role' => 'admin']);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
