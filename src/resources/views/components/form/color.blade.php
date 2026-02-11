@php
    $customization = $classes();
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
        @js($attributes->get('value')),
        @js($clearable),
        @js($excludedColor),
        @js($excludedStep))"
     x-cloak>
    <x-dynamic-component :component="TallStackUi::prefix('input')"
                         scope="form.color.input"
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
        <x-slot:prefix class="ml-2 mr-1">
            <div class="{{ $customization['selected.wrapper'] }}">
                <template x-if="model">
                    <button type="button"
                            class="{{ $customization['selected.base'] }}"
                            x-bind:style="{ 'background-color': model }"
                            x-on:click="show = !show"></button>
                </template>
            </div>
        </x-slot:prefix>
        <x-slot:suffix class="mr-2">
            <div class="{{ $customization['icon.wrapper'] }}">
                @if ($clearable)
                    <button type="button" class="{{ $customization['clearable.button'] }}"
                            dusk="tallstackui_form_color_clearable" x-show="clearable">
                        <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                             :icon="TallStackUi::icon('x-mark')"
                                             internal
                                             x-on:click="clear()"
                                             class="{{ $customization['clearable.size'] }}" />
                    </button>
                @endif
                <button type="button" class="cursor-pointer" x-on:click="show = !show"
                        dusk="tallstackui_form_color_open_close">
                    <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                         :icon="TallStackUi::icon('swatch')"
                                         internal
                                         class="{{ $customization['icon.class'] }}" />
                </button>
            </div>
        </x-slot:suffix>
    </x-dynamic-component>
    <x-dynamic-component :component="TallStackUi::prefix('floating')"
                         scope="form.color.floating"
                         :floating="$customization['floating.default']"
                         :class="$customization['floating.class']"
                         x-on:click.outside="show = false">
        <div class="{{ $customization['box.base'] }}" dusk="tallstackui_form_color_floating">
            <div class="{{ $customization['box.range.wrapper'] }}">
                <input type="range"
                       min="1"
                       x-bind:max="max"
                       x-model="weight"
                       x-show="mode === 'range' && colors.length === 0"
                       dusk="tallstackui_form_range"
                        @class([$customization['box.range.base'], $customization['box.range.thumb']])>
            </div>
            <div class="{{ $customization['box.button.wrapper'] }}">
                <template x-for="color in palette">
                    <button type="button" {{ $attributes->only('x-on:set') }} x-on:click="set(color)">
                        <div class="{{ $customization['box.button.color'] }}" :style="{ 'background-color': color }">
                            <span x-show="color === model"
                                  x-bind:class="{'text-white': !check(color), 'text-dark-500': check(color)}">
                                <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                     :icon="TallStackUi::icon('check')"
                                                     internal
                                                     class="{{ $customization['box.button.icon'] }}" />
                            </span>
                        </div>
                    </button>
                </template>
            </div>
        </div>
    </x-dynamic-component>
</div>
