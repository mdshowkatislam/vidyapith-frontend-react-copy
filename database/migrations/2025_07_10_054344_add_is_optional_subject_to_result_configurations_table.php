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
            if (!Schema::hasColumn('result_configurations', 'is_optional_subject')) {
                $table->tinyInteger('is_optional_subject')->nullable()->after('year');
            }
            if (!Schema::hasColumn('result_configurations', 'is_separately_pass')) {
                $table->tinyInteger('is_separately_pass')->nullable()->after('is_optional_subject');
            }
        });

        Schema::table('results', function (Blueprint $table) {
            if (!Schema::hasColumn('results', 'is_optional_subject')) {
                $table->tinyInteger('is_optional_subject')->nullable()->after('year');
            }
            if (!Schema::hasColumn('results', 'is_separately_pass')) {
                $table->tinyInteger('is_separately_pass')->nullable()->after('is_optional_subject');
            }
            if (!Schema::hasColumn('results', 'result_status')) {
                $table->string('result_status')->nullable()->after('is_separately_pass');
            }
            if (!Schema::hasColumn('results', 'grad_point')) {
                $table->string('grad_point')->nullable()->after('result_status');
            }
            if (!Schema::hasColumn('results', 'grade')) {
                $table->string('grade')->nullable()->after('grad_point');
            }
            if (!Schema::hasColumn('results', 'is_present')) {
                $table->string('is_present')->nullable()->after('grade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('result_configurations', function (Blueprint $table) {
            if (Schema::hasColumn('result_configurations', 'is_optional_subject')) {
                $table->dropColumn('is_optional_subject');
            }
            if (Schema::hasColumn('result_configurations', 'is_separately_pass')) {
                $table->dropColumn('is_separately_pass');
            }
        });

        Schema::table('results', function (Blueprint $table) {
            if (Schema::hasColumn('results', 'is_optional_subject')) {
                $table->dropColumn('is_optional_subject');
            }
            if (Schema::hasColumn('results', 'is_separately_pass')) {
                $table->dropColumn('is_separately_pass');
            }
            if (Schema::hasColumn('results', 'result_status')) {
                $table->dropColumn('result_status');
            }
            if (Schema::hasColumn('results', 'grad_point')) {
                $table->dropColumn('grad_point');
            }
            if (Schema::hasColumn('results', 'grade')) {
                $table->dropColumn('grade');
            }
            if (Schema::hasColumn('results', 'is_present')) {
                $table->dropColumn('is_present');
            }
        });
    }
};
