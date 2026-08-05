@php
    $customization = $classes();
@endphp

<div aria-busy="true" aria-live="polite"
     {{ $attributes->class([$customization['wrapper'], $customization['skeleton.animation']]) }}>
    <svg viewBox="{{ $viewbox }}"
         xmlns="http://www.w3.org/2000/svg"
         aria-hidden="true"
         dusk="tallstackui_qr_code_skeleton"
         @class([$customization['code'], $customization['sizes.' . $scale]])>
        @foreach ($blocks as [$x, $y, $width, $height])
            <rect class="{{ $customization['skeleton.block'] }}"
                  x="{{ $x }}"
                  y="{{ $y }}"
                  width="{{ $width }}"
                  height="{{ $height }}"
                  rx="0.3" />
        @endforeach
    </svg>
</div>
