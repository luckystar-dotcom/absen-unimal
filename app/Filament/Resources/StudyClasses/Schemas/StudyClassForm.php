<?php
namespace App\Filament\Resources\StudyClasses\Schemas;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class StudyClassForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->label('Nama Kelas')->required()->placeholder('A'),
        ]);
    }
}
