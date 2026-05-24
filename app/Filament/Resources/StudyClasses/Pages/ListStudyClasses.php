<?php
namespace App\Filament\Resources\StudyClasses\Pages;
use App\Filament\Resources\StudyClasses\StudyClassResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
class ListStudyClasses extends ListRecords
{
    protected static string $resource = StudyClassResource::class;
    protected function getHeaderActions(): array { return [CreateAction::make()]; }
}
