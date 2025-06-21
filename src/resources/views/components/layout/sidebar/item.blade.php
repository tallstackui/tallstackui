@php
    $personalize = $classes();
@endphp

@aware(['smart' => null, 'navigate' => null, 'navigateHover' => null])

@if ($visible)
    @if ($slot->isNotEmpty())
        <li x-data="{ show : @js($opened ?? \Illuminate\Support\Str::contains($slot, 'ts-ui-group-opened') ?? false) }"
            x-show="($store.sidebar.collapsible && $store['tsui.side-bar'].open)">
            <button x-on:click="show = !show" type="button" class="{{ $personalize['group.button'] }}"
                x-tooltip-conditional="{{ $text }}"
                x-tooltip-condition="$store.sidebar.collapsible && !$store['tsui.side-bar'].open && !$store['tsui.side-bar'].mobile"
                data-position="right">
                @if ($icon instanceof \Illuminate\View\ComponentSlot)
                    {{ $icon }}
                @elseif ($icon)
                    <x-dynamic-component :component="TallStackUi::prefix('icon')" :icon="TallStackUi::icon($icon)" internal
                        class="{{ $personalize['group.icon.base'] }}" />
                @endif
                <span x-show="$store['tsui.side-bar'].mobile || ($store.sidebar.collapsible && $store['tsui.side-bar'].open)"
                    x-transition class="{{ $personalize['group.text'] }}">{{ $text }}</span>
                <x-dynamic-component :component="TallStackUi::prefix('icon')" :icon="TallStackUi::icon('chevron-down')" internal
                    class="{{ $personalize['group.icon.collapse.base'] }}"
                    x-bind:class="{ '{{ $personalize['group.icon.collapse.rotate'] }}': show }" />
            </button>
            <ul x-show="show" class="{{ $personalize['group.group'] }}" x-data x-ref="parent">
                {{ $slot }}
            </ul>
        </li>
        <ul x-show="($store.sidebar.collapsible && !$store['tsui.side-bar'].open)">
            {{ $slot }}
        </ul>
    @else
        <li class="{{ $personalize['item.wrapper.base'] }}" x-bind:class="{ 
                        '{{ $personalize['item.wrapper.border'] }}': $refs.parent !== undefined
                    }">
            <a @if ($route || $href) href="{{ $route ?? $href }}" @endif @class([
                $personalize['item.state.base'],
                $personalize['item.state.normal'] => !$current || (!$smart && !$matches()),
                \Illuminate\Support\Arr::toCssClasses(['ts-ui-group-opened', $personalize['item.state.current']]) => $current || ($smart && $matches()),
            ]) x-bind:class="{
                           '{{ $personalize['item.state.collapsed'] }}': $store.sidebar.collapsible && !$store['tsui.side-bar'].open && !$store['tsui.side-bar'].mobile
                       }" x-tooltip-conditional="{{ $text }}"
                x-tooltip-condition="$store.sidebar.collapsible && !$store['tsui.side-bar'].open && !$store['tsui.side-bar'].mobile"
                data-position="right" @if ($navigate && !$href) wire:navigate @elseif ($navigateHover && !$href)
                wire:navigate.hover @endif {{ $attributes }}>
                @if ($icon instanceof \Illuminate\View\ComponentSlot)
                    {{ $icon }}
                @elseif ($icon)
                    <x-dynamic-component :component="TallStackUi::prefix('icon')" :icon="TallStackUi::icon($icon)" internal
                        class="{{ $personalize['item.icon'] }}" />
                @endif
                <span x-cloak
                    x-show="!$store.sidebar.collapsible || $store['tsui.side-bar'].mobile || ($store.sidebar.collapsible && $store['tsui.side-bar'].open)"
                    x-transition class="{{ $personalize['item.text'] }}">{{ $text }}</span>
            </a>
        </li>
    @endif
@endif
