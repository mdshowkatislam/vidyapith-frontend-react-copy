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
        Schema::table('bi_evaluations', function (Blueprint $table) {
            $table->bigInteger('subject_uid')->after('teacher_uid')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bi_evaluations', function (Blueprint $table) {
            $table->dropColumn('subject_uid');
        });
    }
};
