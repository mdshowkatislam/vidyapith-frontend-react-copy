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
            $table->text('disability_types')->nullable()->after('guardian_occupation'); // Replace 'column_name' with the column after which you want to add the new column
            $table->integer('attached_eiin')->nullable()->after('eiin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('disability_types');
            $table->dropColumn('attached_eiin');
        });
    }
};
