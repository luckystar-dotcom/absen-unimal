<?php

namespace App\Filament\Resources\CampusLocations\Pages;

use App\Filament\Resources\CampusLocations\CampusLocationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCampusLocation extends EditRecord
{
    protected static string $resource = CampusLocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
