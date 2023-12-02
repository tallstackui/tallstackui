@php
    $computed = $attributes->whereStartsWith('wire:model');
    $directive = array_key_first($computed->getAttributes());
    $property = $computed[$directive];
    $error = $property && $errors->has($property);
    $live = str($directive)->contains('.live');
    $personalize = tallstackui_personalization('form.number', $personalization());
@endphp

<x-wrapper.input :$id :computed="$property" :$error :$label :$hint validate>
    <div @class([
            $personalize['input.class.color'] => !$error,
            $personalize['input.class.wrapper'],
            $personalize['input.class.disabled'] => $attributes->get('disabled') || $attributes->get('readonly'),
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
        ]) }} x-ref="input">
        <div @class($personalize['buttons.wrapper'])>
            <button x-on:click="decrement()"
                    x-on:mousedown="interval = setInterval(() => decrement(), delay * 100);"
                    x-on:mouseup="clearInterval(interval);"
                    x-on:mouseleave="clearInterval(interval);"
                    x-bind:class="{ 'opacity-30': atMin, 'pointer-events-none opacity-50': disabled }"
                    type="button"
                    dusk="tallstackui_form_number_decrement"
                    @class($personalize['buttons.left.base'])>
                <x-icon name="minus"
                        @class([
                            $personalize['buttons.left.size'],
                            $personalize['buttons.left.color'] => !$error,
                            $personalize['buttons.left.error'] => $error
                        ]) />
            </button>
            <button x-on:click="increment()"
                    x-on:mousedown="interval = setInterval(() => increment(), delay * 100);"
                    x-on:mouseup="clearInterval(interval);"
                    x-on:mouseleave="clearInterval(interval);"
                    x-bind:class="{ 'opacity-30': atMax, 'pointer-events-none opacity-50': disabled }"
                    type="button"
                    dusk="tallstackui_form_number_increment"
                    @class($personalize['buttons.right.base'])>
                <x-icon name="plus"
                        @class([
                            $personalize['buttons.right.size'],
                            $personalize['buttons.right.color'] => !$error,
                            $personalize['buttons.right.error'] => $error
                        ]) />
            </button>
        </div>
    </div>
</x-wrapper.input>
