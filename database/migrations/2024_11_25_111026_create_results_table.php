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
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->string('uid')->unique();
            $table->string('eiin')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('class_id')->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->unsignedBigInteger('student_id')->nullable();
            $table->tinyInteger('is_submitted')->default(0);  //1 => final save , 0 =>temp save
            $table->string('exam_type')->nullable();
            $table->float('mcq_mark')->nullable();
            $table->float('written_mark')->nullable();
            $table->float('practical_mark')->nullable();
            $table->float('mark')->nullable();
            $table->float('attendance')->nullable();
            $table->float('behavior')->nullable();
            $table->string('full_mark')->nullable();
            $table->string('session')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
