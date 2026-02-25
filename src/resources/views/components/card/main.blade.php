@php
    $customization = $classes();
@endphp

<div x-data="tallstackui_card(@js($initializeMinimized))" class="{{ $customization['wrapper.first'] }}" x-cloak
     @if ($close) x-show="show" @endif wire:ignore.self {{ $attributes->whereStartsWith('x-on:') }}>
    <div @class([$customization['wrapper.second'], 'relative' => $loading])>
        @if ($loading)
            <div class="{{ $customization['loading.wrapper'] }}"
                 @if (!$delay)
                     wire:loading
                 @else
                     wire:loading.delay{{ is_string($delay) && $delay !== "1" ? ".{$delay}" : "" }}
                 @endif
                 @if (is_string($loading)) wire:target="{{ $loading }}" @endif>
                <div class="{{ $customization['loading.bar'] }}"></div>
            </div>
            <div class="{{ $customization['loading.overlay'] }}"
                 @if (!$delay)
                     wire:loading
                 @else
                     wire:loading.delay{{ is_string($delay) && $delay !== "1" ? ".{$delay}" : "" }}
                 @endif
                 @if (is_string($loading)) wire:target="{{ $loading }}" @endif>
            </div>
        @endif
        @if ($image && $position !== 'bottom')
            <div class="{{ $customization['image.wrapper'] }}">
                <img src="{{ $image }}" @class([$customization['image.rounded.top'], $customization['image.size']]) />
            </div>
        @endif
        @if ($header && ! $header instanceof \Illuminate\View\ComponentSlot)
            <div @class([$customization['header.wrapper.base'], $colors['background']]) x-bind:class="{ '{{ $customization['header.wrapper.border'] }}' : !minimize, '{{ $customization['header.wrapper.minimize'] }}' : minimize }">
                <div class="{{ $customization['header.text.size'] }}">
                    {{ $header }}
                </div>
                @if ($minimize || $close)
                    <div>
                        @if ($minimize)
                            <button type="button" class="cursor-pointer" x-on:click="minimize = !minimize"
                                    dusk="tallstackui_card_minimize">
                                <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                     :icon="TallStackUi::icon('minus')"
                                                     class="{{ $customization['button.minimize'] }}"
                                                     internal
                                                     x-show="!minimize" />
                                <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                     :icon="TallStackUi::icon('plus')"
                                                     class="{{ $customization['button.maximize'] }}"
                                                     internal
                                                     x-show="minimize" />
                            </button>
                        @endif
                        @if ($close)
                            <button type="button" class="cursor-pointer" x-on:click="show = false"
                                    dusk="tallstackui_card_close">
                                <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                     :icon="TallStackUi::icon('x-mark')"
                                                     internal
                                                     class="{{ $customization['button.close'] }}" />
                            </button>
                        @endif
                    </div>
                @endif
            </div>
        @elseif ($header instanceof \Illuminate\View\ComponentSlot)
            <div @class([$customization['header.wrapper.base'], $colors['background']]) x-bind:class="{ '{{ $customization['header.wrapper.border'] }}' : !minimize, '{{ $customization['header.wrapper.minimize'] }}' : minimize }">
                {{ $header }}
                @if ($minimize || $close)
                    <div>
                        @if ($minimize)
                            <button type="button" class="cursor-pointer" x-on:click="minimize = !minimize"
                                    dusk="tallstackui_card_minimize">
                                <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                     :icon="TallStackUi::icon('minus')"
                                                     class="{{ $customization['button.minimize'] }}"
                                                     internal
                                                     x-show="!minimize" />
                                <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                     :icon="TallStackUi::icon('plus')"
                                                     class="{{ $customization['button.maximize'] }}"
                                                     internal
                                                     x-show="minimize" />
                            </button>
                        @endif
                        @if ($close)
                            <button type="button" class="cursor-pointer" x-on:click="show = false"
                                    dusk="tallstackui_card_close">
                                <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                     :icon="TallStackUi::icon('x-mark')"
                                                     internal
                                                     class="{{ $customization['button.close'] }}" />
                            </button>
                        @endif
                    </div>
                @endif
            </div>
        @endif
        <div {{ $attributes->whereDoesntStartWith('x-on:')->class($customization['body']) }}
             x-show="!minimize"
             @if (!$ts_ui__flash)
                 x-transition:enter="transition ease-out duration-100"
             x-transition:enter-start="opacity-0 -translate-y-10"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-100"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-10"
                @endif>
            {{ $slot }}
        </div>
        @if ($footer)
            <div class="{{ $customization['footer.wrapper'] }}"
                 x-show="!minimize"
                 @if (!$ts_ui__flash)
                     x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="opacity-0 -translate-y-10"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-10"
                    @endif>
                @if (! $footer instanceof \Illuminate\View\ComponentSlot)
                    <div class="{{ $customization['footer.text'] }}">
                        {{ $footer }}
                    </div>
                @else
                    {{ $footer }}
                @endif
            </div>
        @endif
        @if ($image && $position === 'bottom')
            <div class="{{ $customization['image.wrapper'] }}"
                 x-show="!minimize"
                 @if (!$ts_ui__flash)
                     x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="opacity-0 -translate-y-10"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-10"
                    @endif>
                <img src="{{ $image }}" @class([$customization['image.rounded.bottom'], $customization['image.size']]) />
            </div>
        @endif
    </div>
</div>
