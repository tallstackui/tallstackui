@php
    [$property,, $id, $entangle] = $bind($attributes, $errors ?? null, $livewire);
    $personalize = $classes();
    $value = $attributes->get('value');
@endphp

@if (!$livewire && $property)
    <input hidden id="{{ $id }}" name="{{ $property }}">
@endif

<div x-data="tallstackui_formColor(
        {!! $entangle !!},
        @js($mode),
        @js($colors),
        @js($livewire),
        @js($property),
        @js($value))"
        x-cloak>
    @if (!$selectable)
        <x-dynamic-component :component="TallStackUi::component('input')"
                             {{ $attributes->except(['name', 'value']) }}
                             :$label
                             :$hint
                             :$invalidate
                             :alternative="$attributes->get('name')"
                             x-ref="input"
                             x-model="model"
                             maxlength="7"
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
    @else
        <x-dynamic-component :component="TallStackUi::component('input')"
                             {{ $attributes->except(['name', 'value']) }}
                             :$label
                             :$hint
                             :$invalidate
                             :alternative="$attributes->get('name')"
                             x-ref="input"
                             x-model="model"
                             x-on:click="show = !show"
                             class="cursor-pointer caret-transparent"
                             x-on:keydown="$event.preventDefault()"
                             spellcheck="false">
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
    @endif
    <x-dynamic-component :component="TallStackUi::component('floating')"
                         size="w-[18rem]"
                         x-on:click.outside="show = false"
                         :wrapper="$personalize['box.wrapper']">
        <div @class($personalize['box.base'])>
            <div class="px-4">
                <input type="range"
                       min="1"
                       max="11"
                       x-model="weight"
                       x-show="mode === 'range' && colors.length === 0"
                       dusk="tallstackui_form_range"
                        @class([$personalize['box.range.base'], $personalize['box.range.thumb']])>
            </div>
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
    </x-dynamic-component>
</div>
