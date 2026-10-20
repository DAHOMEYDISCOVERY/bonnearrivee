<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resto_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->nullable()->constrained('resto_orders')->nullOnDelete();
            $table->string('token', 64)->unique();
            $table->string('email');
            $table->timestamp('expires_at');
            $table->timestamp('last_seen_at')->nullable();
            $table->unsignedInteger('hits')->default(0);
            $table->timestamps();

            $table->index(['email', 'expires_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resto_access_tokens');
    }
};
