<flux:dropdown position="bottom" align="start">
    <flux:sidebar.profile
        :name="auth()->user()->name"
        :initials="auth()->user()->initials()"
        icon:trailing="chevrons-up-down"
        data-test="sidebar-menu-button"
    >
        @if(auth()->user()->foto_perfil)
            <x-slot name="avatar">
                <img src="{{ asset('storage/' . auth()->user()->foto_perfil) }}" alt="{{ auth()->user()->name }}" class="w-8 h-8 rounded-full object-cover">
            </x-slot>
        @endif
    </flux:sidebar.profile>

    <flux:menu>
        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
            @if(auth()->user()->foto_perfil)
                <img src="{{ asset('storage/' . auth()->user()->foto_perfil) }}" alt="{{ auth()->user()->name }}" class="w-8 h-8 rounded-full object-cover">
            @else
                <flux:avatar
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                />
            @endif
            <div class="grid flex-1 text-start text-sm leading-tight">
                <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
            </div>
        </div>
        <flux:menu.separator />
        <flux:menu.radio.group>
            <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                Configuración
            </flux:menu.item>

            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <flux:menu.item
                    as="button"
                    type="submit"
                    icon="arrow-right-start-on-rectangle"
                    class="w-full cursor-pointer"
                    data-test="logout-button"
                >
                    Cerrar Sesión
                </flux:menu.item>
            </form>
        </flux:menu.radio.group>
    </flux:menu>
</flux:dropdown>
