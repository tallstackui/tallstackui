@php
    $personalize = $classes();
@endphp

<label @if ($id) for="{{ $id }}" @endif @class([$personalize['text'], $personalize['error'] => $error && !$invalidate]) {{ $attributes }}>
    {!! $word !!}
    @if ($asterisk)
        <span class="{{ $personalize['asterisk'] }}">*</span>
    @endif
</label>
