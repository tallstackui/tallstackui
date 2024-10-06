@php
    $personalize = $classes();
@endphp

<div x-data="{ show: false }" @class([
        $personalize["position.{$position}"],
        'flex' => $horizontal,
    ])
    @if ($hover)
    @mouseover="show = true"
    @mouseleave="show = false"
    @endif
    >
    @if ($position === 'top-left' || ($position === 'top-right' && !$horizontal))
    <button dusk="dial_open" @if(!$hover) x-on:click="show = !show" @endif type="button" 
        @class([
            'mb-4',
            $personalize['button'],
            'rounded-full' => !$square,
            'rounded-lg' => $square,
            'items-center' => $horizontal,
        ])>
        @if ($icon)
            <x-dynamic-component :component="TallStackUi::component('icon')"
                :icon="TallStackUi::icon($icon)"
                @class($personalize['icon']) />
        @else
            <x-dynamic-component :component="TallStackUi::component('icon')"
                :icon="TallStackUi::icon('plus')"
                @class([$personalize['icon'], 'transition-transform group-hover:rotate-45']) />
        @endif
        
        <span class="sr-only">Open actions menu</span>
    </button>
    @endif
    <div @class([
        $personalize['items'],
        'flex-col ' => !$horizontal,
        'space-y-2' => !$horizontal,
        'space-x-2 items-center' => $horizontal,
        'ml-2' => $position === 'top-left',
        'mr-2' => $position === 'top-right',
    ]) 
    :class="{ 'hidden': !show, 'flex': show }">
        {{ $slot }}
    </div>
    @if (Str::startsWith($position, 'bottom') || ($position === 'top-right' && $horizontal))
    <button dusk="dial_open" @click="show = !show" type="button" 
        @class([
            $personalize['button'],
            'rounded-full' => !$square,
            'rounded-lg' => $square,
        ])>
        @if ($icon)
            <x-dynamic-component :component="TallStackUi::component('icon')"
                :icon="TallStackUi::icon($icon)"
                @class($personalize['icon']) />
        @else
            <x-dynamic-component :component="TallStackUi::component('icon')"
                :icon="TallStackUi::icon('plus')"
                x-bind:class="{ 'transition-transform group-hover:rotate-45': show }"
                @class([$personalize['icon']]) />
        @endif
        
        <span class="sr-only">Open actions menu</span>
    </button>
    @endif
</div>