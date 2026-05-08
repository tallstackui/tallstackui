@php
    $customization = $classes();
@endphp

<div x-data="tallstackui_carousel(@js($images), @js($cover), @js($autoplay), @js($interval), @js($withoutLoop), @js($shuffle), @js($clickable), @js($navigable))"
     {{ $attributes->only(['x-on:next', 'x-on:previous', 'x-on:expand', 'x-on:collapse']) }}
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
            $customization['wrapper.fallback-height'] => is_null($wrapper),
            $wrapper => ! is_null($wrapper),
        ])>
            <template x-for="(image, index) in images" :key="index">
                <div x-show="current == index + 1" class="{{ $customization['images.wrapper.first'] }}"
                     @if (!$ts_ui__flash) x-transition.opacity.duration.1000ms @endif>
                    @if ($clickable)
                        <button type="button"
                                class="{{ $customization['clickable.trigger'] }}"
                                x-on:click="expand(image, index + 1)"
                                dusk="tallstackui_carousel_expand">
                            <template x-if="image.title">
                                <div @class([$customization['images.wrapper.second'], $customization['images.rounded'] => $round])>
                                    <h3 class="{{ $customization['images.content.title'] }}" x-text="image.title"></h3>
                                    <p class="{{ $customization['images.content.description'] }}"
                                       x-text="image.description"></p>
                                </div>
                            </template>
                            <img @class([$customization['images.base'], $customization['images.rounded'] => $round])
                                 x-bind:src="image.src"
                                 x-bind:alt="image.alt"
                                 @if ($autoplay && $stopOnHover)
                                     x-on:mouseover="(paused = !paused), reset()"
                                 x-on:mouseleave="(paused = !paused), reset()"
                                    @endif />
                        </button>
                    @else
                        <a x-bind:href="image.url ?? null" x-bind:target="image.target">
                            <template x-if="image.title">
                                <div @class([$customization['images.wrapper.second'], $customization['images.rounded'] => $round])>
                                    <h3 class="{{ $customization['images.content.title'] }}" x-text="image.title"></h3>
                                    <p class="{{ $customization['images.content.description'] }}"
                                       x-text="image.description"></p>
                                </div>
                            </template>
                            <img @class([$customization['images.base'], $customization['images.rounded'] => $round])
                                 x-bind:src="image.src"
                                 x-bind:alt="image.alt"
                                 @if ($autoplay && $stopOnHover)
                                     x-on:mouseover="(paused = !paused), reset()"
                                 x-on:mouseleave="(paused = !paused), reset()"
                                    @endif />
                        </a>
                    @endif
                </div>
            </template>
        </div>
        @if (!$withoutIndicators)
            <div class="{{ $customization['indicators.wrapper'] }}">
                <template x-for="(image, index) in images">
                    <button class="{{ $customization['indicators.buttons.base'] }}"
                            x-on:click="seek(index + 1)"
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
    @if ($clickable)
        <template x-teleport="body">
            <div x-cloak
                 x-show="expanded !== null"
                 @if (!$ts_ui__flash)
                     x-transition.opacity.duration.200ms
                 @endif
                 x-on:keydown.escape.window="top_ui && close()"
                 @if ($navigable)
                     x-on:keydown.left.window="top_ui && expandPrevious()"
                     x-on:keydown.right.window="top_ui && expandNext()"
                 @endif
                 x-on:click.self="close()"
                 role="dialog"
                 aria-modal="true"
                 class="{{ $customization['clickable.overlay'] }}">
                <button type="button"
                        x-on:click="close()"
                        class="{{ $customization['clickable.close.button'] }}"
                        dusk="tallstackui_carousel_close">
                    <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                         :icon="TallStackUi::icon('x-mark')"
                                         internal
                                         class="{{ $customization['clickable.close.icon'] }}" />
                </button>
                @if ($navigable)
                    <button type="button"
                            x-on:click.stop="expandPrevious()"
                            x-bind:disabled="!expandedHasPrevious"
                            class="{{ $customization['clickable.navigable.button.left.base'] }}"
                            dusk="tallstackui_carousel_expanded_previous">
                        <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                             :icon="TallStackUi::icon('chevron-left')"
                                             internal
                                             class="{{ $customization['clickable.navigable.button.left.icon.size'] }}" />
                    </button>
                    <button type="button"
                            x-on:click.stop="expandNext()"
                            x-bind:disabled="!expandedHasNext"
                            class="{{ $customization['clickable.navigable.button.right.base'] }}"
                            dusk="tallstackui_carousel_expanded_next">
                        <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                             :icon="TallStackUi::icon('chevron-right')"
                                             internal
                                             class="{{ $customization['clickable.navigable.button.right.icon.size'] }}" />
                    </button>
                @endif
                @if ($caption === 'overlay')
                    <figure x-on:click.self="close()"
                            class="{{ $customization['clickable.caption.overlay.figure'] }}">
                        <img class="{{ $customization['clickable.caption.overlay.image'] }}"
                             x-bind:src="expanded?.src"
                             x-bind:alt="expanded?.alt" />
                        <template x-if="expanded?.title || expanded?.description">
                            <figcaption class="{{ $customization['clickable.caption.overlay.wrapper'] }}">
                                <template x-if="expanded?.title">
                                    <h3 class="{{ $customization['clickable.caption.overlay.title'] }}"
                                        x-text="expanded?.title"></h3>
                                </template>
                                <template x-if="expanded?.description">
                                    <p class="{{ $customization['clickable.caption.overlay.description'] }}"
                                       x-text="expanded?.description"></p>
                                </template>
                            </figcaption>
                        </template>
                    </figure>
                @elseif ($caption === 'footer')
                    <figure x-on:click.self="close()"
                            class="{{ $customization['clickable.caption.footer.figure'] }}">
                        <img class="{{ $customization['clickable.caption.footer.image'] }}"
                             x-bind:src="expanded?.src"
                             x-bind:alt="expanded?.alt" />
                        <template x-if="expanded?.title || expanded?.description">
                            <figcaption class="{{ $customization['clickable.caption.footer.wrapper'] }}">
                                <template x-if="expanded?.title">
                                    <h3 class="{{ $customization['clickable.caption.footer.title'] }}"
                                        x-text="expanded?.title"></h3>
                                </template>
                                <template x-if="expanded?.description">
                                    <p class="{{ $customization['clickable.caption.footer.description'] }}"
                                       x-text="expanded?.description"></p>
                                </template>
                            </figcaption>
                        </template>
                    </figure>
                @else
                    <img class="{{ $customization['clickable.image'] }}"
                         x-bind:src="expanded?.src"
                         x-bind:alt="expanded?.alt" />
                @endif
            </div>
        </template>
    @endif
</div>
