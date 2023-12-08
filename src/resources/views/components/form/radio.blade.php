@php
    $computed = $attributes->whereStartsWith('wire:model')->first();
    $error = $errors->has($computed);
    $personalize = tallstackui_personalization('form.radio', $personalization());
    $slot = $label instanceof \Illuminate\View\ComponentSlot;
    $position = $slot && $label->attributes->has('left') ? 'left' : $position;
    $align = $slot && $label->attributes->has('start') ? 'start' : 'middle';
    $label = $slot ? $label->toHtml() : $label;
@endphp

<x-wrapper.radio :$id :$computed :$error :$label :$position :$align>
    <input @if ($id) id="{{ $id }}" @endif type="radio" {{ $attributes->class([
            $personalize['input.class'],
            $personalize['input.sizes.' . $size],
            $colors['background'],
            $personalize['error'] => $error
    ]) }}>
</x-wrapper.radio>
