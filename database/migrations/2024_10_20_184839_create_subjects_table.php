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
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('uid')->unique();
            $table->string('subject_name_en')->nullable();
            $table->string('subject_name_bn')->nullable();
            $table->string('subject_code')->nullable();
            $table->string('session')->nullable();
            $table->bigInteger('eiin')->nullable();
            $table->tinyInteger('rec_status')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
