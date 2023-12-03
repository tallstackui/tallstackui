@php
    $computed = $attributes->whereStartsWith('wire:model')->first();
    $error = $computed && $errors->has($computed);
    $personalize = tallstackui_personalization('form.password', $personalization());
@endphp

<x-wrapper.input :$id :$computed :$error :$label :$hint validate password>
    <div @class([
        $personalize['input.wrapper'],
        $personalize['input.color.base'] => !$error,
        $personalize['input.color.background'] => !$attributes->get('disabled') && !$attributes->get('readonly'),
        $personalize['input.color.disabled'] => $attributes->get('disabled') || $attributes->get('readonly'),
        $personalize['error'] => $error
    ])>
        <div @class($personalize['icon.wrapper']) x-cloak>
            <div class="cursor-pointer" x-on:click="show = !show">
                <x-icon name="eye" :$error @class($personalize['icon.class']) x-show="!show"/>
                <x-icon name="eye-slash" :$error @class($personalize['icon.class']) x-show="show"/>
            </div>
        </div>
        <input id="{{ $id }}" {{ $attributes->class([$personalize['input.base']]) }} :type="!show ? 'password' : 'text'">
    </div>
</x-wrapper.input>
