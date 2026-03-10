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
        Schema::create('mark_distributions', function (Blueprint $table) {
            $table->id();
            $table->string('uid')->unique();
            $table->string('eiin')->nullable();
            $table->unsignedBigInteger('class_id')->nullable();
            $table->unsignedBigInteger('section_id')->nullable();
            $table->string('exam_type')->nullable();
            $table->unsignedBigInteger('exam_id')->nullable();
            $table->float('exam_full_mark')->nullable();
            $table->unsignedBigInteger('student_id')->nullable();
            $table->float('mcq_mark')->nullable();
            $table->float('written_mark')->nullable();
            $table->float('practical_mark')->nullable();

            $table->float('obtain_full_mark')->nullable();
            $table->float('converted_full_mark')->nullable();

            $table->tinyInteger('is_submitted')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->text('remark')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mark_distributions');
    }
};
