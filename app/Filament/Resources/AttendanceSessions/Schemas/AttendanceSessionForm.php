<?php

namespace App\Filament\Resources\AttendanceSessions\Schemas;

use App\Models\CourseSchedule;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AttendanceSessionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('course_schedule_id')
                    ->label('Jadwal Kuliah')
                    ->options(function () {
                        $user = auth()->user();
                        $query = CourseSchedule::with(['subject', 'studyClass']);

                        if ($user->isDosen()) {
                            $query->where('dosen_id', $user->id);
                        }

                        return $query->get()->mapWithKeys(fn ($cs) => [
                            $cs->id => $cs->subject->name . ' - Kelas ' . $cs->studyClass->name,
                        ]);
                    })
                    ->searchable()
                    ->required(),
                TextInput::make('meeting_number')
                    ->label('Pertemuan Ke-')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(16)
                    ->default(1),
                DateTimePicker::make('start_time')
                    ->label('Waktu Mulai')
                    ->required()
                    ->seconds(false),
                DateTimePicker::make('end_time')
                    ->label('Waktu Selesai')
                    ->required()
                    ->seconds(false),
                Toggle::make('is_active')
                    ->label('Sesi Aktif (Buka Absensi)')
                    ->default(false),
            ]);
    }
}
