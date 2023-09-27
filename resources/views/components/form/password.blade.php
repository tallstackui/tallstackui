@php
    $computed        = $attributes->whereStartsWith('wire:model')->first();
    $error           = $errors->has($computed);
    $personalization = \TasteUi\Facades\TasteUi::personalization('taste-ui::personalizations.form.password')->toArray();
    $customize       = tasteui_personalize($personalization, $customization($error));
@endphp

<x-taste-ui::form.wrapper.input :$computed :$error :$label :$hint password>
    <div @class($customize['main.icon.wrapper'])>
        <div class="cursor-pointer" x-on:click="show = !show">
            <x-icon name="eye" :$error @class($customize['main.icon.class']) x-show="!show" />
            <x-icon name="eye-slash" :$error @class($customize['main.icon.class']) x-show="show" />
        </div>
    </div>

    <input @if ($id) id="{{ $id }}" @endif {{ $attributes->class($customize['main.base']) }} :type="!show ? 'password' : 'text'">
</x-taste-ui::form.wrapper.input>
