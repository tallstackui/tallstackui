@php($customize = tasteui_personalization('tabs.item', $customization()))

<div x-show="selected(@js($tab))" role="tabpanel" {{ $attributes->merge(['class' => $customize['item']]) }}>
    {{ $slot }}
</div>
