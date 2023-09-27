@php
    $computed  = $attributes->whereStartsWith('wire:model')->first();
    $error     = $errors->has($computed);
    $customize = tasteui_personalization('form.input', $customization($error));
@endphp

<x-taste-ui::wrappers.form.input.wrapper :$computed :$error :$label :$hint :$validated>
    @if ($icon)
        <div @class($customize['icon.wrapper'])>
            <x-icon :$icon :$error @class([$customize['icon.size'], 'text-secondary-500' => !$validated]) />
        </div>
    @endif

    <input @if ($id) id="{{ $id }}" @endif {{ $attributes->class($customize['base']) }}>
</x-taste-ui::wrappers.form.input.wrapper>