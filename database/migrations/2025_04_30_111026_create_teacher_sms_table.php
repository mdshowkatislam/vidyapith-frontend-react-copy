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
        Schema::create('teacher_sms', function (Blueprint $table) {
            $table->id();
            $table->string('uid')->unique();
            $table->string('eiin')->nullable();
            // $table->unsignedBigInteger('branch_id')->nullable();
            // $table->unsignedBigInteger('shift_id')->nullable();
            // $table->unsignedBigInteger('version_id')->nullable();
            // $table->unsignedBigInteger('class_id')->nullable();
            // $table->unsignedBigInteger('section_id')->nullable();
            $table->unsignedBigInteger('teacher_id')->nullable();
            $table->text('phone_no')->nullable();
            $table->text('text')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_sms');
    }
};
