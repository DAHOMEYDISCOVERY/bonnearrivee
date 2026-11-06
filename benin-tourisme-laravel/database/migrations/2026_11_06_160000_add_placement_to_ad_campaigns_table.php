<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Emplacement d'affichage d'une campagne :
 *  - 'agences'    : carrousel de la page « Liens utiles » (agences agréées) ;
 *  - 'compagnies' : bannière latérale de la page « Comment venir » (compagnies).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ad_campaigns', function (Blueprint $table) {
            $table->string('placement')->default('agences')->after('advertiser');
            $table->index(['placement', 'is_active', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::table('ad_campaigns', function (Blueprint $table) {
            $table->dropIndex(['placement', 'is_active', 'sort_order']);
            $table->dropColumn('placement');
        });
    }
};
