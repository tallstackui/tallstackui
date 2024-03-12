@php
    [$property, $error, $id] = $bind($attributes, $errors ?? null, $livewire);
    $personalize = $classes();
@endphp

<x-dynamic-component :component="TallStackUi::component('wrapper.input')" :$id :$property :$error :$label :$hint :$invalidate :wrapper="$personalize['input.wrapper']">
    <input @if ($id) id="{{ $id }}" @endif
           type="range"
           {{ $attributes->class([
                $personalize['input.base'],
                $personalize['input.sizes.' . $size],
                $personalize['input.disabled'] => $attributes->get('disabled') || $attributes->get('readonly'),
                $colors['thumb'],
            ]) }} dusk="tallstackui_form_range_input">
</x-dynamic-component>
