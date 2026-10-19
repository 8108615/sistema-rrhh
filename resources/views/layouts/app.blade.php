<x-layouts::app.sidebar :title="$title ?? null">
    <flux:main class="flex flex-col min-h-[calc(100vh-3rem)] justify-between">
        <div>
            {{ $slot }}
        </div>

        <!-- FOOTER GLOBAL SIEMPRE EN LA PARTE INFERIOR DE LA PANTALLA -->
        <footer class="mt-auto pt-6 pb-2 border-t border-zinc-200 dark:border-zinc-700/60 text-xs text-zinc-500 dark:text-zinc-400 font-medium flex flex-col sm:flex-row items-center justify-between gap-2 w-full">
            <span>&copy; {{ date('Y') }} THE GAME - Todos los derechos reservados.</span>
            <span>Desarrollado por: <strong class="text-zinc-700 dark:text-zinc-200">ERICK FERNANDO MORALES GIL</strong></span>
        </footer>

        {{-- 1. Alerta para errores de validación de formulario --}}
        @if ($errors->any())
            <script>
                Swal.fire({
                    icon: 'error',
                    title: '¡Revisa los campos!',
                    text: 'Algunos datos son incorrectos o faltan por completar.',
                    confirmButtonColor: '#3b82f6'
                });
            </script>
        @endif

        {{-- 2. Alerta para mensajes de éxito o errores capturados en el Catch --}}
        @if (($mensaje = Session::get('mensaje')) && ($icono = Session::get('icono')))
            <script>
                Swal.fire({
                    position: "top-end",
                    icon: "{{ $icono }}",
                    title: "{{ $mensaje }}",
                    showConfirmButton: {{ $icono === 'error' ? 'true' : 'false' }},
                    timer: {{ $icono === 'error' ? 'null' : '4000' }}
                });
            </script>
        @endif
    </flux:main>
</x-layouts::app.sidebar>
