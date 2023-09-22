@php
    $computed = $attributes->whereStartsWith('wire:model')->first();
    $error    = $errors->has($computed);
@endphp

<x-taste-ui::form.wrapper.radio-toggle :$computed :$error :$label :$position :$id>
    <input @if ($id) id="{{ $id }}" @endif type="checkbox" {{ $attributes->class($getInputBaseClass()) }} @checked($checked)>
    <div @class($getBaseClass($error))></div>
</x-taste-ui::form.wrapper.radio-toggle>
