<x-mahasiswa-layout pageTitle="Katalog Mata Kuliah">

    <!-- ============================================ -->
    <!-- KATALOG MATA KULIAH — Dikelompokkan per Semester -->
    <!-- ============================================ -->
    <div class="p-4 lg:p-6 max-w-6xl mx-auto">

        <!-- Page Header -->
        <div class="mb-6 animate-fade-in-up">
            <p class="text-sm text-surface-400 font-medium">Daftar seluruh mata kuliah yang tersedia di program studi.</p>
        </div>

        @if($subjects->count() > 0)
            @foreach($subjects as $semester => $subjectGroup)
            <div class="mb-8 animate-fade-in-up" style="animation-delay: {{ $loop->index * 100 }}ms">
                <!-- Semester Header -->
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-brand-500 to-brand-600 flex items-center justify-center text-sm font-bold text-white shadow-lg shadow-brand-500/20">
                        {{ $semester > 0 ? $semester : '?' }}
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-white">
                            {{ $semester > 0 ? 'Semester ' . $semester : 'Belum Ditentukan' }}
                        </h3>
                        <p class="text-xs text-surface-400">{{ $subjectGroup->count() }} mata kuliah · {{ $subjectGroup->sum('sks') }} SKS</p>
                    </div>
                </div>

                <!-- Subject Cards Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3">
                    @foreach($subjectGroup as $subject)
                    <div class="group bg-surface-700 border border-surface-600/60 rounded-xl p-4 hover:border-brand-500/40 hover:bg-surface-700/80 transition-all duration-200" id="subject-{{ $subject->id }}">
                        <div class="flex items-start justify-between mb-2">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-brand-500/15 text-brand-300 text-[10px] font-bold tracking-wide">
                                {{ $subject->code }}
                            </span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-surface-600 text-surface-300 text-[10px] font-semibold">
                                {{ $subject->sks }} SKS
                            </span>
                        </div>
                        <h4 class="text-sm font-semibold text-white leading-snug group-hover:text-brand-200 transition-colors">{{ $subject->name }}</h4>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        @else
            <!-- Empty State -->
            <div class="text-center py-20 bg-surface-700/50 border border-dashed border-surface-600 rounded-2xl">
                <svg class="w-16 h-16 mx-auto text-surface-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                <h4 class="text-base font-bold text-surface-300 mb-1">Belum Ada Data Mata Kuliah</h4>
                <p class="text-sm text-surface-400">Data mata kuliah belum tersedia.</p>
            </div>
        @endif
    </div>
</x-mahasiswa-layout>
