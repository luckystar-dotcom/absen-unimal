<x-guest-layout>
    <h2 class="text-center text-2xl font-bold leading-9 tracking-tight text-white mb-2">Daftar Akun Baru</h2>
    <p class="text-center text-sm text-slate-400 mb-8">Registrasi untuk Mahasiswa</p>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <!-- NIM -->
        <div>
            <x-input-label for="nip_nim" :value="__('NIM')" />
            <x-text-input id="nip_nim" class="block mt-2 w-full" type="text" name="nip_nim" value="{{ old('nip_nim') }}" required autofocus placeholder="Contoh: 2201020001" />
            <x-input-error :messages="$errors->get('nip_nim')" />
        </div>

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Nama Lengkap')" />
            <x-text-input id="name" class="block mt-2 w-full" type="text" name="name" value="{{ old('name') }}" required autocomplete="name" placeholder="Masukkan nama lengkap Anda" />
            <x-input-error :messages="$errors->get('name')" />
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-2 w-full" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="nama@email.com" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-2 w-full" type="password" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter" />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" />
            <x-text-input id="password_confirmation" class="block mt-2 w-full" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi password baru" />
            <x-input-error :messages="$errors->get('password_confirmation')" />
        </div>

        <!-- Register Button -->
        <div class="pt-2">
            <x-primary-button>
                Daftar Akun
            </x-primary-button>
        </div>

        <!-- Login Link -->
        <div class="text-center mt-6">
            <span class="text-sm text-slate-400">Sudah punya akun?</span>
            <a href="{{ route('login') }}" class="font-semibold text-amber-400 hover:text-amber-300 transition duration-150 ml-1">Masuk di sini</a>
        </div>
    </form>
</x-guest-layout>
