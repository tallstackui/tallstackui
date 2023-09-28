@php
    $computed  = $attributes->whereStartsWith('wire:model')->first();
    $error     = $errors->has($computed);
    $customize = tasteui_personalization('form.input', $customization());
@endphp

<x-taste-ui::wrappers.form.input.wrapper :$computed :$error :$label :$hint :$validate>
    @if ($icon)
        <div @class($customize['icon.wrapper'])>
            <x-icon :$icon :$error @class([$customize['icon.size'], 'text-secondary-500' => !$validate]) />
        </div>
    @endif

    <input @if ($id) id="{{ $id }}" @endif {{ $attributes->class([$customize['base'], $customize['error'] => $error && $validate]) }}>
</x-taste-ui::wrappers.form.input.wrapper>