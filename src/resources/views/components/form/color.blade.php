@php
    [$property, $error, $id, $entangle] = $bind($attributes, $errors ?? null, $livewire);
    $personalize = $classes();
    $value = $attributes->get('value');
@endphp

{{-- // TODO: Make work without livewire --}}
@if (!$livewire && $property)
    <input hidden id="{{ $id }}" name="{{ $property }}">
@endif

<div x-data="tallstackui_formColor(
        {!! $entangle ?? $value !!},
        @js($mode),
        @js($colors),
        @js($livewire),
        @js($property),
        @js($value))"
        x-ref="wrapper"
        x-cloak>
    <x-dynamic-component :component="TallStackUi::component('input')"
                         {{ $attributes->except(['name', 'value']) }}
                         :$label
                         :$hint
                         :$invalidate
                         :alternative="$attributes->get('name')"
                         x-ref="input"
                         x-model="model"
                         x-on:click="show = !show"
                         class="cursor-pointer">
                         <x-slot:prefix>
                            <div @class($personalize['selected.wrapper'])>
                                <template x-if="model">
                                    <button type="button"
                                            @class($personalize['selected.base'])
                                            :style="{ 'background-color': model }"
                                            x-on:click="show = !show"></button>
                                </template>
                            </div>
                         </x-slot:prefix>
                         <x-slot:suffix>
                            <div class="flex items-center gap-1">
                                <button type="button" x-on:click="show = !show">
                                    <x-dynamic-component :component="TallStackUi::component('icon')"
                                                            :icon="TallStackUi::icon('swatch')"
                                                            @class($personalize['icon.class']) />
                                </button>
                            </div>
                         </x-slot:suffix>
    </x-dynamic-component>
    <div x-cloak
         x-show="show"
         x-on:click.away="show = false"
         x-transition:enter="transition duration-100 ease-out"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         x-anchor.bottom-end="$refs.anchor"
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
                    <button type="button" @class($personalize['box.button.base']) {{ $attributes->only('x-on:set') }} x-on:click="set(color)">
                        <div @class($personalize['box.button.color']) :style="{ 'background-color': color }">
                            <span x-show="color === model" x-bind:class="{'text-white': !check(color), 'text-dark-500': check(color)}">
                                <x-dynamic-component :component="TallStackUi::component('icon')"
                                                        :icon="TallStackUi::icon('check')"
                                                        @class($personalize['box.button.icon']) />
                            </span>
                        </div>
                    </button>
                </template>
            </div>
        </div>
    </div>
</div>