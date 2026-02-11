@php
    $customization = $classes();
@endphp

<div>
    <div class="{{ $customization['wrapper.first'] }}">
        <label @if ($id) for="{{ $id }}" @endif class="{{ $customization['label.wrapper'] }}">
            <div @class($customization['wrapper.second.'.$alignment])>
                @if ($label && $position === 'left')
                    <span @class([$customization['label.text'], $customization['label.error'] => $error, 'mr-2'])>
                    {!! $label !!}
                </span>
                @endif
                {!! $slot !!}
                @if ($label && $position === 'right')
                    <span @class([$customization['label.text'], $customization['label.error'] => $error, 'ml-2'])>
                    {!! $label !!}
                </span>
                @endif
            </div>
        </label>
    </div>
    @if ($error)
        <x-dynamic-component :component="TallStackUi::prefix('error')" scope="wrapper.radio.error" :$property />
    @endif
</div>
