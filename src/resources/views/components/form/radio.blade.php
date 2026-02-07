@php
    $customization = $classes();
@endphp

<x-dynamic-component :component="TallStackUi::prefix('wrapper.radio')" :$id :$property :$error :$label :$position
                     :$alignment :$invalidate>
    <input @if ($id) id="{{ $id }}" @endif type="radio" {{ $attributes->class([
            $customization['input.class'],
            $customization['input.sizes.' . $size],
            $colors['background'],
            $customization['error'] => $error
    ]) }}>
</x-dynamic-component>
