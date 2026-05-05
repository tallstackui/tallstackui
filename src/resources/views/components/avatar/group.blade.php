@php
    $customization = $classes();
@endphp

<div {{ $attributes->class([
        $customization['wrapper.base'],
        $customization['wrapper.reverse'] => $reverse,
    ]) }}>
    {{ $slot }}
</div>
