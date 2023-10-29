@php
    $computed = $attributes->whereStartsWith('wire:model')->first();
    $error = $computed && $errors->has($computed);
    $personalize = tallstackui_personalization('form.textarea', $personalization());
@endphp

<x-wrapper.input :$computed :$error :$label :$hint validate>
    @if ($autoResize)
        <x-slot:alpine>
            tallstackui_formTextArea()
        </x-slot:alpine>
    @endif
    <textarea @if ($id) id="{{ $id }}" @endif {{ $attributes->class([
            'resize-none' => !$resize && !$autoResize,
            $personalize['input.base'],
            $personalize['input.color'] => !$error,
            $personalize['error'] => $error,
        ]) }}
        rows="{{ $rows }}" @if ($autoResize) x-on:input="resize()" @endif>{{ $slot }}</textarea>
</x-wrapper.input>
