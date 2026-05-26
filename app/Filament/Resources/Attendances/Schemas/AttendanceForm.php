<?php

namespace App\Filament\Resources\Attendances\Schemas;

use App\Models\AttendanceSession;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class AttendanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('attendance_session_id')
                    ->label('Sesi Pertemuan')
                    ->options(function () {
                        return AttendanceSession::with(['courseSchedule.subject', 'courseSchedule.studyClass'])
                            ->get()
                            ->mapWithKeys(fn ($s) => [
                                $s->id => $s->courseSchedule->subject->name . ' - Kelas ' . $s->courseSchedule->studyClass->name . ' (Ke-' . $s->meeting_number . ')',
                            ]);
                    })
                    ->searchable()
                    ->required(),
                Select::make('student_id')
                    ->label('Mahasiswa')
                    ->options(fn () => User::where('role', 'mahasiswa')->pluck('name', 'id'))
                    ->searchable()
                    ->required(),
                TextInput::make('capture_lat')
                    ->label('Latitude')
                    ->numeric()
                    ->default(0),
                TextInput::make('capture_long')
                    ->label('Longitude')
                    ->numeric()
                    ->default(0),
                TextInput::make('distance_meters')
                    ->label('Jarak (m)')
                    ->numeric()
                    ->default(0),
                Select::make('status')
                    ->options([
                        'hadir' => 'Hadir',
                        'luar_radius' => 'Luar Radius',
                        'terlambat' => 'Terlambat',
                        'izin' => 'Izin',
                        'sakit' => 'Sakit',
                    ])
                    ->default('hadir')
                    ->required()
                    ->live(), // Ensures dynamic form updates when status changes!
                Textarea::make('change_reason')
                    ->label('Alasan Perubahan')
                    ->placeholder('Wajib diisi jika status presensi diubah secara manual oleh Dosen/Admin...')
                    ->columnSpanFull()
                    ->required(fn ($record, $get) => $record && $get('status') !== $record->status)
                    ->visible(fn ($record, $get) => $record && $get('status') !== $record->status),
                Textarea::make('user_agent')
                    ->default(null)
                    ->columnSpanFull()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextInput::make('proof_file')
                    ->label('File Bukti')
                    ->default(null),
            ]);
    }
}
