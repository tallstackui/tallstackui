@php
    $customization = $classes();
@endphp

<x-dynamic-component :component="TallStackUi::prefix('wrapper.input')" :$id :$property :$error :$label :$hint
                     :$invalidate :wrapper="$customization['input.wrapper']">
    <input @if ($id) id="{{ $id }}" @endif
    type="range"
           {{ $attributes->class([
                $customization['input.base'],
                $customization['input.sizes.' . $size],
                $customization['input.disabled'] => $attributes->get('disabled') || $attributes->get('readonly'),
                $colors['thumb'],
            ]) }} dusk="tallstackui_form_range_input">
</x-dynamic-component>
