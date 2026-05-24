<x-guest-layout>
    <h2 class="text-center text-2xl font-bold leading-9 tracking-tight text-white mb-2">Verifikasi Email Anda</h2>
    <p class="text-center text-sm text-slate-400 mb-8">Satu langkah lagi untuk mengaktifkan akun Anda</p>

    <div class="mb-6 text-sm text-slate-400 text-center sm:text-left leading-relaxed">
        {{ __('Terima kasih telah mendaftar! Sebelum memulai, silakan verifikasi alamat email Anda dengan mengeklik tautan yang baru saja kami kirimkan ke email Anda. Jika Anda tidak menerimanya, kami akan dengan senang hati mengirimkan ulang.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-6 font-medium text-sm text-green-400 p-4 bg-green-950/40 border border-green-800/60 rounded-lg text-center">
            {{ __('Tautan verifikasi baru telah dikirim ke alamat email yang Anda daftarkan.') }}
        </div>
    @endif

    <div class="mt-8 flex flex-col sm:flex-row items-center justify-between gap-4">
        <form method="POST" action="{{ route('verification.send') }}" class="w-full sm:w-auto">
            @csrf
            <x-primary-button class="w-full sm:w-auto">
                Kirim Ulang Email Verifikasi
            </x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="w-full sm:w-auto text-center sm:text-right">
            @csrf
            <button type="submit" class="underline text-sm text-slate-400 hover:text-amber-400 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition duration-150">
                {{ __('Keluar') }}
            </button>
        </form>
    </div>
</x-guest-layout>
