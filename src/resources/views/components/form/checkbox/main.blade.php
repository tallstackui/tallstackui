@php
    $customization = $classes();
@endphp

<x-dynamic-component :component="TallStackUi::prefix('wrapper.radio')" :$id :$property :$error :$label :$position :$invalidate :$locked>
    <input @if ($id) id="{{ $id }}" @endif type="checkbox" {{ $attributes->class([
            $customization['input.class'],
            $customization['input.sizes.' . $size],
            $colors['background'],
            $customization['locked'] => $locked,
            $customization['error'] => $error
    ]) }}
    @if ($readonly) x-data x-on:click.prevent aria-readonly="true" @endif>
</x-dynamic-component>
