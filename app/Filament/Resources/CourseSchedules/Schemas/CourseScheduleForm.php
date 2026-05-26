<?php

namespace App\Filament\Resources\CourseSchedules\Schemas;

use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;

class CourseScheduleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('subject_id')
                    ->label('Mata Kuliah')
                    ->relationship('subject', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->disabled(fn () => !auth()->user()->isAdmin()),
                Select::make('study_class_id')
                    ->label('Kelas')
                    ->relationship('studyClass', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->disabled(fn () => !auth()->user()->isAdmin()),
                Select::make('dosen_id')
                    ->label('Dosen Pengampu')
                    ->options(fn () => User::where('role', 'dosen')->pluck('name', 'id'))
                    ->searchable()
                    ->preload()
                    ->required()
                    ->visible(fn () => auth()->user()->isAdmin())
                    ->default(fn () => auth()->user()->isDosen() ? auth()->id() : null)
                    ->disabled(fn () => !auth()->user()->isAdmin()),
                Select::make('campus_location_id')
                    ->label('Lokasi Kampus')
                    ->relationship('campusLocation', 'name_location')
                    ->searchable()
                    ->preload()
                    ->disabled(fn () => !auth()->user()->isAdmin()),
                Select::make('day_of_week')
                    ->label('Hari Kuliah')
                    ->options([
                        'Senin' => 'Senin',
                        'Selasa' => 'Selasa',
                        'Rabu' => 'Rabu',
                        'Kamis' => 'Kamis',
                        'Jumat' => 'Jumat',
                        'Sabtu' => 'Sabtu',
                        'Minggu' => 'Minggu',
                    ])
                    ->required()
                    ->disabled(fn () => !auth()->user()->isAdmin()),
                TimePicker::make('start_time')
                    ->label('Jam Mulai')
                    ->seconds(false)
                    ->required()
                    ->disabled(fn () => !auth()->user()->isAdmin()),
                TimePicker::make('end_time')
                    ->label('Jam Selesai')
                    ->seconds(false)
                    ->required()
                    ->disabled(fn () => !auth()->user()->isAdmin()),
            ]);
    }
}
