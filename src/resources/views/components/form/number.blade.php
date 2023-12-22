@php
    $icons = $icons();
    [$property, $error, $id] = $bind($attributes, $errors ?? null, $__env, isset($__livewire));
    $personalize = $classes();
@endphp

<x-wrapper.input :$id :$property :$error :$label :$hint :$invalidate>
    <div @class([
            $personalize['input.class.wrapper'],
            $personalize['input.class.color.base'] => !$error,
            $personalize['input.class.color.background'] => !$attributes->get('disabled') && !$attributes->get('readonly'),
            $personalize['input.class.color.disabled'] => $attributes->get('disabled') || $attributes->get('readonly'),
            $personalize['error'] => $error === true
        ]) x-data="tallstackui_formNumber(
            @entangleable($attributes),
            @js($min),
            @js($max),
            @js($delay),
            @js($attributes->get('disabled', false) || $attributes->get('readonly', false))
        )">
        <input @if ($id) id="{{ $id }}" @endif
               type="number"
               inputmode="numeric"
               @if ($min) min="{{ $min }}" @endif
               @if ($max) max="{{ $max }}" @endif
               {{ $attributes->class([$personalize['input.class.base'], 'appearance-number-none'])}}
               dusk="tallstackui_form_number_input"
               x-on:blur="validate()"
               x-ref="input">
        <div @class($personalize['buttons.wrapper'])>
            <button x-on:click="decrement()"
                    x-on:mousedown="interval = setInterval(() => decrement(), delay * 100);"
                    x-on:touchstart="interval = setInterval(() => decrement(), delay * 100);"
                    x-on:mouseup="clearInterval(interval);"
                    x-on:mouseleave="clearInterval(interval);"
                    x-on:touchend="clearInterval(interval);"
                    x-ref="minus"
                    type="button"
                    @if ($attributes->get('disabled') || $attributes->get('readonly')) disabled @endif
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
                    x-ref="plus"
                    type="button"
                    @if ($attributes->get('disabled') || $attributes->get('readonly')) disabled @endif
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
