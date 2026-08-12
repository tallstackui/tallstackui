@php
    $customization = $classes();
@endphp

<div
    x-data="tallstackui_accordion(@js((bool) $multiple))"
    x-ref="wrapper"
    dusk="tallstackui_accordion"
    {{ $attributes->class([
        $customization['wrapper.base'],
        $customization['shadowless'] => $shadowless,
        $customization['bordered'] => $bordered,
        $customization['wrapper.chevron-left-cascade'] => $chevron === 'left',
    ]) }}
>
    {{ $slot }}
</div>
