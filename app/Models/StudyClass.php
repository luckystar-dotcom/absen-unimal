<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudyClass extends Model
{
    protected $fillable = [
        'name',
    ];

    /**
     * Relasi: Kelas memiliki banyak jadwal.
     */
    public function courseSchedules(): HasMany
    {
        return $this->hasMany(CourseSchedule::class);
    }
}
