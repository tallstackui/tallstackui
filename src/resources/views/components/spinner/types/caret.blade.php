<span @class([$customization['caret.wrapper'], $customization['caret.sizes.text.' . $size]])
      dusk="spinner-caret">
    <span>{{ $slot }}</span>
    <span @class([$customization['caret.caret'], $customization['caret.sizes.caret.' . $size]])></span>
</span>
