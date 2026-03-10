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
        Schema::create('institutes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('eiin')->unique()->nullable();
            $table->bigInteger('caid')->unique()->nullable();;
            $table->bigInteger('uid')->unique();
            $table->bigInteger('division_id')->nullable();
            $table->bigInteger('district_id')->nullable();
            $table->bigInteger('upazilla_id')->nullable();
            $table->string('unions')->nullable();
            $table->string('institute_name')->nullable();
            $table->string('institute_type')->nullable();
            $table->string('category')->nullable();
            $table->string('level')->nullable();
            $table->string('mpo')->nullable();
            $table->string('phone')->nullable();
            $table->bigInteger('head_caid')->nullable();
            $table->string('head_of_institute_mobile')->nullable();
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('post_office')->nullable();
            $table->string('message')->nullable();
            $table->integer('data_source')->nullable();
            $table->string('institute_source')->nullable();
            $table->string('role', 30)->nullable();
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
        Schema::dropIfExists('institutes');
    }
};
