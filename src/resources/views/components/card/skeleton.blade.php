@php
    $customization = $classes();
@endphp

<div class="{{ $customization['wrapper.first'] }}" aria-busy="true" aria-live="polite"
     {{ $attributes->whereDoesntStartWith('x-on:')->class([$customization['skeleton.animation']]) }}>
    <div @class([$customization['wrapper.second'], $customization['border.radius.'.$rounded], $customization['shadowless'] => $shadowless, $customization['bordered'] => $bordered])>
        @if ($image && $position !== 'bottom')
            <div class="{{ $customization['image.wrapper'] }}">
                <div @class([$customization['skeleton.bar'], $customization['skeleton.image']])></div>
            </div>
        @endif
        @if ($header)
            <div @class([$customization['header.wrapper.base'], $customization['header.wrapper.border']])>
                <div @class([$customization['skeleton.bar'], $customization['skeleton.header']])></div>
            </div>
        @endif
        <div @class([$customization['body'], $customization['body.paddingless'] => $paddingless])>
            <div class="{{ $customization['skeleton.body.wrapper'] }}">
                @for ($line = 1; $line <= $lines; $line++)
                    <div @class([
                        $customization['skeleton.bar'],
                        $customization['skeleton.body.line-last'] => $line === $lines && $lines > 1,
                        $customization['skeleton.body.line'] => $line !== $lines || $lines === 1,
                    ])></div>
                @endfor
            </div>
        </div>
        @if ($footer)
            <div class="{{ $customization['footer.wrapper'] }}">
                <div class="{{ $customization['skeleton.footer.wrapper'] }}">
                    <div @class([$customization['skeleton.bar'], $customization['skeleton.footer.button']])></div>
                </div>
            </div>
        @endif
        @if ($image && $position === 'bottom')
            <div class="{{ $customization['image.wrapper'] }}">
                <div @class([$customization['skeleton.bar'], $customization['skeleton.image']])></div>
            </div>
        @endif
    </div>
</div>
