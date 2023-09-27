@php
    $computed        = $attributes->whereStartsWith('wire:model')->first();
    $error           = $errors->has($computed);
    $personalization = \TasteUi\Facades\TasteUi::personalization('taste-ui::personalizations.form.toggle')->toArray();
    $customize       = tasteui_personalize($personalization, $customization($error));
@endphp

<x-taste-ui::form.wrapper.radio-toggle :$computed :$error :$label :$position :$id>
    <input @if ($id) id="{{ $id }}" @endif type="checkbox" {{ $attributes->class($customize['main.input']) }} @checked($checked)>
    <div @class($customize['main.base'])></div>
</x-taste-ui::form.wrapper.radio-toggle>
