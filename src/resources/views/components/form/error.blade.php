@php
    $customization = $classes();
@endphp

@error ($property)
    <span
        class="{{ $customization['text'] }}"
        x-show="typeof $wire?.$errors?.has === 'function'
            ? $wire.$errors.has('{{ $property }}')
            : true"
        x-text="typeof $wire?.$errors?.first === 'function'
            ? ($wire.$errors.first('{{ $property }}') ?? '')
            : @js($message)"
    >{{ $message }}</span>
@enderror
