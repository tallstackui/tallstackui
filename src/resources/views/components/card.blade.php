@php
    $personalize = $classes();
@endphp

{{-- TODO: move to a js dedicated file --}}
<div x-data="{ minimize: false, show: true }" @class($personalize['wrapper.first']) x-show="show">
    <div @class($personalize['wrapper.second'])>
        @if ($header)
            <div @class([$personalize['header.wrapper.base'], $colors['background']]) x-bind:class="{ '{{ $personalize['header.wrapper.border'] }}' : !minimize }">
                <div @class($personalize['header.text.size'])>
                    {{ $header }}
                </div>
                @if ($minimize || $close)
                <div>
                    @if ($minimize)
                    <button type="button" @click="minimize = !minimize">
                        <x-dynamic-component :component="TallStackUi::component('icon')"
                                             :icon="TallStackUi::icon('minus')"
                                             class="h-6 w-6"
                                             x-show="!minimize" />
                        <x-dynamic-component :component="TallStackUi::component('icon')"
                                             :icon="TallStackUi::icon('plus')"
                                             class="h-6 w-6"
                                             x-show="minimize" />
                    </button>
                    @endif
                    @if ($close)
                    <button type="button" @click="show = false">
                        <x-dynamic-component :component="TallStackUi::component('icon')"
                                             :icon="TallStackUi::icon('x-mark')"
                                             class="h-6 w-6" />
                    </button>
                    @endif
                </div>
                @endif
            </div>
        @endif
        <div {{ $attributes->class($personalize['body']) }}
             x-show="!minimize"
             x-transition:enter="transition ease-out duration-100"
             x-transition:enter-start="max-height-0 opacity-0"
             x-transition:enter-end="max-height-full opacity-100"
             x-transition:leave="transition ease-in duration-100"
             x-transition:leave-start="max-height-full opacity-100"
             x-transition:leave-end="max-height-0 opacity-0">
            {{ $slot }}
        </div>
        @if ($footer)
            <div @class($personalize['footer.wrapper'])
                 x-show="!minimize"
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="max-height-0 opacity-0"
                 x-transition:enter-end="max-height-full opacity-100"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="max-height-full opacity-100"
                 x-transition:leave-end="max-height-0 opacity-0">
                <div @class($personalize['footer.text'])>
                    {{ $footer }}
                </div>
            </div>
        @endif
    </div>
</div>
