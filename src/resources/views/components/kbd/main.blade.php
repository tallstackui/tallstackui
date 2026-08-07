@php
    $customization = $classes();
@endphp

<kbd {{ $attributes->class([
        $customization['wrapper.class'],
        $customization['wrapper.sizes.' . $size],
        $customization['borderless'] => $configurations['borderless'],
        $customization['shadowless'] => $configurations['shadowless'],
    ]) }}
    @if ($tooltip) x-data x-tooltip="{{ $tooltip }}" @endif
>{{ $text ?? $slot }}</kbd>
