@php
    $computed = $attributes->whereStartsWith('wire:model')->first();
    $error = $errors->has($computed);
    $customize = tallstackui_personalization('form.input', $customization());
@endphp

<x-wrapper.input :$computed :$error :$label :$hint :$validate>
    @if ($icon)
        <div @class($customize['icon.wrapper'])>
            <x-icon :$icon :$error @class([$customize['icon.size'], 'text-secondary-500' => !$validate]) />
        </div>
    @endif
    <input @if ($id) id="{{ $id }}" @endif {{ $attributes->class([
            $customize['input'],
            'rounded-md' => !$square && !$round,
            'rounded-full' => $round,
            'pl-10' => $icon && ($position === null || $position === 'left'),
            'pr-10' => $icon && $position === 'right',
            $customize['error'] => $error && $validate
    ]) }}>
</x-wrapper.input>
