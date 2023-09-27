@php
    $personalization = \TasteUi\Facades\TasteUi::personalization('taste-ui::personalizations.tooltip')->toArray();
    $customize = tasteui_personalize($personalization, $customization());
@endphp

<div @class($customize['main.wrapper']) x-data>
    <x-dynamic-component component="taste-ui::icons.{{ $solid ? 'solid' : 'outline' }}.{{ $icon }}"
                         data-position="{{ $position }}"
                         x-tooltip="{!! $text !!}"
                        {{ $attributes->class($customize['main.icon']) }}
    />
</div>
