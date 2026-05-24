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
            ->modifyQueryUsing(function (Builder $query) {
                $user = auth()->user();

                // Dosen: hanya lihat absensi dari jadwal miliknya
                if ($user->isDosen()) {
                    $query->whereHas('attendanceSession.courseSchedule', function (Builder $q) use ($user) {
                        $q->where('dosen_id', $user->id);
                    });
                }

                return $query->with([
                    'student',
                    'attendanceSession.courseSchedule.subject',
                    'attendanceSession.courseSchedule.studyClass',
                ]);
            })
            ->columns([
                TextColumn::make('student.name')
                    ->label('Mahasiswa')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('student.nip_nim')
                    ->label('NIM')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('attendanceSession.courseSchedule.subject.name')
                    ->label('Mata Kuliah')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('attendanceSession.courseSchedule.studyClass.name')
                    ->label('Kelas')
                    ->sortable(),
                TextColumn::make('attendanceSession.meeting_number')
                    ->label('Pertemuan')
                    ->formatStateUsing(fn ($state) => $state ? 'Ke-' . $state : '-')
                    ->sortable(),
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
                        'izin' => 'info',
                        'sakit' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'hadir' => 'Hadir',
                        'luar_radius' => 'Luar Radius',
                        'terlambat' => 'Terlambat',
                        'izin' => 'Izin',
                        'sakit' => 'Sakit',
                        default => $state,
                    }),
                TextColumn::make('proof_file')
                    ->label('Bukti')
                    ->formatStateUsing(fn ($state) => $state ? '📎 Lihat' : '-')
                    ->url(fn ($record) => $record->proof_file ? asset('storage/' . $record->proof_file) : null)
                    ->openUrlInNewTab()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('created_at')
                    ->label('Waktu')
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
                        'izin' => 'Izin',
                        'sakit' => 'Sakit',
                    ]),
                Filter::make('created_at')
                    ->label('Tanggal')
                    ->form([
                        DatePicker::make('created_from')->label('Dari'),
                        DatePicker::make('created_until')->label('Sampai'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['created_from'], fn (Builder $q, $date) => $q->whereDate('created_at', '>=', $date))
                            ->when($data['created_until'], fn (Builder $q, $date) => $q->whereDate('created_at', '<=', $date));
                    }),
            ])
            ->defaultSort('created_at', 'desc')
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
