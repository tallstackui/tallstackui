@php($customize = tallstackui_personalization('wrapper.radio', $customization()))

<div>
    <div @class($customize['wrapper'])>
        @if ($label && $position === 'left')
        <p @class([$customize['label.wrapper.text'], $customize['label.wrapper.error'] => $error, 'mr-2'])>
            {{ $label }}
        </p>
        @endif
        <label @if ($id) for="{{ $id }}" @endif @class($customize['slot'])>
            {!! $slot !!}
        </label>
        @if ($label && $position === 'right')
        <p @class([$customize['label.base.text'], $customize['label.base.error'] => $error, 'ml-2'])>{{ $label }}</p>
        @endif
    </div>
    <x-error :$computed :$error/>
</div>
