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
        Schema::table('payments', function (Blueprint $table) {
            $table->string('invoice_no')->after('transaction_id')->nullable();
            $table->date('invoice_date')->after('invoice_no')->nullable();
            $table->text('session_token')->after('invoice_date')->nullable();
            $table->string('payment_status')->after('session_token')->nullable();
            $table->integer('payment_status_code')->after('payment_status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('invoice_no');
            $table->dropColumn('invoice_date');
            $table->dropColumn('session_token');
            $table->dropColumn('payment_status');
            $table->dropColumn('payment_status_code');
        });
    }
};
