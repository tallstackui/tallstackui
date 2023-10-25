@php
    $computed = $attributes->whereStartsWith('wire:model')->first();
    $error = $computed && $errors->has($computed);
    $customize = tallstackui_personalization('form.textarea', $personalization());
    $required = $attributes->has('required');
@endphp

<x-wrapper.input :$computed :$error :$label :$hint validate :$required>
    @if ($autoResize)
        <x-slot:alpine>
            tallstackui_formTextArea()
        </x-slot:alpine>
    @endif
    <textarea @if ($id) id="{{ $id }}" @endif {{ $attributes->class([
            'resize-none' => !$resize && !$autoResize,
            $customize['input.base'],
            $customize['input.color'] => !$error,
            $customize['error'] => $error,
        ]) }}
        rows="{{ $rows }}" @if ($autoResize) x-on:input="resize()" @endif>{{ $slot }}</textarea>
</x-wrapper.input>
