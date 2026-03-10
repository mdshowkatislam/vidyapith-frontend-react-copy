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
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->string('uid')->unique();
            $table->string('eiin')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('shift_id')->nullable();
            $table->unsignedBigInteger('version_id')->nullable();
            $table->unsignedBigInteger('class_id')->nullable();
            $table->unsignedBigInteger('section_id')->nullable();
            $table->string('assignment_no')->nullable();
            $table->string('assignment_name')->nullable();
            $table->string('subject_code')->nullable();
            $table->float('mcq_mark')->nullable();
            $table->float('written_mark')->nullable();
            $table->float('practical_mark')->nullable();
            $table->integer('assignment_full_mark')->nullable();
            $table->string('assignment_submission_date')->nullable();
            $table->text('assignment_details_info')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
            $table->softDeletes();

            // $table->unique(['branch_id', 'shift_id', 'version_id', 'class_id', 'section_id', 'subject_code', 'assignment_name'], 'unique_assignment_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
