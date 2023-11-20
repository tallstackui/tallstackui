@php
    $computed = $attributes->whereStartsWith('wire:model')->first();
    $error = $computed && $errors->has($computed);
    $personalize = tallstackui_personalization('form.input', $personalization());
@endphp

<x-wrapper.input :$id :$computed :$error :$label :$hint :$validate>
    @if ($icon)
        <div @class([
                $personalize['icon.wrapper'],
                $personalize['icon.paddings.' . $position],
            ])>
            <x-icon :$icon :$error @class([$personalize['icon.size'], 'text-secondary-500' => !$validate]) />
        </div>
    @endif
    <input id="{{ $id }}" 
           {{ $attributes->class([
           $personalize['input.class.base'],
           $personalize['input.class.color'] => !$error,
           $personalize['input.paddings.left'] => $icon && ($position === null || $position === 'left'),
           $personalize['input.paddings.right'] => $icon && $position === 'right',
           $personalize['error'] => $error && $validate
    ]) }}>
</x-wrapper.input>
