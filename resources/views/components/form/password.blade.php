@php($customize = tasteui_personalization('form.password', $customization($error)))

<x-taste-ui::wrappers.form.input.wrapper :$computed :$error :$label :$hint password>
    <div @class($customize['icon.wrapper'])>
        <div class="cursor-pointer" x-on:click="show = !show">
            <x-icon name="eye" :$error @class($customize['icon.class']) x-show="!show"/>
            <x-icon name="eye-slash" :$error @class($customize['icon.class']) x-show="show"/>
        </div>
    </div>

    <input @if ($id) id="{{ $id }}"
           @endif {{ $attributes->class($customize['base']) }} :type="!show ? 'password' : 'text'">
</x-taste-ui::wrappers.form.input.wrapper>
