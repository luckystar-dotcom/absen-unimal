<?php

namespace App\Filament\Resources\CourseSchedules\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class StudentAttendanceStatsRelationManager extends RelationManager
{
    protected static string $relationship = 'students';
    protected static ?string $title = 'Statistik Kehadiran Mahasiswa';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nip_nim')
                    ->label('NIM')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Nama Mahasiswa')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('hadir_count')
                    ->label('✅ Hadir')
                    ->state(function (Model $record): int {
                        return $this->countAttendanceByStatus($record, 'hadir');
                    })
                    ->badge()
                    ->color('success'),
                TextColumn::make('izin_count')
                    ->label('📋 Izin')
                    ->state(function (Model $record): int {
                        return $this->countAttendanceByStatus($record, 'izin');
                    })
                    ->badge()
                    ->color('info'),
                TextColumn::make('sakit_count')
                    ->label('🏥 Sakit')
                    ->state(function (Model $record): int {
                        return $this->countAttendanceByStatus($record, 'sakit');
                    })
                    ->badge()
                    ->color('warning'),
                TextColumn::make('luar_radius_count')
                    ->label('❌ Luar Radius')
                    ->state(function (Model $record): int {
                        return $this->countAttendanceByStatus($record, 'luar_radius');
                    })
                    ->badge()
                    ->color('danger'),
            ])
            ->defaultSort('name');
    }

    /**
     * Hitung jumlah kehadiran mahasiswa berdasarkan status
     * untuk course_schedule (kelas) yang sedang dilihat.
     */
    private function countAttendanceByStatus(Model $student, string $status): int
    {
        $courseScheduleId = $this->getOwnerRecord()->id;

        return $student->studentAttendances()
            ->where('status', $status)
            ->whereHas('attendanceSession', function (Builder $query) use ($courseScheduleId) {
                $query->where('course_schedule_id', $courseScheduleId);
            })
            ->count();
    }
}
