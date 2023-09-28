@php
    $computed  = $attributes->whereStartsWith('wire:model')->first();
    $error     = $errors->has($computed);
    $customize = tasteui_personalization('form.textarea', $customization());
@endphp

<x-taste-ui::wrappers.form.input.wrapper :$computed :$error :$label :$hint>
    <textarea @if ($id) id="{{ $id }}"
              @endif {{ $attributes->class([$customize['base'], $customize['error'] => $error]) }} rows="{{ $rows }}">{{ $slot }}</textarea>
</x-taste-ui::wrappers.form.input.wrapper>
