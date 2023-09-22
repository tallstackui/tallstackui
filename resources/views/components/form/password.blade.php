@php
    $computed    = $attributes->whereStartsWith('wire:model')->first();
    $error       = $errors->has($computed);
    $iconElement = $iconElement($error);
@endphp

<x-taste-ui::form.wrapper.input :$computed :$error :$label :$hint password>
    <div @class($iconElement['wrapper'])>
        <div class="cursor-pointer" x-on:click="show = !show">
            <x-icon name="eye" :$error style="{{ $iconElement['style'] }}" @class([$iconElement['class']]) x-show="!show" />
            <x-icon name="eye-slash" :$error style="{{ $iconElement['style'] }}" @class([$iconElement['class']]) x-show="show" />
        </div>
    </div>

    <input @if ($id) id="{{ $id }}" @endif {{ $attributes->class($baseClass($error)) }} :type="!show ? 'password' : 'text'">
</x-taste-ui::form.wrapper.input>
