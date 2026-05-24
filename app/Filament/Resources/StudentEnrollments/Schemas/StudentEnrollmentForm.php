<?php
namespace App\Filament\Resources\StudentEnrollments\Schemas;
use App\Models\CourseSchedule;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class StudentEnrollmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('course_schedule_id')
                ->label('Jadwal Kuliah')
                ->options(fn () => CourseSchedule::with(['subject', 'studyClass'])->get()->mapWithKeys(fn ($cs) => [$cs->id => $cs->subject->name . ' - Kelas ' . $cs->studyClass->name]))
                ->searchable()
                ->required(),
            Select::make('student_id')
                ->label('Mahasiswa')
                ->options(fn () => User::where('role', 'mahasiswa')->pluck('name', 'id'))
                ->searchable()
                ->required(),
        ]);
    }
}
