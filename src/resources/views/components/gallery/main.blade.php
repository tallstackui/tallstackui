@php
    $customization = $classes();
@endphp

<div @if ($clickable)
         x-data="tallstackui_gallery(@js($images), @js($navigable), @js($withoutLoop))"
         x-ref="gallery"
     @endif
     {{ $attributes->class($customization['wrapper']) }}>
    @if ($header)
        {{ $header }}
    @endif
    <div @class([
        $customization['scroll'] => $height !== null,
        ($customization['height.' . $height] ?? '') => $height !== null,
    ])>
    @if ($configurations['layout'] === 'grid')
        <div @class([
            $customization['grid.wrapper'],
            $customization['grid.gap'],
            $configurations['columns'],
        ])>
            @foreach ($images as $index => $image)
                <x-dynamic-component component="ts-ui::gallery.tile"
                                     :$image
                                     :$index
                                     :ratio-class="$configurations['ratio']"
                                     :overlay="0"
                                     :layout="$configurations['layout']"
                                     :$clickable
                                     :$round
                                     :$customization />
            @endforeach
        </div>
    @elseif ($configurations['layout'] === 'masonry')
        <div @class([
            $customization['masonry.wrapper'],
            $customization['masonry.gap'],
            $configurations['columns'],
        ])>
            @foreach ($images as $index => $image)
                <x-dynamic-component component="ts-ui::gallery.tile"
                                     :$image
                                     :$index
                                     :ratio-class="null"
                                     :overlay="0"
                                     :layout="$configurations['layout']"
                                     :$clickable
                                     :$round
                                     :$customization />
            @endforeach
        </div>
    @else
        <div class="{{ $customization['feature.wrapper.' . $configurations['thumbnails']] }}">
            <div class="{{ $customization['feature.cover.wrapper.' . $configurations['thumbnails']] }}">
                <x-dynamic-component component="ts-ui::gallery.tile"
                                     :image="$cover"
                                     :index="$coverIndex"
                                     :ratio-class="$configurations['ratio']"
                                     :overlay="0"
                                     :layout="$configurations['layout']"
                                     :$clickable
                                     :$round
                                     :$customization />
            </div>
            @if (filled($tiles))
                <div class="{{ $customization['feature.thumbnails.wrapper.' . $configurations['thumbnails']] }}">
                    @foreach ($tiles as $index => $image)
                        <x-dynamic-component component="ts-ui::gallery.tile"
                                             :$image
                                             :$index
                                             :ratio-class="$customization['feature.thumbnails.ratio']"
                                             :overlay="$loop->last ? $remaining : 0"
                                             :layout="$configurations['layout']"
                                             :$clickable
                                             :$round
                                             :$customization />
                    @endforeach
                </div>
            @endif
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
                 class="{{ $customization['lightbox.overlay'] }}">
                <button type="button"
                        x-on:click="close()"
                        class="{{ $customization['lightbox.close.button'] }}"
                        dusk="tallstackui_gallery_close">
                    <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                         :icon="TallStackUi::icon('x-mark')"
                                         internal
                                         class="{{ $customization['lightbox.close.icon'] }}" />
                </button>
                @if ($navigable)
                    <button type="button"
                            x-on:click.stop="expandPrevious()"
                            x-bind:disabled="!expandedHasPrevious"
                            class="{{ $customization['lightbox.navigable.button.left.base'] }}"
                            dusk="tallstackui_gallery_expanded_previous">
                        <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                             :icon="TallStackUi::icon('chevron-left')"
                                             internal
                                             class="{{ $customization['lightbox.navigable.button.left.icon.size'] }}" />
                    </button>
                    <button type="button"
                            x-on:click.stop="expandNext()"
                            x-bind:disabled="!expandedHasNext"
                            class="{{ $customization['lightbox.navigable.button.right.base'] }}"
                            dusk="tallstackui_gallery_expanded_next">
                        <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                             :icon="TallStackUi::icon('chevron-right')"
                                             internal
                                             class="{{ $customization['lightbox.navigable.button.right.icon.size'] }}" />
                    </button>
                @endif
                @if ($caption === 'overlay')
                    <figure x-on:click.self="close()"
                            class="{{ $customization['lightbox.caption.overlay.figure'] }}">
                        <img class="{{ $customization['lightbox.caption.overlay.image'] }}"
                             x-bind:src="expanded?.src"
                             x-bind:alt="expanded?.alt" />
                        <template x-if="expanded?.title || expanded?.description">
                            <figcaption class="{{ $customization['lightbox.caption.overlay.wrapper'] }}">
                                <template x-if="expanded?.title">
                                    <h3 class="{{ $customization['lightbox.caption.overlay.title'] }}"
                                        x-text="expanded?.title"></h3>
                                </template>
                                <template x-if="expanded?.description">
                                    <p class="{{ $customization['lightbox.caption.overlay.description'] }}"
                                       x-text="expanded?.description"></p>
                                </template>
                            </figcaption>
                        </template>
                    </figure>
                @elseif ($caption === 'footer')
                    <figure x-on:click.self="close()"
                            class="{{ $customization['lightbox.caption.footer.figure'] }}">
                        <img class="{{ $customization['lightbox.caption.footer.image'] }}"
                             x-bind:src="expanded?.src"
                             x-bind:alt="expanded?.alt" />
                        <template x-if="expanded?.title || expanded?.description">
                            <figcaption class="{{ $customization['lightbox.caption.footer.wrapper'] }}">
                                <template x-if="expanded?.title">
                                    <h3 class="{{ $customization['lightbox.caption.footer.title'] }}"
                                        x-text="expanded?.title"></h3>
                                </template>
                                <template x-if="expanded?.description">
                                    <p class="{{ $customization['lightbox.caption.footer.description'] }}"
                                       x-text="expanded?.description"></p>
                                </template>
                            </figcaption>
                        </template>
                    </figure>
                @else
                    <img class="{{ $customization['lightbox.image'] }}"
                         x-bind:src="expanded?.src"
                         x-bind:alt="expanded?.alt" />
                @endif
            </div>
        </template>
    @endif
</div>
