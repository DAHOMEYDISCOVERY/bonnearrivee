<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Réglages de contenu éditables depuis le back-office (en-tête, pied de page).
 *
 * Une ligne = une clé (`layout.header`, `layout.footer`) et sa valeur JSON.
 * Migration additive : aucune table existante n'est modifiée ; l'ancienne
 * table `site_fragments` est conservée telle quelle.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key', 100)->unique();
            $table->longText('value');
            $table->timestamps();
        });

        // Reprise automatique de ce qui avait été modifié dans les anciens
        // blocs HTML (header / footer), pour que rien ne soit perdu au passage.
        // Un échec ici ne doit jamais bloquer la migration : les valeurs du
        // code s'affichent alors, et l'import reste possible depuis l'admin.
        try {
            \App\Support\LegacyFragmentImporter::import();
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Import des anciens blocs header/footer impossible', ['error' => $e->getMessage()]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
