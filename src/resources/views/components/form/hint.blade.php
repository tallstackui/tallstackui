@php
    $customization = $classes();
@endphp

<span class="{{ $customization['text'] }}">
    {!! $hint ?? $slot !!}
</span>
