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
        Schema::create('about_companies', function (Blueprint $table) {
            $table->id();
            $table->string('banner_image')->nullable();
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->text('our_story')->nullable();
            $table->string('story_image')->nullable();
            $table->text('mission')->nullable();
            $table->text('vision')->nullable();
            $table->json('core_values')->nullable();
            $table->json('company_stats')->nullable();
            $table->json('why_choose')->nullable();
            $table->tinyInteger('site_type')->default(1);
            $table->foreignId('added_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('about_companies');
    }
};
