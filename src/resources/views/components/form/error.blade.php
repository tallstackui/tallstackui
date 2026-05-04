@php
    $customization = $classes();
@endphp

@if ($property)
    <span
        class="{{ $customization['text'] }}"
        x-cloak
        x-show="typeof $wire?.$errors?.has === 'function'
            ? $wire.$errors.has('{{ $property }}')
            : @js($message !== null)"
        x-text="typeof $wire?.$errors?.first === 'function'
            ? ($wire.$errors.first('{{ $property }}') ?? '')
            : @js($message)"
    >{{ $message }}</span>
@endif
