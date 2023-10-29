@php
    $personalize = tallstackui_personalization('wrapper.radio', $personalization());
    $error = ($computed && $errors->has($computed)) && $error;
@endphp

<div>
    <div @class($personalize['wrapper'])>
        @if ($label && $position === 'left')
        <p @class([$personalize['label.wrapper.text'], $personalize['label.wrapper.error'] => $error, 'mr-2'])>
            {{ $label }}
        </p>
        @endif
        <label @if ($id) for="{{ $id }}" @endif @class($personalize['slot'])>
            {!! $slot !!}
        </label>
        @if ($label && $position === 'right')
        <p @class([$personalize['label.wrapper.text'], $personalize['label.wrapper.error'] => $error, 'ml-2'])>
            {{ $label }}
        </p>
        @endif
    </div>
    @if ($error)
        <x-error :$computed :$error/>
    @endif
</div>
