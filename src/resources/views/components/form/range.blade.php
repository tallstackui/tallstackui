@php
    $computed = $attributes->whereStartsWith('wire:model');
    $directive = array_key_first($computed->getAttributes());
    $property = $computed[$directive];
    $personalize = tallstackui_personalization('form.range', $personalization());
    $disabled = $attributes->get('disabled');
    $readonly = $attributes->get('readonly');
@endphp

<x-wrapper.input :wrapper="$personalize['input.wrapper']" :$id :computed="$property">
    <input id="{{ $id }}"
           type="range"
           {{ $attributes->class([
                $personalize['input.base'],
                $personalize['input.sizes.' . $size],
                $personalize['input.disabled'] => $disabled || $readonly,
                $colors['thumb'],
            ]) }} dusk="tallstackui_form_range_input" x-ref="input">
</x-wrapper.input>
