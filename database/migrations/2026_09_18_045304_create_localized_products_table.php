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
        Schema::create('localized_products', function (Blueprint $table) {
            $table->id();

            $table->string('slug')->unique();

            $table->json('name_translations');
            $table->json('description_translations');

            $table->decimal('price', 10, 2);

            $table->string('category')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('localized_products');
    }
};