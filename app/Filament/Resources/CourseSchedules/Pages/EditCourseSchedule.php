<?php

namespace App\Filament\Resources\CourseSchedules\Pages;

use App\Filament\Resources\CourseSchedules\CourseScheduleResource;
use App\Filament\Resources\CourseSchedules\RelationManagers\StudentAttendanceStatsRelationManager;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCourseSchedule extends EditRecord
{
    protected static string $resource = CourseScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}

