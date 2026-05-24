<?php

namespace App\Filament\Resources\Attendances\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;

class AttendancesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            // Menerapkan Eager Loading -> Hanya mengeksekusi 2 Query secara keseluruhan (Anti N+1)
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['user', 'campus_location']))
            ->columns([
                TextColumn::make('user.name')
                    ->label('Nama Mahasiswa')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.nip_nim')
                    ->label('NIM')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('campus_location.name_location')
                    ->label('Lokasi Kampus')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('capture_lat')
                    ->label('Latitude')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('capture_long')
                    ->label('Longitude')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('distance_meters')
                    ->label('Jarak')
                    ->numeric()
                    ->sortable()
                    ->formatStateUsing(fn ($state) => $state . ' m'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'hadir' => 'success',
                        'luar_radius' => 'danger',
                        'terlambat' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('user_agent')
                    ->label('Device Info')
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Waktu Presensi')
                    ->dateTime('d M Y - H:i:s')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status Kehadiran')
                    ->options([
                        'hadir' => 'Hadir',
                        'luar_radius' => 'Luar Radius',
                        'terlambat' => 'Terlambat',
                    ]),
                Filter::make('created_at')
                    ->label('Tanggal Presensi')
                    ->form([
                        DatePicker::make('created_from')->label('Dari Tanggal'),
                        DatePicker::make('created_until')->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
