@php
    $wire = $wireable($attributes);
    $error = !$invalidate && $wire && $errors->has($wire->value());
    $personalize = $classes();
    $id = $id($attributes);
@endphp

<x-wrapper.input :$id :$wire :$label :$hint :$invalidate>
    <div @class([
        $personalize['input.wrapper'],
        $personalize['input.color.base'] => !$error,
        $personalize['input.color.background'] => !$attributes->get('disabled') && !$attributes->get('readonly'),
        $personalize['input.color.disabled'] => $attributes->get('disabled') || $attributes->get('readonly'),
        $personalize['error'] => $error,
    ])>
        <textarea @if ($resizeAuto) x-data="tallstackui_formTextArea()" x-on:input="resize()" @endif {{ $attributes->class([
            'resize-none' => !$resize && !$resizeAuto,
            $personalize['input.base'],
        ])->merge(['rows' => 3]) }} id="{{ $id }}">{{ $slot }}</textarea>
    </div>
</x-wrapper.input>
