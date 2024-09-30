@php
    $personalize = $classes();
@endphp

<div @class([
    $personalize['wrapper'],
    $rounded ?? 'rounded-md',
    'w-full' => !Str::contains($attributes->get('class'), 'w-') && !Str::contains($attributes->get('class'), 'size-'),
    'h-5' => !Str::contains($attributes->get('class'), 'h-') && !Str::contains($attributes->get('class'), 'size-'),
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
            @class('w-6 h-6 text-gray-400 dark:text-gray-500')/>
    @else
        {{ $slot }}
    @endif

    <span class="sr-only">
        loading...
    </span>
</div>
