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
        Schema::create('board_registation_processes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('uid')->unique();
            $table->bigInteger('payment_uid')->index();
            $table->bigInteger('eiin')->index();
            $table->integer('class')->index();
            $table->integer('no_of_payment_students')->default(0);
            $table->integer('no_of_temp_students')->default(0);
            $table->integer('no_of_registered_students')->default(0);
            $table->integer('session_year')->index();
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
        Schema::dropIfExists('board_registation_processes');
    }
};
