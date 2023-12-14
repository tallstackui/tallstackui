@php
    $personalize = $classes();
    $wire = $attributes->wire('model');
    $error = $wire->value() && $errors->has($wire->value());
    $slot = $label instanceof \Illuminate\View\ComponentSlot;
    $position = $slot && $label->attributes->has('left') ? 'left' : $position;
    $alignment = $slot && $label->attributes->has('start') ? 'start' : 'middle';
    $label = $slot ? $label->toHtml() : $label;
@endphp

<x-wrapper.radio :$id :computed="$wire->value()" :$error :$label :$position :$alignment>
    <input @if ($id) id="{{ $id }}" @endif type="checkbox" {{ $attributes->class([
            $personalize['input.class'],
            $personalize['input.sizes.' . $size],
            $colors['background'],
            $personalize['error'] => $error
    ]) }}>
</x-wrapper.radio>
