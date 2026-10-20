<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('restaurants', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('slug')->unique();
            $table->string('sous_titre')->nullable();
            $table->string('categorie')->default('maquis');
            $table->string('ville')->default('Cotonou');
            $table->string('quartier')->nullable();
            $table->string('adresse')->nullable();
            $table->string('telephone')->nullable();
            $table->string('horaires')->nullable();
            $table->unsignedInteger('budget_min')->nullable();
            $table->unsignedInteger('budget_max')->nullable();
            $table->string('specialite')->nullable();
            $table->json('pourquoi_y_aller')->nullable();
            $table->text('conseil_dahomey')->nullable();
            $table->decimal('gps_lat', 10, 7)->nullable();
            $table->decimal('gps_lng', 10, 7)->nullable();
            $table->json('photos')->nullable();
            $table->boolean('coup_de_coeur')->default(false);
            $table->boolean('published')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['ville', 'categorie']);
            $table->index('published');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('restaurants');
    }
};
