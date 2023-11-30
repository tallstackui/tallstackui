@php
    $computed = $attributes->whereStartsWith('wire:model')->first();
    $error = $computed && $errors->has($computed);
    $personalize = tallstackui_personalization('form.password', $personalization());
@endphp

<x-wrapper.input :$id :$computed :$error :$label :$hint validate password>
        <div @class($personalize['icon.wrapper']) x-cloak>
        <div class="cursor-pointer" x-on:click="show = !show">
            <x-icon name="eye" :$error @class($personalize['icon.class']) x-show="!show"/>
            <x-icon name="eye-slash" :$error @class($personalize['icon.class']) x-show="show"/>
        </div>
    </div>
    <input id="{{ $id }}" 
        {{ $attributes->class([
        $personalize['input.wrapper'],
        $personalize['input.base'],
        $personalize['input.color'] => !$error,
        $personalize['input.disabled'] => $attributes->get('disabled') || $attributes->get('readonly'),
        $personalize['error'] => $error
    ]) }} :type="!show ? 'password' : 'text'">
</x-wrapper.input>
