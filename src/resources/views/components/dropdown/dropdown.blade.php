@php
    $personalize = $classes();
    $side = str_contains($position, 'right') || str_contains($position, 'left');
    $orientation = str_contains($position, 'bottom') || str_contains($position, 'right');
@endphp

<div @class($personalize['wrapper.first'])
     x-data="tallstackui_dropdown(@js(!$static))">
    <div @class($personalize['wrapper.second']) x-on:click.outside="show = false" x-ref="dropdown">
        @if ($text)
            <div @class($personalize['action.wrapper'])>
                <span @class($personalize['action.text'])>{{ $text }}</span>
                <button type="button" x-on:click="show = !show" dusk="open-dropdown">
                    <x-dynamic-component :component="TallStackUi::component('icon')"
                                        icon="chevron-down"
                                        @class($personalize['action.icon'])
                                        x-bind:class="{ 'transform rotate-180': animate && show }"/>
                </button>
            </div>
        @elseif ($icon)
            <div @class($personalize['action.wrapper'])>
                <button type="button" x-on:click="show = !show" dusk="open-dropdown">
                    <x-dynamic-component :component="TallStackUi::component('icon')"
                                        :$icon
                                        @class($personalize['action.icon'])
                                        x-bind:class="{ 'transform rotate-180': animate && show }" />
                </button>
            </div>
        @else
            {!! $action !!}
        @endif
        <div x-show="show" x-cloak
             x-transition:enter="transition duration-100 ease-out"
             x-transition:enter-start="opacity-0 @if ($side) @if ($orientation) -translate-x-2 @else translate-x-2 @endif @else @if ($orientation) -translate-y-2 @else translate-y-2 @endif @endif"
             x-transition:enter-end="opacity-100 @if ($side) translate-x-0 @else translate-y-0 @endif"
             x-transition:leave="transition duration-100 ease-in"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             x-anchor.{{ $position }}.offset.5="$refs.dropdown"
             @class([$personalize['wrapper.third']])
             role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
            <div role="none">
                @if ($header)
                    <div class="m-2">
                        {!! $header !!}
                    </div>
                @endif
                {!! $slot !!}
            </div>
        </div>
    </div>
</div>
