@php
    $icons = $icons();
    $computed = $attributes->whereStartsWith('wire:model');
    $directive = array_key_first($computed->getAttributes());
    $property = $computed[$directive];
    $error = $property && $errors->has($property);
    $live = str($directive)->contains('.live');
    $personalize = tallstackui_personalization('form.number', $personalization());
    $disabled = $attributes->get('disabled');
    $readonly = $attributes->get('readonly');
@endphp

<x-wrapper.input :$id :computed="$property" :$error :$label :$hint validate>
    <div @class([
            $personalize['input.class.wrapper'],
            $personalize['input.class.color.base'] => !$error,
            $personalize['input.class.color.background'] => !$disabled && !$readonly,
            $personalize['input.class.color.disabled'] => $disabled || $readonly,
            $personalize['error'] => $error
        ]) x-data="tallstackui_formNumber(
            @if ($live) @entangle($property).live @else @entangle($property) @endif,
            @js($min),
            @js($max),
            @js($delay),
            @js($attributes->get('disabled', false) || $attributes->get('readonly', false))
        )">
        <input id="{{ $id }}" type="number" inputmode="numeric" {{ $attributes->class([
            $personalize['input.class.base'],
            'appearance-number-none',
        ]) }} dusk="tallstackui_form_number_input" x-ref="input">
        <div @class($personalize['buttons.wrapper'])>
            <button x-on:click="decrement()"
                    x-on:mousedown="interval = setInterval(() => decrement(), delay * 100);"
                    x-on:touchstart="interval = setInterval(() => decrement(), delay * 100);"
                    x-on:mouseup="clearInterval(interval);"
                    x-on:mouseleave="clearInterval(interval);"
                    x-on:touchend="clearInterval(interval);"
                    x-bind:class="{ 'opacity-30': atMin, 'pointer-events-none opacity-50': disabled }"
                    type="button"
                    @if ($disabled || $readonly) disabled @endif
                    dusk="tallstackui_form_number_decrement"
                    @class($personalize['buttons.left.base'])>
                <x-icon :name="$icons['left']"
                        @class([
                            $personalize['buttons.left.size'],
                            $personalize['buttons.left.color'] => !$error,
                            $personalize['buttons.left.error'] => $error
                        ]) />
            </button>
            <button x-on:click="increment()"
                    x-on:mousedown="interval = setInterval(() => increment(), delay * 100);"
                    x-on:touchstart="interval = setInterval(() => increment(), delay * 100);"
                    x-on:mouseup="clearInterval(interval);"
                    x-on:mouseleave="clearInterval(interval);"
                    x-on:touchend="clearInterval(interval);"
                    x-bind:class="{ 'opacity-30': atMax, 'pointer-events-none opacity-50': disabled }"
                    type="button"
                    @if ($disabled || $readonly) disabled @endif
                    dusk="tallstackui_form_number_increment"
                    @class($personalize['buttons.right.base'])>
                <x-icon :name="$icons['right']"
                        @class([
                            $personalize['buttons.right.size'],
                            $personalize['buttons.right.color'] => !$error,
                            $personalize['buttons.right.error'] => $error
                        ]) />
            </button>
        </div>
    </div>
</x-wrapper.input>
