@php
    $personalize = $classes();
@endphp

<div @class($personalize['wrapper.first'])
     x-data="tallstackui_dropdown(@js(!$static))">
    <div @class($personalize['wrapper.second']) x-on:click.outside="show = false" x-ref="dropdown">
        @if ($text)
            <div @class($personalize['action.wrapper'])>
                <span @class($personalize['action.text'])>{{ $text }}</span>
                <button type="button" x-on:click="show = !show" dusk="open-dropdown">
                    <x-dynamic-component :component="TallStackUi::component('icon')"
                                         :icon="TallStackUi::icon('chevron-down')"
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
        <x-dynamic-component :component="TallStackUi::component('floating')"
                             class="w-56"
                             offset="5"
                             :$position
                             x-anchor="$refs.dropdown">
            <x-slot:transition>
                {!! $transitions() !!}
            </x-slot:transition>
            @if ($header)
                <div class="m-2">
                    {!! $header !!}
                </div>
            @endif
            <div @class($personalize['wrapper.slot'])>
                {!! $slot !!}
            </div>
        </x-dynamic-component>
    </div>
</div>
