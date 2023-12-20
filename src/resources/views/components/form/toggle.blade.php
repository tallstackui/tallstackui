@php
    $personalize = $classes();
    $wire = $wireable($attributes);
    $id = $id($attributes);
    $error = !$invalidate && $wire && $errors->has($wire->value());
    [$position, $alignment, $label] = $sloteable($label);

    // We remove any bg color classes from the wrapper if there
    // is an error to apply the red bg color to the input instead
    $personalize['wrapper.class'] = $error ? preg_replace('/\bbg-[a-zA-Z0-9-]+/', '', $personalize['background.class']) : $personalize['background.class'];
@endphp

<x-wrapper.radio :$id :$wire :$label :$position :$alignment :$invalidate>
    <div @class($personalize['wrapper'])>
        <input @if ($id) id="{{ $id }}" @endif type="checkbox" {{ $attributes->class([
            $personalize['input.class'],
            $personalize['input.sizes.' . $size],
        ]) }}>
        <div @class([
            $personalize['background.class'],
            $personalize['background.sizes.' . $size],
            $colors['background'],
            $personalize['error'] => $error
        ])></div>
    </div>
</x-wrapper.radio>
