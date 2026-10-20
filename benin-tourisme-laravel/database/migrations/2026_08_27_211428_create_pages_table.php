<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * Registre des pages du site en base.
 *
 * Le PageRepository lira ces enregistrements plutôt que `resources/pages.php`
 * quand la table est peuplée, ce qui ouvre l'édition par le back-office
 * Filament. Le contenu HTML reste dans les vues Blade sur disque ; seules les
 * métadonnées passent en base à ce stade.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table): void {
            $table->id();
            // Le chemin URL sert de clé métier — indexé pour la lookup à chaque
            // requête, unique pour empêcher deux pages au même endroit.
            $table->string('path')->unique();
            $table->string('view');
            $table->string('title');
            $table->text('description')->nullable();
            // Rubrique de menu à marquer active. Le miroir mélange des ID
            // numériques (« 682 ») et des noms (« accueil ») — on garde en string.
            $table->string('active_menu')->nullable();
            // Profil de <head> : 'standard' (79 pages), 'listing' (5), 'offre' (3).
            $table->string('head_profile')->default('standard');
            // Pages écrites à la main, hors gabarit Woody (credits-photos,
            // fiche-de-relecture) : on saute alors le layout `app`.
            $table->boolean('standalone')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
