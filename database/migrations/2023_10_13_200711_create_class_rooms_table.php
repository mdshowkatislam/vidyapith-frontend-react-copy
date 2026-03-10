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
        Schema::create('class_rooms', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('class_teacher_id');
            $table->bigInteger('eiin');
            $table->bigInteger('uid')->unique(); 
            $table->bigInteger('class_id');
            $table->bigInteger('section_id');
            $table->integer('session_year');
            $table->bigInteger('branch_id')->nullable();
            $table->bigInteger('shift_id')->nullable();
            $table->bigInteger('version_id')->nullable();
            $table->tinyInteger('status')->default('1');
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->bigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_rooms');
    }
};
