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
        Schema::create('sliders', function (Blueprint $table) {
            $table->id();
            $table->string('photo')->nullable();
            $table->string('position')->nullable();
            $table->text('title')->nullable();
            $table->text('subtitle')->nullable();
            $table->text('offer')->nullable();
            $table->tinyInteger('published')->default(1);
            $table->string('link')->nullable();
            $table->string('mobile_banner')->nullable();
            $table->tinyInteger('type')->default(1);
            $table->tinyInteger('serial')->default(0);
            $table->string('video_link')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sliders');
    }
};
