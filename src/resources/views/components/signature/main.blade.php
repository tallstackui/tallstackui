@php
    $customization = $classes();
@endphp

<x-dynamic-component :component="TallStackUi::prefix('wrapper.input')" :$id :$property :$error :$label :$hint
                     :$invalidate>
    <div x-data="tallstackui_signature({!! $entangle !!}, @js($color), @js($background), @js($line), @js($height), @js($jpeg))"
         class="{{ $customization['wrapper.first'] }}" x-cloak>
        <div class="{{ $customization['wrapper.second'] }}">
            <div class="{{ $customization['wrapper.button'] }}">
                <button type="button" aria-label="undo" x-on:click="undo" class="cursor-pointer"
                        dusk="tallstackui_signature_undo">
                    <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                         :icon="TallStackUi::icon('arrow-uturn-left')"
                                         internal
                                         class="{{ $customization['icons'] }}" />
                </button>
                <button type="button" aria-label="redo" x-on:click="redo" class="cursor-pointer"
                        dusk="tallstackui_signature_redo">
                    <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                         :icon="TallStackUi::icon('arrow-uturn-right')"
                                         internal
                                         class="{{ $customization['icons'] }}" />
                </button>
                @if ($clearable)
                    <button type="button" aria-label="clear" x-on:click="clear" class="cursor-pointer"
                            dusk="tallstackui_signature_clear">
                        <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                             :icon="TallStackUi::icon('trash')"
                                             internal
                                             class="{{ $customization['icons'] }}" />
                    </button>
                @endif
            </div>
            @if ($exportable)
                <button type="button" aria-label="export" x-on:click="download" class="cursor-pointer"
                        dusk="tallstackui_signature_export" {{ $attributes->only('x-on:export') }}>
                    <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                         :icon="TallStackUi::icon('document-arrow-down')"
                                         internal
                                         class="{{ $customization['icons'] }}" />
                </button>
            @endif
        </div>
        <div class="{{ $customization['canvas.wrapper'] }}">
            <canvas x-ref="canvas"
                    wire:ignore
                    class="{{ $customization['canvas.base'] }}"
                    :height="height"
                    style="cursor: crosshair; max-height: {{ $height }}px"
                    dusk="tallstackui_signature_canva"
                    x-on:mousedown="start"
                    x-on:mousemove="draw"
                    x-on:mouseup="stop"
                    x-on:mouseleave="stop"
                    x-on:touchstart="start"
                    x-on:touchmove="draw"
                    x-on:touchend="stop"
                    x-on:touchcancel="stop">
            </canvas>
        </div>
    </div>
</x-dynamic-component>
