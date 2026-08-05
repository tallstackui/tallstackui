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
        <div @class([$customization['wrapper.second.footer'] => (bool) $footer])
             @if ($menu)
                 x-bind:class="{
                     '{{ $customization['wrapper.second.expanded'] }}' : !$store['tsui.side-bar'].collapsed,
                     '{{ $customization['wrapper.second.collapsed'] }}' : $store['tsui.side-bar'].collapsed,
                 }"
             @endif>
            @if ($header)
                {{ $header }}
            @endif
            <main @class([$customization['main'], $customization['main.grow'] => (bool) $footer])>
                {{ $slot }}
            </main>
            @if ($footer)
                {{ $footer }}
            @endif
        </div>
    </div>
</div>
