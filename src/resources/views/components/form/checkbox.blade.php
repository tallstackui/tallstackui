@php
    $computed = $attributes->whereStartsWith('wire:model')->first();
    $error = $errors->has($computed);
    $personalize = tallstackui_personalization('form.checkbox', $personalization());
@endphp

<x-wrapper.radio :$computed :$error :$label :$position :$id>
    <input @if ($id) id="{{ $id }}" @endif type="checkbox" {{ $attributes->class([
            $personalize['input.class'],
            $personalize['input.sizes.sm'] => $size === 'sm',
            $personalize['input.sizes.md'] => $size === 'md',
            $personalize['input.sizes.lg'] => $size === 'lg',
            $colors['input.color'],
            $personalize['error'] => $error
    ]) }} @checked($checked)>
</x-wrapper.radio>
