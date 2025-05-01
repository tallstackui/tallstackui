@php
    $personalize = $classes();
@endphp

<div
    x-show="{{ $attributes->get("x-show", "show") }}"
    x-cloak
    x-on:click.outside="{{ $attributes->get("x-show", "show") }} = false"
    x-on:keydown.escape.window="{{ $attributes->get("x-show", "show") }} = false"
    x-intersect:leave="{{ $attributes->get("x-show", "show") }} = false"
    {{ $anchor() }}="{{ $attributes->get("x-anchor", '$refs.anchor') }}"
    {{ $attributes->whereStartsWith("x-on") }}
    @if (count($attributes->whereStartsWith("x-transition")->getAttributes()) === 0 || $transition?->isEmpty())
        x-transition:enter="transition duration-100 ease-out"
        x-transition:enter-start="-translate-y-2 opacity-0"
        x-transition:enter-end="translate-y-0 opacity-100"
        x-transition:leave="transition duration-75 ease-in"
        x-transition:leave-start="translate-y-0 opacity-100"
        x-transition:leave-end="-translate-y-2 opacity-0"
    @elseif ($transition?->isNotEmpty())
        {{ $transition }}
    @else
        {!! $attributes->except(["x-show", "x-anchor", "class"]) !!}
    @endif
    {{ $attributes->except(["floating", "x-anchor"])->merge(["class" => $attributes->get("floating", $personalize["wrapper"])]) }}
>
    {{ $slot }}
    {{ $footer }}
</div>
