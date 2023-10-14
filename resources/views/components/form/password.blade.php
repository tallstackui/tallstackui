@php
    $computed = $attributes->whereStartsWith('wire:model')->first();
    $error = $errors->has($computed);
    $customize = tallstackui_personalization('form.password', $customization());
@endphp

<x-wrapper.input :$computed :$error :$label :$hint validate password>
    <div @class($customize['icon.wrapper']) x-cloak>
        <div class="cursor-pointer" x-on:click="show = !show">
            <x-icon name="eye" :$error @class($customize['icon.class']) x-show="!show"/>
            <x-icon name="eye-slash" :$error @class($customize['icon.class']) x-show="show"/>
        </div>
    </div>
    <input @if ($id) id="{{ $id }}" @endif {{ $attributes->class([
            'pr-10',
            $customize['input'],
            'rounded-md' => !$square && !$round,
            'rounded-full' => $round,
            $customize['error'] => $error
    ]) }} :type="!show ? 'password' : 'text'">
</x-wrapper.input>
