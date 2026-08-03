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
        Schema::create('product_variation_attributes', function (Blueprint $table) {
            $table->id();

            // VARIATION RELATION
            $table->foreignId('variation_id')
                ->constrained('product_variations')
                ->onDelete('cascade');

            // ATTRIBUTE VALUE RELATION
            $table->foreignId('attribute_value_id')
                ->constrained('attribute_values')
                ->onDelete('cascade');

            // Prevent duplicate combinations
            $table->unique(['variation_id', 'attribute_value_id'], 'pv_attrs_unique');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variation_attributes');
    }
};
