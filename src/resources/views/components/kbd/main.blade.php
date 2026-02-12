@php
    $customization = $classes();
@endphp

<kbd {{ $attributes->class([
        $customization['wrapper.class'],
        $customization['wrapper.sizes.' . $size],
        $customization['borderless'] => $borderless,
    ]) }}
    @if ($tooltip) x-data x-tooltip="{{ $tooltip }}" @endif
>{{ $text ?? $slot }}</kbd>
