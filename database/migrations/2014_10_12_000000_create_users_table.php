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
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('caid')->unique();
                $table->bigInteger('eiin')->nullable();
                $table->bigInteger('pdsid')->nullable();
                $table->bigInteger('suid')->nullable();
                $table->string('phone_no', 30)->nullable();
                $table->string('role', 30)->nullable();
                $table->string('name')->nullable();
                $table->string('email')->nullable();
                $table->integer('user_type_id')->nullable();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password')->nullable();
                $table->rememberToken();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
