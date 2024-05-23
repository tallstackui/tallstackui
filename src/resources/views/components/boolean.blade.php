@php
    $personalize = $classes();
@endphp

<span @if ($clickable) class="cursor-pointer" {{ $attributes }} @endif>
    <x-dynamic-component :component="TallStackUi::component('icon')"
                         :icon="TallStackUi::icon($boolean ? $iconWhenTrue : $iconWhenFalse)"
                         @class([$personalize['icon'], $colors['icon']]) />
</span>
