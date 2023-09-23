@php
    $computed    = $attributes->whereStartsWith('wire:model')->first();
    $error       = $errors->has($computed);
    $iconElement = $iconElement();
@endphp

<x-taste-ui::form.wrapper.input :$computed :$error :$label :$hint>
    @if ($icon)
        <div @class($iconElement['base'])>
            <x-icon :$icon :$error style="{{ $iconElement['style'] }}" @class($iconElement['size']) />
        </div>
    @endif

    <input @if ($id) id="{{ $id }}" @endif {{ $attributes->class($baseClass($error)) }}>
</x-taste-ui::form.wrapper.input>