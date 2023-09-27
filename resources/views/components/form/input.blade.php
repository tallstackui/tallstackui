@php
    $computed        = $attributes->whereStartsWith('wire:model')->first();
    $error           = $errors->has($computed);
    $personalization = \TasteUi\Facades\TasteUi::personalization('taste-ui::personalizations.alert')->toArray();
    $customize       = tasteui_personalize($personalization, $customization($error));
@endphp

<x-taste-ui::form.wrapper.input :$computed :$error :$label :$hint>
    @if ($icon)
        <div @class($customize['main.icon.wrapper'])>
            <x-icon :$icon :$error @class($customize['main.icon.size']) />
        </div>
    @endif

    <input @if ($id) id="{{ $id }}" @endif {{ $attributes->class($customize['main.base']) }}>
</x-taste-ui::form.wrapper.input>