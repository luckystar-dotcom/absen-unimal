<x-guest-layout>
    <h2 class="text-center text-2xl font-bold leading-9 tracking-tight text-white mb-2">Konfirmasi Password</h2>
    <p class="text-center text-sm text-slate-400 mb-8">Silakan masukkan password untuk melanjutkan transaksi aman</p>

    <div class="mb-6 text-sm text-slate-400 text-center sm:text-left leading-relaxed">
        {{ __('Ini adalah area aplikasi yang aman. Silakan masukkan password Anda untuk mengonfirmasi identitas Anda sebelum melanjutkan.') }}
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
        @csrf

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-2 w-full" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div>
            <x-primary-button>
                Konfirmasi Password
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
