@php
    $customization = $classes();
@endphp

<x-dynamic-component component="ts-ui::theme-switch.variations.{{ $variation }}"
                     :$size
                     :$onlyIcons
                     :$block
                     :$customization />
