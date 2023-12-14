@php
    $personalize = $classes();
    $error = !$invalidate && $wire && $errors->has($wire);
@endphp

<div>
    <div @class($personalize['wrapper.first'])>
        <label @if ($id) for="{{ $id }}" @endif @class($personalize['label.wrapper'])>
            <div @class($personalize['wrapper.second.'.$alignment])>
                @if ($label && $position === 'left')
                <span @class([$personalize['label.text'], $personalize['label.error'] => $error, 'mr-2'])>
                    {!! $label !!}
                </span>
                @endif
                <div>
                    {!! $slot !!}
                </div>
                @if ($label && $position === 'right')
                <span @class([$personalize['label.text'], $personalize['label.error'] => $error, 'ml-2'])>
                    {!! $label !!}
                </span>
                @endif
            </div>
        </label>
    </div>
    @if ($error)
        <x-error :$wire/>
    @endif
</div>
