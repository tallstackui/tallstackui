@php
    $type = $href ? 'a' : 'button';

    $personalization = \TasteUi\Facades\TasteUi::personalization('taste-ui::personalizations.button')->toArray();
    $customize = tasteui_personalize($personalization, $customization());
@endphp

<{{ $type }} @if ($href) href="{{ $href }}" @else type="button" role="button" @endif {{ $attributes->class($customize['main.wrapper']) }}>
    @if ($icon && $position === 'left')
        <x-icon :$icon
                type="{{ config('tasteui.icon') ?? 'solid' }}"
                @class($customize['main.icon'])
        />
    @endif
    {{ $text ?? $slot }}
    @if ($icon && $position === 'right')
        <x-icon :$icon
                type="{{ config('tasteui.icon') ?? 'solid' }}"
                @class($customize['main.icon'])
        />
    @endif
</{{ $type }}>
