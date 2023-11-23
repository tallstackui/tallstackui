@php
    $computed = $attributes->whereStartsWith('wire:model')->first();
    $error = $computed && $errors->has($computed);
    $personalize = tallstackui_personalization('form.textarea', $personalization());
@endphp

<x-wrapper.input :$id :$computed :$error :$label :$hint validate>
    @if ($resizeAuto)
        <x-slot:alpine>
            tallstackui_formTextArea()
        </x-slot:alpine>
    @endif
    <textarea {{ $attributes->class([
            'resize-none' => !$resize && !$resizeAuto,
            $personalize['input.base'],
            $personalize['input.color'] => !$error,
            $personalize['error'] => $error,
        ]) }}
         id="{{ $id }}" rows="{{ $rows }}" @if ($resizeAuto) x-on:input="resize()" @endif>{{ $slot }}</textarea>
</x-wrapper.input>
