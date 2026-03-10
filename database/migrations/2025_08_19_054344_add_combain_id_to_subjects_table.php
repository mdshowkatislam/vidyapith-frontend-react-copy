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
        Schema::table('subjects', function (Blueprint $table) {
            if (!Schema::hasColumn('subjects', 'is_combine')) {
                $table->string('is_combine')->nullable()->after('eiin');
            }
            if (!Schema::hasColumn('subjects', 'combine_subject_id')) {
                $table->unsignedBigInteger('combine_subject_id')->nullable()->after('is_combine');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            if (Schema::hasColumn('subjects', 'is_combine')) {
                $table->dropColumn('is_combine');
            }
            if (Schema::hasColumn('subjects', 'combine_subject_id')) {
                $table->dropColumn('combine_subject_id');
            }
        });
    }
};
