<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-slate-100 antialiased flex flex-col items-center justify-center p-2 sm:p-3">

        <!-- Contenedor principal ampliado a pantalla completa -->
        <div class="w-full h-[96vh] max-w-[98vw] bg-white shadow-2xl rounded-2xl sm:rounded-3xl overflow-hidden flex flex-col lg:flex-row">

            <!-- COLUMNA IZQUIERDA: Portada -->
            <div class="relative lg:w-7/12 hidden lg:flex flex-col justify-between p-12 text-white overflow-hidden bg-slate-900">
                <!-- Imagen de fondo local -->
                <div class="absolute inset-0 z-0">
                    <img src="{{ asset('img/portada.png') }}"
                         alt="Fondo corporativo"
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-950/40 to-slate-950/20"></div>
                </div>

                <div class="relative z-10"></div>

                <!-- Texto corporativo central en la imagen -->
                <div class="relative z-10 space-y-3 max-w-lg text-center mx-auto">
                    <h2 class="text-3xl sm:text-4xl font-bold tracking-tight text-white drop-shadow-md leading-tight">
                        Bienvenido a The Game
                    </h2>
                </div>

                <!-- Pie de la columna izquierda (Ubicación / Bolivia) -->
                <div class="relative z-10 flex items-center gap-2 text-sm font-medium text-white drop-shadow-md">
                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <span>Bolivia 🇧🇴</span>
                </div>

            </div>


            <!-- COLUMNA DERECHA: Formulario de inicio de sesión -->
            <div class="w-full lg:w-5/12 flex flex-col justify-between p-8 sm:p-14 bg-white overflow-y-auto">

                <!-- Contenido central del formulario -->
                <div class="w-full max-w-lg mx-auto my-auto space-y-6 py-4">
                    {{ $slot }}
                </div>

                <!-- Espaciador inferior interno -->
                <div></div>
                <br>
                <!-- Pie de página inferior fuera de la tarjeta blanca -->
                <div class="text-center pt-6 text-xs text-slate-700 font-medium">
                    &copy;{{ date('Y') }} THEGAME - Todos los derechos Reservados
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
