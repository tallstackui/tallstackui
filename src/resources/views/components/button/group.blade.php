@php
    $customization = $classes();
@endphp

<div role="group" {{ $attributes->class([
        $customization['wrapper.base'],
        $customization['wrapper.layout.horizontal'] => ! $vertical,
        $customization['wrapper.layout.vertical'] => $vertical,
    ]) }}>
{{ $slot }}
</div>
