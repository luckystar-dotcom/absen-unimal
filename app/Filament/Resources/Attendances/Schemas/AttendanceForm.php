<?php

namespace App\Filament\Resources\Attendances\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class AttendanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Select::make('campus_location_id')
                    ->relationship('campus_location', 'name_location')
                    ->required(),
                TextInput::make('capture_lat')
                    ->required()
                    ->numeric(),
                TextInput::make('capture_long')
                    ->required()
                    ->numeric(),
                TextInput::make('distance_meters')
                    ->required()
                    ->numeric(),
                Select::make('status')
                    ->options(['hadir' => 'Hadir', 'luar_radius' => 'Luar radius', 'terlambat' => 'Terlambat'])
                    ->default('hadir')
                    ->required(),
                Textarea::make('user_agent')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
