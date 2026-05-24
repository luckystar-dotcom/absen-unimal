<?php

namespace App\Filament\Resources\StudyClasses;

use App\Filament\Resources\StudyClasses\Pages\CreateStudyClass;
use App\Filament\Resources\StudyClasses\Pages\EditStudyClass;
use App\Filament\Resources\StudyClasses\Pages\ListStudyClasses;
use App\Filament\Resources\StudyClasses\Schemas\StudyClassForm;
use App\Filament\Resources\StudyClasses\Tables\StudyClassesTable;
use App\Models\StudyClass;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class StudyClassResource extends Resource
{
    protected static ?string $model = StudyClass::class;
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationLabel = 'Kelas';
    protected static ?string $pluralModelLabel = 'Kelas';
    protected static ?string $modelLabel = 'Kelas';
    protected static string | \UnitEnum | null $navigationGroup = 'Master Data';
    protected static ?int $navigationSort = 2;

    public static function canViewAny(): bool
    {
        return auth()->user()->isAdmin();
    }

    public static function form(Schema $schema): Schema
    {
        return StudyClassForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StudyClassesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStudyClasses::route('/'),
            'create' => CreateStudyClass::route('/create'),
            'edit' => EditStudyClass::route('/{record}/edit'),
        ];
    }
}
