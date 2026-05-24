<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->foreignId('study_class_id')->constrained('study_classes')->cascadeOnDelete();
            $table->foreignId('dosen_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('campus_location_id')->nullable()->constrained('campus_locations')->nullOnDelete();
            $table->timestamps();

            // Satu mata kuliah di satu kelas hanya bisa satu jadwal
            $table->unique(['subject_id', 'study_class_id']);
            $table->index('dosen_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_schedules');
    }
};
