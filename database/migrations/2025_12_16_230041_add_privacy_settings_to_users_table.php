<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('profile_visibility')->default(true)->after('password');
            $table->boolean('two_factor_enabled')->default(false)->after('profile_visibility');
            $table->string('two_factor_code')->nullable()->after('two_factor_enabled');
            $table->boolean('biometric_lock')->default(false)->after('two_factor_code');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['profile_visibility', 'two_factor_enabled', 'two_factor_code', 'biometric_lock']);
        });
    }
};
