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
            if (!Schema::hasColumn('results', 'exam_category_id')) {
                $table->unsignedBigInteger('exam_category_id')->nullable()->after('subject_id');
            }

            if (!Schema::hasColumn('results', 'year')) {
                $table->string('year')->nullable()->after('session');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('results', function (Blueprint $table) {
            if (Schema::hasColumn('results', 'exam_category_id')) {
                $table->dropColumn('exam_category_id');
            }

            if (Schema::hasColumn('results', 'year')) {
                $table->dropColumn('year');
            }
        });
    }
};
