@php($customize = tallstackui_personalization('tabs.items', $customization()))

<div x-data="{name: '{{$tab}}'}" x-show="selected(@js($tab))" role="tabpanel" {{ $attributes->merge(['class' => $customize['item']]) }}>
    {{ $slot }}
</div>
