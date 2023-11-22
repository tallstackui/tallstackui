@php($personalize = tallstackui_personalization('dropdown', $personalization()))

<div @class($personalize['wrapper.first']) x-data="{ show : false, animate : @js(!$static) }">
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
                        x-bind:class="{ 'transform rotate-180': animate && show }"
                />
            </div>
        @elseif ($icon)
            <div @class($personalize['action.wrapper'])>
                <x-icon :$icon
                        dusk="open-dropdown"
                        @class($personalize['action.icon'])
                        x-on:click="show = !show"
                        x-bind:class="{ 'transform rotate-180': animate && show }"
                />
            </div>
        @else
            {!! $action !!}
        @endif
        <div x-show="show" x-cloak
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 scale-50"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-50"
             x-anchor.{{ $position }}="$refs.dropdown"
             @class([
                $personalize['wrapper.third'],
             ])
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
