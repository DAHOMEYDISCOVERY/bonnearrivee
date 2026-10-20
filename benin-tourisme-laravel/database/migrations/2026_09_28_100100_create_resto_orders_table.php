<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resto_orders', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('nom_client')->nullable();
            $table->unsignedInteger('amount_xof');
            $table->string('kkiapay_transaction_id')->nullable()->unique();
            $table->enum('status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('access_expires_at')->nullable();
            $table->json('kkiapay_payload')->nullable();
            $table->timestamps();

            $table->index('email');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resto_orders');
    }
};
