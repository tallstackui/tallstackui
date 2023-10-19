@php($customize = tallstackui_personalization('dropdown', $personalization()))

<div @class($customize['wrapper.first']) x-data="{ show : false, animate : @js($static) }">
    <div @class($customize['wrapper.second']) x-on:click.outside="show = false">
        @if ($text)
            <div @class($customize['action.wrapper'])>
                <span @class($customize['action.text'])>{{ $text }}</span>
                <x-icon name="chevron-down"
                        dusk="open-dropdown"
                        @class($customize['action.icon'])
                        x-on:click="show = !show"
                        x-bind:class="{ 'transform rotate-180': !animate && show }"
                />
            </div>
        @elseif ($icon)
            <div @class($customize['action.wrapper'])>
                <x-icon :$icon
                        dusk="open-dropdown"
                        @class($customize['action.icon'])
                        x-on:click="show = !show"
                        x-bind:class="{ 'transform rotate-180': !animate && show }"
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
             @class([
                'rounded-md' => !$configurations['square'],
                'right-0 origin-top-right' => !$right,
                'left-0 origin-top-left' => $right,
                $customize['wrapper.third'],
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
