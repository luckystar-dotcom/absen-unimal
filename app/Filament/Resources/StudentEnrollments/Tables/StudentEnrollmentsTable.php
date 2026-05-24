<?php
namespace App\Filament\Resources\StudentEnrollments\Tables;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class StudentEnrollmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['courseSchedule.subject', 'courseSchedule.studyClass', 'student']))
            ->columns([
                TextColumn::make('student.name')->label('Mahasiswa')->searchable()->sortable(),
                TextColumn::make('student.nip_nim')->label('NIM')->searchable()->sortable(),
                TextColumn::make('courseSchedule.subject.name')->label('Mata Kuliah')->searchable()->sortable(),
                TextColumn::make('courseSchedule.studyClass.name')->label('Kelas')->sortable(),
                TextColumn::make('created_at')->label('Terdaftar')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
