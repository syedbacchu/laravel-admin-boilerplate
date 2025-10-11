<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->integer('referred_by')->nullable();
            $table->string('provider_id',100)->nullable();
            $table->string('user_type',30);
            $table->string('name')->nullable();
            $table->string('email',180)->unique();
            $table->string('verification_code',10)->nullable();
            $table->dateTime('code_expire')->nullable();
            $table->string('new_email_verificiation_code')->nullable();
            $table->string('password')->nullable();
            // $table->string('remember_token')->nullable();
            $table->text('device_token')->nullable();
            $table->string('avatar')->nullable();
            $table->string('avatar_original')->nullable();
            $table->text('address')->nullable();
            $table->string('country',80)->nullable();
            $table->string('city',80)->nullable();
            $table->string('postal_code',20)->nullable();
            $table->string('phone',20)->unique();
            $table->decimal('balance',19,2)->default(0);
            $table->tinyInteger('banned')->default(0);
            $table->string('referral_code')->nullable();
            $table->unsignedBigInteger('customer_package_id')->nullable();
            $table->integer('remaining_uploads')->default(0);
            $table->timestamp('expires_at')->nullable();
            $table->string('messenger_color')->default('#2180f3');
            $table->tinyInteger('dark_mode')->default(0);
            $table->tinyInteger('active_status')->default(0);
            $table->string('district',10)->nullable();
            $table->string('thana',10)->nullable();
            $table->string('membership_group', 50)->nullable();
            $table->string('occupation')->nullable();
            $table->string('concern')->nullable();
            $table->string('user_sap_id')->nullable();
            $table->tinyInteger('verification_status')->default(0);
            $table->string('skinType')->nullable();
            $table->text('interested')->nullable();
            $table->string('gender',80)->nullable();
            $table->date('dateOfBirth')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->date('membership_expired_date')->nullable();
            $table->integer('otp_attempts')->default(0);
            $table->timestamp('last_otp_sent_at')->nullable();
            $table->date('sap_sync_date')->nullable();
            $table->tinyInteger('sap_sync_status')->default(0);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
