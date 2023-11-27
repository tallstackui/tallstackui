@php
    $computed = $attributes->whereStartsWith('wire:model')->first();
    $error = $computed && $errors->has($computed);
    $personalize = tallstackui_personalization('form.textarea', $personalization());
@endphp

<x-wrapper.input :$id :$computed :$error :$label :$hint validate>
    <textarea @if ($resizeAuto) x-data="tallstackui_formTextArea()" @endif {{ $attributes->class([
            'resize-none' => !$resize && !$resizeAuto,
            $personalize['input.base'],
            $personalize['input.color'] => !$error,
            $personalize['error'] => $error,
        ]) }}
         id="{{ $id }}" rows="{{ $rows }}" @if ($resizeAuto) x-on:input="resize()" @endif>{{ $slot }}</textarea>
</x-wrapper.input>
