@php
    $computed = $attributes->whereStartsWith('wire:model')->first();
    $error = $errors->has($computed);
    $customize = tallstackui_personalization('form.toggle', $personalization());
    // We remove any bg color classes from the wrapper if there
    // is an error to apply the red bg color to the input instead
    $customize['wrapper.class'] = $error ? preg_replace('/\bbg-[a-zA-Z0-9-]+/', '', $customize['wrapper.class']) : $customize['wrapper.class'];
@endphp

<x-wrapper.radio :$computed :$error :$label :$position :$id>
    <input @if ($id) id="{{ $id }}" @endif type="checkbox" {{ $attributes->class([
            $customize['input.class'],
            $customize['input.sizes.sm'] => $size === 'sm',
            $customize['input.sizes.md'] => $size === 'md',
            $customize['input.sizes.lg'] => $size === 'lg',
        ]) }} @checked($checked)>
    <div @class([
        $customize['wrapper.class'],
        $customize['wrapper.sizes.sm'] => $size === 'sm',
        $customize['wrapper.sizes.md'] => $size === 'md',
        $customize['wrapper.sizes.lg'] => $size === 'lg',
        $colors['wrapper.color'],
        $customize['error'] => $error
    ])></div>
</x-wrapper.radio>
