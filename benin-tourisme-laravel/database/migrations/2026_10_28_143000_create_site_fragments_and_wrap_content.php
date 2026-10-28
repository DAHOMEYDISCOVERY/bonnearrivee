<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Deux changements :
 *   · Nouvelle table `site_fragments` : morceaux de template editables depuis
 *     le back-office (header, footer, bandeau cookies, etc.). Un fragment = un
 *     slug unique + le HTML a injecter dans le partial correspondant.
 *   · Colonne `wrap_content` sur `pages` : quand use_override=true, controle si
 *     le HTML personnalise est enveloppe dans le composant 'custom-text' du
 *     theme (true, legacy) ou injecte brut dans le layout (false, nouveau
 *     comportement par defaut).
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('site_fragments', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 60)->unique();
            $table->string('label', 120);
            $table->longText('content');
            $table->string('notes', 500)->nullable();
            $table->timestamps();
        });

        Schema::table('pages', function (Blueprint $table) {
            // false = HTML brut injecte dans le layout (defaut moderne).
            // true  = enveloppe custom-text + titre centre (comportement historique).
            $table->boolean('wrap_content')->default(false)->after('use_override');
        });
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn('wrap_content');
        });
        Schema::dropIfExists('site_fragments');
    }
};
