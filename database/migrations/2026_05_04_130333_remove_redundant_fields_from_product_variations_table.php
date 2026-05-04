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
        Schema::table('product_variations', function (Blueprint $table) {
            // Remove redundant fields - we'll get data from relationships
            $table->dropColumn('name');
            $table->dropColumn('attributes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_variations', function (Blueprint $table) {
            // Add them back if rollback needed
            $table->string('name')->nullable();
            $table->json('attributes')->nullable();
        });
    }
};
