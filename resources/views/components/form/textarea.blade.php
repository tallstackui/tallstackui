@php
    $computed = $attributes->whereStartsWith('wire:model')->first();
    $error    = $errors->has($computed);
@endphp

<x-taste-ui::form.wrapper.input :$computed :$error :$label :$hint>
    <textarea @if ($id) id="{{ $id }}" @endif {{ $attributes->class($baseClass($error)) }} rows="{{ $rows }}">{{ $slot }}</textarea>
</x-taste-ui::form.wrapper.input>
