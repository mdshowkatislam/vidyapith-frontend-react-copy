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
        Schema::table('attendances', function (Blueprint $table) {
            if (!Schema::hasColumn('attendances', 'entry_time')) {
                $table->string('entry_time')->nullable()->after('date');
            }

            if (!Schema::hasColumn('attendances', 'source')) {
                $table->string('source')->nullable()->after('status');
            }

            if (!Schema::hasColumn('attendances', 'machine_id')) {
                $table->string('machine_id')->nullable()->after('source');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            if (Schema::hasColumn('attendances', 'entry_time')) {
                $table->dropColumn('entry_time');
            }

            if (Schema::hasColumn('attendances', 'source')) {
                $table->dropColumn('source');
            }

            if (Schema::hasColumn('attendances', 'machine_id')) {
                $table->dropColumn('machine_id');
            }
        });
    }
};
