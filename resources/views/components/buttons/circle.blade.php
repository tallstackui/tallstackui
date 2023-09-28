@php
    $tag = $href ? 'a' : 'button';
    $customize = tasteui_personalization('button.circle', $customization());
@endphp

<{{ $tag }} @if ($href) href="{{ $href }}" @else type="button" role="button" @endif {{ $attributes->class($customize['base']) }}>
    <div @class($customize['wrapper'])>
        @if ($icon)
            <x-icon :$icon @class($customize['icon']) />
        @else
            {{ $text ?? $slot }}
        @endif
    </div>
</{{ $tag }}>
