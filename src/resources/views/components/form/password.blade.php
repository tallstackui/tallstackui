@php
    $computed = $attributes->whereStartsWith('wire:model')->first();
    $error = $computed && $errors->has($computed);
    $personalize = tallstackui_personalization('form.password', $personalization());
@endphp

<x-wrapper.input :$computed :$error :$label :$hint validate password>
    <div @class($personalize['icon.wrapper']) x-cloak>
        <div class="cursor-pointer" x-on:click="show = !show">
            <x-icon name="eye" :$error @class($personalize['icon.class']) x-show="!show"/>
            <x-icon name="eye-slash" :$error @class($personalize['icon.class']) x-show="show"/>
        </div>
    </div>
    <input {{ $attributes->class([
            $personalize['input.base'],
            $personalize['input.color'] => !$error,
            $personalize['error'] => $error
    ]) }} :type="!show ? 'password' : 'text'">
</x-wrapper.input>
