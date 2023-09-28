@php
    $computed  = $attributes->whereStartsWith('wire:model')->first();
    $error     = $errors->has($computed);
    $customize = tasteui_personalization('form.checkbox', $customization($error));
@endphp

<x-taste-ui::form.wrapper.radio-toggle :$computed :$error :$label :$position :$id>
    <input @if ($id) id="{{ $id }}" @endif type="checkbox" {{ $attributes->class([$customize['base'], $customize['error']]) }} @checked($checked)>
</x-taste-ui::form.wrapper.radio-toggle>
