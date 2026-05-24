<?php
namespace App\Filament\Resources\CourseSchedules\Pages;
use App\Filament\Resources\CourseSchedules\CourseScheduleResource;
use Filament\Resources\Pages\CreateRecord;
class CreateCourseSchedule extends CreateRecord
{
    protected static string $resource = CourseScheduleResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Jika dosen, auto-set dosen_id ke diri sendiri
        if (auth()->user()->isDosen()) {
            $data['dosen_id'] = auth()->id();
        }
        return $data;
    }
}
