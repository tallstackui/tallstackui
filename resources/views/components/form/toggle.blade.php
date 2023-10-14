@php
    $computed = $attributes->whereStartsWith('wire:model')->first();
    $error = $errors->has($computed);
    $customize = tallstackui_personalization('form.toggle', $personalization());
    // We remove any bg color classes from the wrapper if there
    // is an error to apply the red bg color to the input instead
    $customize['wrapper'] = $error ? preg_replace('/\bbg-[a-zA-Z0-9-]+/', '', $customize['wrapper']) : $customize['wrapper'];
    $internal = $internals();
@endphp

<x-wrapper.radio :$computed :$error :$label :$position :$id>
    <input @if ($id) id="{{ $id }}" @endif type="checkbox" {{ $attributes->class($customize['input']) }} @checked($checked)>
    <div @class([
        $customize['wrapper'],
        $internal['wrapper.color'],
        $customize['error'] => $error
    ])></div>
</x-wrapper.radio>
