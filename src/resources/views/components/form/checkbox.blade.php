@php
    $computed = $attributes->whereStartsWith('wire:model')->first();
    $error = $errors->has($computed);
    $personalize = tallstackui_personalization('form.checkbox', $personalization());
@endphp

<x-wrapper.radio :$computed :$error :$label :$position>
    <input type="checkbox" {{ $attributes->class([
            $personalize['input.class'],
            $personalize['input.sizes.' . $size],
            $colors['background'],
            $personalize['error'] => $error
    ]) }} @checked($checked)>
</x-wrapper.radio>
