@php($customize = tasteui_personalization('wrapper.radio', $customization()))

<div>
    <div @class($customize['wrapper'])>
        @if ($label && $position === 'left')
        <span @class([$customize['label.span'], 'mr-1'])>
            <p @class([
                    $customize['label.base'],
                    $customize['label.error'] => $error,
                ])>{{ $label }}</p>
        </span>
        @endif
        <label @if ($id) for="{{ $id }}" @endif @class($customize['slot'])>
            {!! $slot !!}
        </label>
        @if ($label && $position === 'right')
        <span @class([$customize['label.span'], 'ml-1'])>
            <p @class([
                    $customize['label.base'],
                    $customize['label.error'] => $error,
                ])>{{ $label }}</p>
        </span>
        @endif
    </div>
    <x-error :$computed :$error />
</div>
