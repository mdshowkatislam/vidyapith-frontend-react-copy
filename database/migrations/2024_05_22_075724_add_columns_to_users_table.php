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
            $table->bigInteger('upazila_id')->after('user_type_id')->nullable();
            $table->bigInteger('district_id')->after('upazila_id')->nullable();
            $table->bigInteger('division_id')->after('district_id')->nullable();
            $table->bigInteger('board_id')->after('division_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('upazila_id');
            $table->dropColumn('district_id');
            $table->dropColumn('division_id');
            $table->dropColumn('board_id');
        });
    }
};
