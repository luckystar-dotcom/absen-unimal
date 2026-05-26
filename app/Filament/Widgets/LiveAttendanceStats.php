<?php

namespace App\Filament\Widgets;

use App\Models\Attendance;
use App\Models\AttendanceSession;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Livewire\Attributes\On;

class LiveAttendanceStats extends BaseWidget
{
    public ?int $sessionId = null;

    protected ?string $pollingInterval = null; // Menggunakan WebSockets, polling dinonaktifkan

    public function mount(): void
    {
        $user = auth()->user();
        if ($user && $user->isDosen()) {
            // Dapatkan sesi pertemuan aktif pertama milik dosen bersangkutan
            $activeSession = AttendanceSession::where('is_active', true)
                ->whereHas('courseSchedule', fn ($q) => $q->where('dosen_id', $user->id))
                ->latest()
                ->first();

            if ($activeSession) {
                $this->sessionId = $activeSession->id;
            }
        }
    }

    #[On('echo:attendance.{sessionId},AttendanceSubmitted')]
    public function handleAttendanceSubmitted($event = null): void
    {
        // Livewire otomatis me-render ulang widget ini saat mendeteksi event dari WebSockets!
    }

    protected function getStats(): array
    {
        $user = auth()->user();

        // Jika bukan Dosen, kembalikan array kosong agar widget tidak merender apa pun
        if (!$user || !$user->isDosen()) {
            return [];
        }

        if (!$this->sessionId) {
            return [
                Stat::make('Sesi Presensi Aktif', 'Tidak Ada')
                    ->description('Belum ada kelas atau sesi yang aktif saat ini')
                    ->descriptionIcon('heroicon-m-clock')
                    ->color('gray'),
            ];
        }

        $session = AttendanceSession::with('courseSchedule.subject', 'courseSchedule.studyClass')->find($this->sessionId);
        if (!$session) {
            return [];
        }

        $totalHadir = Attendance::where('attendance_session_id', $this->sessionId)
            ->where('status', 'hadir')
            ->count();

        $totalIzinSakit = Attendance::where('attendance_session_id', $this->sessionId)
            ->whereIn('status', ['izin', 'sakit'])
            ->count();

        $totalLuarRadius = Attendance::where('attendance_session_id', $this->sessionId)
            ->where('status', 'luar_radius')
            ->count();

        $subjectName = $session->courseSchedule->subject->name ?? 'Mata Kuliah';
        $className = $session->courseSchedule->studyClass->name ?? 'Kelas';
        $meetingNo = $session->meeting_number;

        return [
            Stat::make('Total Hadir', $totalHadir)
                ->description("Sesi Ke-{$meetingNo} · {$subjectName} ({$className})")
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
            Stat::make('Izin / Sakit', $totalIzinSakit)
                ->description('Diajukan oleh mahasiswa')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('info'),
            Stat::make('Luar Radius', $totalLuarRadius)
                ->description('Menunggu verifikasi / ditolak')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),
        ];
    }

    public static function canView(): bool
    {
        $user = auth()->user();
        return $user && $user->isDosen();
    }
}
