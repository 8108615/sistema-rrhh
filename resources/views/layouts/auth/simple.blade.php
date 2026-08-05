<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-slate-100 antialiased flex flex-col items-center justify-center p-2 sm:p-4 md:p-6">

        <!-- Contenedor principal ampliado -->
        <div class="w-full max-w-7xl bg-white shadow-2xl rounded-2xl sm:rounded-3xl overflow-hidden flex flex-col lg:flex-row min-[82vh]">

            <!-- COLUMNA IZQUIERDA: Portada -->
            <div class="relative lg:w-6/12 hidden lg:flex flex-col justify-between p-12 text-white overflow-hidden bg-slate-900">
                <!-- Imagen de fondo local -->
                <div class="absolute inset-0 z-0">
                    <img src="{{ asset('img/portada.png') }}"
                         alt="Fondo corporativo"
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-950/40 to-slate-950/20"></div>
                </div>

                <div class="relative z-10"></div>

                <!-- Texto corporativo central en la imagen -->
                <div class="relative z-10 space-y-3 max-w-lg">
                    <h2 class="text-3xl sm:text-4xl font-bold tracking-tight text-white drop-shadow-md leading-tight">
                        Gestión de personas, desarrollo de talentos, éxito de tu empresa.
                    </h2>
                </div>

                <!-- Pie de la columna izquierda (Ubicación / Bolivia) -->
                <div class="relative z-10 flex items-center gap-2 text-sm font-medium text-white drop-shadow-md">
                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <span>Bolivia 🇧🇴</span>
                </div>
            </div>

            <!-- COLUMNA DERECHA: Formulario de inicio de sesión -->
            <div class="w-full lg:w-6/12 flex flex-col justify-between p-8 sm:p-14 bg-white">

                <!-- Cabecera de la columna derecha (Selector de Idioma) -->
                <div class="flex justify-end items-center">
                    <button type="button" class="inline-flex items-center gap-1.5 text-xs font-medium text-slate-700 hover:text-slate-900 bg-slate-50 hover:bg-slate-100 border border-slate-200 px-2.5 py-1.5 rounded-lg transition-colors">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>Español</span>
                        <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                </div>

                <!-- Contenido central del formulario -->
                <div class="w-full max-w-lg mx-auto my-auto space-y-6 py-4">
                    {{ $slot }}
                </div>

                <!-- Espaciador inferior interno -->
                <div></div>
            </div>

        </div>

        <!-- Pie de página inferior fuera de la tarjeta blanca -->
        <div class="text-center pt-6 text-xs text-slate-700 font-medium">
            &copy;{{ date('Y') }} THEGAME - Todos los derechos Reservados
        </div>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
