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
        Schema::create('settings_fields', function (Blueprint $table) {
            $table->id();
            $table->string('group');            // general, logo, sms
            $table->string('slug')->unique();   // sms_api_key
            $table->string('label');            // API Key
            $table->string('type');             // text, password, select, file
            $table->json('options')->nullable(); // ["twilio","ssl"]
            $table->integer('sort_order')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings_fields');
    }
};
