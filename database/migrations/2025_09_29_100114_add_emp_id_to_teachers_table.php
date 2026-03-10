<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
          $table->string('emp_id')->nullable()->after('uid');
        });
        
        // ডুপ্লিকেট এবং খালি emp_id মান পরিষ্কার করুন
        DB::statement("UPDATE teachers SET emp_id = NULL WHERE emp_id = '' OR emp_id IS NULL");
        
        // এখন ইউনিক কনস্ট্রেন্ট যোগ করুন
        Schema::table('teachers', function (Blueprint $table) {
            $table->unique('emp_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropColumn('emp_id');   
         
        });
    }
};
