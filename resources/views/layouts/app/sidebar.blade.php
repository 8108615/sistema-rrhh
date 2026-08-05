<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky collapsible="mobile" class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.header>
                <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-2 px-2 py-1 overflow-hidden">
                    <img src="{{ asset('img/logo_thegame.png') }}" alt="Logo TheGame" class="w-8 h-8 object-contain rounded-md">
                    <span class="text-sm font-bold text-zinc-800 dark:text-white truncate">Sistema de RRHH</span>
                </a>
                <flux:sidebar.collapse class="lg:hidden" />
            </flux:sidebar.header>

            <flux:sidebar.nav>
                <flux:sidebar.group :heading="__('Platform')" class="grid">
                    <!-- Dashboard siempre visible para todos los autenticados -->
                    <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')"
                                        wire:navigate>{{ __('Dashboard') }}
                    </flux:sidebar.item>

                    @can('admin.ajustes.index')
                    <flux:navlist.item icon="cog-6-tooth" :href="route('admin.ajustes.index')"
                                :current="request()->routeIs('admin.ajustes.index')" wire:navigate>Ajustes
                    </flux:navlist.item>
                    @endcan

                    @can('admin.roles.index')
                    <flux:navlist.item icon="shield-check" :href="route('admin.roles.index')"
                                :current="request()->routeIs('admin.roles.index')" wire:navigate>Roles
                    </flux:navlist.item>
                    @endcan

                    @can('admin.usuarios.index')
                    <flux:navlist.item icon="users" :href="route('admin.usuarios.index')"
                                :current="request()->routeIs('admin.usuarios.index')" wire:navigate>Usuarios
                    </flux:navlist.item>
                    @endcan

                    @can('admin.departamentos.index')
                    <flux:navlist.item icon="building-office" :href="route('admin.departamentos.index')"
                                :current="request()->routeIs('admin.departamentos.index')" wire:navigate>Departamentos
                    </flux:navlist.item>
                    @endcan

                    @can('admin.areas.index')
                    <flux:navlist.item icon="rectangle-group" :href="route('admin.areas.index')"
                                :current="request()->routeIs('admin.areas.index')" wire:navigate>Áreas
                    </flux:navlist.item>
                    @endcan

                    @can('admin.cargos.index')
                    <flux:navlist.item icon="briefcase" :href="route('admin.cargos.index')"
                                :current="request()->routeIs('admin.cargos.index')" wire:navigate>Cargos
                    </flux:navlist.item>
                    @endcan

                    <!-- Sección de Gestión de Personal / Recursos Humanos -->
                    <!-- Solo se muestra el grupo completo si tiene al menos acceso a uno de estos módulos, o puedes condicionar ítem por ítem -->
                    @canany(['admin.empleados.index', 'admin.contratos.index', 'admin.permisos.index', 'admin.planillas.index', 'admin.pagos.index', 'admin.aguinaldos.index', 'admin.retroactivos.index', 'admin.finiquitos.index'])
                    <flux:navlist.group heading="Personal">
                        @can('admin.empleados.index')
                        <flux:navlist.item icon="users" :href="route('admin.empleados.index')" :current="request()->routeIs('admin.empleados*')">
                            Empleados
                        </flux:navlist.item>
                        @endcan

                        @can('admin.contratos.index')
                        <flux:navlist.item icon="document-duplicate" :href="route('admin.contratos.index')" :current="request()->routeIs('admin.contratos*')">
                            Contratos
                        </flux:navlist.item>
                        @endcan

                        @can('admin.permisos.index')
                        <flux:navlist.item icon="calendar" :href="route('admin.permisos.index')" :current="request()->routeIs('admin.permisos*')">
                            Permisos y Vacaciones
                        </flux:navlist.item>
                        @endcan

                        @can('admin.planillas.index')
                        <flux:navlist.item icon="banknotes" :href="route('admin.planillas.index')" :current="request()->routeIs('admin.planillas*')">
                            Planillas
                        </flux:navlist.item>
                        @endcan

                        @can('admin.pagos.index')
                        <flux:navlist.item icon="credit-card" :href="route('admin.pagos.index')" :current="request()->routeIs('admin.pagos*')">
                            Pagos
                        </flux:navlist.item>
                        @endcan

                        @can('admin.aguinaldos.index')
                        <flux:navlist.item icon="gift" :href="route('admin.aguinaldos.index')" :current="request()->routeIs('admin.aguinaldos*')">
                            Aguinaldos
                        </flux:navlist.item>
                        @endcan

                        @can('admin.retroactivos.index')
                        <flux:navlist.item icon="arrow-trending-up" :href="route('admin.retroactivos.index')" :current="request()->routeIs('admin.retroactivos*')">
                            Retroactivos
                        </flux:navlist.item>
                        @endcan

                        @can('admin.finiquitos.index')
                        <flux:navlist.item icon="document-text" :href="route('admin.finiquitos.index')" :current="request()->routeIs('admin.finiquitos*')">
                            Finiquitos
                        </flux:navlist.item>
                        @endcan


                    </flux:navlist.group>
                    @endcanany
                </flux:sidebar.group>
            </flux:sidebar.nav>

            <flux:spacer />



            <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <flux:avatar
                                    :name="auth()->user()->name"
                                    :initials="auth()->user()->initials()"
                                />

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                    <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                            {{ __('Settings') }}
                        </flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item
                            as="button"
                            type="submit"
                            icon="arrow-right-start-on-rectangle"
                            class="w-full cursor-pointer"
                            data-test="logout-button"
                        >
                            {{ __('Log out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
