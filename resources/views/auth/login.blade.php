<x-guest-layout>
    <style>
        /* Spinner */
        .submit-spinner {
            display: inline-block;
            width: 18px;
            height: 18px;
            border: 2.5px solid rgba(15, 23, 42, 0.3);
            border-radius: 50%;
            border-top-color: #0f172a;
            animation: spin 0.8s linear infinite;
            margin-right: 8px;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>

    <h2 class="text-center text-2xl font-bold leading-9 tracking-tight text-white mb-6">Masuk ke akun Anda</h2>

    <!-- Session Status -->
    @if (session('status'))
        <div class="mb-6 rounded-lg bg-green-950/40 p-4 text-sm text-green-400 border border-green-800/60 flex items-center gap-2">
            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
            </svg>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    <form class="space-y-6" action="{{ route('login') }}" method="POST">
        @csrf

        <!-- Email / NIM -->
        <div>
            <x-input-label for="login" :value="__('Email / NIM')" />
            <x-text-input id="login" name="login" type="text" value="{{ old('login') }}" required autofocus autocomplete="username" class="block mt-2 w-full" placeholder="Masukkan email atau NIM Anda" />
            <x-input-error :messages="$errors->get('login')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between">
                <x-input-label for="password" :value="__('Password')" />
                @if (Route::has('password.request'))
                    <div class="text-sm leading-6">
                        <a href="{{ route('password.request') }}" class="font-semibold text-amber-400 hover:text-amber-300 transition duration-150">Lupa password?</a>
                    </div>
                @endif
            </div>
            <div class="mt-2 relative rounded-md shadow-sm">
                <x-text-input id="password" name="password" type="password" required autocomplete="current-password" class="block w-full pr-10" placeholder="••••••••" />
                <button type="button" id="toggle-password" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-200">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 eye-open">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 eye-closed hidden">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 rounded border-slate-700 bg-slate-900 text-amber-500 focus:ring-amber-500">
            <label for="remember_me" class="ml-3 block text-sm leading-6 text-slate-300">Ingat saya</label>
        </div>

        <!-- Sign In Button -->
        <div>
            <x-primary-button id="submit-button" class="w-full">
                Masuk
            </x-primary-button>
        </div>

        <!-- Register Link -->
        <div class="text-center mt-6">
            <span class="text-sm text-slate-400">Belum punya akun?</span>
            <a href="{{ route('register') }}" class="font-semibold text-amber-400 hover:text-amber-300 transition duration-150 ml-1">Daftar gratis di sini</a>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Password Show/Hide Toggle
            const togglePasswordBtn = document.getElementById('toggle-password');
            const passwordInput = document.getElementById('password');
            const eyeOpenIcon = togglePasswordBtn.querySelector('.eye-open');
            const eyeClosedIcon = togglePasswordBtn.querySelector('.eye-closed');
            
            togglePasswordBtn.addEventListener('click', function() {
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    eyeOpenIcon.classList.add('hidden');
                    eyeClosedIcon.classList.remove('hidden');
                } else {
                    passwordInput.type = 'password';
                    eyeOpenIcon.classList.remove('hidden');
                    eyeClosedIcon.classList.add('hidden');
                }
            });
            
            // Dynamic Form Submit Indicator
            const loginForm = document.querySelector('form');
            const submitBtn = document.getElementById('submit-button');
            const btnText = submitBtn.querySelector('span');
            
            loginForm.addEventListener('submit', function() {
                // Disable button to prevent double-submit
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
                submitBtn.classList.remove('hover:bg-amber-400');
                
                // Add loader spinner before text
                const loader = document.createElement('span');
                loader.className = 'submit-spinner';
                submitBtn.insertBefore(loader, btnText);
                
                btnText.textContent = 'Menghubungkan...';
            });
        });
    </script>
</x-guest-layout>
