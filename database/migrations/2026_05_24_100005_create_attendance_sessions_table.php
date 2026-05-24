<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_schedule_id')->constrained('course_schedules')->cascadeOnDelete();
            $table->integer('meeting_number');          // Pertemuan ke-N
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->boolean('is_active')->default(false);
            $table->timestamps();

            // Satu jadwal hanya bisa punya satu pertemuan per nomor
            $table->unique(['course_schedule_id', 'meeting_number']);
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_sessions');
    }
};
