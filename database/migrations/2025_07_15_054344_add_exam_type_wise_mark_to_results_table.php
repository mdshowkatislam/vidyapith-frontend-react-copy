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
        Schema::table('results', function (Blueprint $table) {
            // class test
            if (!Schema::hasColumn('results', 'class_test_mark')) {
                $table->decimal('class_test_mark', 10,2)->nullable()->after('is_present');
            }
            if (!Schema::hasColumn('results', 'weekly_test_mark')) {
                $table->decimal('weekly_test_mark', 10,2)->nullable()->after('class_test_mark');
            }
            if (!Schema::hasColumn('results', 'bi_weekly_test_mark')) {
                $table->decimal('bi_weekly_test_mark', 10,2)->nullable()->after('weekly_test_mark');
            }
            if (!Schema::hasColumn('results', 'monthly_test_mark')) {
                $table->decimal('monthly_test_mark', 10,2)->nullable()->after('bi_weekly_test_mark');
            }
            if (!Schema::hasColumn('results', 'assignment_mark')) {
                $table->decimal('assignment_mark', 10,2)->nullable()->after('monthly_test_mark');
            }


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('results', function (Blueprint $table) {
            // class test
            if (Schema::hasColumn('results', 'class_test_mark')) {
                $table->dropColumn('class_test_mark');
            }
            if (Schema::hasColumn('results', 'weekly_test_mark')) {
                $table->dropColumn('weekly_test_mark');
            }
            if (Schema::hasColumn('results', 'bi_weekly_test_mark')) {
                $table->dropColumn('bi_weekly_test_mark');
            }
            if (Schema::hasColumn('results', 'monthly_test_mark')) {
                $table->dropColumn('monthly_test_mark');
            }
            if (Schema::hasColumn('results', 'assignment_mark')) {
                $table->dropColumn('assignment_mark');
            }

        });
    }
};
