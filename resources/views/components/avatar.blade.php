@php
    $personalization = \TasteUi\Facades\TasteUi::personalization('taste-ui::personalizations.avatar')->toArray();
    $customize = tasteui_personalize($personalization, $customization());
@endphp

<div {{ $attributes->class($customize['wrapper']) }}>
    @if ($modelable)
        <img @class($customize['content']) src="{{ $label ?? $slot }}" alt="{{ $alt() }}" />
    @else
        <span @class($customize['content'])>{{ $label ?? $slot }}</span>
    @endif
</div>
