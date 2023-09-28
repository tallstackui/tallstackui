@php
    $tag = $href ? 'a' : 'button';
    $customize = tasteui_personalization('button', $customization());
@endphp

<{{ $tag }} @if ($href) href="{{ $href }}" @else type="button" role="button" @endif {{ $attributes->class($customize['wrapper']) }}>
    @if ($icon && $position === 'left')
        <x-icon :$icon @class($customize['icon']) />
    @endif
    {{ $text ?? $slot }}
    @if ($icon && $position === 'right')
        <x-icon :$icon @class($customize['icon']) />
    @endif
</{{ $tag }}>
