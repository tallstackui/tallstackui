<body>
    <x-layout>
        <x-slot:header>
            <x-layout.header collapsible>
                <x-slot:right>
                    <x-dropdown text="Hello, {{ auth()->user()->name }}!">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown.items text="Logout"
                                onclick="event.preventDefault(); this.closest('form').submit();" />
                        </form>
                    </x-dropdown>
                </x-slot:right>
            </x-layout.header>
        </x-slot:header>

        <x-slot:menu>
            <x-side-bar collapsible>
                <x-side-bar.item text="Home" icon="home" :route="route('dashboard')" />
                <x-side-bar.item text="Settings" icon="cog" :route="route('settings')" />
            </x-side-bar>
        </x-slot:menu>

        {{ $slot }}
    </x-layout>

    @livewireScripts
</body>