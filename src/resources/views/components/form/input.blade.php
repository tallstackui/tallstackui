@php
    $computed = $attributes->whereStartsWith('wire:model')->first();
    $error = $computed && $errors->has($computed);
    $customize = tallstackui_personalization('form.input', $personalization());
    $required = $attributes->has('required');
@endphp

<x-wrapper.input :$computed :$error :$label :$hint :$validate :$required>
    @if ($icon)
        <div @class([
                $customize['icon.wrapper'],
                $customize['icon.paddings.left'] => $position === 'left',
                $customize['icon.paddings.right'] => $position === 'right',
            ])>
            <x-icon :$icon :$error @class([$customize['icon.size'], 'text-secondary-500' => !$validate]) />
        </div>
    @endif
    <input @if ($id) id="{{ $id }}" @endif {{ $attributes->class([
            $customize['input.class.base'],
            $customize['input.class.color'] => !$error,
            $customize['input.paddings.left'] => $icon && ($position === null || $position === 'left'),
            $customize['input.paddings.right'] => $icon && $position === 'right',
            $customize['error'] => $error && $validate
    ]) }}>
</x-wrapper.input>
