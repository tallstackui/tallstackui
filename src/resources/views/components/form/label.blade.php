@php
    $customization = $classes();
@endphp

<label @if ($id) for="{{ $id }}" @endif @class([$customization['text'], $customization['error'] => $error && !$invalidate]) {{ $attributes }}>
    {!! $word !!}
    @if ($asterisk)
        <span class="{{ $customization['asterisk'] }}">*</span>
    @endif
</label>
