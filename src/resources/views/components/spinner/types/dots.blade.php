<span @class([$customization['dots.wrapper'], $customization['dots.sizes.wrapper.' . $size]])
      dusk="spinner-dots">
    @for ($dot = 0; $dot < 3; $dot++)
        <span @class([
            $customization['dots.dot'],
            $customization['dots.sizes.dot.' . $size],
            $customization['delays.' . $dot],
        ])></span>
    @endfor
</span>
