<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    protected $fillable = [
        'code',
        'name',
        'sks',
        'semester',
    ];

    protected function casts(): array
    {
        return [
            'sks' => 'integer',
            'semester' => 'integer',
        ];
    }

    /**
     * Relasi: Mata kuliah memiliki banyak jadwal.
     */
    public function courseSchedules(): HasMany
    {
        return $this->hasMany(CourseSchedule::class);
    }
}
