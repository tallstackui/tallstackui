@php
    [$property, $error, $id] = $bind($attributes, $errors ?? null, isset($__livewire));
    $personalize = $classes();
    [$position, $alignment, $label] = $sloteable($label);
    $value = $attributes->get('value');

    // We remove any bg color classes from the wrapper if there
    // is an error to apply the red bg color to the input instead
    $personalize['wrapper.class'] = $error ? preg_replace('/\bbg-[a-zA-Z0-9-]+/', '', $personalize['background.class']) : $personalize['background.class'];
@endphp

<x-wrapper.radio :$id :$property :$error :$label :$position :$alignment :$invalidate>
    <div @class($personalize['wrapper'])>
        <input @if ($id) id="{{ $id }}" @endif type="checkbox" {{ $attributes->class([
            $personalize['input.class'],
            $personalize['input.sizes.' . $size],
        ]) }} @checked($value && (bool) $value === true)>
        <div @class([
            $personalize['background.class'],
            $personalize['background.sizes.' . $size],
            $colors['background'],
            $personalize['error'] => $error
        ])></div>
    </div>
</x-wrapper.radio>
