@php
    $computed = $attributes->whereStartsWith('wire:model')->first();
    $error = $errors->has($computed);
    $personalize = tallstackui_personalization('form.toggle', $personalization());
    // We remove any bg color classes from the wrapper if there
    // is an error to apply the red bg color to the input instead
    $personalize['wrapper.class'] = $computed && $error ? preg_replace('/\bbg-[a-zA-Z0-9-]+/', '', $personalize['wrapper.class']) : $personalize['wrapper.class'];
@endphp

<x-wrapper.radio :$computed :$error :$label :$position :$id>
    <input @if ($id) id="{{ $id }}" @endif type="checkbox" {{ $attributes->class([
            $personalize['input.class'],
            $personalize['input.sizes.sm'] => $size === 'sm',
            $personalize['input.sizes.md'] => $size === 'md',
            $personalize['input.sizes.lg'] => $size === 'lg',
        ]) }} @checked($checked)>
    <div @class([
        $personalize['wrapper.class'],
        $personalize['wrapper.sizes.sm'] => $size === 'sm',
        $personalize['wrapper.sizes.md'] => $size === 'md',
        $personalize['wrapper.sizes.lg'] => $size === 'lg',
        $colors['wrapper.color'],
        $personalize['error'] => $computed && $error
    ])></div>
</x-wrapper.radio>
