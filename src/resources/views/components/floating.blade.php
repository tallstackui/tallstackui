@php($personalize = $classes())

<div x-show="{{ $attributes->get('x-show', 'show') }}"
     {{ $anchor() }}="{{ $attributes->get('x-anchor', '$refs.anchor') }}"
     @if (method_exists($attributes, 'isEmpty') && count($attributes->whereStartsWith('x-transition')->getAttributes()) === 0)
         x-transition:enter="transition duration-100 ease-out"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
     @else {!! $attributes->except(['x-show', 'x-anchor']) !!} @endif @class([$attributes->get('wrapper', $personalize['wrapper']), $size])>
    {{ $slot }}
    {{ $footer }}
</div>
