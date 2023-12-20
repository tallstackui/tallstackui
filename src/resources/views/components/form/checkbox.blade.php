@php
    $personalize = $classes();
    $wire = $wireable($attributes);
    $error = !$invalidate && $wire && $errors->has($wire->value());
    [$position, $alignment, $label] = $sloteable($label);
    $id = $id($attributes);
@endphp

<x-wrapper.radio :$id :$wire :$label :$position :$alignment :$invalidate>
    <input @if ($id) id="{{ $id }}" @endif type="checkbox" {{ $attributes->class([
            $personalize['input.class'],
            $personalize['input.sizes.' . $size],
            $colors['background'],
            $personalize['error'] => $error
    ]) }}>
</x-wrapper.radio>
