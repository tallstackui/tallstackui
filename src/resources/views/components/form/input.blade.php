@php
    $computed = $attributes->whereStartsWith('wire:model')->first();
    $error = $computed && $errors->has($computed);
    $personalize = tallstackui_personalization('form.input', $personalization());
    $type = $attributes->get('type');
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
    <div @class([
            $personalize['input.class.wrapper'],
            $personalize['input.class.color.base'] => !$error,
            $personalize['input.class.color.background'] => !$attributes->get('disabled') && !$attributes->get('readonly'),
            $personalize['input.class.color.disabled'] => $attributes->get('disabled') || $attributes->get('readonly'),
            $personalize['input.paddings.left'] => $icon && ($position === null || $position === 'left'),
            $personalize['input.paddings.right'] => $icon && $position === 'right',
            $personalize['error'] => $error && $validate
        ])>
        @if ($prefix)
            <span @class([$personalize['input.class.slot'], $personalize['error'] => $error && $validate])>{{ $prefix }}</span>
        @endif
        <input id="{{ $id }}" @if ($type) type="{{ $type }}" @endif {{ $attributes->class([$personalize['input.class.base']]) }}>
        @if ($suffix)
            <span @class([$personalize['input.class.slot'], $personalize['error'] => $error && $validate])>{{ $suffix }}</span>
        @endif
    </div>
</x-wrapper.input>
