@php
    $computed = $attributes->whereStartsWith('wire:model')->first();
    $error = $computed && $errors->has($computed);
    $personalize = tallstackui_personalization('form.textarea', $personalization());
@endphp

<x-wrapper.input :$id :$computed :$error :$label :$hint validate>
    <div @class([
        $personalize['input.wrapper'],
        $personalize['input.color.base'] => !$error,
        $personalize['input.color.background'] => !$attributes->get('disabled') && !$attributes->get('readonly'),
        $personalize['input.color.disabled'] => $attributes->get('disabled') || $attributes->get('readonly'),
        $personalize['error'] => $error,
    ])>
        <textarea @if ($resizeAuto) x-data="tallstackui_formTextArea()" @endif {{ $attributes->class([
            'resize-none' => !$resize && !$resizeAuto,
            $personalize['input.base'],
        ]) }}
         id="{{ $id }}" rows="{{ $rows }}" @if ($resizeAuto) x-on:input="resize()" @endif>{{ $slot }}</textarea>
    </div>
</x-wrapper.input>
