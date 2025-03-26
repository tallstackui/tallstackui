@php
    $personalize = $classes();
@endphp

<div x-data="tallstackui_card(@js($initializeMinimized))" class="{{ $personalize['wrapper.first'] }}" x-show="show" x-cloak>
    <div class="{{ $personalize['wrapper.second'] }}">
        @if ($image && $position !== 'bottom')
            <div class="{{ $personalize['image.wrapper'] }}">
                <img src="{{ $image }}" @class([$personalize['image.rounded.top'], $personalize['image.size']]) />
            </div>
        @endif
        @if ($header)
            <div @class([$personalize['header.wrapper.base'], $colors['background']]) x-bind:class="{ '{{ $personalize['header.wrapper.border'] }}' : !minimize }">
                <div class="{{ $personalize['header.text.size'] }}">
                    {{ $header }}
                </div>
                @if ($minimize || $close)
                <div>
                    @if ($minimize)
                    <button type="button" class="cursor-pointer" x-on:click="minimize = !minimize" dusk="tallstackui_card_minimize">
                        <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                             :icon="TallStackUi::icon('minus')"
                                             class="{{ $personalize['button.minimize'] }}"
                                             internal
                                             x-show="!minimize" />
                        <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                             :icon="TallStackUi::icon('plus')"
                                             class="{{ $personalize['button.maximize'] }}"
                                             internal
                                             x-show="minimize" />
                    </button>
                    @endif
                    @if ($close)
                    <button type="button" class="cursor-pointer" x-on:click="show = false" dusk="tallstackui_card_close">
                        <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                             :icon="TallStackUi::icon('x-mark')"
                                             internal
                                             class="{{ $personalize['button.close'] }}" />
                    </button>
                    @endif
                </div>
                @endif
            </div>
        @endif
        <div {{ $attributes->class($personalize['body']) }}
             x-show="!minimize"
             x-transition:enter="transition ease-out duration-100"
             x-transition:enter-start="opacity-0 -translate-y-10"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-100"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-10">
            {{ $slot }}
        </div>
        @if ($footer)
            <div class="{{ $personalize['footer.wrapper'] }}"
                 x-show="!minimize"
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="opacity-0 -translate-y-10"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-10">
                <div class="{{ $personalize['footer.text'] }}">
                    {{ $footer }}
                </div>
            </div>
        @endif
        @if ($image && $position === 'bottom')
            <div class="{{ $personalize['image.wrapper'] }}"
                 x-show="!minimize"
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="opacity-0 -translate-y-10"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-10">
                <img src="{{ $image }}" @class([$personalize['image.rounded.bottom'], $personalize['image.size']]) />
            </div>
        @endif
    </div>
</div>
