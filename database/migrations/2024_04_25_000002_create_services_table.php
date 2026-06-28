<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('slug', 255)->unique();
            $table->string('short_description', 500)->nullable();
            $table->text('description')->nullable();
            $table->string('thumbnail', 500)->nullable();
            $table->string('image', 500)->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->tinyInteger('is_featured')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->unsignedBigInteger('added_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->text('meta_title')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_image', 500)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->tinyInteger('site_type')->default(1);
            $table->index('slug');
            $table->index('status');
            $table->index('is_featured');
            $table->index('sort_order');
            $table->index('category_id');
            $table->foreign('category_id')->references('id')->on('service_categories')->onDelete('set null');
            $table->foreign('added_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            $table->json('feature_list')->nullable();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
