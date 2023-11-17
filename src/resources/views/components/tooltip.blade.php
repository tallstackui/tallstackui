@php
    $personalize = tallstackui_personalization('tooltip', $personalization());
    $sizes = $personalize['sizes.' . $size];
@endphp

<div @class($personalize['wrapper']) x-data>
    <div class="relative" x-data="tallstackui_tooltip(@js($text), @js($position))">
        <div x-ref="tooltip"
             x-show="visible"
             x-cloak
             @class([
                'absolute w-auto text-sm',
                'top-0 left-1/2 -translate-x-1/2 -mt-2.5 -translate-y-full' => $position === 'top',
                'top-1/2 -translate-y-1/2 -ml-2.5 left-0 -translate-x-full' => $position === 'left',
                'bottom-0 left-1/2 -translate-x-1/2 -mb-2.5 translate-y-full' => $position === 'bottom',
                'top-1/2 -translate-y-1/2 -mr-2.5 right-0 translate-x-full' => $position === 'right',
             ])>
            <div x-show="visible"
                 x-transition
                 @class([
                    $personalize['color'],
                    'relative px-2 py-1 text-white rounded',
                 ])>
                <p x-html="text" class="text-sm whitespace-nowrap"></p>
                <div x-ref="arrow"
                    @class([
                        'absolute inline-flex items-center justify-center',
                        'bottom-0 -translate-x-1/2 left-1/2 w-5 translate-y-full' => $position === 'top',
                        'right-0 -translate-y-1/2 top-1/2 h-5 translate-x-full' => $position === 'left',
                        'top-0 -translate-x-1/2 left-1/2 w-5 -translate-y-full' => $position === 'bottom',
                        'left-0 -translate-y-1/2 top-1/2 h-5 -translate-x-full' => $position === 'right',
                    ])>
                    <div @class([
                        $personalize['color'],
                        'w-1.5 h-1.5 transform',
                        'origin-top-left -rotate-45' => $position === 'top',
                        'origin-top-left rotate-45' => $position === 'left',
                        'origin-bottom-left rotate-45' => $position === 'bottom',
                        'origin-top-right -rotate-45' => $position === 'right',
                    ])>
                    </div>
                </div>
            </div>
        </div>
        <div x-ref="content">
            <x-dynamic-component 
                component="tallstack-ui::icon.{{ $style }}.{{ $icon }}"
                {{ $attributes->class(['cursor-pointer', 'focus:outline-none', $sizes, $colors['icon']]) }} 
            />
        </div>
    </div>
</div>
