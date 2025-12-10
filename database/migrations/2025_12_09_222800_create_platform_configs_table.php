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
        Schema::create('platform_configs', function (Blueprint $table) {
            $table->id();
            $table->integer('commission_percentage')->default(10);
            $table->integer('cancellation_policy')->default(24);
            $table->enum('payment_cycle',['hourly','daily','weekly','monthly','semi-annually','annually'])->default('monthly');
            $table->boolean('auto_approve_facility')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('platform_configs');
    }
};
