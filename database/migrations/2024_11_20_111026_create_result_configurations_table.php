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
        Schema::create('result_configurations', function (Blueprint $table) {
            $table->id();
            $table->string('uid')->unique();
            $table->string('eiin')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('class_id')->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->string('exam_type')->nullable();
            $table->tinyInteger('is_best')->default(0);  //1 => best , 0 =>avg
            $table->string('num_of_best')->nullable();
            $table->string('full_mark')->nullable();
            $table->string('percent')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('result_configurations');
    }
};
