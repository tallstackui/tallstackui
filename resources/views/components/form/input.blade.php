@php
    $computed  = $attributes->whereStartsWith('wire:model')->first();
    $error     = $errors->has($computed);
    $customize = tallstackui_personalization('form.input', $customization());
    $internal  = $internals();
@endphp

<x-wrapper.input :$computed :$error :$label :$hint :$validate>
    @if ($icon)
        <div @class($customize['icon.wrapper'])>
            <x-icon :$icon :$error @class([$customize['icon.size'], 'text-secondary-500' => !$validate]) />
        </div>
    @endif
    <input @if ($id) id="{{ $id }}" @endif {{ $attributes->class([
            $customize['input'],
            $internal['input.icon'],
            $internal['input.round'],
            $customize['error'] => $error && $validate
    ]) }}>
</x-wrapper.input>
