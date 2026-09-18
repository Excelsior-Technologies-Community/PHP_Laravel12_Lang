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
        Schema::create('language_histories', function (Blueprint $table) {
            $table->id();

            $table->string('locale', 10);

            $table->string('language_name');

            $table->string('ip_address')->nullable();

            $table->string('user_agent', 500)->nullable();

            $table->timestamps();

            $table->index('locale');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('language_histories');
    }
};