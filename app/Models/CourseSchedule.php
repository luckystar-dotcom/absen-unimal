<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class CourseSchedule extends Model
{
    protected $fillable = [
        'subject_id',
        'study_class_id',
        'dosen_id',
        'campus_location_id',
    ];

    /**
     * Relasi: Jadwal dimiliki oleh satu mata kuliah.
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Relasi: Jadwal dimiliki oleh satu kelas.
     */
    public function studyClass(): BelongsTo
    {
        return $this->belongsTo(StudyClass::class);
    }

    /**
     * Relasi: Jadwal diampu oleh satu dosen.
     */
    public function dosen(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }

    /**
     * Relasi: Jadwal di satu lokasi kampus.
     */
    public function campusLocation(): BelongsTo
    {
        return $this->belongsTo(CampusLocation::class);
    }

    /**
     * Relasi: Jadwal memiliki banyak enrollment (KRS).
     */
    public function studentEnrollments(): HasMany
    {
        return $this->hasMany(StudentEnrollment::class);
    }

    /**
     * Relasi: Jadwal memiliki banyak sesi pertemuan.
     */
    public function attendanceSessions(): HasMany
    {
        return $this->hasMany(AttendanceSession::class);
    }

    /**
     * Relasi: Mahasiswa yang terdaftar di jadwal ini (via pivot).
     */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'student_enrollments', 'course_schedule_id', 'student_id')
            ->withTimestamps();
    }

    /**
     * Relasi: Semua absensi dari sesi pertemuan jadwal ini.
     * HasManyThrough: CourseSchedule → AttendanceSession → Attendance
     */
    public function attendances(): HasManyThrough
    {
        return $this->hasManyThrough(Attendance::class, AttendanceSession::class);
    }

    /**
     * Helper: Format label jadwal untuk dropdown/display.
     */
    public function getFullLabelAttribute(): string
    {
        return ($this->subject->name ?? '?') . ' - Kelas ' . ($this->studyClass->name ?? '?');
    }
}
