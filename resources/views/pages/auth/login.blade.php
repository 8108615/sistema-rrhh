<x-layouts::auth.simple>
    <div class="space-y-6">

        <div class="text-center space-y-3">
            <div class="flex justify-center">
                <img src="{{ asset('img/logo_thegame.png') }}" alt="The Game Logo" class="w-62 h-62 object-contain drop-shadow-sm">
            </div>
            <div class="space-y-1">
                <h1 class="text-3xl font-bold tracking-tight text-slate-900">
                    Iniciar sesión
                </h1>
                <p class="text-lg font-semibold text-slate-950">
                    Sistema de Recursos Humanos
                </p>
            </div>
        </div>

        <div class="relative flex py-2 items-center">
            <div class="flex-grow border-t border-slate-900"></div>
            <span class="flex-shrink mx-4 text-slate-900">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            </span>
            <div class="flex-grow border-t border-slate-900"></div>
        </div>

        <x-auth-session-status class="text-center text-sm" :status="session('status')" />

        <form method="POST" action="{{ route('login.store') }}" class="space-y-4">
            @csrf

            <!-- Correo Electrónico -->
            <div class="space-y-1">
                <flux:label class="text-slate-950 font-semibold text-sm">{{ __('Correo electrónico') }}</flux:label>

                <div class="relative flex items-center w-full rounded-xl border-3 border-slate-700 bg-white shadow-sm">
                    <div class="pl-3.5 flex items-center pointer-events-none text-slate-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="email"
                        placeholder="ejemplo@thegame.bo"
                        class="w-full bg-transparent border-0 px-3 py-2.5 text-slate-900 placeholder-slate-700 focus:outline-none focus:ring-0 text-sm"
                    />
                </div>
            </div>

            <!-- Contraseña -->
            <div class="space-y-1">
                <div class="flex items-center justify-between">
                    <flux:label class="text-slate-950 font-semibold text-sm">{{ __('Contraseña') }}</flux:label>
                    @if (Route::has('password.request'))
                        <flux:link class="text-xs font-medium text-blue-600 hover:text-blue-800" :href="route('password.request')" wire:navigate>
                            {{ __('¿Olvidaste tu contraseña?') }}
                        </flux:link>
                    @endif
                </div>

                <div class="relative flex items-center w-full rounded-xl border-3 border-slate-700 bg-white shadow-sm">
                    <div class="pl-3.5 flex items-center pointer-events-none text-slate-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <input
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        placeholder="Ingresa tu contraseña"
                        class="w-full bg-transparent border-0 px-3 py-2.5 text-slate-900 placeholder-slate-700 focus:outline-none focus:ring-0 text-sm"
                    />
                </div>
            </div>

            <!-- Recuérdame -->
            <div class="flex items-center justify-between pt-1">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input
                        type="checkbox"
                        name="remember"
                        value="1"
                        {{ old('remember') ? 'checked' : '' }}
                        class="w-4 h-4 rounded border-2 border-slate-700 text-slate-900 focus:ring-slate-900 focus:ring-offset-0 cursor-pointer"
                    />
                    <span class="text-xs font-medium text-blue-600 hover:text-blue-800">
                        {{ __('Recordarme') }}
                    </span>
                </label>
            </div>

            <!-- Botón de Acceso -->
            <div class="pt-2">
                <button type="submit" style="background-color: #0e2a4a !important; color: #ffffff !important;" class="w-full py-3 px-4 font-semibold rounded-xl shadow-lg transition-all cursor-pointer hover:opacity-90 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                    <span>{{ __('Iniciar sesión') }}</span>
                </button>
            </div>
        </form>
    </div>
</x-layouts::auth.simple>
