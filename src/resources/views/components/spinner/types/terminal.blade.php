<span @class([$customization['terminal.wrapper'], $customization['terminal.sizes.text.' . $size]])
      dusk="spinner-terminal">
    <span class="{{ $customization['terminal.prompt'] }}">&gt;</span>
    @if (!empty(trim($slot->toHtml())))
        <span>{{ $slot }}</span>
    @endif
    <span @class([$customization['terminal.caret'], $customization['terminal.sizes.caret.' . $size]])></span>
</span>
