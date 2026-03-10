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
        Schema::create('attached_students', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('uid')->unique();
            $table->bigInteger('student_uid')->index();
            $table->integer('roll')->index();
            $table->integer('attached_eiin')->index();
            $table->bigInteger('class_room_uid')->nullable()->index();
            $table->bigInteger('old_class_room_uid')->index();
            $table->integer('session_year')->index();
            $table->tinyInteger('approve_status')->default(0)->comment('1=approve,0=pending,2=reject');
            $table->tinyInteger('rec_status')->default(1)->comment('1=active,0=inactive');
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
        Schema::dropIfExists('attached_students');
    }
};
