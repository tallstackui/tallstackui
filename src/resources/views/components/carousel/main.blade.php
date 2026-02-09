@php
    $customization = $classes();
@endphp

<div x-data="tallstackui_carousel(@js($images), @js($cover), @js($autoplay), @js($interval), @js($withoutLoop), @js($shuffle))"
     {{ $attributes->only(['x-on:next', 'x-on:previous']) }}
     x-ref="carousel">
    @if ($header)
        {{ $header }}
    @endif
    <div class="{{ $customization['wrapper.first'] }}">
        @if (!$autoplay)
            <button type="button"
                    class="{{ $customization['buttons.left.base'] }}"
                    dusk="tallstackui_carousel_previous"
                    x-on:click="previous()">
                <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                     :icon="TallStackUi::icon('chevron-left')"
                                     internal
                                     class="{{ $customization['buttons.left.icon.size'] }}" />
            </button>
            <button type="button"
                    class="{{ $customization['buttons.right.base'] }}"
                    dusk="tallstackui_carousel_next"
                    x-on:click="next()">
                <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                     :icon="TallStackUi::icon('chevron-right')"
                                     internal
                                     class="{{ $customization['buttons.right.icon.size'] }}" />
            </button>
        @endif
        <div @class([
            $customization['wrapper.second'],
            'min-h-[50svh]' => is_null($wrapper),
            $wrapper => ! is_null($wrapper),
        ])>
            <template x-for="(image, index) in images" :key="index">
                <div x-show="current == index + 1" class="{{ $customization['images.wrapper.first'] }}"
                     @if (!$ts_ui__flash) x-transition.opacity.duration.1000ms @endif>
                    <a x-bind:href="image.url ?? null" x-bind:target="image.target">
                        <template x-if="image.title">
                            <div @class([$customization['images.wrapper.second'], 'rounded-xl' => $round])>
                                <h3 class="{{ $customization['images.content.title'] }}" x-text="image.title"></h3>
                                <p class="{{ $customization['images.content.description'] }}"
                                   x-text="image.description"></p>
                            </div>
                        </template>
                        <img @class([$customization['images.base'], 'rounded-xl' => $round])
                             x-bind:src="image.src"
                             x-bind:alt="image.alt"
                             @if ($autoplay && $stopOnHover)
                                 x-on:mouseover="(paused = !paused), reset()"
                             x-on:mouseleave="(paused = !paused), reset()"
                                @endif />
                    </a>
                </div>
            </template>
        </div>
        @if (!$withoutIndicators)
            <div class="{{ $customization['indicators.wrapper'] }}">
                <template x-for="(image, index) in images">
                    <button class="{{ $customization['indicators.buttons.base'] }}"
                            x-on:click="(current = index + 1), reset()"
                            x-bind:class="[
                                current === index + 1 ? '{{ $customization['indicators.buttons.current'] }}' : '{{ $customization['indicators.buttons.inactive'] }}'
                            ]"></button>
                </template>
            </div>
        @endif
    </div>
    @if ($footer)
        {{ $footer }}
    @endif
</div>
