@php
    $computed  = $attributes->whereStartsWith('wire:model')->first();
    $error     = $errors->has($computed);
    $customize = tasteui_personalization('form.toggle', $customization($error));
@endphp

<x-taste-ui::wrappers.form.radio-toggle.wrapper :$computed :$error :$label :$position :$id>
    <input @if ($id) id="{{ $id }}"
           @endif type="checkbox" {{ $attributes->class($customize['input']) }} @checked($checked)>
    <div @class($customize['base'])></div>
</x-taste-ui::wrappers.form.radio-toggle.wrapper>
