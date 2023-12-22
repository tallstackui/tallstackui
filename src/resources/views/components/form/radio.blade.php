@php
    [$property, $error, $id] = $bind($attributes, $errors ?? null, isset($__livewire));
    $personalize = $classes();
    [$position, $alignment, $label] = $sloteable($label);
    $value = $attributes->get('value');
@endphp

<x-wrapper.radio :$id :$property :$error :$label :$position :$alignment :$invalidate>
    <input @if ($id) id="{{ $id }}" @endif type="radio" {{ $attributes->class([
            $personalize['input.class'],
            $personalize['input.sizes.' . $size],
            $colors['background'],
            $personalize['error'] => $error
    ]) }} @checked((bool) $value === true)>
</x-wrapper.radio>
