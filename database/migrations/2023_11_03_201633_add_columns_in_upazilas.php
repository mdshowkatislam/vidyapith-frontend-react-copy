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
        Schema::table('upazilas', function (Blueprint $table) {
            $table->bigInteger('district_id')->nullable();
            $table->bigInteger('upazila_id')->nullable();
            $table->string('upazila_name_en')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('upazilas', function (Blueprint $table) {
            $table->dropColumn('district_id');
            $table->dropColumn('upazila_id');
            $table->dropColumn('upazila_name_en');
        });
    }
};
