@php
    $personalize = $classes();
@endphp

<div @class([
    $personalize['wrapper'],
    $rounded ?? 'rounded-md',
    $size,
    $width => !$size,
    $height => !$size,
    $attributes->get('class'),
    ])
    aria-hidden="true"
    {!! $attributes !!}>

    @if($image)
        <x-dynamic-component :component="TallStackUi::component('icon')"
            :icon="TallStackUi::icon('photo')"
            @class([$personalize['icon']])/>
    @elseif($video)
        <x-dynamic-component :component="TallStackUi::component('icon')"
            :icon="TallStackUi::icon('video-camera')"
            @class([$personalize['icon']])/>
    @elseif($avatar)
        <x-dynamic-component :component="TallStackUi::component('icon')"
            :icon="TallStackUi::icon('user-circle')"
            @class([$personalize['icon']])/>
    @else
        {{ $slot }}
    @endif

    <span class="sr-only">
        loading...
    </span>
</div>
