<x-app-layout>
    @push('styles')
    <style>
        .attendance-container {
            max-width: 480px;
            margin: 0 auto;
            padding: 32px 20px;
        }

        /* Greeting Section */
        .greeting {
            text-align: center;
            margin-bottom: 36px;
        }

        .greeting-emoji {
            font-size: 2.5rem;
            margin-bottom: 8px;
        }

        .greeting-text {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0 0 4px;
            color: #ffffff;
            letter-spacing: -0.5px;
        }

        .greeting-date {
            color: #94a3b8;
            font-size: 0.9rem;
        }

        /* Status Card */
        .status-card {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 20px;
            padding: 32px;
            text-align: center;
            margin-bottom: 24px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.3);
        }

        .status-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #fbbf24, #f59e0b, #fbbf24);
        }

        /* Attendance Button */
        .attend-btn {
            width: 160px;
            height: 160px;
            border-radius: 50%;
            border: none;
            cursor: pointer;
            position: relative;
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            color: #0f172a;
            font-size: 1.15rem;
            font-weight: 800;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 4px;
            margin: 28px auto;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 10px 30px rgba(245, 158, 11, 0.25);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .attend-btn:hover:not(:disabled) {
            transform: scale(1.05);
            box-shadow: 0 12px 35px rgba(245, 158, 11, 0.4);
        }

        .attend-btn:active:not(:disabled) {
            transform: scale(0.97);
        }

        .attend-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .attend-btn .btn-icon {
            font-size: 2.2rem;
            line-height: 1;
        }

        .attend-btn .btn-text {
            font-size: 0.85rem;
        }

        /* Pulse Animation */
        .attend-btn::before {
            content: '';
            position: absolute;
            inset: -8px;
            border-radius: 50%;
            border: 2px solid rgba(251, 191, 36, 0.3);
            animation: pulse-ring-gold 2s ease-out infinite;
        }

        .attend-btn::after {
            content: '';
            position: absolute;
            inset: -16px;
            border-radius: 50%;
            border: 1px solid rgba(251, 191, 36, 0.15);
            animation: pulse-ring-gold 2s ease-out infinite 0.5s;
        }

        @keyframes pulse-ring-gold {
            0% { transform: scale(1); opacity: 1; }
            100% { transform: scale(1.25); opacity: 0; }
        }

        /* Loading State */
        .attend-btn.loading {
            pointer-events: none;
        }

        .attend-btn.loading::before,
        .attend-btn.loading::after {
            animation: none;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .spinner {
            width: 32px;
            height: 32px;
            border: 3.5px solid rgba(15, 23, 42, 0.25);
            border-top-color: #0f172a;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        /* Result Feedback */
        .result-card {
            border-radius: 16px;
            padding: 24px;
            margin-top: 24px;
            text-align: center;
            animation: slideUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            display: none;
        }

        .result-card.show {
            display: block;
        }

        .result-card.success {
            background: rgba(34, 197, 94, 0.08);
            border: 1px solid rgba(34, 197, 94, 0.25);
            color: #4ade80;
        }

        .result-card.danger {
            background: rgba(239, 68, 68, 0.08);
            border: 1px solid rgba(239, 68, 68, 0.25);
            color: #f87171;
        }

        .result-card.warning {
            background: rgba(245, 158, 11, 0.08);
            border: 1px solid rgba(245, 158, 11, 0.25);
            color: #fbbf24;
        }

        .result-card.info {
            background: rgba(59, 130, 246, 0.08);
            border: 1px solid rgba(59, 130, 246, 0.25);
            color: #60a5fa;
        }

        .result-icon {
            font-size: 2.5rem;
            margin-bottom: 8px;
        }

        .result-title {
            font-size: 1.1rem;
            font-weight: 700;
            margin: 0 0 4px;
        }

        .result-message {
            color: #94a3b8;
            font-size: 0.85rem;
            margin: 0;
            line-height: 1.5;
        }

        .result-details {
            display: flex;
            gap: 16px;
            justify-content: center;
            margin-top: 16px;
        }

        .result-detail-item {
            background: #0f172a;
            border: 1px solid #334155;
            border-radius: 10px;
            padding: 10px 16px;
            text-align: center;
        }

        .result-detail-label {
            font-size: 0.7rem;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .result-detail-value {
            font-size: 1rem;
            font-weight: 700;
            color: #fff;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Already Attended State */
        .already-attended {
            text-align: center;
            padding: 20px;
        }

        .already-attended .checkmark {
            font-size: 4rem;
            margin-bottom: 12px;
            animation: bounceIn 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        }

        @keyframes bounceIn {
            0% { transform: scale(0); }
            65% { transform: scale(1.15); }
            100% { transform: scale(1); }
        }

        /* History Section */
        .history-section {
            margin-top: 36px;
        }

        .history-title {
            font-size: 1.05rem;
            font-weight: 700;
            margin: 0 0 16px;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .history-item {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 14px;
            padding: 14px 18px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: all 0.2s ease;
        }

        .history-item:hover {
            border-color: #475569;
            background: #243147;
            transform: translateY(-1px);
        }

        .history-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .history-status-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .history-status-dot.hadir { background: #22c55e; box-shadow: 0 0 8px rgba(34,197,94,0.5); }
        .history-status-dot.luar_radius { background: #ef4444; box-shadow: 0 0 8px rgba(239,68,68,0.5); }
        .history-status-dot.terlambat { background: #f59e0b; box-shadow: 0 0 8px rgba(245,158,11,0.5); }

        .history-date {
            font-size: 0.85rem;
            font-weight: 600;
            color: #fff;
        }

        .history-time {
            font-size: 0.75rem;
            color: #94a3b8;
            margin-top: 2px;
        }

        .history-right {
            text-align: right;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 4px;
        }

        .history-badge {
            font-size: 0.7rem;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-block;
        }

        .history-badge.hadir { background: rgba(34,197,94,0.15); color: #4ade80; border: 1px solid rgba(34,197,94,0.2); }
        .history-badge.luar_radius { background: rgba(239,68,68,0.15); color: #f87171; border: 1px solid rgba(239,68,68,0.2); }
        .history-badge.terlambat { background: rgba(245,158,11,0.15); color: #fbbf24; border: 1px solid rgba(245,158,11,0.2); }

        .history-distance {
            font-size: 0.75rem;
            color: #64748b;
        }

        .empty-history {
            text-align: center;
            padding: 36px;
            background: #1e293b;
            border: 1px dashed #334155;
            border-radius: 16px;
            color: #64748b;
            font-size: 0.9rem;
        }

        /* Location Info */
        .location-info {
            background: rgba(59, 130, 246, 0.08);
            border: 1px solid rgba(59, 130, 246, 0.15);
            border-radius: 14px;
            padding: 14px 18px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 0.85rem;
            color: #cbd5e1;
        }

        .location-info-icon {
            font-size: 1.25rem;
            flex-shrink: 0;
            filter: drop-shadow(0 2px 4px rgba(59, 130, 246, 0.3));
        }
    </style>
    @endpush

    <div class="attendance-container">
        <!-- Greeting -->
        <div class="greeting">
            <div class="greeting-emoji">
                @php
                    $hour = now()->format('H');
                    $emoji = $hour < 12 ? '🌅' : ($hour < 17 ? '☀️' : '🌙');
                    $greeting = $hour < 12 ? 'Selamat Pagi' : ($hour < 17 ? 'Selamat Siang' : 'Selamat Malam');
                @endphp
                {{ $emoji }}
            </div>
            <h1 class="greeting-text">{{ $greeting }}, {{ explode(' ', Auth::user()->name)[0] }}!</h1>
            <p class="greeting-date">{{ now()->translatedFormat('l, d F Y') }}</p>
        </div>

        @if($campusLocation)
        <!-- Location Info -->
        <div class="location-info shadow-sm">
            <span class="location-info-icon">📍</span>
            <div>
                <strong class="text-white">{{ $campusLocation->name_location }}</strong><br>
                <span class="text-slate-400">Radius toleransi: </span><strong class="text-amber-400 font-semibold">{{ $campusLocation->radius_tolerance }}m</strong>
            </div>
        </div>
        @endif

        <!-- Status Card -->
        <div class="status-card shadow-xl">
            @if($todayAttendance)
                <!-- Already Attended -->
                <div class="already-attended">
                    <div class="checkmark">
                        @if($todayAttendance->status === 'hadir')
                            🎉
                        @elseif($todayAttendance->status === 'luar_radius')
                            ❌
                        @else
                            ⚠️
                        @endif
                    </div>
                    <h2 class="text-white font-bold text-lg mb-1 leading-tight">
                        @if($todayAttendance->status === 'hadir')
                            Presensi Tercatat!
                        @elseif($todayAttendance->status === 'luar_radius')
                            Di Luar Radius
                        @else
                            Terlambat
                        @endif
                    </h2>
                    <p class="text-slate-400 text-sm mb-0">
                        Dicatat pada <strong class="text-white">{{ $todayAttendance->created_at->format('H:i:s') }}</strong>
                    </p>
                    <div class="result-details" style="margin-top: 20px;">
                        <div class="result-detail-item">
                            <div class="result-detail-label">Jarak</div>
                            <div class="result-detail-value">{{ $todayAttendance->distance_meters }}m</div>
                        </div>
                        <div class="result-detail-item">
                            <div class="result-detail-label">Status</div>
                            <div class="result-detail-value mt-1">
                                <span class="history-badge {{ $todayAttendance->status }}">
                                    {{ str_replace('_', ' ', $todayAttendance->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Attendance Button -->
                <p class="text-slate-400 text-sm mb-1">
                    Tekan tombol untuk presensi
                </p>

                <button id="attendBtn" class="attend-btn shadow-lg" onclick="handleAttendance()">
                    <span class="btn-icon">📍</span>
                    <span class="btn-text">Hadir</span>
                </button>

                <p class="text-slate-500 text-xs mt-2">
                    Pastikan GPS aktif & izinkan akses lokasi
                </p>

                <!-- Result Feedback -->
                <div id="resultCard" class="result-card shadow-lg">
                    <div id="resultIcon" class="result-icon"></div>
                    <h3 id="resultTitle" class="result-title"></h3>
                    <p id="resultMessage" class="result-message"></p>
                    <div id="resultDetails" class="result-details" style="display: none;">
                        <div class="result-detail-item">
                            <div class="result-detail-label">Jarak</div>
                            <div class="result-detail-value" id="resultDistance">-</div>
                        </div>
                        <div class="result-detail-item">
                            <div class="result-detail-label">Radius</div>
                            <div class="result-detail-value" id="resultRadius">-</div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- History Section -->
        <div class="history-section">
            <h3 class="history-title">
                <svg class="h-5 w-5 text-amber-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-13a.75.75 0 00-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 000-1.5h-3.25V5z" clip-rule="evenodd" />
                </svg>
                <span>Riwayat Presensi</span>
            </h3>

            @forelse($recentHistory as $history)
                <div class="history-item shadow-sm">
                    <div class="history-left">
                        <div class="history-status-dot {{ $history->status }}"></div>
                        <div>
                            <div class="history-date">{{ $history->created_at->format('d M Y') }}</div>
                            <div class="history-time">{{ $history->created_at->format('H:i:s') }}</div>
                        </div>
                    </div>
                    <div class="history-right">
                        <span class="history-badge {{ $history->status }}">
                            {{ str_replace('_', ' ', $history->status) }}
                        </span>
                        <div class="history-distance">{{ $history->distance_meters }}m</div>
                    </div>
                </div>
            @empty
                <div class="empty-history">
                    <p class="flex flex-col items-center gap-2">
                        <svg class="h-8 w-8 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0V9a2 2 0 00-2-2H6a2 2 0 00-2 2v2m16 4h-2a2 2 0 00-2 2v1a2 2 0 01-2 2H8a2 2 0 01-2-2v-1a2 2 0 00-2-2H2" />
                        </svg>
                        <span>Belum ada riwayat presensi</span>
                    </p>
                </div>
            @endforelse
        </div>
    </div>

    <script>
        /**
         * Geolocation Attendance Handler
         * Menggunakan HTML5 Geolocation API untuk menangkap koordinat
         * dan mengirim ke backend via AJAX/Fetch.
         */
        function handleAttendance() {
            const btn = document.getElementById('attendBtn');
            const resultCard = document.getElementById('resultCard');

            // Set loading state
            btn.classList.add('loading');
            btn.innerHTML = '<div class="spinner"></div><span class="btn-text" style="font-size: 0.8rem; margin-top: 4px;">Memproses...</span>';

            // Cek dukungan Geolocation
            if (!navigator.geolocation) {
                showResult('danger', '🚫', 'GPS Tidak Didukung',
                    'Browser Anda tidak mendukung Geolocation API. Gunakan browser modern (Chrome, Firefox, Safari).');
                resetButton();
                return;
            }

            // Request posisi GPS
            navigator.geolocation.getCurrentPosition(
                // Success callback
                function(position) {
                    const latitude = position.coords.latitude;
                    const longitude = position.coords.longitude;

                    // Kirim ke backend via AJAX
                    submitAttendance(latitude, longitude);
                },
                // Error callback
                function(error) {
                    let message = '';
                    switch (error.code) {
                        case error.PERMISSION_DENIED:
                            message = 'Akses lokasi ditolak. Izinkan akses GPS di pengaturan browser Anda.';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            message = 'Informasi lokasi tidak tersedia. Pastikan GPS perangkat aktif.';
                            break;
                        case error.TIMEOUT:
                            message = 'Waktu permintaan lokasi habis. Coba lagi.';
                            break;
                        default:
                            message = 'Terjadi kesalahan tidak diketahui saat mengambil lokasi.';
                    }
                    showResult('danger', '📡', 'Gagal Mendapatkan Lokasi', message);
                    resetButton();
                },
                // Options
                {
                    enableHighAccuracy: true,
                    timeout: 15000,
                    maximumAge: 0
                }
            );
        }

        /**
         * Kirim koordinat ke backend via Fetch API (AJAX)
         */
        async function submitAttendance(latitude, longitude) {
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                const response = await fetch('{{ route("absensi.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        latitude: latitude,
                        longitude: longitude,
                    }),
                });

                const data = await response.json();

                if (data.success) {
                    showResult('success', '🎉', 'Presensi Berhasil!', data.message);
                    if (data.data) {
                        showDetails(data.data.distance + 'm', data.data.radius_tolerance + 'm');
                    }
                    // Reload setelah 2 detik untuk menampilkan state "already attended"
                    setTimeout(() => window.location.reload(), 2500);
                } else {
                    const type = data.status === 'duplicate' ? 'info' : 'danger';
                    const icon = data.status === 'duplicate' ? 'ℹ️' : '❌';
                    showResult(type, icon, data.status === 'duplicate' ? 'Sudah Presensi' : 'Di Luar Radius', data.message);

                    if (data.data) {
                        showDetails(data.data.distance + 'm', data.data.radius_tolerance + 'm');
                    }

                    if (data.status === 'duplicate') {
                        setTimeout(() => window.location.reload(), 2000);
                    }
                }
            } catch (error) {
                showResult('danger', '⚠️', 'Kesalahan Jaringan',
                    'Gagal mengirim data ke server. Periksa koneksi internet Anda dan coba lagi.');
                console.error('Attendance submission error:', error);
            }

            resetButton();
        }

        /**
         * Tampilkan hasil feedback
         */
        function showResult(type, icon, title, message) {
            const card = document.getElementById('resultCard');
            card.className = 'result-card show ' + type;
            document.getElementById('resultIcon').textContent = icon;
            document.getElementById('resultTitle').textContent = title;
            document.getElementById('resultMessage').textContent = message;
        }

        /**
         * Tampilkan detail jarak & radius
         */
        function showDetails(distance, radius) {
            document.getElementById('resultDetails').style.display = 'flex';
            document.getElementById('resultDistance').textContent = distance;
            document.getElementById('resultRadius').textContent = radius;
        }

        /**
         * Reset tombol ke state awal
         */
        function resetButton() {
            const btn = document.getElementById('attendBtn');
            if (btn) {
                btn.classList.remove('loading');
                btn.innerHTML = '<span class="btn-icon">📍</span><span class="btn-text">Hadir</span>';
            }
        }
    </script>
</x-app-layout>
