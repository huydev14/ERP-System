<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('oauth_configs', function (Blueprint $table) {
            $table->id();
            $table->string('provider', 50)->unique();

            $table->text('client_id');
            $table->text('client_secret');
            $table->text('redirect_uri');

            $table->json('scopes')->nullable();
            $table->json('additional_config')->nullable();

            $table->boolean('is_active')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oauth_configs');
    }
};
