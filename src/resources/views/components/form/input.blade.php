@php
    $personalize = $classes();
@endphp

<x-dynamic-component :component="TallStackUi::component('wrapper.input')" :$id :$property :$error :$label :$hint :$invalidate :floatable="$attributes->get('floatable', false)">
    @if ($icon)
        <div @class([ $personalize['icon.wrapper'], $personalize['icon.paddings.' . $position]])>
            <x-dynamic-component :component="TallStackUi::component('icon')"
                                 :$icon
                                 :$error
                                 @class([
                                     $personalize['icon.size'],
                                     $personalize['error'] => $error,
                                     $personalize['icon.color'] => !$error && !$invalidate
                                 ]) />
        </div>
    @endif
    @if ($clearable)
        <div x-data="tallstackui_formInputClearable()" @class([ $personalize['clearable.wrapper'], $personalize['clearable.padding'], '!pr-8' => $icon && $position === 'right']) x-show="clearable">
            <button type="button" dusk="tallstackui_form_input_clearable">
                <x-dynamic-component :component="TallStackUi::component('icon')"
                                     :icon="TallStackUi::icon('x-mark')"
                                     x-on:click="clear()"
                        @class([
                            $personalize['clearable.size'],
                            $personalize['clearable.color'] => !$error && !$invalidate,
                        ]) />
            </button>
        </div>
    @endif
    <div @class([
            $personalize['input.wrapper'],
            $personalize['input.color.base'] => !$error,
            $personalize['input.color.background'] => !$attributes->get('disabled') && !$attributes->get('readonly'),
            $personalize['input.color.disabled'] => $attributes->get('disabled') || $attributes->get('readonly'),
            $personalize['error'] => $error
        ])>
        @if ($prefix)
            <span @class(['ml-2 mr-1', $personalize['input.slot'], $personalize['error'] => $error])>{{ $prefix }}</span>
        @endif
        <input type="{{ $attributes->get('type', 'text') }}"
               x-ref="input"
               @if ($prefix || $suffix) autocomplete="{{ $attributes->get('autocomplete', 'off') }}" @endif
               {{ $attributes->class([
                    $personalize['input.base'],
                    $personalize['input.paddings.prefix'] => $prefix,
                    $personalize['input.paddings.suffix'] => $suffix,
                    $personalize['input.paddings.left'] => $icon && ($position === null || $position === 'left'),
                    $personalize['input.paddings.right'] => $icon && $position === 'right' || $icon && $clearable,
                    $personalize['input.paddings.clearable'] => $icon && $clearable && $position === 'right',
                ]) }}>
        @if ($suffix)
            <span @class(['ml-1 mr-2', $personalize['input.slot'], $personalize['error'] => $error])>{{ $suffix }}</span>
        @endif
    </div>
</x-dynamic-component>
