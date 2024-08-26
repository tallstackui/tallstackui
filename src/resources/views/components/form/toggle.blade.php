@php
    $personalize = $classes(fn (array $personalize) => $error ? array_merge($personalize, ['background.class' => preg_replace('/\bbg-[a-zA-Z0-9-]+/', '', $personalize['background.class'])]) : $personalize);
@endphp

<x-dynamic-component :component="TallStackUi::component('wrapper.radio')" :$id :$property :$error :$label :$position :$alignment :$invalidate>
    <div @class($personalize['wrapper'])>
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
