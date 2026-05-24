<x-app-layout>
    @push('styles')
    <style>
        .attendance-container { max-width: 480px; margin: 0 auto; padding: 32px 20px; }

        .greeting { text-align: center; margin-bottom: 36px; }
        .greeting-emoji { font-size: 2.5rem; margin-bottom: 8px; }
        .greeting-text { font-size: 1.5rem; font-weight: 700; margin: 0 0 4px; color: #fff; letter-spacing: -0.5px; }
        .greeting-date { color: #94a3b8; font-size: 0.9rem; }

        .status-card { background: #1e293b; border: 1px solid #334155; border-radius: 20px; padding: 32px; text-align: center; margin-bottom: 24px; position: relative; overflow: hidden; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.3); }
        .status-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; background: linear-gradient(90deg, #fbbf24, #f59e0b, #fbbf24); }

        /* Session Info Card */
        .session-info { background: rgba(59,130,246,0.08); border: 1px solid rgba(59,130,246,0.15); border-radius: 14px; padding: 16px 18px; margin-bottom: 20px; }
        .session-info-title { font-size: 1rem; font-weight: 700; color: #fff; margin: 0 0 8px; display: flex; align-items: center; gap: 8px; }
        .session-detail { display: flex; flex-wrap: wrap; gap: 6px; }
        .session-tag { background: #0f172a; border: 1px solid #334155; border-radius: 8px; padding: 4px 10px; font-size: 0.75rem; color: #94a3b8; }
        .session-tag strong { color: #fff; }

        .attend-btn { width: 160px; height: 160px; border-radius: 50%; border: none; cursor: pointer; position: relative; background: linear-gradient(135deg, #fbbf24, #f59e0b); color: #0f172a; font-size: 1.15rem; font-weight: 800; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 4px; margin: 28px auto; transition: all 0.3s cubic-bezier(0.4,0,0.2,1); box-shadow: 0 10px 30px rgba(245,158,11,0.25); text-transform: uppercase; letter-spacing: 1px; }
        .attend-btn:hover:not(:disabled) { transform: scale(1.05); box-shadow: 0 12px 35px rgba(245,158,11,0.4); }
        .attend-btn:active:not(:disabled) { transform: scale(0.97); }
        .attend-btn:disabled { opacity: 0.5; cursor: not-allowed; }
        .attend-btn .btn-icon { font-size: 2.2rem; line-height: 1; }
        .attend-btn .btn-text { font-size: 0.85rem; }

        .attend-btn::before { content: ''; position: absolute; inset: -8px; border-radius: 50%; border: 2px solid rgba(251,191,36,0.3); animation: pulse-ring 2s ease-out infinite; }
        .attend-btn::after { content: ''; position: absolute; inset: -16px; border-radius: 50%; border: 1px solid rgba(251,191,36,0.15); animation: pulse-ring 2s ease-out infinite 0.5s; }
        .attend-btn.disabled-btn::before, .attend-btn.disabled-btn::after { animation: none; border-color: rgba(100,116,139,0.2); }
        @keyframes pulse-ring { 0% { transform: scale(1); opacity: 1; } 100% { transform: scale(1.25); opacity: 0; } }

        .attend-btn.loading { pointer-events: none; }
        .attend-btn.loading::before, .attend-btn.loading::after { animation: none; }
        @keyframes spin { to { transform: rotate(360deg); } }
        .spinner { width: 32px; height: 32px; border: 3.5px solid rgba(15,23,42,0.25); border-top-color: #0f172a; border-radius: 50%; animation: spin 0.8s linear infinite; }

        .result-card { border-radius: 16px; padding: 24px; margin-top: 24px; text-align: center; animation: slideUp 0.4s cubic-bezier(0.16,1,0.3,1) forwards; display: none; }
        .result-card.show { display: block; }
        .result-card.success { background: rgba(34,197,94,0.08); border: 1px solid rgba(34,197,94,0.25); color: #4ade80; }
        .result-card.danger { background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.25); color: #f87171; }
        .result-card.warning { background: rgba(245,158,11,0.08); border: 1px solid rgba(245,158,11,0.25); color: #fbbf24; }
        .result-card.info { background: rgba(59,130,246,0.08); border: 1px solid rgba(59,130,246,0.25); color: #60a5fa; }
        .result-icon { font-size: 2.5rem; margin-bottom: 8px; }
        .result-title { font-size: 1.1rem; font-weight: 700; margin: 0 0 4px; }
        .result-message { color: #94a3b8; font-size: 0.85rem; margin: 0; line-height: 1.5; }
        .result-details { display: flex; gap: 16px; justify-content: center; margin-top: 16px; }
        .result-detail-item { background: #0f172a; border: 1px solid #334155; border-radius: 10px; padding: 10px 16px; text-align: center; }
        .result-detail-label { font-size: 0.7rem; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }
        .result-detail-value { font-size: 1rem; font-weight: 700; color: #fff; }
        @keyframes slideUp { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }

        .already-attended { text-align: center; padding: 20px; }
        .already-attended .checkmark { font-size: 4rem; margin-bottom: 12px; animation: bounceIn 0.6s cubic-bezier(0.175,0.885,0.32,1.275) forwards; }
        @keyframes bounceIn { 0% { transform: scale(0); } 65% { transform: scale(1.15); } 100% { transform: scale(1); } }

        .session-closed-banner { background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.2); border-radius: 12px; padding: 14px 18px; margin-bottom: 16px; display: flex; align-items: center; gap: 10px; font-size: 0.85rem; color: #fca5a5; }

        /* History */
        .history-section { margin-top: 36px; }
        .history-title { font-size: 1.05rem; font-weight: 700; margin: 0 0 16px; color: #fff; display: flex; align-items: center; gap: 8px; }
        .history-item { background: #1e293b; border: 1px solid #334155; border-radius: 14px; padding: 14px 18px; margin-bottom: 10px; display: flex; align-items: center; justify-content: space-between; transition: all 0.2s ease; }
        .history-item:hover { border-color: #475569; background: #243147; transform: translateY(-1px); }
        .history-left { display: flex; align-items: center; gap: 12px; }
        .history-status-dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }
        .history-status-dot.hadir { background: #22c55e; box-shadow: 0 0 8px rgba(34,197,94,0.5); }
        .history-status-dot.luar_radius { background: #ef4444; box-shadow: 0 0 8px rgba(239,68,68,0.5); }
        .history-status-dot.terlambat { background: #f59e0b; box-shadow: 0 0 8px rgba(245,158,11,0.5); }
        .history-status-dot.izin { background: #3b82f6; box-shadow: 0 0 8px rgba(59,130,246,0.5); }
        .history-status-dot.sakit { background: #a855f7; box-shadow: 0 0 8px rgba(168,85,247,0.5); }
        .history-date { font-size: 0.85rem; font-weight: 600; color: #fff; }
        .history-sub { font-size: 0.7rem; color: #94a3b8; margin-top: 2px; }
        .history-right { text-align: right; display: flex; flex-direction: column; align-items: flex-end; gap: 4px; }
        .history-badge { font-size: 0.7rem; font-weight: 700; padding: 4px 10px; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.5px; }
        .history-badge.hadir { background: rgba(34,197,94,0.15); color: #4ade80; border: 1px solid rgba(34,197,94,0.2); }
        .history-badge.luar_radius { background: rgba(239,68,68,0.15); color: #f87171; border: 1px solid rgba(239,68,68,0.2); }
        .history-badge.terlambat { background: rgba(245,158,11,0.15); color: #fbbf24; border: 1px solid rgba(245,158,11,0.2); }
        .history-badge.izin { background: rgba(59,130,246,0.15); color: #60a5fa; border: 1px solid rgba(59,130,246,0.2); }
        .history-badge.sakit { background: rgba(168,85,247,0.15); color: #c084fc; border: 1px solid rgba(168,85,247,0.2); }
        .history-distance { font-size: 0.75rem; color: #64748b; }
        .empty-state { text-align: center; padding: 48px 24px; background: #1e293b; border: 1px dashed #334155; border-radius: 20px; color: #64748b; }
        .empty-state h3 { color: #94a3b8; font-size: 1.1rem; font-weight: 700; margin: 12px 0 4px; }
        .empty-state p { font-size: 0.85rem; margin: 0; }

        /* Permit Form */
        .permit-section { margin-top: 28px; }
        .permit-card { background: #1e293b; border: 1px solid #334155; border-radius: 16px; padding: 24px; }
        .permit-title { font-size: 1rem; font-weight: 700; color: #fff; margin: 0 0 16px; display: flex; align-items: center; gap: 8px; }
        .permit-select { width: 100%; padding: 10px 14px; border-radius: 10px; border: 1px solid #334155; background: #0f172a; color: #fff; font-size: 0.85rem; font-family: 'Inter', sans-serif; margin-bottom: 14px; outline: none; transition: border-color 0.2s; }
        .permit-select:focus { border-color: #fbbf24; }
        .permit-file-label { display: block; padding: 20px; border: 2px dashed #334155; border-radius: 12px; text-align: center; cursor: pointer; transition: all 0.2s; margin-bottom: 14px; color: #94a3b8; font-size: 0.85rem; }
        .permit-file-label:hover { border-color: #fbbf24; background: rgba(251,191,36,0.05); }
        .permit-file-label.has-file { border-color: #22c55e; color: #4ade80; background: rgba(34,197,94,0.05); }
        .permit-submit { width: 100%; padding: 12px; border-radius: 10px; border: none; background: linear-gradient(135deg, #3b82f6, #2563eb); color: #fff; font-weight: 700; font-size: 0.9rem; cursor: pointer; transition: all 0.2s; font-family: 'Inter', sans-serif; }
        .permit-submit:hover:not(:disabled) { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(59,130,246,0.3); }
        .permit-submit:disabled { opacity: 0.5; cursor: not-allowed; }
        #permitResult { margin-top: 14px; padding: 12px 16px; border-radius: 10px; font-size: 0.85rem; display: none; }
        #permitResult.show { display: block; }
        #permitResult.success { background: rgba(34,197,94,0.1); border: 1px solid rgba(34,197,94,0.25); color: #4ade80; }
        #permitResult.error { background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.25); color: #f87171; }
    </style>
    @endpush

    <div class="attendance-container">
        <!-- Greeting -->
        <div class="greeting">
            <div class="greeting-emoji">
                @php $hour = now()->format('H'); $emoji = $hour < 12 ? '🌅' : ($hour < 17 ? '☀️' : '🌙'); $greeting = $hour < 12 ? 'Selamat Pagi' : ($hour < 17 ? 'Selamat Siang' : 'Selamat Malam'); @endphp
                {{ $emoji }}
            </div>
            <h1 class="greeting-text">{{ $greeting }}, {{ explode(' ', Auth::user()->name)[0] }}!</h1>
            <p class="greeting-date">{{ now()->translatedFormat('l, d F Y') }}</p>
        </div>

        @if($activeSession)
            <!-- Session Info -->
            <div class="session-info shadow-sm">
                <div class="session-info-title">
                    📚 {{ $activeSession->courseSchedule->subject->name }}
                </div>
                <div class="session-detail">
                    <span class="session-tag">Kelas <strong>{{ $activeSession->courseSchedule->studyClass->name }}</strong></span>
                    <span class="session-tag">Pertemuan <strong>Ke-{{ $activeSession->meeting_number }}</strong></span>
                    <span class="session-tag">Dosen <strong>{{ $activeSession->courseSchedule->dosen->name }}</strong></span>
                    <span class="session-tag">⏰ <strong>{{ $activeSession->start_time->format('H:i') }} - {{ $activeSession->end_time->format('H:i') }}</strong></span>
                    @if($campusLocation)
                    <span class="session-tag">📍 <strong>{{ $campusLocation->name_location }}</strong> ({{ $campusLocation->radius_tolerance }}m)</span>
                    @endif
                </div>
            </div>

            @if(!$isSessionOpen && !$todayAttendance)
            <div class="session-closed-banner">
                <svg class="h-5 w-5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-13a.75.75 0 00-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 000-1.5h-3.25V5z" clip-rule="evenodd" />
                </svg>
                <span>{{ $sessionMessage }}</span>
            </div>
            @endif

            <!-- Status Card -->
            <div class="status-card shadow-xl">
                @if($todayAttendance)
                    <div class="already-attended">
                        <div class="checkmark">
                            @if($todayAttendance->status === 'hadir') 🎉
                            @elseif($todayAttendance->status === 'luar_radius') ❌
                            @elseif($todayAttendance->status === 'izin') 📋
                            @elseif($todayAttendance->status === 'sakit') 🏥
                            @else ⚠️ @endif
                        </div>
                        <h2 class="text-white font-bold text-lg mb-1">
                            @if($todayAttendance->status === 'hadir') Presensi Tercatat!
                            @elseif($todayAttendance->status === 'luar_radius') Di Luar Radius
                            @elseif($todayAttendance->status === 'izin') Izin Tercatat
                            @elseif($todayAttendance->status === 'sakit') Sakit Tercatat
                            @else Status: {{ $todayAttendance->status }} @endif
                        </h2>
                        <p class="text-slate-400 text-sm">Dicatat pada <strong class="text-white">{{ $todayAttendance->created_at->format('H:i:s') }}</strong></p>
                        <div class="result-details" style="margin-top: 20px;">
                            @if(in_array($todayAttendance->status, ['hadir', 'luar_radius', 'terlambat']))
                            <div class="result-detail-item">
                                <div class="result-detail-label">Jarak</div>
                                <div class="result-detail-value">{{ $todayAttendance->distance_meters }}m</div>
                            </div>
                            @endif
                            <div class="result-detail-item">
                                <div class="result-detail-label">Status</div>
                                <div class="result-detail-value mt-1"><span class="history-badge {{ $todayAttendance->status }}">{{ str_replace('_', ' ', $todayAttendance->status) }}</span></div>
                            </div>
                        </div>
                    </div>
                @else
                    <p class="text-slate-400 text-sm mb-1">Tekan tombol untuk presensi</p>
                    <button id="attendBtn" class="attend-btn shadow-lg {{ !$isSessionOpen ? 'disabled-btn' : '' }}" onclick="handleAttendance()" {{ !$isSessionOpen ? 'disabled' : '' }}>
                        <span class="btn-icon">📍</span>
                        <span class="btn-text">Hadir</span>
                    </button>
                    <p class="text-slate-500 text-xs mt-2">
                        @if($isSessionOpen) Pastikan GPS aktif & izinkan akses lokasi
                        @else {{ $sessionMessage }} @endif
                    </p>
                    <div id="resultCard" class="result-card shadow-lg">
                        <div id="resultIcon" class="result-icon"></div>
                        <h3 id="resultTitle" class="result-title"></h3>
                        <p id="resultMessage" class="result-message"></p>
                        <div id="resultDetails" class="result-details" style="display: none;">
                            <div class="result-detail-item"><div class="result-detail-label">Jarak</div><div class="result-detail-value" id="resultDistance">-</div></div>
                            <div class="result-detail-item"><div class="result-detail-label">Radius</div><div class="result-detail-value" id="resultRadius">-</div></div>
                        </div>
                    </div>
                @endif
            </div>

            @if(!$todayAttendance)
            <!-- Permit Section -->
            <div class="permit-section">
                <div class="permit-card shadow-lg">
                    <h3 class="permit-title">
                        <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" /></svg>
                        Ajukan Izin / Sakit
                    </h3>
                    <form id="permitForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="session_id" value="{{ $activeSession->id }}">
                        <select name="type" id="permitType" class="permit-select" required>
                            <option value="" disabled selected>Pilih jenis pengajuan...</option>
                            <option value="izin">📋 Izin</option>
                            <option value="sakit">🏥 Sakit</option>
                        </select>
                        <label for="proofFile" class="permit-file-label" id="fileLabel">
                            <svg class="h-8 w-8 mx-auto mb-2 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" /></svg>
                            <span id="fileLabelText">Upload bukti surat (JPG, PNG, PDF - Max 2MB)</span>
                        </label>
                        <input type="file" name="proof_file" id="proofFile" accept=".jpg,.jpeg,.png,.pdf" style="display: none;" required>
                        <button type="submit" class="permit-submit" id="permitSubmitBtn">Kirim Pengajuan</button>
                    </form>
                    <div id="permitResult"></div>
                </div>
            </div>
            @endif

        @else
            <!-- No Active Session -->
            <div class="empty-state">
                <svg class="h-12 w-12 mx-auto text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <h3>Tidak Ada Sesi Aktif</h3>
                <p>Saat ini tidak ada sesi pertemuan yang dibuka oleh dosen untuk mata kuliah Anda. Silakan tunggu dosen membuka sesi presensi.</p>
            </div>
        @endif

        <!-- History Section -->
        <div class="history-section">
            <h3 class="history-title">
                <svg class="h-5 w-5 text-amber-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-13a.75.75 0 00-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 000-1.5h-3.25V5z" clip-rule="evenodd" /></svg>
                <span>Riwayat Terbaru</span>
            </h3>
            @forelse($recentHistory as $history)
                <div class="history-item shadow-sm">
                    <div class="history-left">
                        <div class="history-status-dot {{ $history->status }}"></div>
                        <div>
                            <div class="history-date">
                                {{ $history->attendanceSession?->courseSchedule?->subject?->name ?? 'N/A' }}
                            </div>
                            <div class="history-sub">
                                {{ $history->created_at->format('d M Y · H:i') }}
                                @if($history->attendanceSession)
                                · Ke-{{ $history->attendanceSession->meeting_number }}
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="history-right">
                        <span class="history-badge {{ $history->status }}">{{ str_replace('_', ' ', $history->status) }}</span>
                        @if(in_array($history->status, ['hadir', 'luar_radius', 'terlambat']))
                        <div class="history-distance">{{ $history->distance_meters }}m</div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="empty-state" style="padding: 28px;">
                    <p>Belum ada riwayat presensi</p>
                </div>
            @endforelse
        </div>
    </div>

    <script>
        function handleAttendance() {
            const btn = document.getElementById('attendBtn');
            btn.classList.add('loading');
            btn.innerHTML = '<div class="spinner"></div><span class="btn-text" style="font-size:0.8rem;margin-top:4px;">Memproses...</span>';

            if (!navigator.geolocation) {
                showResult('danger', '🚫', 'GPS Tidak Didukung', 'Browser Anda tidak mendukung Geolocation API.');
                resetButton(); return;
            }

            navigator.geolocation.getCurrentPosition(
                (pos) => submitAttendance(pos.coords.latitude, pos.coords.longitude),
                (err) => {
                    const msgs = { 1: 'Akses lokasi ditolak.', 2: 'Lokasi tidak tersedia.', 3: 'Timeout.' };
                    showResult('danger', '📡', 'Gagal GPS', msgs[err.code] || 'Error tidak diketahui.');
                    resetButton();
                },
                { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 }
            );
        }

        async function submitAttendance(lat, lng) {
            try {
                const res = await fetch('{{ route("absensi.store") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
                    body: JSON.stringify({ latitude: lat, longitude: lng, session_id: {{ $activeSession?->id ?? 'null' }} }),
                });
                const data = await res.json();
                if (data.success) {
                    showResult('success', '🎉', 'Presensi Berhasil!', data.message);
                    if (data.data) showDetails(data.data.distance + 'm', data.data.radius_tolerance + 'm');
                    setTimeout(() => window.location.reload(), 2500);
                } else {
                    const type = data.status === 'duplicate' ? 'info' : data.status === 'closed' ? 'warning' : 'danger';
                    const icon = data.status === 'duplicate' ? 'ℹ️' : data.status === 'closed' ? '🕐' : '❌';
                    showResult(type, icon, data.status === 'duplicate' ? 'Sudah Presensi' : 'Gagal', data.message);
                    if (data.data) showDetails(data.data.distance + 'm', data.data.radius_tolerance + 'm');
                    if (data.status === 'duplicate') setTimeout(() => window.location.reload(), 2000);
                }
            } catch (e) {
                showResult('danger', '⚠️', 'Kesalahan Jaringan', 'Gagal mengirim data ke server.');
                console.error(e);
            }
            resetButton();
        }

        function showResult(type, icon, title, msg) {
            const c = document.getElementById('resultCard');
            c.className = 'result-card show ' + type;
            document.getElementById('resultIcon').textContent = icon;
            document.getElementById('resultTitle').textContent = title;
            document.getElementById('resultMessage').textContent = msg;
        }
        function showDetails(d, r) {
            document.getElementById('resultDetails').style.display = 'flex';
            document.getElementById('resultDistance').textContent = d;
            document.getElementById('resultRadius').textContent = r;
        }
        function resetButton() {
            const b = document.getElementById('attendBtn');
            if (b) { b.classList.remove('loading'); b.innerHTML = '<span class="btn-icon">📍</span><span class="btn-text">Hadir</span>'; }
        }

        // File upload
        const pf = document.getElementById('proofFile');
        if (pf) pf.addEventListener('change', function() {
            const l = document.getElementById('fileLabel'), t = document.getElementById('fileLabelText');
            if (this.files.length) { l.classList.add('has-file'); t.textContent = '✅ ' + this.files[0].name; }
            else { l.classList.remove('has-file'); t.textContent = 'Upload bukti surat (JPG, PNG, PDF - Max 2MB)'; }
        });

        // Permit form
        const pForm = document.getElementById('permitForm');
        if (pForm) pForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const btn = document.getElementById('permitSubmitBtn'), res = document.getElementById('permitResult');
            const type = document.getElementById('permitType'), file = document.getElementById('proofFile');
            if (!type.value) { res.className = 'show error'; res.style.display = 'block'; res.textContent = 'Pilih jenis pengajuan.'; return; }
            if (!file.files.length) { res.className = 'show error'; res.style.display = 'block'; res.textContent = 'Upload file bukti.'; return; }
            btn.disabled = true; btn.textContent = 'Mengirim...';
            try {
                const fd = new FormData();
                fd.append('type', type.value); fd.append('proof_file', file.files[0]);
                fd.append('session_id', '{{ $activeSession?->id ?? '' }}');
                const r = await fetch('{{ route("absensi.permit") }}', {
                    method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' }, body: fd
                });
                const d = await r.json();
                if (d.success) { res.className = 'show success'; res.style.display = 'block'; res.textContent = d.message; setTimeout(() => window.location.reload(), 2000); }
                else { res.className = 'show error'; res.style.display = 'block'; res.textContent = d.message || 'Gagal mengirim.'; }
            } catch (err) { res.className = 'show error'; res.style.display = 'block'; res.textContent = 'Gagal mengirim data.'; }
            btn.disabled = false; btn.textContent = 'Kirim Pengajuan';
        });
    </script>
</x-app-layout>
