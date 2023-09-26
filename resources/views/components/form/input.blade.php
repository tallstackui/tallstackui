@php
    $computed    = $attributes->whereStartsWith('wire:model')->first();
    $error       = $errors->has($computed);

    $customize = $customization($error);

    $customize['main'] ??= $customMainClasses($error);
    $customize['icon'] ??= $customIconClasses();
@endphp

<x-taste-ui::form.wrapper.input :$computed :$error :$label :$hint>
    @if ($icon)
        <div @class($customize['icon']['wrapper'])>
            <x-icon :$icon :$error @class($customize['icon']['size']) />
        </div>
    @endif

    <input @if ($id) id="{{ $id }}" @endif {{ $attributes->class($customize['main']) }}>
</x-taste-ui::form.wrapper.input>