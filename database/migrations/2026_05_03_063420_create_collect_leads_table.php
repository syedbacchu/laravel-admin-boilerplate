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
        Schema::create('collect_leads', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('type')->default(1);
            $table->tinyInteger('site_type')->default(1);

            $table->string('company_name')->nullable();
            $table->string('full_name')->nullable();
            $table->string('phone',20)->nullable();
            $table->string('whatsapp',20)->nullable();
            $table->string('email')->nullable();
            $table->string('nid')->nullable();

            $table->string('customer_type')->nullable();

            $table->text('address')->nullable();
            $table->string('district')->nullable();
            $table->text('google_map')->nullable();
            $table->string('installation_site_type')->nullable();

            $table->string('electricity_source')->nullable();
            $table->decimal('monthly_bill', 19, 2)->default(0);
            $table->string('meter_type')->nullable();
            $table->string('daytime_usage')->nullable();
            $table->string('grid_connection')->nullable();
            $table->decimal('transformer_capacity', 10, 2)->nullable();
            $table->decimal('contract_demand', 10, 2)->nullable();
            $table->decimal('monthly_consumption', 10, 2)->nullable();

            $table->json('machinery_load_details')->nullable();
            $table->json('motor_load_details')->nullable();

            $table->decimal('total_connected_load',19,2)->default(0);
            $table->decimal('total_motor_load',19,2)->default(0);

            $table->string('working_shift')->nullable();
            $table->string('peak_load_time')->nullable();
            $table->decimal('daytime_load_percentage',10,2)->default(0);

            $table->string('system_type')->nullable();
            $table->decimal('system_size_kw', 10, 2)->nullable();
            $table->string('main_purpose')->nullable();

            $table->string('budget_range')->nullable();
            $table->string('payment_preference')->nullable();

            $table->string('roof_size')->nullable();
            $table->string('roof_type')->nullable();
            $table->boolean('has_shadow')->default(false);
            $table->string('installation_area')->nullable();

            $table->string('lead_source')->nullable();

            $table->string('decision_maker')->nullable();
            $table->string('decision_time')->nullable();

            // Sales team
            $table->string('lead_priority')->nullable();
            $table->decimal('estimated_deal_value',19,2)->default(0);
            $table->string('status')->default('new');
            $table->string('sales_person_name')->nullable();

            $table->decimal('demand_factor', 19, 2)->nullable();
            $table->decimal('diversity_factor', 19, 2)->nullable();
            $table->decimal('maximum_demand', 19, 2)->nullable();
            $table->decimal('daily_consumption', 19, 2)->nullable();

            $table->decimal('solar_target_percent', 5, 2)->nullable();
            $table->decimal('required_capacity_kw', 19, 2)->nullable();

            $table->integer('backup_hours')->nullable();
            $table->decimal('critical_load', 19, 2)->nullable();

            $table->decimal('inverter_size', 19, 2)->nullable();
            $table->decimal('panel_size',19,2)->nullable();
            $table->integer('panel_quantity')->nullable();

            $table->decimal('estimated_project_cost',19,2)->default(0);
            $table->decimal('expected_payback_period',19,2)->default(0);

            $table->string('customer_signature')->nullable();
            $table->date('declaration_date')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collect_leads');
    }
};
