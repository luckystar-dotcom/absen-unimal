<x-mahasiswa-layout pageTitle="Dasbor KRS">
    @push('styles')
    <style>
        /* Mini Calendar Styles */
        .mini-calendar { font-size: 0.75rem; }
        .mini-calendar .cal-header { font-weight: 700; color: #e2e8f0; }
        .mini-calendar .cal-day-name { color: #64748b; font-weight: 600; font-size: 0.65rem; text-transform: uppercase; }
        .mini-calendar .cal-day { width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #94a3b8; transition: all 0.15s; cursor: default; }
        .mini-calendar .cal-day.today { background: linear-gradient(135deg, #3b82f6, #2563eb); color: #fff; font-weight: 700; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3); }
        .mini-calendar .cal-day.other-month { color: #334155; }

        /* Timeline styles */
        .timeline-item::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 36px;
            bottom: -12px;
            width: 2px;
            background: #1e293b;
        }
        .timeline-item:last-child::before { display: none; }

        /* Card hover animation */
        .krs-card {
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .krs-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 28px -8px rgba(0, 0, 0, 0.4);
            border-color: #3b82f6;
        }
    </style>
    @endpush

    <!-- ============================================ -->
    <!-- CONTENT AREA: Grid Tengah + Panel Kanan      -->
    <!-- ============================================ -->
    <div class="p-4 lg:p-6">
        <div class="flex flex-col xl:flex-row gap-6">

            <!-- ================================ -->
            <!-- KOLOM TENGAH (Main Content ~70%) -->
            <!-- ================================ -->
            <div class="flex-1 min-w-0 space-y-6">

                <!-- Welcome Banner -->
                <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-brand-600 via-brand-700 to-brand-800 p-6 lg:p-8 shadow-xl shadow-brand-900/20 animate-fade-in-up" id="welcome-banner">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/4"></div>
                    <div class="absolute bottom-0 left-0 w-40 h-40 bg-white/5 rounded-full translate-y-1/2 -translate-x-1/4"></div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-2xl" id="greeting-emoji">
                                🌅
                            </span>
                        </div>
                        <h2 class="text-xl lg:text-2xl font-extrabold text-white tracking-tight mb-1">
                            <span id="greeting-text">Selamat Pagi</span>, {{ explode(' ', $user->name)[0] }}!
                        </h2>
                        <p class="text-brand-200 text-sm font-medium" id="current-date"></p>
                        <div class="flex flex-wrap items-center gap-3 mt-4">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white/15 backdrop-blur-sm text-white text-xs font-semibold">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                {{ $krs->count() }} Mata Kuliah
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white/15 backdrop-blur-sm text-white text-xs font-semibold">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                {{ $totalSks }} SKS
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white/15 backdrop-blur-sm text-white text-xs font-semibold">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                {{ $jadwalHariIni->count() }} Kelas Hari Ini
                            </span>
                        </div>
                    </div>
                </div>

                <!-- KRS Grid Section -->
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-base font-bold text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-brand-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            Kartu Rencana Studi (KRS)
                        </h3>
                        <span class="text-xs font-medium text-surface-400">Semester Berjalan</span>
                    </div>

                    @if($krs->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 2xl:grid-cols-3 gap-4" id="krs-grid">
                        @foreach($krs as $index => $schedule)
                        <div class="krs-card bg-surface-700 border border-surface-600/60 rounded-xl p-5 relative overflow-hidden animate-fade-in-up" style="animation-delay: {{ $index * 80 }}ms" id="krs-card-{{ $schedule->id }}">
                            <!-- Top accent line -->
                            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-brand-500 to-brand-400 rounded-t-xl"></div>

                            <!-- Subject Code Badge -->
                            <div class="flex items-start justify-between mb-3">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-brand-500/15 text-brand-300 text-[11px] font-bold tracking-wide">
                                    {{ $schedule->subject->code ?? 'N/A' }}
                                </span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-surface-600 text-surface-300 text-[11px] font-semibold">
                                    {{ $schedule->subject->sks ?? 0 }} SKS
                                </span>
                            </div>

                            <!-- Subject Name -->
                            <h4 class="text-sm font-bold text-white mb-3 leading-snug">{{ $schedule->subject->name ?? 'Mata Kuliah' }}</h4>

                            <!-- Info Grid -->
                            <div class="space-y-2 mb-4">
                                <div class="flex items-center gap-2 text-xs text-surface-300">
                                    <svg class="w-3.5 h-3.5 text-surface-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    <span class="truncate">{{ $schedule->dosen->name ?? 'Belum ditentukan' }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-xs text-surface-300">
                                    <svg class="w-3.5 h-3.5 text-surface-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    <span>Kelas {{ $schedule->studyClass->name ?? '-' }}</span>
                                </div>
                                @if($schedule->day_of_week)
                                <div class="flex items-center gap-2 text-xs text-surface-300">
                                    <svg class="w-3.5 h-3.5 text-surface-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span>{{ $schedule->day_of_week }}{{ $schedule->start_time && $schedule->end_time ? ', ' . \Carbon\Carbon::parse($schedule->start_time)->format('H:i') . ' - ' . \Carbon\Carbon::parse($schedule->end_time)->format('H:i') : '' }}</span>
                                </div>
                                @endif
                            </div>

                            <!-- Action Button -->
                            <a href="{{ route('absensi', ['schedule_id' => $schedule->id]) }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg bg-gradient-to-r from-brand-500 to-brand-600 text-white text-xs font-bold shadow-lg shadow-brand-500/20 hover:shadow-brand-500/40 hover:from-brand-400 hover:to-brand-500 transition-all duration-200 active:scale-[0.98]" id="btn-absen-{{ $schedule->id }}">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Masuk Kelas & Absen
                            </a>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <!-- Empty KRS state -->
                    <div class="text-center py-16 bg-surface-700/50 border border-dashed border-surface-600 rounded-2xl">
                        <svg class="w-16 h-16 mx-auto text-surface-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        <h4 class="text-base font-bold text-surface-300 mb-1">Belum Ada KRS</h4>
                        <p class="text-sm text-surface-400">KRS Anda belum diisi oleh Admin. Silakan hubungi bagian akademik.</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- ================================ -->
            <!-- KOLOM KANAN (Right Panel ~30%)   -->
            <!-- ================================ -->
            <div class="w-full xl:w-[340px] flex-shrink-0 space-y-6 hidden xl:block">

                <!-- Mini Calendar -->
                <div class="bg-surface-700 border border-surface-600/60 rounded-xl p-5 animate-fade-in-up" id="mini-calendar-panel" style="animation-delay: 100ms">
                    <h4 class="text-sm font-bold text-white mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-brand-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Kalender
                    </h4>
                    <div class="mini-calendar" id="miniCalendar"></div>
                </div>

                <!-- Jadwal Hari Ini -->
                <div class="bg-surface-700 border border-surface-600/60 rounded-xl p-5 animate-fade-in-up" id="today-schedule-panel" style="animation-delay: 200ms">
                    <h4 class="text-sm font-bold text-white mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-brand-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Jadwal Hari Ini
                        <span class="ml-auto text-[11px] font-semibold text-surface-400">{{ $hariIni }}</span>
                    </h4>

                    @if($jadwalHariIni->count() > 0)
                    <div class="space-y-3">
                        @foreach($jadwalHariIni as $jadwal)
                        <div class="timeline-item relative pl-10">
                            <!-- Timeline dot -->
                            <div class="absolute left-2 top-1 w-[10px] h-[10px] rounded-full bg-brand-500 ring-4 ring-brand-500/20 z-10"></div>

                            <div class="bg-surface-800/60 border border-surface-600/40 rounded-lg p-3">
                                <p class="text-xs font-bold text-white leading-snug mb-1">{{ $jadwal->subject->name ?? 'MK' }}</p>
                                <div class="flex items-center gap-2 flex-wrap">
                                    @if($jadwal->start_time && $jadwal->end_time)
                                    <span class="text-[11px] text-brand-300 font-semibold">
                                        {{ \Carbon\Carbon::parse($jadwal->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->end_time)->format('H:i') }}
                                    </span>
                                    @endif
                                    <span class="text-[11px] text-surface-400">· Kelas {{ $jadwal->studyClass->name ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-8">
                        <svg class="w-10 h-10 mx-auto text-surface-500 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4"/></svg>
                        <p class="text-xs text-surface-400 font-medium">Tidak ada kelas hari ini</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // ===== MINI CALENDAR =====
        (function() {
            const container = document.getElementById('miniCalendar');
            if (!container) return;

            const today = new Date();
            const year = today.getFullYear();
            const month = today.getMonth();
            const dayNames = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
            const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

            // Header
            let html = `<div class="flex items-center justify-between mb-3">
                <span class="cal-header text-sm">${monthNames[month]} ${year}</span>
            </div>`;

            // Day names
            html += '<div class="grid grid-cols-7 gap-0 mb-1">';
            dayNames.forEach(d => {
                html += `<div class="cal-day-name text-center py-1">${d}</div>`;
            });
            html += '</div>';

            // Days
            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            const daysInPrevMonth = new Date(year, month, 0).getDate();

            html += '<div class="grid grid-cols-7 gap-0.5">';

            // Previous month trailing days
            for (let i = firstDay - 1; i >= 0; i--) {
                html += `<div class="cal-day other-month">${daysInPrevMonth - i}</div>`;
            }

            // Current month days
            for (let d = 1; d <= daysInMonth; d++) {
                const isToday = d === today.getDate() && month === today.getMonth() && year === today.getFullYear();
                html += `<div class="cal-day ${isToday ? 'today' : ''}">${d}</div>`;
            }

            // Next month leading days
            const totalCells = firstDay + daysInMonth;
            const remaining = totalCells % 7 === 0 ? 0 : 7 - (totalCells % 7);
            for (let i = 1; i <= remaining; i++) {
                html += `<div class="cal-day other-month">${i}</div>`;
            }

            html += '</div>';
            container.innerHTML = html;
        })();

        // ===== REAL-TIME GREETING & DATE =====
        (function() {
            function updateGreetingAndDate() {
                const now = new Date();
                const hours = now.getHours();
                
                let greeting = 'Selamat Malam';
                let emoji = '🌙';
                
                if (hours >= 5 && hours < 12) {
                    greeting = 'Selamat Pagi';
                    emoji = '🌅';
                } else if (hours >= 12 && hours < 15) {
                    greeting = 'Selamat Siang';
                    emoji = '☀️';
                } else if (hours >= 15 && hours < 18) {
                    greeting = 'Selamat Sore';
                    emoji = '🌇';
                }

                const greetingTextEl = document.getElementById('greeting-text');
                const greetingEmojiEl = document.getElementById('greeting-emoji');
                if (greetingTextEl) greetingTextEl.textContent = greeting;
                if (greetingEmojiEl) greetingEmojiEl.textContent = emoji;

                // Format Indonesian Date: e.g. "Senin, 27 Mei 2026"
                const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                
                const dayName = days[now.getDay()];
                const dateNum = now.getDate();
                const monthName = months[now.getMonth()];
                const year = now.getFullYear();
                
                const formattedDate = `${dayName}, ${dateNum} ${monthName} ${year}`;
                const dateEl = document.getElementById('current-date');
                if (dateEl) dateEl.textContent = formattedDate;
            }
            
            updateGreetingAndDate();
            setInterval(updateGreetingAndDate, 60000);
        })();
    </script>
    @endpush
</x-mahasiswa-layout>
