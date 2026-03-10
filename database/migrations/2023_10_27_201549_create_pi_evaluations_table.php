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
        Schema::create('pi_evaluations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('uid')->unique();
            $table->bigInteger('evaluate_type')->nullable();
            $table->bigInteger('competence_uid')->nullable();
            $table->bigInteger('pi_uid')->nullable();
            $table->bigInteger('weight_uid')->nullable();
            $table->bigInteger('student_uid')->nullable();
            $table->bigInteger('teacher_uid')->nullable();
            $table->bigInteger('class_room_uid')->nullable();
            $table->tinyInteger('submit_status')->nullable()->comment('1=draft,2=submit');
            $table->tinyInteger('is_approved')->nullable()->comment('1=approved,2=not approved');
            $table->text('remark')->nullable();
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
        Schema::dropIfExists('pi_evaluations');
    }
};
