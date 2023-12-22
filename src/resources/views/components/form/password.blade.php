@php
    [$property, $error, $id] = $bind($attributes, $errors ?? null, $__env, isset($__livewire));
    $personalize = $classes();
@endphp

<x-wrapper.input :$id :$property :$error :$label :$hint :$invalidate password>
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
        <input @if ($id) id="{{ $id }}" @endif {{ $attributes->class([$personalize['input.base']]) }} :type="!show ? 'password' : 'text'">
    </div>
</x-wrapper.input>
