<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Modifikasi tabel attendances untuk mendukung skema session-based.
     * Menambahkan referensi ke attendance_sessions dan student_id.
     */
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            // Kolom baru: referensi ke sesi pertemuan
            $table->foreignId('attendance_session_id')
                ->nullable()
                ->after('id')
                ->constrained('attendance_sessions')
                ->cascadeOnDelete();

            // Kolom baru: referensi eksplisit ke mahasiswa (alias dari user_id)
            $table->foreignId('student_id')
                ->nullable()
                ->after('attendance_session_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Buat campus_location_id nullable (backward compat)
            $table->foreignId('campus_location_id')
                ->nullable()
                ->change();

            // Buat user_id nullable (backward compat)
            $table->foreignId('user_id')
                ->nullable()
                ->change();

            // Index untuk anti-duplikasi per sesi
            $table->index(['attendance_session_id', 'student_id']);
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['attendance_session_id']);
            $table->dropForeign(['student_id']);
            $table->dropIndex(['attendance_session_id', 'student_id']);
            $table->dropColumn(['attendance_session_id', 'student_id']);
        });
    }
};
