@php
    $customization = $classes();
@endphp

<div aria-busy="true" aria-live="polite"
     wire:key="tallstackui-chart-skeleton-{{ uniqid() }}"
     {{ $attributes->class([$customization['wrapper'], $customization['skeleton.animation']]) }}>
    @if ($header)
        <div @class([$customization['skeleton.bar'], $customization['skeleton.header']])></div>
    @endif

    <div class="{{ $customization['plot.wrapper'] }}" style="min-height: {{ $configurations['height'] }}px">
        <div class="{{ $customization['axis.y.wrapper'] }}"></div>

        <div class="relative min-w-0">
            <svg class="{{ $customization['plot.svg'] }}"
                 viewBox="{{ $viewbox }}"
                 preserveAspectRatio="{{ $aspect }}"
                 xmlns="http://www.w3.org/2000/svg"
                 aria-hidden="true"
                 dusk="tallstackui_chart_skeleton">
                @if ($radial)
                    @foreach ($slices as $slice)
                        <path class="{{ $customization['plot.slice'] }} {{ $customization['skeleton.fill'] }}"
                              d="{{ $slice['path'] }}" />
                    @endforeach
                @else
                    @foreach ($bars as $bar)
                        <path class="{{ $customization['skeleton.fill'] }}" d="{{ $bar['path'] }}" />
                    @endforeach
                    @if ($fill)
                        <path class="{{ $customization['skeleton.fill'] }}" d="{{ $fill }}" />
                    @endif
                    @if ($stroke)
                        <path class="{{ $customization['skeleton.stroke'] }}" d="{{ $stroke }}" />
                    @endif
                @endif
            </svg>
        </div>

        <div class="{{ $customization['axis.y.right.wrapper'] }}"></div>
        <div></div>
        <div class="{{ $customization['axis.x.wrapper'] }}"></div>
    </div>

    @if ($footer)
        <div @class([$customization['skeleton.bar'], $customization['skeleton.footer']])></div>
    @endif
</div>
