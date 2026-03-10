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
        Schema::create('class_wise_subjects', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('uid')->unique();
            $table->bigInteger('class_id')->nullable();
            $table->bigInteger('subject_id')->nullable();
            $table->bigInteger('session_id')->nullable();
            $table->bigInteger('eiin')->nullable();
            $table->tinyInteger('rec_status')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['class_id', 'subject_id', 'session_id', 'eiin'], 'unique_class_wise_subject');

            $table->foreign('class_id')->references('uid')->on('class_names')->restrictOnDelete();
            $table->foreign('subject_id')->references('uid')->on('subjects')->restrictOnDelete();
            $table->foreign('session_id')->references('uid')->on('sessions')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_wise_subjects');
    }
};
