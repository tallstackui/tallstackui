@php
    $personalize = $classes();
@endphp

@aware(['smart' => null, 'navigate' => null, 'navigateHover' => null])

@if ($visible)
    @if ($slot->isNotEmpty())
        <li x-data="{ show : @js($opened ?? \Illuminate\Support\Str::contains($slot, 'ts-ui-group-opened') ?? false), open: true }">
            <button x-on:click="show = !show"
                    type="button"
                    class="{{ $personalize['group.button'] }}">
                @if ($icon instanceof \Illuminate\View\ComponentSlot)
                    {{ $icon }}
                @elseif ($icon)
                    <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                         :icon="TallStackUi::icon($icon)"
                                         internal
                                         class="{{ $personalize['group.icon.base'] }}" />
                @endif
                <span x-show="$store.sidebar.open" x-transition class="whitespace-nowrap">{{ $text }}</span>
                <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                     :icon="TallStackUi::icon('chevron-down')"
                                     internal
                                     class="{{ $personalize['group.icon.collapse.base'] }}"
                                     x-bind:class="{ '{{ $personalize['group.icon.collapse.rotate'] }}': show }" />
            </button>
            <ul x-show="show" class="{{ $personalize['group.group'] }}" x-data x-ref="parent">
                {{ $slot }}
            </ul>
        </li>
    @else
        <li class="{{ $personalize['item.wrapper.base'] }}"
            x-bind:class="{ '{{ $personalize['item.wrapper.border'] }}' : $refs.parent !== undefined }"
            x-data="{ open: true }"
            >
            <a @if ($route) href="{{ $route }}" @endif
            @class([
                $personalize['item.state.base'],
                $personalize['item.state.normal'] => ! $current || (! $smart && ! $matches()),
                \Illuminate\Support\Arr::toCssClasses(['ts-ui-group-opened', $personalize['item.state.current']]) => $current || ($smart && $matches()),
            ]) @if ($navigate) wire:navigate @elseif ($navigateHover) wire:navigate.hover @endif>
                @if ($icon instanceof \Illuminate\View\ComponentSlot)
                    {{ $icon }}
                @elseif ($icon)
                    <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                         :icon="TallStackUi::icon($icon)"
                                         internal
                                         class="{{ $personalize['item.icon'] }}" />
                @endif
                <span x-cloak x-show="$store.sidebar.open" x-transition class="whitespace-nowrap">{{ $text }}</span>
            </a>
        </li>
    @endif
@endif
