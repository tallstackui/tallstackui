@php
    $personalize = $classes();
@endphp

<span @if ($clickable) class="cursor-pointer" {{ $attributes }} @endif>
    @if ($boolean)
        <x-dynamic-component :component="TallStackUi::component('icon')"
                             :icon="TallStackUi::icon($iconWhenTrue)"
                             @class([$personalize['icon'], $colors['icon']]) />
    @else
        <x-dynamic-component :component="TallStackUi::component('icon')"
                             :icon="TallStackUi::icon($iconWhenFalse)"
                             @class([$personalize['icon'], $colors['icon']]) />
    @endif
</span>
