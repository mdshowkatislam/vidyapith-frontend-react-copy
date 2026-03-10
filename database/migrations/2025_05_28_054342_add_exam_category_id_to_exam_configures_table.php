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
        Schema::table('exam_configures', function (Blueprint $table) {
            if (!Schema::hasColumn('exam_configures', 'exam_category_id')) {
                $table->unsignedBigInteger('exam_category_id')->nullable()->after('section_id');
            }

            if (!Schema::hasColumn('exam_configures', 'year')) {
                $table->string('year')->nullable()->after('exam_details_info');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exam_configures', function (Blueprint $table) {
            if (Schema::hasColumn('exam_configures', 'exam_category_id')) {
                $table->dropColumn('exam_category_id');
            }

            if (Schema::hasColumn('exam_configures', 'year')) {
                $table->dropColumn('year');
            }
        });
    }
};
