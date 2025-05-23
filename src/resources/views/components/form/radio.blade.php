@php
    $personalize = $classes();
@endphp

<x-dynamic-component :component="TallStackUi::prefix('wrapper.radio')" :$id :$property :$error :$label :$position :$alignment :$invalidate>
    <input @if ($id) id="{{ $id }}" @endif type="radio" {{ $attributes->class([
            $personalize['input.class'],
            $personalize['input.sizes.' . $size],
            $colors['background'],
            $personalize['error'] => $error
    ]) }}>
</x-dynamic-component>
