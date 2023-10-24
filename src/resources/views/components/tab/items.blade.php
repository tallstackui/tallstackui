@php($customize = tallstackui_personalization('tab.items', $personalization()))

<div x-data="{ name: @js($tab) }" x-show="selected(@js($tab))" role="tabpanel" {{ $attributes->class($customize['item']) }}>
    {{ $slot }}
</div>
