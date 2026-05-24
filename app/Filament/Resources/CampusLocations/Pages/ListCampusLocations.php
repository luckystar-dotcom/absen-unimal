<?php

namespace App\Filament\Resources\CampusLocations\Pages;

use App\Filament\Resources\CampusLocations\CampusLocationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCampusLocations extends ListRecords
{
    protected static string $resource = CampusLocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
