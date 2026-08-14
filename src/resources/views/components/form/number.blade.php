@php
    $customization = $classes();
@endphp

<x-dynamic-component :component="TallStackUi::prefix('wrapper.input')" :$id :$property :$error :$label :$hint
                     :$invalidate>
    <div @class([
            $customization['input.wrapper'],
            $customization['input.color.base'] => !$error,
            $customization['input.color.background'] => !$locked,
            $customization['input.color.disabled'] => $locked,
            $customization['error'] => $error === true
        ]) x-data="tallstackui_formNumber({!! $entangle !!}, @js($min), @js($max), @js($configurations['delay']), @js($step), @js($debounce), @js($disabled), @js($readonly))">
        <div @class([$customization['buttons.wrapper'], $customization['input.wrapper-centralized'] => $configurations['centralized']])>
            <input @if ($id) id="{{ $id }}" @endif
            type="number"
                   inputmode="{{ $mode() }}"
                   pattern="{{ $pattern() }}"
                   @if ($min) min="{{ $min }}" @endif
                   @if ($max) max="{{ $max }}" @endif
                   @if ($step) step="{{ $step }}" @endif
                   @if ($configurations['selectable']) x-on:keydown="$event.preventDefault()" @endif
                   {{ $attributes->class([
                        $customization['input.base'],
                        $customization['input.centralized'] => $configurations['centralized'],
                        $customization['input.caret'] => $configurations['selectable'],
                        $customization['input.appearance']
                    ])}}
                   dusk="tallstackui_form_number_input"
                   x-on:blur="validate()"
                   x-ref="input">
            <button @if (!$locked) x-on:click="decrement()"
                    x-on:pointerdown="if (!interval) interval = setInterval(() => decrement(), delay * 100);"
                    x-on:pointerup="if (interval) { clearInterval(interval); interval = null; }"
                    x-on:pointerleave="if (interval) { clearInterval(interval); interval = null; }"
                    x-on:pointercancel="if (interval) { clearInterval(interval); interval = null; }"
                    @endif
                    x-ref="minus"
                    type="button"
                    @disabled($locked)
                    dusk="tallstackui_form_number_decrement"
                    @class([$customization['buttons.left.base'], $customization['buttons.left.centralized'] => $configurations['centralized']])>
                <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                     :icon="$icons['left']"
                                     internal
                        @class([$customization['buttons.left.size'], $customization['buttons.left.color'] => !$error, $customization['buttons.left.error'] => $error]) />
            </button>
            <button @if (!$locked) x-on:click="increment()"
                    x-on:pointerdown="if (!interval) interval = setInterval(() => increment(), delay * 100);"
                    x-on:pointerup="if (interval) { clearInterval(interval); interval = null; }"
                    x-on:pointerleave="if (interval) { clearInterval(interval); interval = null; }"
                    x-on:pointercancel="if (interval) { clearInterval(interval); interval = null; }"
                    @endif
                    x-ref="plus"
                    type="button"
                    @disabled($locked)
                    dusk="tallstackui_form_number_increment"
                    @class([$customization['buttons.right.base'], $customization['buttons.right.separator'] => !$configurations['centralized']])>
                <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                     :icon="$icons['right']"
                                     internal
                        @class([$customization['buttons.right.size'], $customization['buttons.right.color'] => !$error, $customization['buttons.right.error'] => $error]) />
            </button>
        </div>
    </div>
</x-dynamic-component>
