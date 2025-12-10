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
        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('recipient_id')->nullable()->after('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('transfer_id')->nullable()->after('payment_method_id');
            $table->decimal('platform_fee', 10, 2)->default(0)->after('amount');
            $table->decimal('recipient_amount', 10, 2)->after('platform_fee');
            $table->string('transfer_status')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['recipient_id']);
            $table->dropColumn('recipient_id');
            $table->dropColumn('transfer_id');
            $table->dropColumn('platform_fee');
            $table->dropColumn('recipient_amount');
            $table->dropColumn('transfer_status');
        });
    }
};
