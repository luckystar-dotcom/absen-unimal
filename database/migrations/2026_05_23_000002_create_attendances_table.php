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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('campus_location_id')->constrained('campus_locations')->cascadeOnDelete();
            $table->decimal('capture_lat', 10, 8);
            $table->decimal('capture_long', 11, 8);
            $table->integer('distance_meters'); // Jarak absolut hasil kalkulasi Haversine
            $table->enum('status', ['hadir', 'luar_radius', 'terlambat'])->default('hadir');
            $table->text('user_agent')->nullable(); // Device Context untuk audit
            $table->timestamps();

            // === Strategi Indexing (Optimasi Database) ===

            // Foreign Key Index: Pencarian presensi berdasarkan mahasiswa
            $table->index('user_id');

            // Timestamp Index: Filter data harian di dashboard
            $table->index('created_at');

            // Composite Index: Validasi anti-duplikasi absen harian (mikro-detik)
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
