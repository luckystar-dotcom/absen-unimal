<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menambahkan status 'izin' dan 'sakit' ke enum, serta kolom proof_file.
     */
    public function up(): void
    {
        // Modifikasi enum status untuk menambahkan 'izin' dan 'sakit' hanya di MySQL
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE attendances MODIFY COLUMN status ENUM('hadir', 'luar_radius', 'terlambat', 'izin', 'sakit') DEFAULT 'hadir'");
        }

        Schema::table('attendances', function (Blueprint $table) {
            $table->string('proof_file')->nullable()->after('user_agent');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn('proof_file');
        });

        // Kembalikan enum ke semula hanya di MySQL
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE attendances MODIFY COLUMN status ENUM('hadir', 'luar_radius', 'terlambat') DEFAULT 'hadir'");
        }
    }
};
