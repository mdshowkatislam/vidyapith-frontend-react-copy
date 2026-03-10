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
        Schema::table('pi_evaluations', function (Blueprint $table) {
            $table->bigInteger('oviggota_uid')->after('pi_uid')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pi_evaluations', function (Blueprint $table) {
            $table->dropColumn('oviggota_uid');
        });
    }
};
