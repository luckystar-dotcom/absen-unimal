<?php

namespace App\Filament\Resources\CourseSchedules;

use App\Filament\Resources\CourseSchedules\Pages\CreateCourseSchedule;
use App\Filament\Resources\CourseSchedules\Pages\EditCourseSchedule;
use App\Filament\Resources\CourseSchedules\Pages\ListCourseSchedules;
use App\Filament\Resources\CourseSchedules\RelationManagers\StudentAttendanceStatsRelationManager;
use App\Filament\Resources\CourseSchedules\Schemas\CourseScheduleForm;
use App\Filament\Resources\CourseSchedules\Tables\CourseSchedulesTable;
use App\Models\CourseSchedule;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CourseScheduleResource extends Resource
{
    protected static ?string $model = CourseSchedule::class;
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationLabel = 'Jadwal Kuliah';
    protected static ?string $pluralModelLabel = 'Jadwal Kuliah';
    protected static ?string $modelLabel = 'Jadwal Kuliah';
    protected static string | \UnitEnum | null $navigationGroup = 'Akademik';
    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return CourseScheduleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CourseSchedulesTable::configure($table);
    }

    /**
     * Dosen hanya bisa melihat jadwal miliknya (resource-level scoping).
     */
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        if ($user && $user->isDosen()) {
            $query->where('dosen_id', $user->id);
        }

        return $query;
    }

    public static function getRelations(): array
    {
        return [
            StudentAttendanceStatsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCourseSchedules::route('/'),
            'create' => CreateCourseSchedule::route('/create'),
            'edit' => EditCourseSchedule::route('/{record}/edit'),
        ];
    }
}

