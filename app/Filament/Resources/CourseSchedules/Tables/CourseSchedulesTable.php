<?php

namespace App\Filament\Resources\CourseSchedules\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CourseSchedulesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            // Dosen hanya melihat jadwal miliknya
            ->modifyQueryUsing(function (Builder $query) {
                $user = auth()->user();
                if ($user->isDosen()) {
                    $query->where('dosen_id', $user->id);
                }
                return $query->with(['subject', 'studyClass', 'dosen', 'campusLocation']);
            })
            ->columns([
                TextColumn::make('subject.name')
                    ->label('Mata Kuliah')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('subject.code')
                    ->label('Kode')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('studyClass.name')
                    ->label('Kelas')
                    ->sortable(),
                TextColumn::make('dosen.name')
                    ->label('Dosen')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: auth()->check() && auth()->user()->isDosen()),
                TextColumn::make('campusLocation.name_location')
                    ->label('Lokasi')
                    ->placeholder('Belum diset')
                    ->toggleable(),
                TextColumn::make('day_of_week')
                    ->label('Hari')
                    ->sortable(),
                TextColumn::make('start_time')
                    ->label('Waktu')
                    ->state(fn ($record) => $record->start_time && $record->end_time 
                        ? \Carbon\Carbon::parse($record->start_time)->format('H:i') . ' - ' . \Carbon\Carbon::parse($record->end_time)->format('H:i') 
                        : '-'
                    )
                    ->sortable(),
                TextColumn::make('student_enrollments_count')
                    ->label('Mahasiswa')
                    ->counts('studentEnrollments')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
