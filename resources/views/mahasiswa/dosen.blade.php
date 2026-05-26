<x-mahasiswa-layout pageTitle="Direktori Dosen">

    <!-- ============================================ -->
    <!-- DIREKTORI DOSEN — Daftar Dosen & MK Ampu    -->
    <!-- ============================================ -->
    <div class="p-4 lg:p-6 max-w-6xl mx-auto">

        <!-- Page Header -->
        <div class="mb-6 animate-fade-in-up">
            <p class="text-sm text-surface-400 font-medium">Daftar seluruh dosen beserta mata kuliah yang diampu.</p>
        </div>

        @if($dosenList->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
            @foreach($dosenList as $index => $dosen)
            <div class="group bg-surface-700 border border-surface-600/60 rounded-xl p-5 hover:border-brand-500/30 hover:shadow-lg hover:shadow-brand-900/10 transition-all duration-200 animate-fade-in-up" style="animation-delay: {{ $index * 60 }}ms" id="dosen-card-{{ $dosen->id }}">

                <!-- Dosen Profile -->
                <div class="flex items-start gap-4 mb-4">
                    <!-- Avatar -->
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-brand-400 to-brand-600 flex items-center justify-center text-base font-bold text-white shadow-lg shadow-brand-500/20 flex-shrink-0">
                        {{ strtoupper(substr($dosen->name, 0, 2)) }}
                    </div>
                    <div class="min-w-0">
                        <h4 class="text-sm font-bold text-white leading-snug truncate group-hover:text-brand-200 transition-colors">{{ $dosen->name }}</h4>
                        <p class="text-xs text-surface-400 font-medium mt-0.5">NIP: {{ $dosen->nip_nim }}</p>
                        <p class="text-xs text-surface-400 mt-0.5 truncate">{{ $dosen->email }}</p>
                    </div>
                </div>

                <!-- Divider -->
                <div class="border-t border-surface-600/40 mb-3"></div>

                <!-- MK yang Diampu -->
                <div>
                    <p class="text-[11px] font-semibold text-surface-400 uppercase tracking-wider mb-2">Mata Kuliah Diampu</p>
                    @php
                        $courses = $dosen->courseSchedulesAsDosen->map(function ($schedule) {
                            return $schedule->subject;
                        })->filter()->unique('id');
                    @endphp

                    @if($courses->count() > 0)
                    <div class="flex flex-wrap gap-1.5">
                        @foreach($courses as $subject)
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-surface-800/80 border border-surface-600/40 text-[11px] font-medium text-surface-300">
                            {{ $subject->name }}
                        </span>
                        @endforeach
                    </div>
                    @else
                    <p class="text-xs text-surface-500 italic">Belum ada data mata kuliah.</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-20 bg-surface-700/50 border border-dashed border-surface-600 rounded-2xl">
                <svg class="w-16 h-16 mx-auto text-surface-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <h4 class="text-base font-bold text-surface-300 mb-1">Belum Ada Data Dosen</h4>
                <p class="text-sm text-surface-400">Data dosen belum tersedia.</p>
            </div>
        @endif
    </div>
</x-mahasiswa-layout>
