@php
    $customization = $classes();
@endphp

<div aria-busy="true" aria-live="polite"
     {{ $attributes->class([$customization['wrapper.first'], $customization['skeleton.animation'], $customization['shadowless'] => $shadowless, $customization['bordered'] => $bordered]) }}>
    @if ($header)
        <div class="{{ $customization['slots.header.wrapper'] }}">
            <div class="{{ $customization['slots.header.text'] }}">
                <div @class([$customization['skeleton.bar'], $customization['skeleton.header']])></div>
            </div>
        </div>
    @endif
    <div @class([
        $customization['wrapper.second'],
        $customization['wrapper.second-no-header'] => ! $header,
        $customization['wrapper.second-no-footer'] => ! $footer,
    ])>
        @if ($icon)
            <div @class([$customization['skeleton.bar'], $customization['skeleton.icon']])></div>
        @endif
        <div class="grow space-y-2">
            @if ($title)
                <div @class([$customization['skeleton.bar'], $customization['skeleton.title']])></div>
            @endif
            <div @class([$customization['skeleton.bar'], $customization['skeleton.number']])></div>
        </div>
    </div>
    @if ($footer)
        <div class="{{ $customization['slots.footer.wrapper'] }}">
            <div class="{{ $customization['slots.footer.text'] }}">
                <div @class([$customization['skeleton.bar'], $customization['skeleton.footer']])></div>
            </div>
        </div>
    @endif
</div>
