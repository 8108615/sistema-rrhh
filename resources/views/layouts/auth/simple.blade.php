<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-slate-950 text-slate-100 antialiased flex flex-col justify-between overflow-x-hidden font-sans">

        <!-- CONTENEDOR DE PANTALLA COMPLETA CON FONDO DE ESTADIO -->
        <div class="relative min-h-screen w-full flex items-center justify-center overflow-hidden">

            <!-- IMAGEN DE FONDO (FONDO.png) CON VISIBILIDAD OPTIMIZADA -->
            <div class="absolute inset-0 z-0">
                <img src="{{ asset('img/FONDO.png') }}"
                     alt="Fondo Estadio The Game"
                     class="w-full h-full object-cover opacity-85 scale-105">
                <!-- Overlay suave para mantener legibilidad sin oscurecer la imagen -->
                <div class="absolute inset-0 bg-gradient-to-r from-slate-950/70 via-slate-950/40 to-slate-950/70 backdrop-brightness-90"></div>
            </div>

            <!-- CONTENEDOR PRINCIPAL DE 2 COLUMNAS -->
            <div class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 min-h-screen flex flex-col justify-between py-6">

                <!-- ESPACIADOR SUPERIOR -->
                <div></div>

                <!-- CUERPO PRINCIPAL (IZQ: BRANDING | DER: FORMULARIO) -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-16 items-center my-auto">

                    <!-- COLUMNA IZQUIERDA: BRANDING THE GAME (Centrada) -->
                    <div class="lg:col-span-7 flex flex-col items-center text-center space-y-8 px-2">
                        <div class="space-y-6 w-full flex flex-col items-center">

                            <!-- Logo Principal Centrado -->
                            <div class="flex justify-center w-full">
                                <img src="{{ asset('img/LOGO CON BORDE BLANCO.png') }}" alt="The Game Logo" class="w-64 h-64 object-contain drop-shadow-[0_10px_20px_rgba(0,0,0,0.8)]">
                            </div>

                            <!-- Títulos Centrados con sombra -->
                            <div class="space-y-2 text-center w-full">
                                <h1 class="text-4xl sm:text-5xl font-extrabold tracking-tight text-white leading-tight drop-shadow-lg">
                                    Sistema de Recursos <span class="text-blue-500">Humanos</span>
                                </h1>
                                <p class="text-base sm:text-lg text-slate-200 font-medium max-w-lg mx-auto pt-2 drop-shadow">
                                    Somos una empresa líder en el mercado boliviano en la producción de eventos deportivos.
                                </p>
                            </div>

                            <!-- Íconos inferiores informativos centrados -->
                            <div class="grid grid-cols-3 gap-4 pt-6 max-w-lg w-full border-t border-slate-700/60 justify-items-center">
                                <div class="flex flex-col items-center space-y-1">
                                    <div class="p-2 rounded-lg bg-slate-900/80 border border-slate-700/50 text-blue-400 shadow-md">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    </div>
                                    <span class="text-xs font-semibold text-slate-200 drop-shadow">Nuestro Equipo</span>
                                </div>
                                <div class="flex flex-col items-center space-y-1">
                                    <div class="p-2 rounded-lg bg-slate-900/80 border border-slate-700/50 text-blue-400 shadow-md">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                    <span class="text-xs font-semibold text-slate-200 drop-shadow">Grandes Eventos</span>
                                </div>
                                <div class="flex flex-col items-center space-y-1">
                                    <div class="p-2 rounded-lg bg-slate-900/80 border border-slate-700/50 text-blue-400 shadow-md">
                                        <!-- Ícono de Pelota de Fútbol -->
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <circle cx="12" cy="12" r="9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 3v6l4.5 3.5-1.5 5.5h-6l-1.5-5.5L12 9" />
                                            <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 9l5-2M12 9l-5-2M16.5 12.5L21 14M7.5 12.5L3 14M15 18l3 3M9 18l-3 3" />
                                        </svg>
                                    </div>
                                    <span class="text-xs font-semibold text-slate-200 drop-shadow">El Deporte Nos Une</span>
                                </div>
                            </div>

                            <!-- Ubicación Santa Cruz centrada -->
                            <div class="flex items-center justify-center space-x-2 text-sm text-blue-400 font-semibold pt-2 w-full drop-shadow">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                <span>Santa Cruz de la Sierra, Bolivia</span>
                            </div>
                        </div>
                    </div>

                    <!-- COLUMNA DERECHA: TARJETA DE LOGIN OSCURA (GLASSMORPHISM) -->
                    <div class="lg:col-span-5 w-full">
                        <div class="bg-slate-950/85 backdrop-blur-xl border border-slate-800/80 rounded-3xl p-8 sm:p-10 shadow-[0_20px_50px_rgba(0,0,0,0.9)] space-y-6">

                            <!-- FORMULARIO DE LOGIN -->
                            {{ $slot }}

                        </div>
                    </div>

                </div>

                <!-- FOOTER INFERIOR CON COPYRIGHT Y AUTOR -->
                <div class="pt-6 pb-2 text-xs text-slate-400 font-medium tracking-wider flex flex-col sm:flex-row items-center justify-between border-t border-slate-800/60 drop-shadow">
                    <span>&copy; {{ date('Y') }} THE GAME - Todos los derechos reservados.</span>
                    <span>Desarrollado por: ERICK FERNANDO MORALES GIL</span>
                </div>

            </div>
        </div>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
