@php
    $computed = $attributes->whereStartsWith('wire:model')->first();
    $error = $computed && $errors->has($computed);
    $personalize = $classes();
    $disabled = $attributes->get('disabled');
    $readonly = $attributes->get('readonly');
@endphp

<x-wrapper.input :$id :$computed :$error :$label :$hint validate :wrapper="$personalize['input.wrapper']">
    <input id="{{ $id }}"
           type="range"
           {{ $attributes->class([
                $personalize['input.base'],
                $personalize['input.sizes.' . $size],
                $personalize['input.disabled'] => $disabled || $readonly,
                $colors['thumb'],
            ]) }} dusk="tallstackui_form_range_input" x-ref="input">
</x-wrapper.input>
