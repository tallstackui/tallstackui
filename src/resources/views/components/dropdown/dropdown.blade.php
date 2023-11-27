@php
    $personalize = tallstackui_personalization('dropdown', $personalization());
    $side = str_contains($position, 'right') || str_contains($position, 'left');
    $orientation = str_contains($position, 'bottom') || str_contains($position, 'right');
@endphp

<div @class($personalize['wrapper.first'])
     x-data="{
        show: false,
        animate: @js(!$static),
        init() {
            window.addEventListener('scroll', () => {
                const element = this.$refs.dropdown.getBoundingClientRect();
                this.show = (element.bottom < 0 || element.top > window.innerHeight && this.show) ? false : this.show;
            });
        }
     }">
    <div @class($personalize['wrapper.second'])
        x-on:click.outside="show = false"
        x-ref="dropdown">
        @if ($text)
            <div @class($personalize['action.wrapper'])>
                <span @class($personalize['action.text'])>{{ $text }}</span>
                <x-icon name="chevron-down"
                    dusk="open-dropdown"
                    @class($personalize['action.icon'])
                    x-on:click="show = !show"
                    x-bind:class="{ 'transform rotate-180': animate && show }" />
            </div>
        @elseif ($icon)
            <div @class($personalize['action.wrapper'])>
                <x-icon :$icon
                    dusk="open-dropdown"
                    @class($personalize['action.icon'])
                    x-on:click="show = !show"
                    x-bind:class="{ 'transform rotate-180': animate && show }" />
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
            <div class="p-1" role="none">
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
