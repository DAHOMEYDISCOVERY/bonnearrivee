<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Espace publicitaire « partenaires » de la page Liens utiles.
 *
 * Chaque ligne est une campagne affichée dans le carrousel. Les campagnes
 * désactivées ou hors de leur fenêtre de diffusion ne sont jamais servies
 * (voir AdCampaign::scopeVisible).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ad_campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('advertiser')->nullable();
            $table->string('image_path');
            $table->string('image_alt')->nullable();
            $table->text('description')->nullable();
            $table->string('button_label')->nullable();
            $table->string('url')->nullable();
            $table->date('starts_at')->nullable();
            $table->date('ends_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['is_active', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ad_campaigns');
    }
};
