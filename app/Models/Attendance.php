<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'attendance_session_id',
        'student_id',
        'capture_lat',
        'capture_long',
        'distance_meters',
        'status',
        'user_agent',
        'proof_file',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'capture_lat' => 'float',
            'capture_long' => 'float',
            'distance_meters' => 'integer',
        ];
    }

    /**
     * Relasi: Presensi dimiliki oleh seorang mahasiswa.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Relasi: Presensi terkait dengan satu sesi pertemuan.
     */
    public function attendanceSession(): BelongsTo
    {
        return $this->belongsTo(AttendanceSession::class);
    }

    /**
     * Accessor: Lokasi kampus diturunkan dari sesi → jadwal → lokasi (3NF).
     * Menjaga backward compatibility tanpa kolom redundan.
     */
    public function getCampusLocationAttribute(): ?CampusLocation
    {
        return $this->attendanceSession?->courseSchedule?->campusLocation;
    }

    /**
     * Relasi: Audit trail pencatatan perubahan status presensi.
     */
    public function audits(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AttendanceAudit::class);
    }
}
