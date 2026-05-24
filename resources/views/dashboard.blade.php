<x-app-layout>
    <div class="flex items-center justify-center min-h-[calc(100vh-64px)] p-6 bg-slate-900">
        <div class="text-center max-w-md w-full">
            <!-- Glowing Gold Star Logo -->
            <div class="flex justify-center mb-6">
                <svg class="h-16 w-16 text-amber-400 filter drop-shadow-[0_0_12px_rgba(251,191,36,0.6)]" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                </svg>
            </div>
            
            <h2 class="text-2xl font-bold text-white mb-2 tracking-tight">Selamat Datang</h2>
            <p class="text-slate-400 mb-8 text-sm">Menghubungkan sesi Anda...</p>

            @php
                $user = Auth::user();
            @endphp

            @if($user->isMahasiswa())
                <a href="{{ route('absensi') }}" class="inline-block px-8 py-3 bg-amber-500 hover:bg-amber-400 text-slate-900 font-bold rounded-xl shadow-lg hover:shadow-amber-500/20 transform hover:-translate-y-0.5 transition duration-150 ease-in-out">
                    Ke Halaman Presensi &rarr;
                </a>
            @else
                <a href="/admin" class="inline-block px-8 py-3 bg-amber-500 hover:bg-amber-400 text-slate-900 font-bold rounded-xl shadow-lg hover:shadow-amber-500/20 transform hover:-translate-y-0.5 transition duration-150 ease-in-out">
                    Ke Dashboard Admin &rarr;
                </a>
            @endif
        </div>
    </div>

    <script>
        // Auto redirect based on role
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                @if($user->isMahasiswa())
                    window.location.href = '{{ route("absensi") }}';
                @else
                    window.location.href = '/admin';
                @endif
            }, 1000);
        });
    </script>
</x-app-layout>
