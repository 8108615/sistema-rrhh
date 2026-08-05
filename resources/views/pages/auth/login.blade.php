<x-layouts::auth.simple>
    <div class="space-y-6">

        <div class="text-center space-y-3">
            <div class="flex justify-center">
                <img src="{{ asset('img/logo_thegame.png') }}" alt="The Game Logo" class="w-36 h-36 object-contain drop-shadow-sm">
            </div>
            <div class="space-y-1">
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                    Iniciar sesión
                </h1>
                <p class="text-sm font-normal text-slate-600">
                    Sistema de Recursos Humanos
                </p>
            </div>
        </div>

        <div class="relative flex py-2 items-center">
            <div class="flex-grow border-t border-slate-200"></div>
            <span class="flex-shrink mx-4 text-slate-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            </span>
            <div class="flex-grow border-t border-slate-200"></div>
        </div>

        <x-auth-session-status class="text-center text-sm" :status="session('status')" />

        <form method="POST" action="{{ route('login.store') }}" class="space-y-4">
            @csrf

            <div class="space-y-1">
                <flux:label class="text-slate-950 font-semibold text-sm">{{ __('Correo electrónico') }}</flux:label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-700 z-10">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <flux:input
                        name="email"
                        class="!border-slate-700 !border text-slate-900 w-full pl-10"
                        :value="old('email')"
                        type="email"
                        required
                        autofocus
                        autocomplete="email"
                        placeholder="ejemplo@thegame.bo"
                    />
                </div>
            </div>

            <div class="space-y-1">
                <div class="flex items-center justify-between">
                    <flux:label class="text-slate-950 font-semibold text-sm">{{ __('Contraseña') }}</flux:label>
                    @if (Route::has('password.request'))
                        <flux:link class="text-xs font-medium text-blue-600 hover:text-blue-800" :href="route('password.request')" wire:navigate>
                            {{ __('¿Olvidaste tu contraseña?') }}
                        </flux:link>
                    @endif
                </div>

                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-700 z-10">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <flux:input
                        name="password"
                        class="!border-slate-800 !border text-slate-900 w-full pl-10"
                        type="password"
                        required
                        autocomplete="current-password"
                        :placeholder="__('Ingresa tu contraseña')"
                        viewable
                    />
                </div>
            </div>

            <div class="flex items-center justify-between pt-1">
                <flux:checkbox name="remember" class="text-slate-950 font-medium" :label="__('Recordarme')" :checked="old('remember')" />
            </div>

            <div class="pt-2">
                <button type="submit" style="background-color: #0e2a4a !important; color: #ffffff !important;" class="w-full py-3 px-4 font-semibold rounded-xl shadow-lg transition-all cursor-pointer hover:opacity-90 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                    <span>{{ __('Iniciar sesión') }}</span>
                </button>
            </div>
        </form>
    </div>
</x-layouts::auth.simple>
