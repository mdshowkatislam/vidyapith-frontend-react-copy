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
        Schema::create('class_tests', function (Blueprint $table) {
            $table->id();
            $table->string('uid')->unique();
            $table->string('eiin')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('shift_id')->nullable();
            $table->unsignedBigInteger('version_id')->nullable();
            $table->unsignedBigInteger('class_id')->nullable();
            $table->unsignedBigInteger('section_id')->nullable();
            $table->string('subject_code')->nullable();
            $table->string('exam_no')->nullable();
            $table->string('exam_name')->nullable();
            $table->float('mcq_mark')->nullable();
            $table->float('written_mark')->nullable();
            $table->float('practical_mark')->nullable();
            $table->integer('exam_full_mark')->nullable();
            $table->string('exam_date')->nullable();
            $table->string('exam_time')->nullable();
            $table->text('exam_details_info')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
            $table->softDeletes();


            // $table->unique(['branch_id', 'shift_id', 'version_id', 'class_id', 'section_id', 'subject_code', 'exam_name'], 'unique_class_test_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_tests');
    }
};
