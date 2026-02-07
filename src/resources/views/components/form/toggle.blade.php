@php
    $customization = $classes();
@endphp

<x-dynamic-component :component="TallStackUi::prefix('wrapper.radio')" :$id :$property :$error :$label :$position
                     :$alignment :$invalidate>
    <div class="{{ $customization['wrapper'] }}">
        <input @if ($id) id="{{ $id }}" @endif type="checkbox" {{ $attributes->class([
            $customization['input.class'],
            $customization['input.sizes.' . $size],
        ]) }}>
        <div @class([
            $customization['background.class'],
            $customization['background.sizes.' . $size],
            $colors['background'],
            $customization['error'] => $error
        ])></div>
    </div>
</x-dynamic-component>
