@php
    $tag = $href ? 'a' : 'button';
    $personalization = \TasteUi\Facades\TasteUi::personalization('taste-ui::personalizations.button.circle')->toArray();
    $customize = tasteui_personalize($personalization, $customization());
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
