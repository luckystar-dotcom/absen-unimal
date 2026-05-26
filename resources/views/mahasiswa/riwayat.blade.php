<x-mahasiswa-layout pageTitle="Riwayat Presensi">
    @push('styles')
    <style>
        .riwayat-container { max-width: 640px; margin: 0 auto; padding: 32px 20px; }

        .riwayat-header { margin-bottom: 28px; }
        .riwayat-header h1 { font-size: 1.5rem; font-weight: 700; color: #fff; margin: 0 0 4px; display: flex; align-items: center; gap: 10px; }
        .riwayat-header p { color: #94a3b8; font-size: 0.85rem; margin: 0; }

        .stats-bar { display: grid; grid-template-columns: repeat(auto-fit, minmax(90px, 1fr)); gap: 10px; margin-bottom: 28px; }
        .stat-item { background: #1e293b; border: 1px solid #334155; border-radius: 14px; padding: 14px 12px; text-align: center; }
        .stat-count { font-size: 1.5rem; font-weight: 800; line-height: 1; margin-bottom: 4px; }
        .stat-label { font-size: 0.65rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
        .stat-item.hadir .stat-count, .stat-item.hadir .stat-label { color: #4ade80; }
        .stat-item.luar_radius .stat-count, .stat-item.luar_radius .stat-label { color: #f87171; }
        .stat-item.terlambat .stat-count, .stat-item.terlambat .stat-label { color: #fbbf24; }
        .stat-item.izin .stat-count, .stat-item.izin .stat-label { color: #60a5fa; }
        .stat-item.sakit .stat-count, .stat-item.sakit .stat-label { color: #c084fc; }

        .attendance-card { background: #1e293b; border: 1px solid #334155; border-radius: 16px; padding: 18px 20px; margin-bottom: 12px; transition: all 0.2s ease; }
        .attendance-card:hover { border-color: #475569; background: #243147; transform: translateY(-1px); }
        .card-top { display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; }
        .card-date { display: flex; align-items: center; gap: 10px; }
        .card-status-dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }
        .card-status-dot.hadir { background: #22c55e; box-shadow: 0 0 8px rgba(34,197,94,0.5); }
        .card-status-dot.luar_radius { background: #ef4444; box-shadow: 0 0 8px rgba(239,68,68,0.5); }
        .card-status-dot.terlambat { background: #f59e0b; box-shadow: 0 0 8px rgba(245,158,11,0.5); }
        .card-status-dot.izin { background: #3b82f6; box-shadow: 0 0 8px rgba(59,130,246,0.5); }
        .card-status-dot.sakit { background: #a855f7; box-shadow: 0 0 8px rgba(168,85,247,0.5); }
        .card-date-text { font-weight: 600; color: #fff; font-size: 0.9rem; }
        .card-time { color: #94a3b8; font-size: 0.75rem; margin-top: 1px; }
        .card-badge { font-size: 0.7rem; font-weight: 700; padding: 4px 12px; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.5px; }
        .card-badge.hadir { background: rgba(34,197,94,0.15); color: #4ade80; border: 1px solid rgba(34,197,94,0.2); }
        .card-badge.luar_radius { background: rgba(239,68,68,0.15); color: #f87171; border: 1px solid rgba(239,68,68,0.2); }
        .card-badge.terlambat { background: rgba(245,158,11,0.15); color: #fbbf24; border: 1px solid rgba(245,158,11,0.2); }
        .card-badge.izin { background: rgba(59,130,246,0.15); color: #60a5fa; border: 1px solid rgba(59,130,246,0.2); }
        .card-badge.sakit { background: rgba(168,85,247,0.15); color: #c084fc; border: 1px solid rgba(168,85,247,0.2); }

        .card-subject { font-size: 0.8rem; font-weight: 600; color: #fbbf24; margin-bottom: 8px; }
        .card-details { display: flex; gap: 16px; flex-wrap: wrap; }
        .card-detail-item { display: flex; align-items: center; gap: 6px; font-size: 0.8rem; color: #94a3b8; }
        .card-detail-item svg { width: 14px; height: 14px; flex-shrink: 0; color: #64748b; }
        .card-proof-link { color: #60a5fa; text-decoration: none; font-weight: 600; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 4px; transition: color 0.2s; }
        .card-proof-link:hover { color: #93bbfc; }

        .empty-riwayat { text-align: center; padding: 60px 24px; background: #1e293b; border: 1px dashed #334155; border-radius: 20px; color: #64748b; }
        .empty-riwayat svg { width: 48px; height: 48px; margin: 0 auto 16px; color: #334155; }
        .empty-riwayat h3 { font-size: 1.1rem; font-weight: 700; color: #94a3b8; margin: 0 0 4px; }
        .empty-riwayat p { font-size: 0.85rem; margin: 0; }
    </style>
    @endpush

    <div class="riwayat-container">
        <div class="riwayat-header">
            <h1>
                <svg class="h-6 w-6 text-amber-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-13a.75.75 0 00-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 000-1.5h-3.25V5z" clip-rule="evenodd" />
                </svg>
                Riwayat Presensi
            </h1>
            <p>Seluruh riwayat kehadiran Anda tercatat di sini.</p>

            @if(request()->has('schedule_id'))
                @php
                    $filteredSchedule = \App\Models\CourseSchedule::with('subject')->find(request('schedule_id'));
                @endphp
                @if($filteredSchedule)
                    <div class="mt-4 flex items-center justify-between p-3.5 rounded-xl bg-amber-500/10 border border-amber-500/20 backdrop-blur-sm">
                        <span class="text-xs text-amber-200/90 font-medium">
                            Menampilkan riwayat untuk: <strong class="text-white font-semibold">{{ $filteredSchedule->subject->name }}</strong>
                        </span>
                        <a href="{{ route('riwayat') }}" class="text-xs font-bold text-amber-400 hover:text-amber-300 hover:underline flex items-center gap-1 transition-colors">
                            Tampilkan Semua &rarr;
                        </a>
                    </div>
                @endif
            @endif
        </div>

        @if($attendances->count() > 0)
        @php
            $stats = [
                'hadir' => $attendances->where('status', 'hadir')->count(),
                'luar_radius' => $attendances->where('status', 'luar_radius')->count(),
                'terlambat' => $attendances->where('status', 'terlambat')->count(),
                'izin' => $attendances->where('status', 'izin')->count(),
                'sakit' => $attendances->where('status', 'sakit')->count(),
            ];
        @endphp
        <div class="stats-bar">
            @foreach(['hadir' => 'Hadir', 'luar_radius' => 'Luar Radius', 'terlambat' => 'Terlambat', 'izin' => 'Izin', 'sakit' => 'Sakit'] as $key => $label)
            <div class="stat-item {{ $key }}">
                <div class="stat-count">{{ $stats[$key] }}</div>
                <div class="stat-label">{{ $label }}</div>
            </div>
            @endforeach
        </div>

        @foreach($attendances as $attendance)
        <div class="attendance-card shadow-sm">
            @if($attendance->attendanceSession?->courseSchedule?->subject)
            <div class="card-subject">
                📚 {{ $attendance->attendanceSession->courseSchedule->subject->name }}
                · Kelas {{ $attendance->attendanceSession->courseSchedule->studyClass->name ?? '-' }}
                · Pertemuan Ke-{{ $attendance->attendanceSession->meeting_number }}
            </div>
            @endif
            <div class="card-top">
                <div class="card-date">
                    <div class="card-status-dot {{ $attendance->status }}"></div>
                    <div>
                        <div class="card-date-text">{{ $attendance->created_at->translatedFormat('l, d M Y') }}</div>
                        <div class="card-time">{{ $attendance->created_at->format('H:i:s') }} WIB</div>
                    </div>
                </div>
                <span class="card-badge {{ $attendance->status }}">
                    {{ str_replace('_', ' ', $attendance->status) }}
                </span>
            </div>
            <div class="card-details">
                @if(in_array($attendance->status, ['hadir', 'luar_radius', 'terlambat']))
                <div class="card-detail-item">
                    <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" /></svg>
                    {{ $attendance->distance_meters }}m
                </div>
                @endif
                @if($attendance->proof_file)
                <a href="{{ asset('storage/' . $attendance->proof_file) }}" target="_blank" class="card-proof-link">📎 Lihat Bukti</a>
                @endif
            </div>
        </div>
        @endforeach

        @else
        <div class="empty-riwayat">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
            <h3>Belum Ada Riwayat</h3>
            <p>Riwayat presensi Anda akan muncul di sini setelah Anda melakukan presensi pertama.</p>
        </div>
        @endif
    </div>
</x-mahasiswa-layout>
