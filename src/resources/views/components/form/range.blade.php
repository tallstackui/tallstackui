@php
    $wire = $wireable($attributes);
    $error = !$invalidate && $wire && $errors->has($wire->value());
    $personalize = $classes();
    $id = $id($attributes);
@endphp

<x-wrapper.input :$id :$wire :$label :$hint :$invalidate :wrapper="$personalize['input.wrapper']">
    <input @if ($id) id="{{ $id }}" @endif
           type="range"
           {{ $attributes->class([
                $personalize['input.base'],
                $personalize['input.sizes.' . $size],
                $personalize['input.disabled'] => $attributes->get('disabled') || $attributes->get('readonly'),
                $colors['thumb'],
            ]) }} dusk="tallstackui_form_range_input" x-ref="input">
</x-wrapper.input>
