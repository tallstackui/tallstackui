@php
    $customization = $classes();
@endphp

<div x-data="tallstackui_layout()" x-on:tallstackui-menu-mobile.window="tallStackUiMenuMobile = $event.detail.status">
    @if ($top)
        {{ $top }}
    @endif
    @if ($menu)
        {{ $menu }}
    @endif
    <div class="{{ $customization['wrapper.first'] }}">
        <div x-bind:class="{ '{{ $customization['wrapper.second.expanded'] }}' : $store['tsui.side-bar'].open, '{{ $customization['wrapper.second.collapsed'] }}' : !$store['tsui.side-bar'].open }">
            @if ($header)
                {{ $header }}
            @endif
            <main class="{{ $customization['main'] }}">
                {{ $slot }}
            </main>
        </div>
    </div>
    @if ($footer)
        {{ $footer }}
    @endif
</div>
