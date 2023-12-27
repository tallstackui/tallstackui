@php
    $personalize = $classes();
    $entangle = TallStackUi::blade($attributes, $livewire)->entangle();
@endphp

<div @if (!$selected) x-data="tallstackui_tab({!! $entangle !!})" @else x-data="tallstackui_tab(@js($selected))"@endif
    @class($personalize['wrapper'])>
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
