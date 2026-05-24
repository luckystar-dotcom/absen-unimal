<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menambahkan kolom start_time dan end_time untuk manajemen sesi presensi.
     */
    public function up(): void
    {
        Schema::table('campus_locations', function (Blueprint $table) {
            $table->time('start_time')->nullable()->after('is_active');
            $table->time('end_time')->nullable()->after('start_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('campus_locations', function (Blueprint $table) {
            $table->dropColumn(['start_time', 'end_time']);
        });
    }
};
