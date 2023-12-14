@php
    $computed = $attributes->whereStartsWith('wire:model')->first();
    $error = $computed && $errors->has($computed);
    $personalize = $classes();
    $disabled = $attributes->get('disabled');
    $readonly = $attributes->get('readonly');
@endphp

<x-wrapper.input :$id :$computed :$error :$label :$hint validate>
    <div @class([
        $personalize['input.wrapper'],
        $personalize['input.color.base'] => !$error,
        $personalize['input.color.background'] => !$disabled && !$readonly,
        $personalize['input.color.disabled'] => $disabled || $readonly,
        $personalize['error'] => $error,
    ])>
        <textarea @if ($resizeAuto) x-data="tallstackui_formTextArea()" x-on:input="resize()" @endif {{ $attributes->class([
            'resize-none' => !$resize && !$resizeAuto,
            $personalize['input.base'],
        ])->merge(['rows' => 3]) }} id="{{ $id }}">{{ $slot }}</textarea>
    </div>
</x-wrapper.input>
