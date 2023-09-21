@php
    $computed = $attributes->whereStartsWith('wire:model')->first();
    $error    = $errors->has($computed);
    $baseIcon = $getBaseIcon();
@endphp

<x-taste-ui::form.wrapper :$computed :$error :$label :$hint>
    @if ($icon)
        <div @class($baseIcon['base'])>
            <x-icon :$icon :$error style="{{ $baseIcon['style'] }}" @class($baseIcon['size']) />
        </div>
    @endif

    <input @if ($id) id="{{ $id }}" @endif {{ $attributes->class($getBaseClass($error)) }}>
</x-taste-ui::form.wrapper>