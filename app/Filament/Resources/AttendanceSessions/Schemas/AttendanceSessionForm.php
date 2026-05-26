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
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if (!$state) return;

                        // 1. Auto-Suggest Pertemuan Ke- berikutnya
                        $latestSession = \App\Models\AttendanceSession::where('course_schedule_id', $state)
                            ->orderBy('meeting_number', 'desc')
                            ->first();
                        $nextMeetingNumber = $latestSession ? $latestSession->meeting_number + 1 : 1;
                        $set('meeting_number', $nextMeetingNumber);

                        // 2. Auto-Fill Waktu Mulai & Selesai berdasarkan Jadwal
                        $schedule = \App\Models\CourseSchedule::find($state);
                        if (!$schedule) return;

                        $dayMap = [
                            'Senin' => \Carbon\Carbon::MONDAY,
                            'Selasa' => \Carbon\Carbon::TUESDAY,
                            'Rabu' => \Carbon\Carbon::WEDNESDAY,
                            'Kamis' => \Carbon\Carbon::THURSDAY,
                            'Jumat' => \Carbon\Carbon::FRIDAY,
                            'Sabtu' => \Carbon\Carbon::SATURDAY,
                            'Minggu' => \Carbon\Carbon::SUNDAY,
                        ];

                        $now = \Carbon\Carbon::now();
                        $targetDay = $dayMap[$schedule->day_of_week] ?? null;

                        if ($targetDay !== null) {
                            if ($now->dayOfWeek === $targetDay) {
                                $date = $now;
                            } else {
                                $date = $now->next($targetDay);
                            }
                        } else {
                            $date = $now;
                        }

                        if ($schedule->start_time) {
                            $startDateTime = $date->copy()->setTimeFromTimeString($schedule->start_time);
                            $set('start_time', $startDateTime->toDateTimeString());
                        }

                        if ($schedule->end_time) {
                            $endDateTime = $date->copy()->setTimeFromTimeString($schedule->end_time);
                            $set('end_time', $endDateTime->toDateTimeString());
                        }
                    }),
                TextInput::make('meeting_number')
                    ->label('Pertemuan Ke-')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(16)
                    ->default(1)
                    ->rules(function ($get, $record) {
                        $rule = \Illuminate\Validation\Rule::unique('attendance_sessions', 'meeting_number')
                            ->where('course_schedule_id', $get('course_schedule_id'));
                        
                        if ($record) {
                            $rule->ignore($record->id);
                        }
                        
                        return [$rule];
                    })
                    ->validationMessages([
                        'unique' => 'Pertemuan Ke-:input sudah ada untuk Jadwal Kuliah yang dipilih.',
                    ]),
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
