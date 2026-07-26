<x-layouts::app title="Usuarios del Sistema">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Listado de usuarios</flux:heading>
        <br>
        <flux:separator variant="subtle" />
    </div>


    <div class="flex gap-4">
        <div class="flex-1">
            <form action="{{ url('/admin/usuarios') }}" method="GET" class="flex gap-2 w-1/2">
                <div class="flex-1">
                    <flux:input name="buscar" type="text" icon="magnifying-glass" placeholder="Buscar usuarios..."
                        value="{{ $_REQUEST['buscar'] ?? '' }}" class="transition-all duration-200" />
                </div>
                <button type="submit"
                    class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg
                transition flex items-center gap-2">
                    <i class="fas fa-search"></i>
                    Buscar
                </button>
                @if (isset($_REQUEST['buscar']))
                    <a href="{{ url('/admin/usuarios') }}"
                        class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white font-semibold
                    rounded-lg transition
                    flex items-center gap-2">
                        <i class="fas fa-trash"></i> Limpiar
                    </a>
                @endif
            </form>
        </div>
        <div class="flex-1 justify-end flex">
            <a href="{{ url('/admin/usuarios/create') }}"
                class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition flex items-center gap-2">
                <i class="fas fa-plus mr-2"></i>
                Crear nuevo
            </a>
        </div>
    </div>

    @if (request('buscar'))
        <div class="mt-4 p-4 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg">
            <p class="text-sm text-gray-700 dark:text-black-300">
                <i class="fas fa-search mr-2"></i>
                Se {{ $usuarios->total() == 1 ? 'encontró' : 'encontraron' }}
                <span class="font-semibold text-blue-600 dark:text-blue-400">{{ $usuarios->total() }}</span>
                {{ $usuarios->total() == 1 ? 'resultado' : 'resultados' }}
                con la busqueda: <span class="font-semibold">"{{ request('buscar') }}"</span>
            </p>
        </div>
    @endif

    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 mt-6">
        <table class="min-w-full border-collapse">
            <thead class="bg-gray-50 dark:bg-zinc-900 text-center">
                <tr>
                    <th
                        class="px-6 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Nro
                    </th>
                    <th
                        class="px-6 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Foto
                    </th>
                    <th
                        class="px-6 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Rol de Usuario
                    </th>
                    <th
                        class="px-6 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Nombres y apellidos
                    </th>
                    <th
                        class="px-6 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Email
                    </th>


                    <th
                        class="px-6 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Estado
                    </th>
                    <th
                        class="px-6 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-zinc-800">

                @foreach ($usuarios as $usuario)
                    <tr
                        class="even:bg-slate-50 odd:bg-white dark:even:bg-zinc-700/20 dark:odd:bg-zinc-800 hover:bg-blue-50 dark:hover:bg-zinc-700/50 transition">
                        <td
                            class="px-3 py-2 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 text-center">
                            {{ $loop->iteration }}
                        </td>

                        <td class="px-3 py-2 border text-sm text-center">
                            <img src="{{ $usuario->foto_perfil ? asset('storage/'.$usuario->foto_perfil) : asset('img/default-user.png') }}" class="w-10 h-10 rounded-full mx-auto">
                        </td>
                        {{-- <td
                            class="px-3 py-2 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                            {{ $rol_usuario }} </td> --}}
                        <td
                            class="px-3 py-2 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                            {{ $usuario->getRoleNames()->join(', ') }}
                        </td>

                         <td
                            class="px-3 py-2 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                            {{ $usuario->name }}
                        </td>

                        <td
                            class="px-3 py-2 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                            {{ $usuario->email }}
                        </td>



                        <td class="text-center px-3 py-2 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                            @if ($usuario->estado == 'Inactivo')
                                <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full bg-red-500 text-white border border-red-200">
                                    Inactivo
                                </span>
                            @else
                                <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full bg-emerald-500 text-white border border-emerald-200">
                                    Activo
                                </span>
                            @endif
                        </td>


                        <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-center">

                            @if ($usuario->deleted_at)
                                <form action="{{ url('/admin/usuario/' . $usuario->id . '/restaurar') }}"
                                    method="post" id="miFormulario{{ $usuario->id }}">
                                    @csrf
                                    <button type="submit"
                                        class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-semibold rounded transition"
                                        onclick="preguntar{{ $usuario->id }}(event)">
                                        <i class="fas fa-undo-alt mr-2"></i> Restaurar
                                    </button>
                                </form>

                                <script>
                                    function preguntar{{ $usuario->id }}(event) {
                                        event.preventDefault();

                                        Swal.fire({
                                            title: '¿Desea restaurar este registro?',
                                            text: '',
                                            icon: 'question',
                                            showDenyButton: true,
                                            confirmButtonText: 'Restaurar',
                                            confirmButtonColor: '#a68d1e',
                                            denyButtonColor: '#270a0a',
                                            denyButtonText: 'Cancelar',
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                // JavaScript puro para enviar el formulario
                                                document.getElementById('miFormulario{{ $usuario->id }}').submit();
                                            }
                                        });
                                    }
                                </script>
                            @else
                                <div class="flex justify-center gap-2">
                                    <a href="{{ url('/admin/usuario/' . $usuario->id) }}"
                                        class="inline-flex items-center px-4 py-2 bg-zinc-500 hover:bg-zinc-600 text-white text-xs font-semibold rounded transition shadow-sm">
                                        <i class="fas fa-eye mr-2"></i> Ver
                                    </a>

                                    <a href="{{ url('/admin/usuario/' . $usuario->id . '/edit') }}"
                                        class="inline-flex items-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white text-xs font-semibold rounded transition">
                                        <i class="fas fa-pencil-alt mr-2"></i> Editar
                                    </a>


                                    <form action="{{ url('/admin/usuario/' . $usuario->id) }}" method="post"
                                        id="miFormulario{{ $usuario->id }}">
                                        @csrf
                                        @method('DELETE')
                                        @php
                                            // Verificamos si tiene el rol de 'SUPER ADMIN'
                                            $esSuperAdmin = $usuario->hasRole('SUPER ADMIN');
                                        @endphp
                                        <button type="submit"
                                            {{-- Quitamos el atributo disabled para que el cursor pueda ser controlado por CSS --}}
                                            class="inline-flex items-center px-4 py-2 text-white text-xs font-semibold rounded transition
                                            {{ $esSuperAdmin ? 'bg-gray-400 cursor-not-allowed' : 'bg-red-500 hover:bg-red-600 cursor-pointer' }}"

                                            {{-- Si es Super Admin, evitamos que ocurra cualquier acción --}}
                                            onclick="{{ $esSuperAdmin ? 'event.preventDefault();' : 'preguntar'.$usuario->id.'(event)' }}">

                                            <i class="fas fa-trash-alt mr-2"></i> {{ $esSuperAdmin ? 'Protegido' : 'Eliminar' }}
                                        </button>
                                    </form>

                                    <script>
                                        function preguntar{{ $usuario->id }}(event) {
                                            event.preventDefault();

                                            Swal.fire({
                                                title: '¿Desea eliminar este registro?',
                                                text: '',
                                                icon: 'question',
                                                showDenyButton: true,
                                                confirmButtonText: 'Eliminar',
                                                confirmButtonColor: '#a5161d',
                                                denyButtonColor: '#270a0a',
                                                denyButtonText: 'Cancelar',
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    // JavaScript puro para enviar el formulario
                                                    document.getElementById('miFormulario{{ $usuario->id }}').submit();
                                                }
                                            });
                                        }
                                    </script>
                                </div>
                            @endif


                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <style>
        /* Ocultar textos en inglés de la paginación */
        nav[role="navigation"] p {
            display: none !important;
        }
    </style>

    @if ($usuarios->hasPages())
        <div class="px-3 mt-4 flex justify-between items-center">
            <div class="text-gray-600 dark:text-gray-400 text-sm">
                Mostrando
                <span class="font-semibold">{{ $usuarios->firstItem() }}</span>
                al
                <span class="font-semibold">{{ $usuarios->lastItem() }}</span>
                de
                <span class="font-semibold">{{ $usuarios->total() }}</span>
                resultados.
            </div>
            <div>
                {{ $usuarios->links() }}
            </div>
        </div>
    @endif

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#3b82f6',
                timer: 3000,
                timerProgressBar: true
            });
        </script>
    @endif
</x-layouts::app>
