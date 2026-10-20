<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * Ajoute la possibilité d'éditer le contenu d'une page depuis Filament.
 *
 * Deux colonnes :
 *   · `content_override` : HTML brut qui remplace le corps de la vue Blade
 *     historique quand il est renseigné et activé. Nullable — laisser NULL
 *     signifie « garder la vue Blade d'origine ».
 *   · `use_override` : interrupteur ; permet d'éditer un brouillon puis de
 *     l'activer en un clic, ou de revenir à la vue Blade sans perdre le
 *     brouillon.
 *
 * Le contrôleur bascule sur la vue générique `pages.editable` uniquement quand
 * `use_override` est true ET que `content_override` n'est pas vide. Sinon on
 * garde le comportement historique.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table): void {
            $table->longText('content_override')->nullable()->after('standalone');
            $table->boolean('use_override')->default(false)->after('content_override');
        });
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table): void {
            $table->dropColumn(['content_override', 'use_override']);
        });
    }
};
