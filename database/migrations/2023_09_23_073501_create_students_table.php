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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('eiin')->nullable();     //foreign key institutes 
            $table->bigInteger('suid')->unique()->nullable();
            $table->bigInteger('uid')->unique(); 
            $table->bigInteger('caid')->unique()->nullable();
            $table->tinyInteger('type')->nullable();
            $table->string('incremental_no')->nullable();
            $table->string('student_name_en')->nullable();              
            $table->string('student_name_bn')->nullable();
            $table->string('brid')->nullable(); 
            $table->date('date_of_birth')->nullable();
            $table->string('email')->nullable();
            $table->string('registration_year')->nullable();
            $table->string('religion')->nullable();
            $table->string('birth_place')->nullable();
            $table->string('gender')->nullable();
            $table->string('nationality')->nullable();
            $table->string('recent_study_class')->nullable();
            $table->string('disability_status')->nullable();
            $table->string('blood_group')->nullable();
            $table->string('student_mobile_no')->nullable();
            $table->string('ethnic_info')->nullable();
            $table->string('branch')->nullable();
            $table->string('version')->nullable();
            $table->string('shift')->nullable();
            $table->string('class')->nullable();
            $table->string('section')->nullable();
            $table->string('roll')->nullable();
            $table->tinyInteger('is_regular')->nullable();
            $table->string('father_name_en')->nullable();
            $table->string('father_name_bn')->nullable();
            $table->string('father_nid')->nullable();
            $table->string('father_brid')->nullable();
            $table->date('father_date_of_birth')->nullable();
            $table->string('father_mobile_no')->nullable();
            $table->string('mother_name_en')->nullable();
            $table->string('mother_name_bn')->nullable();
            $table->string('mother_nid')->nullable();
            $table->string('mother_brid')->nullable();
            $table->date('mother_date_of_birth')->nullable();
            $table->string('mother_mobile_no')->nullable();
            $table->string('guardian_name_bn')->nullable();
            $table->string('guardian_name_en')->nullable();
            $table->string('guardian_mobile_no')->nullable();
            $table->string('guardian_nid')->nullable();
            $table->string('guardian_occupation')->nullable();
            $table->string('relation_with_guardian')->nullable();
            $table->text('present_address')->nullable();
            $table->text('permanent_address')->nullable();
            $table->string('post_office')->nullable();
            $table->bigInteger('division_id')->nullable();
            $table->bigInteger('district_id')->nullable();
            $table->bigInteger('upazilla_id')->nullable();
            $table->string('unions')->nullable();
            $table->text('image')->nullable();
            $table->string('role', 30)->nullable();
            $table->tinyInteger('data_source')->nullable();
            $table->string('student_source')->nullable();
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
        Schema::dropIfExists('students');
    }
};
