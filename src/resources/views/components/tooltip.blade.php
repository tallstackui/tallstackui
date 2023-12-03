@php
    $personalize = tallstackui_personalization('tooltip', $personalization());
    $side = str_contains($position, 'right') || str_contains($position, 'left');
    $orientation = str_contains($position, 'bottom') || str_contains($position, 'right');
@endphp

<div class="inline-flex" x-data>
    <div class="relative" x-data="{ show : false }">
        <div x-ref="tooltip"
             x-show="show"
             x-transition:enter="transition duration-100 ease-out"
             x-transition:enter-start="opacity-0 @if ($side) @if ($orientation) -translate-x-2 @else translate-x-2 @endif @else @if ($orientation) -translate-y-2 @else translate-y-2 @endif @endif"
             x-transition:enter-end="opacity-100 @if ($side) translate-x-0 @else translate-y-0 @endif"
             x-anchor.{{ $position }}.offset.10="$refs.content"
             x-bind:style="{ position: 'absolute', top: $anchor.y+'px', left: $anchor.x+'px' }"
             x-cloak
             class="absolute z-10 w-max">
            <div x-show="show" @class([
                     $personalize['color.default'] => $configurations['thematic'] === false,
                     $personalize['color.thematic'] => $configurations['thematic'] === true,
                     $personalize['wrapper']
                 ])>
                <p class="text-sm relative z-20">{!! $text ?? $slot !!}</p>
            </div>
        </div>
        <div x-ref="content"
             x-on:mouseenter="show = true"
             x-on:mouseleave="show = false">
            <x-dynamic-component component="tallstack-ui::icon.{{ $style }}.{{ $icon }}"
                                 @class(['cursor-pointer', 'focus:outline-none', $personalize['sizes.' . $size], $colors['icon']])
            />
        </div>
    </div>
</div>
