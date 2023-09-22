@php
    $computed = $attributes->whereStartsWith('wire:model')->first();
    $error    = $errors->has($computed);
    $baseIcon = $getBaseIcon($error);
@endphp

<x-taste-ui::form.wrapper :$computed :$error :$label :$hint password>
    <div @class($baseIcon['wrapper'])>
        <div class="cursor-pointer" x-on:click="show = !show">
            <x-icon name="eye" :$error style="{{ $baseIcon['style'] }}" @class([$baseIcon['size'],$baseIcon['color']]) x-show="!show" />
            <x-icon name="eye-slash" :$error style="{{ $baseIcon['style'] }}" @class([$baseIcon['size'],$baseIcon['color']]) x-show="show" />
        </div>
    </div>

    <input @if ($id) id="{{ $id }}" @endif {{ $attributes->class($getBaseClass($error)) }} :type="!show ? 'password' : 'text'">
</x-taste-ui::form.wrapper>
