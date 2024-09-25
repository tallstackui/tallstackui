@php
    $personalize = $classes();
@endphp

<x-dynamic-component :component="TallStackUi::component('wrapper.input')" :$id :$property :$error :$label :$hint :$invalidate>
    <div x-data="tallstackui_signature({!! $entangle !!}, @js($color), @js($background), @js($line), @js($height), @js($extension))" @class($personalize['wrapper.first']) x-cloak>
        <input type="hidden" x-model="model" {!! $attributes->except('x-on:export') !!}>
        <div @class($personalize['wrapper.second'])>
            <div @class($personalize['wrapper.button'])>
                <button type="button" aria-label="undo" x-on:click="undo">
                    <x-dynamic-component :component="TallStackUi::component('icon')"
                                         :icon="TallStackUi::icon('arrow-uturn-left')"
                                         @class($personalize['icons']) />
                </button>
                <button type="button" aria-label="redo" x-on:click="redo">
                    <x-dynamic-component :component="TallStackUi::component('icon')"
                                         :icon="TallStackUi::icon('arrow-uturn-right')"
                                         @class($personalize['icons']) />
                </button>
                <button type="button" aria-label="clear" x-on:click="clear">
                    <x-dynamic-component :component="TallStackUi::component('icon')"
                                         :icon="TallStackUi::icon('trash')"
                                         @class($personalize['icons']) />
                </button>
            </div>
            <button type="button" aria-label="export" x-on:click="exportImage" {{ $attributes->only('x-on:export') }}>
                <x-dynamic-component :component="TallStackUi::component('icon')"
                                     :icon="TallStackUi::icon('document-arrow-down')"
                                     @class($personalize['icons']) />
            </button>
        </div>
       <div @class($personalize['canvas.wrapper'])>
         <canvas x-ref="canvas"
                wire:ignore
                @class($personalize['canvas.base'])
                :height="height"
                style="cursor: crosshair; max-height: {{ $height }}px"
                x-on:mousedown="startDrawing"
                x-on:mousemove="draw"
                x-on:mouseup="stopDrawing"
                x-on:mouseleave="stopDrawing"
                x-on:touchstart="startDrawing"
                x-on:touchmove="draw"
                x-on:touchend="stopDrawing"
                x-on:touchcancel="stopDrawing">
        </canvas>
       </div>
    </div>
</x-dynamic-component>
