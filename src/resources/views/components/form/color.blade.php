@php
    [$property, $error, $id, $entangle] = $bind($attributes, $errors ?? null, $livewire);
    $personalize = $classes();
@endphp

<x-dynamic-component :component="TallStackUi::component('wrapper.input')" :$id :$property :$error :$label :$hint :$invalidate>
    <div x-data="tallstackui_formColor(
            {!! $entangle !!},
            @js($mode),
            @js($colors),
            @js($attributes->get('value')))"
         x-cloak
         @class([
            $personalize['input.wrapper'],
            $personalize['input.color.base'] => !$error,
            $personalize['input.color.background'] => !$attributes->get('disabled') && !$attributes->get('readonly'),
            $personalize['input.color.disabled'] => $attributes->get('disabled') || $attributes->get('readonly'),
            $personalize['error'] => $error
         ])>
        <div @class($personalize['selected.wrapper'])>
            <template x-if="model">
                <button type="button"
                        @class($personalize['selected.base'])
                        :style="{ 'background-color': model }"
                        x-on:click="show = !show">
                </button>
            </template>
        </div>
        <div class="w-full" wire:ignore>
            <input @if ($id) id="{{ $id }}" @endif
                   {{ $attributes->class($personalize['input.base']) }}
                   type="text"
                   x-model="model"
                   x-ref="input"
                   @if ($selectable) x-on:click="show = !show" @endif
                   @readonly($selectable)>
        </div>
        <div class="flex items-center">
            <button type="button"
                    @if ($attributes->get('disabled') || $attributes->get('readonly')) disabled @endif
                    x-on:click="show = !show"
                    dusk="tallstackui_form_color">
                <x-dynamic-component :component="TallStackUi::component('icon')" icon="swatch" :$error @class($personalize['icon.class']) />
            </button>
        </div>
        <div x-cloak
             x-show="show"
             x-on:click.away="show = false"
             x-transition:enter="transition duration-100 ease-out"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             x-anchor.bottom-end="$refs.wrapper"
             @class($personalize['box.wrapper'])>
            <div @class($personalize['box.base'])>
                <input type="range"
                       min="1"
                       max="11"
                       x-model="weight"
                       x-show="mode === 'range' && colors.length === 0"
                       dusk="tallstackui_form_range"
                       @class([$personalize['box.range.base'], $personalize['box.range.thumb']])>
                <div @class($personalize['box.button.wrapper'])>
                    <template x-for="color in palette">
                        <button type="button" @class($personalize['box.button.base']) x-on:click="set(color)">
                            <div @class($personalize['box.button.color']) :style="{ 'background-color': color }">
                                <span x-show="color === model" x-bind:class="{'text-white': !check(color), 'text-dark-500': check(color)}">
                                    <x-dynamic-component :component="TallStackUi::component('icon')" icon="check" @class($personalize['box.button.icon']) />
                                </span>
                            </div>
                        </button>
                    </template>
                </div>
            </div>
        </div>
    </div>
</x-dynamic-component>
