<x-layouts::auth.simple>
    <div class="space-y-6">

        <!-- Encabezado de la Tarjeta con Escudo Ampliado -->
        <div class="text-center space-y-3">
            <div class="flex justify-center">
                <img src="{{ asset('img/LOGO CON BODE AZUL.png') }}" alt="The Game Logo" class="w-40 h-40 object-contain drop-shadow-xl">
            </div>
            <div class="space-y-1">
                <h3 class="text-2xl font-bold tracking-tight text-white">
                    Iniciar sesión
                </h3>
                <p class="text-xs text-slate-400 font-normal">
                    Accede a tu cuenta de Recursos Humanos
                </p>
            </div>
        </div>

        <x-auth-session-status class="text-center text-sm text-amber-400" :status="session('status')" />

        <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
            @csrf

            <!-- Correo Electrónico / Usuario -->
            <div class="space-y-1.5">
                <flux:label class="text-slate-300 font-medium text-xs uppercase tracking-wider">{{ __('Usuario o correo electrónico') }}</flux:label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <input
                        name="email"
                        type="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="email"
                        placeholder="Usuario o correo electrónico"
                        class="w-full pl-10 pr-4 py-3 bg-slate-950/70 border border-slate-800 rounded-xl text-white placeholder-slate-500 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all shadow-inner"
                    />
                </div>
            </div>

            <!-- Contraseña -->
            <div class="space-y-1.5">
                <div class="flex items-center justify-between">
                    <flux:label class="text-slate-300 font-medium text-xs uppercase tracking-wider">{{ __('Contraseña') }}</flux:label>
                    @if (Route::has('password.request'))
                        <flux:link class="text-xs font-medium text-blue-400 hover:text-blue-300 transition-colors" :href="route('password.request')" wire:navigate>
                            {{ __('¿Olvidaste tu contraseña?') }}
                        </flux:link>
                    @endif
                </div>

                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <input
                        name="password"
                        type="password"
                        required
                        autocomplete="current-password"
                        placeholder="Ingresa tu contraseña"
                        class="w-full pl-10 pr-4 py-3 bg-slate-950/70 border border-slate-800 rounded-xl text-white placeholder-slate-500 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all shadow-inner"
                    />
                </div>
            </div>

            <!-- Recuérdame -->
            <div class="flex items-center justify-between pt-1">
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded border-slate-700 bg-slate-950 text-blue-600 focus:ring-blue-500/20 w-4 h-4">
                    <span class="text-xs text-slate-300 font-medium">Recordarme</span>
                </label>
            </div>

            <!-- Botón de Acceso -->
            <div class="pt-2">
                <button type="submit" class="w-full py-3.5 px-4 bg-blue-600 hover:bg-blue-500 text-white font-semibold rounded-xl shadow-lg shadow-blue-600/30 transition-all cursor-pointer flex items-center justify-center gap-2">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                    <span>{{ __('Ingresar') }}</span>
                </button>
            </div>
        </form>

        <!-- Pie de tarjeta: Leyenda trasladada -->
        <div class="pt-4 border-t border-slate-800/60 flex items-center justify-center space-x-2 text-[11px] text-slate-400 font-medium tracking-wider uppercase">
            <span>THE GAME</span>
            <span>|</span>
            <span>EVENTOS DEPORTIVOS</span>
            <span>|</span>
            <span>SANTA CRUZ - BOLIVIA</span>
        </div>

    </div>
</x-layouts::auth.simple>
