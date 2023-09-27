@php
    $computed        = $attributes->whereStartsWith('wire:model')->first();
    $error           = $errors->has($computed);
    $personalization = \TasteUi\Facades\TasteUi::personalization('taste-ui::personalizations.form.textarea')->toArray();
    $customize       = tasteui_personalize($personalization, $customization($error));
@endphp

<x-taste-ui::form.wrapper.input :$computed :$error :$label :$hint>
    <textarea @if ($id) id="{{ $id }}" @endif {{ $attributes->class($customize['main.base']) }} rows="{{ $rows }}">{{ $slot }}</textarea>
</x-taste-ui::form.wrapper.input>
