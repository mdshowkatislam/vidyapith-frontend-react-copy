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
        Schema::create('student_attendances', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('uid')->unique();
            $table->bigInteger('student_uid')->index();
            $table->integer('session')->index();
            $table->json('january')->nullable();
            $table->json('february')->nullable();
            $table->json('march')->nullable();
            $table->json('april')->nullable();
            $table->json('may')->nullable();
            $table->json('june')->nullable();
            $table->json('july')->nullable();
            $table->json('august')->nullable();
            $table->json('september')->nullable();
            $table->json('october')->nullable();
            $table->json('november')->nullable();
            $table->json('december')->nullable();
            // $table->tinyInteger('rec_status')->default(1)->comment('1=active,0=inactive');
            // $table->bigInteger('created_by')->nullable();
            // $table->bigInteger('updated_by')->nullable();
            // $table->bigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_attendances');
    }
};
