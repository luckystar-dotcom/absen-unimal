<?php

namespace App\Filament\Resources\Attendances\Pages;

use App\Filament\Resources\Attendances\AttendanceResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditAttendance extends EditRecord
{
    protected static string $resource = AttendanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    /**
     * Override penyelamatan record untuk menulis log audit secara manual.
     */
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $oldStatus = $record->status;
        $newStatus = $data['status'];
        $reason = $data['change_reason'] ?? 'Perubahan manual oleh Dosen/Admin';

        // Buang change_reason dari array data agar tidak memicu error kolom SQL
        unset($data['change_reason']);

        $record->update($data);

        if ($oldStatus !== $newStatus) {
            $record->audits()->create([
                'changed_by_user_id' => auth()->id(),
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'reason' => $reason,
            ]);
        }

        return $record;
    }
}
