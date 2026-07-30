<svg @class([$customization['throbber.base'], $customization['throbber.sizes.' . $size]])
     dusk="spinner-throbber"
     viewBox="0 0 24 24"
     fill="none"
     xmlns="http://www.w3.org/2000/svg">
    @for ($segment = 0; $segment < 12; $segment++)
        <line class="{{ $customization['throbber.segment'] }}"
              x1="12"
              y1="2.5"
              x2="12"
              y2="6.5"
              stroke-width="2.5"
              stroke-linecap="round"
              opacity="{{ round(0.15 + ($segment / 11) * 0.85, 2) }}"
              transform="rotate({{ $segment * 30 }} 12 12)" />
    @endfor
</svg>
