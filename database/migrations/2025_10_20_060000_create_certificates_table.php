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
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('uid')->unique();
            $table->string('student_name', 255);
            $table->string('roll_number', 100);
            $table->string('class_name', 100);
            $table->string('section_name', 100);
            $table->decimal('grade_point', 3, 2);
            $table->integer('total_marks')->nullable();
            $table->string('exam_type', 100)->nullable();
            $table->string('exam_name', 255)->nullable();
            $table->string('academic_year', 20);
            $table->integer('merit_position')->nullable();
            $table->string('school_name', 255);
            $table->bigInteger('institute_id')->nullable();
            $table->timestamp('issue_date')->useCurrent();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Indexes for performance
            $table->index('uid', 'idx_certificate_id');
            $table->index('roll_number', 'idx_student_roll');
            $table->index('issue_date', 'idx_issue_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
