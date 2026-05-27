<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Normalisasi tabel attendances:
     * 1. Backfill student_id dari user_id (jika ada data legacy).
     * 2. Hapus kolom user_id (duplikat dari student_id).
     * 3. Hapus kolom campus_location_id (derivable via session→schedule→location).
     * 4. Jadikan attendance_session_id & student_id NOT NULL.
     */
    public function up(): void
    {
        // Step 1: Backfill student_id dari user_id untuk data legacy
        DB::table('attendances')
            ->whereNull('student_id')
            ->whereNotNull('user_id')
            ->update(['student_id' => DB::raw('user_id')]);

        // Step 2: Hapus foreign key & kolom redundan
        Schema::table('attendances', function (Blueprint $table) {
            // Hapus foreign key constraints terlebih dahulu
            $table->dropForeign(['user_id']);
            $table->dropForeign(['campus_location_id']);

            // Hapus index composite lama yang melibatkan user_id
            $table->dropIndex(['user_id']);
            $table->dropIndex(['user_id', 'created_at']);

            // Hapus kolom
            $table->dropColumn(['user_id', 'campus_location_id']);
        });

        // Step 3: Ubah attendance_session_id & student_id menjadi NOT NULL
        Schema::table('attendances', function (Blueprint $table) {
            $table->unsignedBigInteger('attendance_session_id')->nullable(false)->change();
            $table->unsignedBigInteger('student_id')->nullable(false)->change();
        });
    }

    /**
     * Reverse: kembalikan kolom user_id & campus_location_id, jadikan nullable lagi.
     */
    public function down(): void
    {
        // Kembalikan nullable
        Schema::table('attendances', function (Blueprint $table) {
            $table->unsignedBigInteger('attendance_session_id')->nullable()->change();
            $table->unsignedBigInteger('student_id')->nullable()->change();
        });

        // Tambahkan kembali kolom yang dihapus
        Schema::table('attendances', function (Blueprint $table) {
            $table->foreignId('user_id')
                ->nullable()
                ->after('student_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('campus_location_id')
                ->nullable()
                ->after('user_id')
                ->constrained('campus_locations')
                ->cascadeOnDelete();

            // Kembalikan index
            $table->index('user_id');
            $table->index(['user_id', 'created_at']);
        });

        // Backfill user_id dari student_id
        DB::table('attendances')
            ->whereNull('user_id')
            ->whereNotNull('student_id')
            ->update(['user_id' => DB::raw('student_id')]);
    }
};
