@php
    $customization = $classes();
@endphp

<div {{ $attributes->class([$customization['wrapper']]) }}>
    {{ $slot }}
</div>
