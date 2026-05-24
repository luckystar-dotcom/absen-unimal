<?php
namespace App\Filament\Resources\StudyClasses\Pages;
use App\Filament\Resources\StudyClasses\StudyClassResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
class EditStudyClass extends EditRecord
{
    protected static string $resource = StudyClassResource::class;
    protected function getHeaderActions(): array { return [DeleteAction::make()]; }
}
