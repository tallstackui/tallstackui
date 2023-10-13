@php
    $computed  = $attributes->whereStartsWith('wire:model')->first();
    $error     = $errors->has($computed);
    $customize = tallstackui_personalization('form.textarea', $customization());
    $internal  = $internals();
@endphp

<x-wrapper.input :$computed :$error :$label :$hint validate>
    @if ($autoResize)
        <x-slot:alpine>
            tallstackui_formTextArea()
        </x-slot:alpine>
    @endif
    <textarea @if ($id) id="{{ $id }}" @endif {{ $attributes->class([
            $customize['input'],
            $internal['input.round'],
            $customize['error'] => $error
        ]) }}
        rows="{{ $rows }}" @if ($autoResize) x-on:input="resize()" @endif>{{ $slot }}</textarea>
</x-wrapper.input>
