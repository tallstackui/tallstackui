@php
    $computed = $attributes->whereStartsWith('wire:model')->first();
    $error    = $errors->has($computed);

    $customize = $customize($error);

    $customize['main'] ??= $customMainClasses($error);
    $customize['input'] ??= $customInputClasses();
@endphp

<x-taste-ui::form.wrapper.radio-toggle :$computed :$error :$label :$position :$id>
    <input @if ($id) id="{{ $id }}" @endif type="checkbox" {{ $attributes->class($customize['input']) }} @checked($checked)>
    <div @class($customize['main'])></div>
</x-taste-ui::form.wrapper.radio-toggle>
