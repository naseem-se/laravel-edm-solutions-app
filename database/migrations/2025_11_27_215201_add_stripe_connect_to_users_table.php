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
        Schema::table('users', function (Blueprint $table) {
            $table->string('stripe_account_id')->nullable()->after('stripe_customer_id'); // For Connect
            $table->boolean('stripe_onboarded')->default(false)->after('stripe_account_id');
            $table->json('stripe_capabilities')->nullable()->after('stripe_onboarded');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('stripe_customer_id');
            $table->dropColumn('stripe_account_id');
            $table->dropColumn('stripe_onboarded');
            $table->dropColumn('stripe_capabilities');
        });
    }
};
