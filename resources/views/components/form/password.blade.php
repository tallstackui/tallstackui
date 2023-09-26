@php
    $computed    = $attributes->whereStartsWith('wire:model')->first();
    $error       = $errors->has($computed);

    $customize = $customize($error);

    $customize['main'] ??= $customMainClasses($error);
    $customize['icon'] ??= $customIconClasses();
@endphp

<x-taste-ui::form.wrapper.input :$computed :$error :$label :$hint password>
    <div @class($customize['icon']['wrapper'])>
        <div class="cursor-pointer" x-on:click="show = !show">
            <x-icon name="eye" :$error @class($customize['icon']['class']) x-show="!show" />
            <x-icon name="eye-slash" :$error @class($customize['icon']['class']) x-show="show" />
        </div>
    </div>

    <input @if ($id) id="{{ $id }}" @endif {{ $attributes->class($customize['main']) }} :type="!show ? 'password' : 'text'">
</x-taste-ui::form.wrapper.input>
