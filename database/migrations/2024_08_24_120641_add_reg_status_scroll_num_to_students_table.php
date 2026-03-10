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
        Schema::table('students', function (Blueprint $table) {
            $table->tinyInteger('reg_status')->after('email')->default(0)->comment('0=pending,1=temp,2=registered');
            $table->bigInteger('scroll_num')->after('reg_status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('reg_status');
            $table->dropColumn('scroll_num');
        });
    }
};
