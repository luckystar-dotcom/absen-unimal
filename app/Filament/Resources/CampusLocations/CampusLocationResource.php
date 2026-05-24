<?php

namespace App\Filament\Resources\CampusLocations;

use App\Filament\Resources\CampusLocations\Pages\CreateCampusLocation;
use App\Filament\Resources\CampusLocations\Pages\EditCampusLocation;
use App\Filament\Resources\CampusLocations\Pages\ListCampusLocations;
use App\Filament\Resources\CampusLocations\Schemas\CampusLocationForm;
use App\Filament\Resources\CampusLocations\Tables\CampusLocationsTable;
use App\Models\CampusLocation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CampusLocationResource extends Resource
{
    protected static ?string $model = CampusLocation::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-map-pin';

    protected static ?string $navigationLabel = 'Titik Absensi Kampus';
    protected static ?string $pluralModelLabel = 'Titik Absensi Kampus';
    protected static ?string $modelLabel = 'Titik Absensi';

    protected static ?string $recordTitleAttribute = 'name_location';

    public static function form(Schema $schema): Schema
    {
        return CampusLocationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CampusLocationsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCampusLocations::route('/'),
            'create' => CreateCampusLocation::route('/create'),
            'edit' => EditCampusLocation::route('/{record}/edit'),
        ];
    }
}
