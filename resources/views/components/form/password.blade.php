@php
    $computed  = $attributes->whereStartsWith('wire:model')->first();
    $error     = $errors->has($computed);
    $customize = tasteui_personalization('form.password', $customization())
@endphp

<x-taste-ui::wrappers.form.input.wrapper :$computed :$error :$label :$hint password>
    <div @class($customize['icon.wrapper'])>
        <div class="cursor-pointer" x-on:click="show = !show">
            <x-icon name="eye" :$error @class($customize['icon.class']) x-show="!show"/>
            <x-icon name="eye-slash" :$error @class($customize['icon.class']) x-show="show"/>
        </div>
    </div>

    <input @if ($id) id="{{ $id }}"
           @endif {{ $attributes->class([$customize['base'], $customize['error'] => $error]) }} :type="!show ? 'password' : 'text'">
</x-taste-ui::wrappers.form.input.wrapper>
