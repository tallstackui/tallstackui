@php
    [$property, $error, $id] = $bind($attributes, $errors, $__env, isset($__livewire));
    $personalize = $classes();
    [$position, $alignment, $label] = $sloteable($label);
@endphp

<x-wrapper.radio :$id :$property :$error :$label :$position :$alignment :$invalidate>
    <input @if ($id) id="{{ $id }}" @endif type="radio" {{ $attributes->class([
            $personalize['input.class'],
            $personalize['input.sizes.' . $size],
            $colors['background'],
            $personalize['error'] => $error
    ]) }}>
</x-wrapper.radio>
