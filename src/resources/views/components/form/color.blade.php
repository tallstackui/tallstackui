@php
    $personalize = $classes();
@endphp

@if (!$livewire && $property)
    <input hidden name="{{ $property }}">
@endif

<div x-data="tallstackui_formColor(
        {!! $entangle !!},
        @js($mode),
        @js($configurations['colors']),
        @js($livewire),
        @js($property),
        @js($attributes->get('value')))"
        x-cloak>
    <x-dynamic-component :component="TallStackUi::component('input')"
                         {{ $attributes->merge($select)->class([
                            '-ml-3',
                            'cursor-pointer caret-transparent' => $selectable,
                         ])->except(['name', 'value']) }}
                         :$label
                         :$hint
                         :$invalidate
                         :alternative="$attributes->get('name')"
                         floatable
                         x-ref="input"
                         x-model="model"
                         maxlength="7">
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
            <div class="flex items-center min-w-full">
                <div @class([ $personalize['clearable.wrapper'], $personalize['clearable.padding']])>
                    <button type="button" dusk="tallstackui_form_color_clearable" x-show="clearable">
                        <x-dynamic-component :component="TallStackUi::component('icon')"
                        :icon="TallStackUi::icon('x-mark')"
                        x-on:click="clear()"
                        @class([
                            $personalize['clearable.size'],
                            $personalize['clearable.color'] => !$invalidate,
                        ]) />
                    </button>
                </div>
                <button type="button" x-on:click="show = !show" dusk="tallstackui_form_color_open_close">
                    <x-dynamic-component :component="TallStackUi::component('icon')"
                                         :icon="TallStackUi::icon('swatch')"
                                         @class($personalize['icon.class']) />
                </button>
            </div>
        </x-slot:suffix>
    </x-dynamic-component>
    <x-dynamic-component :component="TallStackUi::component('floating')"
                         :floating="$personalize['floating']"
                         class="w-[18rem] overflow-auto"
                         x-on:click.outside="show = false">
        <div @class($personalize['box.base'])>
            <div @class($personalize['box.range.wrapper'])>
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
