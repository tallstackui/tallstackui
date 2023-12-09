@php
    $computed = $attributes->whereStartsWith('wire:model');
    $error = $computed && $errors->has($computed);
    $personalize = tallstackui_personalization('form.color', $personalization());
    $directive = array_key_first($computed->getAttributes());
    $property = $computed[$directive];
    $live = str($directive)->contains('.live');
    $disabled = $attributes->get('disabled');
    $readonly = $attributes->get('readonly');
@endphp

<x-wrapper.input :$id :computed="$property" :$error :$label :$hint validate>
    <div x-data="tallstackui_formColor(
            @if ($live) @entangle($property).live @else @entangle($property) @endif,
            @js($mode),
            @js($custom))"
         x-ref="wrapper"
         x-cloak
         @class([
            $personalize['input.wrapper'],
            $personalize['input.color.base'] => !$error,
            $personalize['input.color.background'] => !$disabled && !$readonly,
            $personalize['input.color.disabled'] => $disabled || $readonly,
            $personalize['error'] => $error
         ])>
        <div @class($personalize['selected.wrapper'])>
            <template x-if="selected">
                <div @class($personalize['selected.base']) :style="{ 'background-color': selected }"></div>
            </template>
        </div>
        <input id="{{ $id }}" {{ $attributes->class([$personalize['input.base']]) }} type="text" x-ref="input">
        <div class="flex items-center">
            <button @if ($disabled || $readonly) disabled @endif
                    x-on:click="show = !show"
                    dusk="tallstackui_form_color">
                <x-icon name="swatch" :$error @class($personalize['icon.class'])/>
            </button>
        </div>
        <div x-cloak
             x-show="show"
             x-on:click.away="show = false"
             x-transition:enter="{{ $personalize['animation.enter'] }}"
             x-transition:enter-start="{{ $personalize['animation.enter-start'] }}"
             x-transition:enter-end="{{ $personalize['animation.enter-end'] }}"
             x-transition:leave="{{ $personalize['animation.leave'] }}"
             x-transition:leave-start="{{ $personalize['animation.leave-start'] }}"
             x-transition:leave-end="{{ $personalize['animation.leave-end'] }}"
             x-anchor.bottom-end.offset.5="$refs.wrapper"
             @class($personalize['box.wrapper'])>
            <div @class($personalize['box.base'])>
                <input type="range" min="1" max="11" x-model="weight" x-show="mode === 'range' && custom.length === 0" @class([$personalize['box.range.base'], $personalize['box.range.thumb']])>
                <div @class($personalize['box.button.wrapper'])>
                    <template x-for="color in palette">
                        <button type="button" @class($personalize['box.button.base']) x-on:click="selectColor(color)">
                            <div @class($personalize['box.button.color']) :style="{ 'background-color': color }">
                                <span x-show="color === selected" x-bind:class="{'text-white': !colorCheck(color), 'text-dark-500': colorCheck(color)}" @class($personalize['box.button.icon'])>
                                    <svg  xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                    </svg>
                                </span>
                            </div>
                        </button>
                    </template>
                </div>
            </div>
        </div>
    </div>
</x-wrapper.input>
