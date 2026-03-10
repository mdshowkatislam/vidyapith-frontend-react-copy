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
        Schema::create('merit_lists', function (Blueprint $table) {
            $table->id();
            $table->string('uid')->unique();
            $table->string('eiin')->nullable();
            $table->unsignedBigInteger('student_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('class_id')->nullable();
            $table->unsignedBigInteger('section_id')->nullable();
            $table->string('exam_type')->nullable();
            $table->integer('position')->nullable();
            $table->integer('class_position')->nullable();
            $table->boolean('is_fail')->default(false);
            $table->decimal('total_marks', 8, 2)->nullable();
            $table->string('year')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merit_lists');
    }
};
