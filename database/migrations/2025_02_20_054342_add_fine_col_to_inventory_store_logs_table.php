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
        Schema::table('inventory_store_logs', function (Blueprint $table) {
            $table->enum('fine_type', ['daily', 'weekly', 'monthly', 'fixed'])->nullable()->after('actual_return');
            $table->float('fine_amount')->nullable()->after('fine_type');
            $table->tinyInteger('is_damage')->nullable()->after('fine_amount');
            $table->tinyInteger('is_loss')->nullable()->after('is_damage');
            $table->tinyInteger('is_paid')->default(0)->after('is_loss');
            $table->string('unique_id')->nullable()->after('is_paid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventory_store_logs', function (Blueprint $table) {
            $table->dropColumn('fine_type');
            $table->dropColumn('fine_amount');
            $table->dropColumn('is_damage');
            $table->dropColumn('is_loss');
            $table->dropColumn('is_paid');
            $table->dropColumn('unique_id');
        });
    }
};
