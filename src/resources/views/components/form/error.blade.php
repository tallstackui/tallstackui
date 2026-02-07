@php
    $customization = $classes();
@endphp

@error ($property)
<span class="{{ $customization['text'] }}">
        {{ $message }}
    </span>
@enderror
