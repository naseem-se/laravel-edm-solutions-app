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
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('email')->unique();
            $table->string('phone_number')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('stripe_customer_id')->nullable();
            $table->string('stripe_account_id')->nullable();
            $table->boolean('stripe_onboarded')->default(false);
            $table->json('stripe_capabilities')->nullable();
            $table->boolean('is_google')->default(false);
            $table->boolean('is_apple')->default(false);
            $table->integer('code')->default(0);
            $table->string('id_card')->nullable();
            $table->string('certificate')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->boolean('two_factor')->default(false);
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('image')->nullable();
            $table->enum('role', ['super_admin','sub_admin']);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
