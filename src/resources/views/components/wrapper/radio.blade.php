@php
    $customization = $classes();
@endphp

<div>
    <div class="{{ $customization['wrapper.first'] }}">
        <label @if ($id) for="{{ $id }}" @endif class="{{ $customization['label.wrapper'] }}">
            <div @class($customization['wrapper.second.'.$alignment])>
                @if ($label && $position === 'left')
                    <span @class([$customization['label.text'], $customization['label.error'] => $error, $customization['label.spacing.left']])>
                    {!! $label !!}
                </span>
                @endif
                {!! $slot !!}
                @if ($label && $position === 'right')
                    <span @class([$customization['label.text'], $customization['label.error'] => $error, $customization['label.spacing.right']])>
                    {!! $label !!}
                </span>
                @endif
            </div>
        </label>
    </div>
    @if ($property && !$invalidate)
        <x-dynamic-component :component="TallStackUi::prefix('error')" scope="wrapper.radio.error" :$property />
    @endif
</div>
