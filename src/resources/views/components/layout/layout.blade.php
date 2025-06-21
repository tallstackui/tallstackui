@php
    $personalize = $classes();
@endphp

<div x-data="tallstackui_layout()" x-on:tallstackui-menu-mobile.window="tallStackUiMenuMobile = $event.detail.status"
    x-init="$store.sidebar = { collapsible: {{ $sidebarCollapsible ? 'true' : 'false' }} }">
    @if ($top)
        {{ $top }}
    @endif
    @if ($menu)
        {{ $menu }}
    @endif
    <div class="{{ $personalize['wrapper.first'] }}">
        <div x-bind:class="{ 
                '{{ $personalize['wrapper.second.expanded'] }}' : $store['tsui.side-bar'].open && $store.sidebar.collapsible, 
                '{{ $personalize['wrapper.second.collapsed'] }}' : !$store['tsui.side-bar'].open && $store.sidebar.collapsible 
             }">
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