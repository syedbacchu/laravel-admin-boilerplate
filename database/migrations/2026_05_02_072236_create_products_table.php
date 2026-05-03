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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('tagline')->nullable();
            $table->string('image')->nullable();
            $table->json('gallery')->nullable();
            $table->string('video_img',20)->nullable();
            $table->decimal('price', 19, 2)->nullable();
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->json('attributes')->nullable();
            $table->json('features')->nullable();
            $table->string('short_description')->nullable();
            $table->string('description')->nullable();
            $table->text('usage_instructions')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->integer('stock')->nullable();
            $table->integer('sold')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('video_link',20)->nullable();
            $table->decimal('discount',19,2)->nullable();
            $table->string('discount_type',10)->nullable();
            $table->decimal('tax',19,2)->default(5);
            $table->string('tax_type')->default('percent');
            $table->json('quantity_discounts')->nullable();
            $table->tinyInteger('is_featured')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
