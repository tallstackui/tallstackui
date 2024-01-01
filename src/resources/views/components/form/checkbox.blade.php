@php
    [$property, $error, $id] = $bind($attributes, $errors ?? null, $livewire);
    $personalize = $classes();
    [$position, $alignment, $label] = $sloteable($label);
    $value = $attributes->get('value');
@endphp

<x-dynamic-component :component="$resolver('wrapper.radio')" :$id :$property :$error :$label :$position :$alignment :$invalidate>
    <input @if ($id) id="{{ $id }}" @endif type="checkbox" {{ $attributes->class([
            $personalize['input.class'],
            $personalize['input.sizes.' . $size],
            $colors['background'],
            $personalize['error'] => $error
    ]) }} @checked((bool) $value === true)>
</x-dynamic-component>
