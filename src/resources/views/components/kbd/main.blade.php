@php
    $customization = $classes();
@endphp

<{{ $tag }} @if ($href) href="{!! $href !!}" @endif
    {{ $attributes->class([
        $customization['wrapper.class'],
        $customization['wrapper.sizes.' . $size],
        $customization['borderless'] => $borderless,
        $customization['clickable'] => $href || $attributes->hasAny(['wire:click', 'x-on:click']),
    ]) }}
    @if ($tooltip) x-data x-tooltip="{{ $tooltip }}" @endif
><code>{{ $text ?? $slot }}</code></{{ $tag }}>
