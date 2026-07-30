<span class="{{ $customization['thinking.wrapper'] }}"
      dusk="spinner-thinking">
    <span x-data="tallstackui_spinner(@js($frames), @js($interval))"
          x-text="frames[index]"
          @class([$customization['thinking.glyph'], $customization['thinking.sizes.glyph.' . $size]])
          dusk="spinner-thinking-glyph">{{ $frames[0] }}</span>
    @if (!empty(trim($slot->toHtml())))
        <span @class([$customization['thinking.label'], $customization['thinking.sizes.text.' . $size]])>{{ $slot }}</span>
    @endif
</span>
