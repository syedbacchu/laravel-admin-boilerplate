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
        Schema::create('comparism_areas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('compare_id');
            $table->text('left_side');
            $table->text('right_side');
            $table->integer('sort_order')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comparism_areas');
    }
};
