@php
    $personalization = \TasteUi\Facades\TasteUi::personalization('taste-ui::personalizations.avatar')->toArray();
    $customize = tasteui_personalize($personalization, $customization());
@endphp

<div {{ $attributes->class($customize['main.wrapper']) }}>
    @if ($modelable)
        <img @class($customize['main.content']) src="{{ $label ?? $slot }}" alt="{{ $alt() }}" />
    @else
        <span @class($customize['main.content'])>{{ $label ?? $slot }}</span>
    @endif
</div>
