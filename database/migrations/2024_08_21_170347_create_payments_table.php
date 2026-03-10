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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('uid')->unique();
            $table->bigInteger('eiin')->index();
            $table->integer('class')->index();
            $table->string('depositor_name')->nullable();
            $table->string('depositor_mobile')->nullable();
            $table->integer('no_of_students')->default(0);
            $table->string('amount', 10);
            $table->string('transaction_id')->nullable();
            $table->integer('session_year')->index();
            $table->tinyInteger('rec_status')->default(0)->comment('1=paid,0=unpaid');
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
        Schema::dropIfExists('payments');
    }
};
