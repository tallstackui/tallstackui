@php
    $personalize = $classes();
@endphp

<div {{ $attributes->class([$personalize['wrapper']]) }}>
    {{ $slot }}
</div>
