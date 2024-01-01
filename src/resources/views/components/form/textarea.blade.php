@php
    [$property, $error, $id] = $bind($attributes, $errors ?? null, $livewire);
    $personalize = $classes();
    $slot = $attributes->get('value', $slot);
@endphp

<x-dynamic-component :component="$resolver('wrapper.input')" :$id :$property :$error :$label :$hint :$invalidate>
    <div @class([
        $personalize['input.wrapper'],
        $personalize['input.color.base'] => !$error,
        $personalize['input.color.background'] => !$attributes->get('disabled') && !$attributes->get('readonly'),
        $personalize['input.color.disabled'] => $attributes->get('disabled') || $attributes->get('readonly'),
        $personalize['error'] => $error,
    ])>
        <textarea @if ($id) id="{{ $id }}" @endif @if ($resizeAuto) x-data="tallstackui_formTextArea()" x-on:input="resize()" @endif {{ $attributes->class([
            'resize-none' => !$resize && !$resizeAuto,
            $personalize['input.base'],
        ])->merge(['rows' => 3]) }}>{{ $slot }}</textarea>
    </div>
</x-dynamic-component>
