@php
    $computed = $attributes->whereStartsWith('wire:model')->first();
    $error = $errors->has($computed);
    $personalize = tallstackui_personalization('form.toggle', $personalization());
    // We remove any bg color classes from the wrapper if there
    // is an error to apply the red bg color to the input instead
    $personalize['wrapper.class'] = $computed && $error ? preg_replace('/\bbg-[a-zA-Z0-9-]+/', '', $personalize['wrapper.class']) : $personalize['wrapper.class'];
@endphp

<x-wrapper.radio :$computed :$error :$label :$position>
    <input type="checkbox" {{ $attributes->class([
            $personalize['input.class'],
            $personalize['input.sizes.' . $size],
        ]) }} @checked($checked)>
    <div @class([
        $personalize['wrapper.class'],
        $personalize['wrapper.sizes.' . $size],
        $colors['wrapper.color'],
        $personalize['error'] => $computed && $error
    ])></div>
</x-wrapper.radio>
