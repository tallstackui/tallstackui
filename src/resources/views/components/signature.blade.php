@php
    $personalize = $classes();
@endphp

<x-dynamic-component :component="TallStackUi::prefix('wrapper.input')" :$id :$property :$error :$label :$hint :$invalidate>
    <div x-data="tallstackui_signature({!! $entangle !!}, @js($color), @js($background), @js($line), @js($height), @js($jpeg))" class="{{ $personalize['wrapper.first'] }}" x-cloak>
        <div class="{{ $personalize['wrapper.second'] }}">
            <div class="{{ $personalize['wrapper.button'] }}">
                <button type="button" aria-label="undo" x-on:click="undo" dusk="tallstackui_signature_undo">
                    <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                         :icon="TallStackUi::icon('arrow-uturn-left')"
                                         internal
                                         class="{{ $personalize['icons'] }}" />
                </button>
                <button type="button" aria-label="redo" x-on:click="redo" dusk="tallstackui_signature_redo">
                    <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                         :icon="TallStackUi::icon('arrow-uturn-right')"
                                         internal
                                         class="{{ $personalize['icons'] }}" />
                </button>
                @if ($clearable)
                    <button type="button" aria-label="clear" x-on:click="clear" dusk="tallstackui_signature_clear">
                        <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                             :icon="TallStackUi::icon('trash')"
                                             internal
                                             class="{{ $personalize['icons'] }}" />
                    </button>
                @endif
            </div>
            @if ($exportable)
                <button type="button" aria-label="export" x-on:click="download" dusk="tallstackui_signature_export" {{ $attributes->only('x-on:export') }}>
                    <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                         :icon="TallStackUi::icon('document-arrow-down')"
                                         internal
                                         class="{{ $personalize['icons'] }}" />
                </button>
            @endif
        </div>
       <div class="{{ $personalize['canvas.wrapper'] }}">
         <canvas x-ref="canvas"
                wire:ignore
                class="{{ $personalize['canvas.base'] }}"
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
