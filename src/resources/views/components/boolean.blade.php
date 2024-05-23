@php
    $personalize = $classes();
@endphp

<span {{ $attributes }} @if ($attributes->has('wire:click')) class="cursor-pointer" @endif>
    <x-dynamic-component :component="TallStackUi::component('icon')"
                         :icon="TallStackUi::icon($boolean ? $iconWhenTrue : $iconWhenFalse)"
                         @class([$personalize['icon'], $colors['icon']]) />
</span>
