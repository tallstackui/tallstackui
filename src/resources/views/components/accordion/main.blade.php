@php
    $customization = $classes();
@endphp

<div
    x-data="tallstackui_accordion(@js((bool) $multiple))"
    x-ref="wrapper"
    dusk="tallstackui_accordion"
    {{ $attributes->class([
        $customization['wrapper.base'],
        $customization['wrapper.bordered'] => ! $flat,
        $customization['wrapper.chevron-left-cascade'] => $chevron === 'left',
    ]) }}
>
    {{ $slot }}
</div>
