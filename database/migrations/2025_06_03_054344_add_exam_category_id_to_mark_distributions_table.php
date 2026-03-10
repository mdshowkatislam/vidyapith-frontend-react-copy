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
        Schema::table('mark_distributions', function (Blueprint $table) {
            if (!Schema::hasColumn('mark_distributions', 'exam_category_id')) {
                $table->unsignedBigInteger('subject_id')->nullable()->after('section_id');
            }

            if (!Schema::hasColumn('mark_distributions', 'exam_category_id')) {
                $table->unsignedBigInteger('exam_category_id')->nullable()->after('subject_id');
            }

            if (!Schema::hasColumn('mark_distributions', 'year')) {
                $table->string('year')->nullable()->after('remark');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mark_distributions', function (Blueprint $table) {
            if (Schema::hasColumn('mark_distributions', 'subject_id')) {
                $table->dropColumn('subject_id');
            }

            if (Schema::hasColumn('mark_distributions', 'exam_category_id')) {
                $table->dropColumn('exam_category_id');
            }

            if (Schema::hasColumn('mark_distributions', 'year')) {
                $table->dropColumn('year');
            }
        });
    }
};
