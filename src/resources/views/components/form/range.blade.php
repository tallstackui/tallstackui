@php
    $wire = $wireable($attributes);
    $error = $validate && $wire && $errors->has($wire->value());
    $personalize = $classes();
@endphp

<x-wrapper.input :$id :$wire :$label :$hint :$validate :wrapper="$personalize['input.wrapper']">
    <input id="{{ $id }}"
           type="range"
           {{ $attributes->class([
                $personalize['input.base'],
                $personalize['input.sizes.' . $size],
                $personalize['input.disabled'] => $attributes->get('disabled') || $attributes->get('readonly'),
                $colors['thumb'],
            ]) }} dusk="tallstackui_form_range_input" x-ref="input">
</x-wrapper.input>
