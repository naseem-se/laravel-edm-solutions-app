<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('shift_invitations', function (Blueprint $table) {
            $table->string('token')->nullable()->unique()->after('expires_at');
        });
    }

    public function down()
    {
        Schema::table('shift_invitations', function (Blueprint $table) {
            $table->dropColumn('token');
        });
    }
};
