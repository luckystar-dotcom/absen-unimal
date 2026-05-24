<?php

namespace App\Filament\Resources\CampusLocations\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CampusLocationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name_location')
                    ->required(),
                TextInput::make('latitude')
                    ->required()
                    ->numeric(),
                TextInput::make('longitude')
                    ->required()
                    ->numeric(),
                TextInput::make('radius_tolerance')
                    ->required()
                    ->numeric()
                    ->default(100),
                Toggle::make('is_active')
                    ->required(),
                TimePicker::make('start_time')
                    ->label('Waktu Mulai Sesi')
                    ->seconds(false),
                TimePicker::make('end_time')
                    ->label('Waktu Akhir Sesi')
                    ->seconds(false),
            ]);
    }
}
