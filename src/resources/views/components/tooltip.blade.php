@php($personalize = tallstackui_personalization('tooltip', $personalization()))

<div class="inline-flex" x-data>
    <div class="relative" x-data="{ show : false }">
        <div x-ref="tooltip"
             x-show="show"
             x-cloak
             @class([
                'absolute min-w-max',
                'top-0 left-1/2 -translate-x-1/2 -mt-1.5 -translate-y-full' => $position === 'top',
                'top-1/2 -translate-y-1/2 -ml-1.5 left-0 -translate-x-full' => $position === 'left',
                'bottom-0 left-1/2 -translate-x-1/2 -mb-1.5 translate-y-full' => $position === 'bottom',
                'top-1/2 -translate-y-1/2 -mr-1.5 right-0 translate-x-full' => $position === 'right',
             ])>
            <div x-show="show" @class([
                     $personalize['color.default'] => $configurations['thematic'] === false,
                     $personalize['color.thematic'] => $configurations['thematic'] === true,
                     $personalize['wrapper']
                 ])>
                <p class="text-sm">{!! $text ?? $slot !!}</p>
                <div x-ref="arrow"
                    @class([
                        'absolute inline-flex items-center justify-center',
                        'bottom-0 -translate-x-1/2 left-1/2 w-5 translate-y-full' => $position === 'top',
                        'right-0 -translate-y-1/2 top-1/2 h-5 translate-x-full' => $position === 'left',
                        'top-0 -translate-x-1/2 left-1/2 w-5 -translate-y-full' => $position === 'bottom',
                        'left-0 -translate-y-1/2 top-1/2 h-5 -translate-x-full' => $position === 'right',
                    ])>
                    <div @class([
                        $personalize['color.default'] => $configurations['thematic'] === false,
                        $personalize['color.thematic'] => $configurations['thematic'] === true,
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
        <div x-ref="content"
             x-on:mouseenter="show = true"
             x-on:mouseleave="show = false">
            <x-dynamic-component component="tallstack-ui::icon.{{ $style }}.{{ $icon }}"
                                 @class(['cursor-pointer', 'focus:outline-none', $personalize['sizes.' . $size], $colors['icon']])
            />
        </div>
    </div>
</div>
