@php
    $customization = $classes();
@endphp

<div @class(['relative grid', $customization['sizes.circle.' . $size]])>
    <svg class="{{ $customization['svg.stack'] }}"
         width="{{ $sizeCircle }}"
         height="{{ $sizeCircle }}"
         viewBox="0 0 36 36"
         xmlns="http://www.w3.org/2000/svg">
        <circle cx="18"
                cy="18"
                r="{{ $sizeCircle / 2 - $strokePercent / 2 }}"
                fill="none"
                @class([$customization['svg.stroke'], $customization['background']])
                stroke-width="{{ $strokeCircle }}"></circle>
        <g class="{{ $customization['svg.rotation'] }}">
            <circle cx="18"
                    cy="18"
                    r="{{ $sizeCircle / 2 - $strokePercent / 2 }}"
                    fill="none"
                    @class([$customization['svg.stroke'], str_replace('bg-', 'text-', $colors['background'])])
                    stroke-width="{{ $strokePercent }}"
                    stroke-dasharray="100"
                    stroke-dashoffset="{{ 100 - $percent }}"></circle>
        </g>
    </svg>
    <div class="{{ $customization['wrapper'] }}">
        <span @class([$customization['text'], $customization['sizes.text.' . $size]])>{{ $percent }}%</span>
    </div>
    @if ($footer)
        <div>{{ $footer }}</div>
    @endif
</div>
