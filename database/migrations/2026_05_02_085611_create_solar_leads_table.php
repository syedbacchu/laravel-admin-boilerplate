<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('solar_leads', function (Blueprint $table) {
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | COMMON TYPE (IMPORTANT)
            |--------------------------------------------------------------------------
            | customer = normal form
            | industrial = factory / load form
            */
            $table->string('type'); 

            /*
            |--------------------------------------------------------------------------
            | 1. CUSTOMER BASIC INFO
            |--------------------------------------------------------------------------
            */
            $table->string('full_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('email')->nullable();
            $table->string('nid_or_business_id')->nullable();

            /*
            |--------------------------------------------------------------------------
            | 2. CUSTOMER TYPE
            |--------------------------------------------------------------------------
            */
            $table->string('customer_type')->nullable(); // residential, commercial...

            /*
            |--------------------------------------------------------------------------
            | 3. LOCATION
            |--------------------------------------------------------------------------
            */
            $table->text('address')->nullable();
            $table->string('district')->nullable();
            $table->string('google_map')->nullable();
            $table->string('installation_site_type')->nullable();
            $table->string('installation_site_other')->nullable();

            /*
            |--------------------------------------------------------------------------
            | 4. ELECTRICITY USAGE
            |--------------------------------------------------------------------------
            */
            $table->string('electricity_source')->nullable();
            $table->decimal('monthly_bill', 10, 2)->nullable();
            $table->string('meter_type')->nullable();
            $table->string('daytime_usage')->nullable();

            /*
            |--------------------------------------------------------------------------
            | 5. SYSTEM REQUIREMENT
            |--------------------------------------------------------------------------
            */
            $table->string('system_type')->nullable();
            $table->decimal('system_size_kw', 10, 2)->nullable();
            $table->string('main_purpose')->nullable();
            $table->string('purpose_other')->nullable();

            /*
            |--------------------------------------------------------------------------
            | 6. PROPERTY
            |--------------------------------------------------------------------------
            */
            $table->integer('roof_size')->nullable();
            $table->string('roof_type')->nullable();
            $table->string('roof_type_other')->nullable();
            $table->boolean('has_shadow')->default(false);
            $table->string('installation_area')->nullable();

            /*
            |--------------------------------------------------------------------------
            | 7. DECISION
            |--------------------------------------------------------------------------
            */
            $table->string('decision_maker')->nullable();
            $table->string('decision_time')->nullable();

            /*
            |--------------------------------------------------------------------------
            | INDUSTRIAL FORM START
            |--------------------------------------------------------------------------
            */

            /*
            | Company Info
            */
            $table->string('company_name')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('factory_location')->nullable();

            /*
            | Power Supply
            */
            $table->string('grid_connection')->nullable();
            $table->decimal('transformer_capacity', 10, 2)->nullable();
            $table->decimal('contract_demand', 10, 2)->nullable();
            $table->decimal('monthly_consumption', 10, 2)->nullable();

            /*
            | Machinery Load (JSON)
            */
            $table->json('machinery_load')->nullable();

            /*
            | Motor Load (JSON)
            */
            $table->json('motor_load')->nullable();

            /*
            | Operating Pattern
            */
            $table->string('working_shift')->nullable();
            $table->string('peak_load_time')->nullable();
            $table->decimal('daytime_load_percent', 5, 2)->nullable();

            /*
            | Demand & Energy
            */
            $table->decimal('demand_factor', 5, 2)->nullable();
            $table->decimal('diversity_factor', 5, 2)->nullable();
            $table->decimal('maximum_demand', 10, 2)->nullable();
            $table->decimal('daily_consumption', 10, 2)->nullable();

            /*
            | Solar Planning
            */
            $table->decimal('solar_target_percent', 5, 2)->nullable();
            $table->decimal('required_capacity_kw', 10, 2)->nullable();

            /*
            | Roof Info (Industrial)
            */
            $table->integer('roof_area')->nullable();
            $table->string('industrial_roof_type')->nullable();
            $table->boolean('industrial_shadow')->default(false);

            /*
            | Backup
            */
            $table->integer('backup_hours')->nullable();
            $table->decimal('critical_load', 10, 2)->nullable();

            /*
            | Inverter & Panel
            */
            $table->decimal('inverter_size', 10, 2)->nullable();
            $table->integer('panel_size')->nullable();
            $table->integer('panel_quantity')->nullable();

            /*
            |--------------------------------------------------------------------------
            | COMMON EXTRA
            |--------------------------------------------------------------------------
            */
            $table->text('signature')->nullable();
            $table->date('submitted_date')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solar_leads');
    }
};