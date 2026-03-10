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
        Schema::create('inventory_store_logs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('uid')->unique();
            $table->foreignId('eiin')->nullable();
            $table->foreignId('branch_id')->nullable();
            $table->foreignId('product_id')->nullable();
            
            $table->date('stock_in_at')->nullable();
            $table->bigInteger('stock_in_by')->nullable();
            $table->bigInteger('store_id')->nullable();
            $table->string('store_name')->nullable();
            $table->bigInteger('location_id')->nullable();
            $table->string('location')->nullable();
            $table->date('stock_out_at')->nullable();
            $table->bigInteger('stock_out_by')->nullable();
            $table->bigInteger('assign_by')->nullable();
            $table->string('assign_type')->nullable();  //teacher , student
            $table->date('return_date')->nullable();
            $table->date('actual_return')->nullable();

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
        Schema::dropIfExists('inventory_store_in_outs');
    }
};
