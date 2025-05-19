@php
    $personalize = $classes();
@endphp

<div x-data="tallstackui_layout()" x-on:tallstackui-menu-mobile.window="tallStackUiMenuMobile = $event.detail.status">
    @if ($top)
        {{ $top }}
    @endif
    @if ($menu)
        {{ $menu }}
    @endif
    <div class="{{ $personalize['wrapper.first'] }}">
        <div :class="$store.sidebar.open ? '{{ $personalize['wrapper.second.expanded'] }}' : '{{ $personalize['wrapper.second.collapsed'] }}'">
            @if ($header)
                {{ $header }}
            @endif
            <main class="{{ $personalize['main'] }}">
                {{ $slot }}
            </main>
        </div>
    </div>
    @if ($footer)
        {{ $footer }}
    @endif
</div>
