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
        Schema::create('inventory_products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('uid')->unique();
            $table->bigInteger('eiin')->nullable();
            $table->bigInteger('branch_id')->nullable();
            $table->foreignId('category_id')->nullable();
            $table->foreignId('item_id')->nullable();
            $table->string('unique_no')->unique();
            $table->string('author_name')->nullable();
            $table->string('edition')->nullable();
            $table->decimal('price', 8, 2)->nullable();
            $table->string('quantity')->nullable();
            $table->date('purchase_date')->nullable();
            $table->string('supplier')->nullable();

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

            $table->tinyInteger('rec_status')->default(1);
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->bigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('category_id')->references('uid')->on('inventory_categories')->restrictOnDelete();
            $table->foreign('item_id')->references('uid')->on('inventory_items')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_products');
    }
};
