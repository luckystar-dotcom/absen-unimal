<?php

namespace App\Filament\Resources\AttendanceSessions;

use App\Filament\Resources\AttendanceSessions\Pages\CreateAttendanceSession;
use App\Filament\Resources\AttendanceSessions\Pages\EditAttendanceSession;
use App\Filament\Resources\AttendanceSessions\Pages\ListAttendanceSessions;
use App\Filament\Resources\AttendanceSessions\Schemas\AttendanceSessionForm;
use App\Filament\Resources\AttendanceSessions\Tables\AttendanceSessionsTable;
use App\Models\AttendanceSession;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AttendanceSessionResource extends Resource
{
    protected static ?string $model = AttendanceSession::class;
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationLabel = 'Sesi Pertemuan';
    protected static ?string $pluralModelLabel = 'Sesi Pertemuan';
    protected static ?string $modelLabel = 'Sesi Pertemuan';
    protected static string | \UnitEnum | null $navigationGroup = 'Akademik';
    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return AttendanceSessionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AttendanceSessionsTable::configure($table);
    }

    /**
     * Dosen hanya bisa melihat sesi pertemuan dari jadwal miliknya (resource-level scoping).
     */
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        if ($user && $user->isDosen()) {
            $query->whereHas('courseSchedule', fn (Builder $q) => $q->where('dosen_id', $user->id));
        }

        return $query;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAttendanceSessions::route('/'),
            'create' => CreateAttendanceSession::route('/create'),
            'edit' => EditAttendanceSession::route('/{record}/edit'),
        ];
    }
}
