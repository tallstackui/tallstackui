@php
    $computed  = $attributes->whereStartsWith('wire:model')->first();
    $error     = $errors->has($computed);
    $customize = tasteui_personalization('form.checkbox', $customization());
@endphp

<x-taste-ui::wrappers.form.radio-toggle.wrapper :$computed :$error :$label :$position :$id>
    <input @if ($id) id="{{ $id }}" @endif type="checkbox" {{ $attributes->class([$customize['base'], $customize['error'] => $error]) }} @checked($checked)>
</x-taste-ui::wrappers.form.radio-toggle.wrapper>
