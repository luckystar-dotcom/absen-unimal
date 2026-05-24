<x-guest-layout>
    <h2 class="text-center text-2xl font-bold leading-9 tracking-tight text-white mb-2">Lupa Password</h2>
    <p class="text-center text-sm text-slate-400 mb-8">Masukkan email untuk mendapatkan tautan pemulihan</p>

    <div class="mb-6 text-sm text-slate-400 text-center sm:text-left leading-relaxed">
        {{ __('Lupa password Anda? Tidak masalah. Cukup masukkan email Anda dan kami akan mengirimkan email tautan reset password agar Anda dapat memilih password baru.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-6" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Alamat Email')" />
            <x-text-input id="email" class="block mt-2 w-full" type="email" name="email" :value="old('email')" required autofocus placeholder="nama@email.com" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div>
            <x-primary-button>
                Kirim Tautan Reset Password
            </x-primary-button>
        </div>

        <!-- Back to Login Link -->
        <div class="text-center mt-6">
            <a href="{{ route('login') }}" class="font-semibold text-amber-400 hover:text-amber-300 transition duration-150">Kembali ke Halaman Masuk</a>
        </div>
    </form>
</x-guest-layout>
