<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceAudit extends Model
{
    protected $fillable = [
        'attendance_id',
        'changed_by_user_id',
        'old_status',
        'new_status',
        'reason',
    ];

    /**
     * Relasi: Audit dimiliki oleh satu presensi.
     */
    public function attendance(): BelongsTo
    {
        return $this->belongsTo(Attendance::class);
    }

    /**
     * Relasi: Audit dilakukan oleh seorang user (Dosen/Admin).
     */
    public function changer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by_user_id');
    }
}
