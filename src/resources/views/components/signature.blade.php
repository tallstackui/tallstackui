@php
    [$property, $error, $id, $entangle] = $bind($attributes, $errors ?? null, $livewire);
    $personalize = $classes();
@endphp

<x-dynamic-component :component="TallStackUi::component('wrapper.input')" :$id :$property :$error :$label :$hint :$invalidate>
    <div x-data="tallstackui_signature({!! $entangle !!}, @js($color), @js($background), @js($line), @js($height))"
         @class($personalize['wrapper.first'])>
        <input type="hidden" x-model="model" {!! $attributes->except('x-on:export') !!}>
        <div @class($personalize['wrapper.second'])>
            <div>
                <button type="button" x-on:click="undo" @class($personalize['button'])>
                    <x-dynamic-component :component="TallStackUi::component('icon')" 
                                         :icon="TallStackUi::icon('arrow-uturn-left')" 
                                         @class($personalize['icons']) />
                </button>
                <button type="button" x-on:click="redo" @class($personalize['button'])>
                    <x-dynamic-component :component="TallStackUi::component('icon')" 
                                         :icon="TallStackUi::icon('arrow-uturn-right')" 
                                         @class($personalize['icons']) />
                </button>
                <button type="button" x-on:click="clear" @class($personalize['button'])>
                    <x-dynamic-component :component="TallStackUi::component('icon')" 
                                         :icon="TallStackUi::icon('trash')" 
                                         @class($personalize['icons']) />
                </button>
            </div>
            <div>
                <button type="button" x-on:click="exportImage" {{ $attributes->only('x-on:export') }}>
                    <x-dynamic-component :component="TallStackUi::component('icon')" 
                                         :icon="TallStackUi::icon('document-arrow-down')" 
                                         @class($personalize['icons']) />
                </button>
            </div>
        </div>
        <div class="w-full">
            <canvas x-ref="canvas"
                    @class($personalize['canvas'])
                    :height="height" 
                    style="image-rendering: pixelated; cursor: crosshair; min-height: {{ $height }}" 
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
