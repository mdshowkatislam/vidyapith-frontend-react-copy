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
        Schema::table('result_configurations', function (Blueprint $table) {
            if (!Schema::hasColumn('result_configurations', 'section_id')) {
                $table->unsignedBigInteger('section_id')->nullable()->after('class_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('result_configurations', function (Blueprint $table) {
            if (Schema::hasColumn('result_configurations', 'section_id')) {
                $table->dropColumn('section_id');
            }
        });
    }
};
