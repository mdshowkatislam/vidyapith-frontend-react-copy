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
            if (!Schema::hasColumn('results', 'exam_taken_mark')) {
                $table->float('exam_taken_mark')->nullable()->after('full_mark');
            }

            if (!Schema::hasColumn('results', 'converted_full_mark')) {
                $table->float('converted_full_mark')->nullable()->after('exam_taken_mark');
            }

            if (!Schema::hasColumn('results', 'highest_mark')) {
                $table->float('highest_mark')->nullable()->after('converted_full_mark');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('results', function (Blueprint $table) {
            if (Schema::hasColumn('results', 'exam_taken_mark')) {
                $table->dropColumn('exam_taken_mark');
            }

            if (Schema::hasColumn('results', 'converted_full_mark')) {
                $table->dropColumn('converted_full_mark');
            }

            if (Schema::hasColumn('results', 'highest_mark')) {
                $table->dropColumn('highest_mark');
            }
        });
    }
};
