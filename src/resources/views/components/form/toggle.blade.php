@php
    $customization = $classes();
@endphp

<x-dynamic-component :component="TallStackUi::prefix('wrapper.radio')" :$id :$property :$error :$label :$position
                     :$invalidate :$locked>
    <div class="{{ $customization['wrapper'] }}">
        <input @if ($id) id="{{ $id }}" @endif type="checkbox" {{ $attributes->class([
            $customization['input.class'],
            $customization['input.sizes.' . $size],
            $customization['locked'] => $locked,
        ]) }}
        @if ($readonly) x-data x-on:click.prevent aria-readonly="true" @endif>
        <div @class([
            $customization['background.class'],
            $customization['background.sizes.' . $size],
            $colors['background'],
            $customization['locked'] => $locked,
            $customization['error'] => $error
        ])></div>
    </div>
</x-dynamic-component>
