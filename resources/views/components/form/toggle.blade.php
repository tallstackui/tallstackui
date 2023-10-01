@php
    $computed          = $attributes->whereStartsWith('wire:model')->first();
    $error             = $errors->has($computed);
    $customize         = tasteui_personalization('form.toggle', $customization());
    $customize['base'] = $error ? preg_replace('/bg-[^-]+-[^-]+/', '', $customize['base']) : $customize['base'];
@endphp

<x-wrapper.radio :$computed :$error :$label :$position :$id>
    <input @if ($id) id="{{ $id }}" @endif type="checkbox" {{ $attributes->class($customize['input']) }} @checked($checked)>
    <div @class([$customize['base'], $customize['error'] => $error])></div>
</x-wrapper.radio>
