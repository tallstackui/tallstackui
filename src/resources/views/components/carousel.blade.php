@php
    $personalize = $classes();
@endphp

<div x-data="tallstackui_carousel(@js($images), @js($cover), @js($autoplay), @js($interval), @js($withoutLoop))"
     {{ $attributes->only(['x-on:next', 'x-on:previous']) }}
     x-ref="carousel">
    @if ($header)
        {{ $header }}
    @endif
    <div class="{{ $personalize['wrapper.first'] }}">
        @if (!$autoplay)
            <button type="button"
                    class="{{ $personalize['buttons.left.base'] }}"
                    dusk="tallstackui_carousel_previous"
                    x-on:click="previous()">
                <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                     :icon="TallStackUi::icon('chevron-left')"
                                     internal
                                     class="{{ $personalize['buttons.left.icon.size'] }}" />
            </button>
            <button type="button"
                    class="{{ $personalize['buttons.right.base'] }}"
                    dusk="tallstackui_carousel_next"
                    x-on:click="next()">
                <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                     :icon="TallStackUi::icon('chevron-right')"
                                     internal
                                     class="{{ $personalize['buttons.right.icon.size'] }}" />
            </button>
        @endif
        <div @class([
            $personalize['wrapper.second'],
            'min-h-[50svh]' => is_null($wrapper),
            $wrapper => ! is_null($wrapper),
        ])>
            <template x-for="(image, index) in images" :key="index">
                <div x-show="current == index + 1" class="{{ $personalize['images.wrapper.first'] }}" x-transition.opacity.duration.1000ms>
                    <a x-bind:href="image.url ?? '#'" x-bind:target="image.target">
                        <template x-if="image.title">
                            <div @class([$personalize['images.wrapper.second'], 'rounded-xl' => $round])>
                                <h3 class="{{ $personalize['images.content.title'] }}" x-text="image.title"></h3>
                                <p class="{{ $personalize['images.content.description'] }}" x-text="image.description"></p>
                            </div>
                        </template>
                        <img @class([$personalize['images.base'], 'rounded-xl' => $round])
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
            <div class="{{ $personalize['indicators.wrapper'] }}">
                <template x-for="(image, index) in images">
                    <button class="{{ $personalize['indicators.buttons.base'] }}"
                            x-on:click="(current = index + 1), reset()"
                            x-bind:class="[
                                current === index + 1 ? '{{ $personalize['indicators.buttons.current'] }}' : '{{ $personalize['indicators.buttons.inactive'] }}'
                            ]"></button>
                </template>
            </div>
        @endif
    </div>
    @if ($footer)
        {{ $footer }}
    @endif
</div>
