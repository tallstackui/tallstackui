@php
    $computed = $attributes->whereStartsWith('wire:model')->first();
    $error    = $errors->has($computed);
@endphp

<x-taste-ui::form.wrapper.radio-toggle :$computed :$error :$label :$position :$id>
    <input @if ($id) id="{{ $id }}" @endif type="radio" {{ $attributes->class($baseClass($error)) }} @checked($checked)>
</x-taste-ui::form.wrapper.radio-toggle>
