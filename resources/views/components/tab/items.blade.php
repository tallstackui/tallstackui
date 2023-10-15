@php($customize = tallstackui_personalization('tab.items', $personalization()))

<div x-data="{name: @js($tab)}" x-show="selected(@js($tab))" role="tabpanel" {{ $attributes->class([
        $customize['item'],
        'rounded-bl-lg rounded-br-lg rounded-tr-lg' => !$configurations['square'],
    ]) }}>
    {{ $slot }}
</div>
