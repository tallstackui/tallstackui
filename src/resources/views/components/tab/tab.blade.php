@php
    $computed = $attributes->whereStartsWith('wire:model');
    $directive = array_key_first($computed->getAttributes());
    $property = $computed[$directive];
    $personalize = tallstackui_personalization('tab', $personalization());
@endphp

<div @if ($property)
        @if (!str($directive)->contains('.live'))
            x-data="tallstackui_tab(@entangle($property))"
        @else
            x-data="tallstackui_tab(@entangle($property).live)"
        @endif
    @else x-data="tallstackui_tab(@js($selected))"
    @endif @class($personalize['wrapper'])>
    <div class="p-2 sm:p-0">
        <select id="tab-select-{{ $id }}"
                x-model="tab"
                @class($personalize['select'])>
        </select>
    </div>
    <ul>
        <div @class($personalize['body'])>
            {{ $slot }}
        </div>
        <hr @class($personalize['divider'])>
        <div @class($personalize['item']) id="tab-content-{{ $id }}"></div>
    </ul>
</div>
