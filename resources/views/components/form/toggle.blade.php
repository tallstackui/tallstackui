@php
    $computed             = $attributes->whereStartsWith('wire:model')->first();
    $error                = $errors->has($computed);
    $customize            = tallstackui_personalization('form.toggle', $customization());
    $customize['wrapper'] = $error ? preg_replace('/\bbg-[a-zA-Z0-9-]+/', '', $customize['wrapper']) : $customize['wrapper'];
@endphp

<x-wrapper.radio :$computed :$error :$label :$position :$id>
    <input @if ($id) id="{{ $id }}" @endif type="checkbox" {{ $attributes->class($customize['input']) }} @checked($checked)>
    <div @class([
        $customize['wrapper'],
        $customize['internal.wrapper.color'],
        $customize['error'] => $error
    ])></div>
</x-wrapper.radio>
