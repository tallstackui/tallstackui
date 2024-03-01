@php($personalize = $classes())

<div x-show="{{ $attributes->get('x-show', 'show') }}" x-cloak
     {{ $anchor() }}="{{ $attributes->get('x-anchor', '$refs.anchor') }}"
     {{ $attributes->whereStartsWith('x-on') }}
     @if (method_exists($attributes, 'isEmpty') && count($attributes->whereStartsWith('x-transition')->getAttributes()) === 0 && $transition->isEmpty())
         x-transition:enter="transition duration-100 ease-out"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
     @elseif ($transition->isNotEmpty()) {{ $transition }} @else {!! $attributes->except(['x-show', 'x-anchor', 'class']) !!} @endif
    {{ $attributes->except('x-anchor')->merge(['class' => $personalize['wrapper']]) }}>
    {{ $slot }}
    {{ $footer }}
</div>
