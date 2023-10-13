@php
    $computed  = $attributes->whereStartsWith('wire:model')->first();
    $error     = $errors->has($computed);
    $customize = tallstackui_personalization('form.input', $customization());
@endphp

<x-wrapper.input :$computed :$error :$label :$hint :$validate>
    @if ($icon)
        <div @class([
                $customize['icon.wrapper'],
                $customize['icon.padding.left'] => $position === null || $position === 'left',
                $customize['icon.padding.right'] => $position === 'right',
            ])>
            <x-icon :$icon :$error @class([$customize['icon.size'], 'text-secondary-500' => !$validate]) />
        </div>
    @endif
    <input @if ($id) id="{{ $id }}" @endif {{ $attributes->class([
            $customize['base'],
            $customize['icon.input.padding.left'] => $icon && ($position === null || $position === 'left'),
            $customize['icon.input.padding.right'] => $icon && $position === 'right',
            $customize['error'] => $error && $validate
        ]) }}>
</x-wrapper.input>
