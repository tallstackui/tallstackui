@php
    $personalize = $classes();
    // Replaces bg- with text- because svg uses text to apply the color
    // and there is no need to create a new method in the progress color.
    $colors['background'] = str_replace('bg-', 'text-', $colors['background']);
@endphp

<div @class(['relative', $personalize['sizes.circle.' . $size]])>
    <svg class="h-full w-full"
         width="{{ $sizeCircle }}"
         height="{{ $sizeCircle }}"
         viewBox="0 0 36 36"
         xmlns="http://www.w3.org/2000/svg">
        <circle cx="18"
                cy="18"
                r="{{ $sizeCircle / 2 - $strokePercent / 2 }}"
                fill="none"
                @class(['stroke-current', $personalize['background']])
                stroke-width="{{ $strokeCircle }}"></circle>
        <g class="origin-center -rotate-90 transform">
            <circle cx="18"
                    cy="18"
                    r="{{ $sizeCircle / 2 - $strokePercent / 2 }}"
                    fill="none"
                    @class(['stroke-current', $colors['background']])
                    stroke-width="{{ $strokePercent }}"
                    stroke-dasharray="100"
                    stroke-dashoffset="{{ 100 - $percent }}"></circle>
        </g>
    </svg>
    <div @class($personalize['wrapper'])>
        <span @class([$personalize['text'], $personalize['sizes.text.' . $size]])>{{ $percent }}%</span>
    </div>
    @if ($footer)
       <div>{{ $footer }}</div>
    @endif
</div>
