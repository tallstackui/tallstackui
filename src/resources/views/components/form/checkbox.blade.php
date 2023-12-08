@php
    $computed = $attributes->whereStartsWith('wire:model')->first();
    $error = $errors->has($computed);
    $personalize = tallstackui_personalization('form.checkbox', $personalization());
    $slot = $label instanceof \Illuminate\View\ComponentSlot;
    $position = $slot && $label->attributes->has('left') ? 'left' : $position;
    $label = $slot ? $label->toHtml() : $label;
@endphp

<x-wrapper.radio :$id :$computed :$error :$label :$position>
    <input @if ($id) id="{{ $id }}" @endif type="checkbox" {{ $attributes->class([
            $personalize['input.class'],
            $personalize['input.sizes.' . $size],
            $colors['background'],
            $personalize['error'] => $error
    ]) }} @checked($checked)>
</x-wrapper.radio>
