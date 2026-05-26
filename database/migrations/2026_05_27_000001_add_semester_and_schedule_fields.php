<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah kolom semester pada tabel subjects untuk pengelompokan katalog MK
        Schema::table('subjects', function (Blueprint $table) {
            $table->unsignedTinyInteger('semester')->nullable()->after('sks');
        });

        // Tambah kolom hari & jam pada tabel course_schedules untuk jadwal harian
        Schema::table('course_schedules', function (Blueprint $table) {
            $table->enum('day_of_week', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'])
                ->nullable()
                ->after('campus_location_id');
            $table->time('start_time')->nullable()->after('day_of_week');
            $table->time('end_time')->nullable()->after('start_time');
        });
    }

    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->dropColumn('semester');
        });

        Schema::table('course_schedules', function (Blueprint $table) {
            $table->dropColumn(['day_of_week', 'start_time', 'end_time']);
        });
    }
};
