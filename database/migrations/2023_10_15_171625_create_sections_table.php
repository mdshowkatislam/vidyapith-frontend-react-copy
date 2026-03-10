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
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('uid')->unique(); 
            $table->bigInteger('branch_id')->nullable();  
            $table->bigInteger('class_id')->nullable();  
            $table->bigInteger('shift_id')->nullable();  
            $table->bigInteger('version_id')->nullable();  
            $table->integer('section_year')->nullable();  
            $table->string('section_name')->nullable(); 
            $table->string('section_details')->nullable(); 
            $table->bigInteger('eiin')->nullable();
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
        Schema::dropIfExists('sections');
    }
};
