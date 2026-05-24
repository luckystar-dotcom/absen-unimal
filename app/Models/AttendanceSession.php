<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AttendanceSession extends Model
{
    protected $fillable = [
        'course_schedule_id',
        'meeting_number',
        'start_time',
        'end_time',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'start_time' => 'datetime',
            'end_time' => 'datetime',
            'is_active' => 'boolean',
            'meeting_number' => 'integer',
        ];
    }

    /**
     * Relasi: Sesi dimiliki oleh satu jadwal.
     */
    public function courseSchedule(): BelongsTo
    {
        return $this->belongsTo(CourseSchedule::class);
    }

    /**
     * Relasi: Sesi memiliki banyak log absensi.
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Helper: Cek apakah sesi sedang berlangsung (aktif + dalam rentang waktu).
     */
    public function isCurrentlyOpen(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();
        return $now->between($this->start_time, $this->end_time);
    }
}
