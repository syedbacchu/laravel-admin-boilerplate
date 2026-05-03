<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variations', function (Blueprint $table) {

            $table->id();

            // PRODUCT RELATION
            $table->foreignId('product_id')
                ->constrained('products')
                ->onDelete('cascade');

            // ATTRIBUTE (optional single attribute support)
            $table->foreignId('attribute_value_id')
                ->nullable()
                ->constrained('attribute_values')
                ->nullOnDelete();

            // VARIANT INFO
            $table->string('name')->nullable(); 
            // Example: "Red - XL", "Blue - M"

            $table->string('sku')->unique();

            $table->decimal('price', 19, 2)->nullable();

            $table->integer('stock')->default(0);

            // JSON for multi attributes (BEST PRACTICE)
            $table->json('attributes')->nullable();
            // Example:
            // { "color": "Red", "size": "XL" }

            $table->tinyInteger('status')->default(1);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variations');
    }
};