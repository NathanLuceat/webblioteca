<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-5">
            <x-input-label for="password" :value="__('Senha')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-5">
            <label for="remember_me" class="inline-flex items-center gap-2.5 cursor-pointer">
                <input id="remember_me" type="checkbox" class="checkbox-paper border-line-strong" name="remember">
                <span class="text-sm text-ink-soft">Manter-me conectado</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-6 pt-5 border-t border-line-soft">
            @if (Route::has('password.request'))
                <a class="text-sm text-ink-soft hover:text-leather underline underline-offset-2" href="{{ route('password.request') }}">
                    Esqueci a senha
                </a>
            @endif

            <x-primary-button class="ms-4">
                {{ __('Entrar') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>