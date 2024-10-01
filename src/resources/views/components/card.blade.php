@php
    $personalize = $classes();
@endphp

<div x-data="tallstackui_card(@js($initializeMinimized))" @class($personalize['wrapper.first']) x-show="show">
    <div @class([
            $personalize['wrapper.second'], 
            $personalize['wrapper.overlay'] => $overlay
        ]) @if($overlay) style="background-image: url('{{ $image }}'); background-size: cover;" @endif >
        @if ($image && $position !== 'bottom' && !$overlay)
            <div @class([$personalize['image.wrapper']])>
                <img src="{{ $image }}" @class([
                    $personalize['image.rounded.top'],
                    $personalize['image.size'],
                ]) />
            </div>
        @endif
        @if ($header)
            <div @class([$personalize['header.wrapper.base'], $colors['background']]) x-bind:class="{ '{{ $personalize['header.wrapper.border'] }}' : !minimize }">
                <div @class($personalize['header.text.size'])>
                    {{ $header }}
                </div>
                @if ($minimize || $close)
                <div>
                    @if ($minimize)
                    <button type="button" @click="minimize = !minimize" dusk="tallstackui_card_minimize">
                        <x-dynamic-component :component="TallStackUi::component('icon')"
                                             :icon="TallStackUi::icon('minus')"
                                             @class($personalize['button.minimize'])
                                             x-show="!minimize" />
                        <x-dynamic-component :component="TallStackUi::component('icon')"
                                             :icon="TallStackUi::icon('plus')"
                                             @class($personalize['button.maximize'])
                                             x-show="minimize" />
                    </button>
                    @endif
                    @if ($close)
                    <button type="button" @click="show = false" dusk="tallstackui_card_close">
                        <x-dynamic-component :component="TallStackUi::component('icon')"
                                             :icon="TallStackUi::icon('x-mark')"
                                             @class($personalize['button.close']) />
                    </button>
                    @endif
                </div>
                @endif
            </div>
        @endif
        <div {{ $attributes->class($personalize['body']) }} x-show="!minimize">
            {{ $slot }}
        </div>
        @if ($footer)
            <div @class($personalize['footer.wrapper']) x-show="!minimize">
                <div @class($personalize['footer.text'])>
                    {{ $footer }}
                </div>
            </div>
        @endif
        @if ($image && $position === 'bottom' && !$overlay)
            <div @class([$personalize['image.wrapper']]) x-show="!minimize">
                <img src="{{ $image }}" @class([
                    $personalize['image.rounded.bottom'],
                    $personalize['image.size'],
                ]) />
            </div>
        @endif
    </div>
</div>
