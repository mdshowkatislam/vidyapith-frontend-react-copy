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
            if (!Schema::hasColumn('result_configurations', 'exam_category_id')) {
                $table->unsignedBigInteger('exam_category_id')->nullable()->after('subject_id');
            }

            if (!Schema::hasColumn('result_configurations', 'year')) {
                $table->string('year')->nullable()->after('percent');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('result_configurations', function (Blueprint $table) {
            if (Schema::hasColumn('result_configurations', 'exam_category_id')) {
                $table->dropColumn('exam_category_id');
            }

            if (Schema::hasColumn('result_configurations', 'year')) {
                $table->dropColumn('year');
            }
        });
    }
};
