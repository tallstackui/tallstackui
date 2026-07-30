<span @class([$customization['bars.wrapper'], $customization['bars.sizes.wrapper.' . $size]])
      dusk="spinner-bars">
    @for ($bar = 0; $bar < 3; $bar++)
        <span @class([
            $customization['bars.bar'],
            $customization['bars.sizes.bar.' . $size],
            $customization['delays.' . $bar],
        ])></span>
    @endfor
</span>
