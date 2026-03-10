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
        Schema::create('lab_fine_configures', function (Blueprint $table) {
            $table->id();
            $table->string('uid')->unique();
            $table->string('eiin')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->enum('fine_type', ['daily', 'weekly', 'monthly', 'fixed'])->nullable();
            $table->float('fine_amount')->nullable();
            $table->float('damage_fine_amount')->nullable();
            $table->float('loss_fine_amount')->nullable();
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
        Schema::dropIfExists('lab_fine_configures');
    }
};
