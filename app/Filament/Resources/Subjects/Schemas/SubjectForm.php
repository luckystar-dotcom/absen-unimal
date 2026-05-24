<?php

namespace App\Filament\Resources\Subjects\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SubjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->label('Kode Mata Kuliah')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->placeholder('TIF101'),
                TextInput::make('name')
                    ->label('Nama Mata Kuliah')
                    ->required()
                    ->placeholder('Pemrograman Web'),
                TextInput::make('sks')
                    ->label('SKS')
                    ->required()
                    ->numeric()
                    ->default(3)
                    ->minValue(1)
                    ->maxValue(6),
            ]);
    }
}
