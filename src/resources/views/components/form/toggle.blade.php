@php
    $personalize = $classes();
@endphp

<x-dynamic-component :component="TallStackUi::prefix('wrapper.radio')" :$id :$property :$error :$label :$position :$alignment :$invalidate>
    <div class="{{ $personalize['wrapper'] }}">
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
</x-dynamic-component>
