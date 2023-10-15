@php
    $computed = $attributes->whereStartsWith('wire:model')->first();
    $error = $errors->has($computed);
    $customize = tallstackui_personalization('form.checkbox', $personalization());
@endphp

<x-wrapper.radio :$computed :$error :$label :$position :$id>
    <input @if ($id) id="{{ $id }}" @endif type="checkbox" {{ $attributes->class([
            $customize['input.class'],
            $customize['input.sizes.sm'] => $sm,
            $customize['input.sizes.md'] => $md,
            $customize['input.sizes.lg'] => $lg,
            $colors['input.color'],
            $customize['error'] => $error
    ]) }} @checked($checked)>
</x-wrapper.radio>
