@php
    $personalize = $classes();
@endphp

<div
    x-data="{ tallStackUiMenuMobile: false }"
    x-on:tallstackui-menu-mobile.window="tallStackUiMenuMobile = $event.detail.status"
>
    @if ($top)
        {{ $top }}
    @endif

    @if ($menu)
        {{ $menu }}
    @endif

    <div class="{{ $personalize["wrapper.first"] }}">
        <div class="{{ $personalize["wrapper.second"] }}">
            @if ($header)
                {{ $header }}
            @endif

            <main class="{{ $personalize["main"] }}">
                {{ $slot }}
            </main>
        </div>
    </div>
    @if ($footer)
        {{ $footer }}
    @endif
</div>
