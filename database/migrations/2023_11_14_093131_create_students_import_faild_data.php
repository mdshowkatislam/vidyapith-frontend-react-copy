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
        Schema::create('students_import_faild_data', function (Blueprint $table) {
            $table->id();
            $table->longText('error_description')->nullable();
            $table->longText('imported_data')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->string('batch_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students_import_faild_data');
    }
};
