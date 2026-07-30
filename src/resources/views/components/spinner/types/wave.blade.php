<span @class([$customization['wave.wrapper'], $customization['wave.sizes.wrapper.' . $size]])
      dusk="spinner-wave">
    @for ($bar = 0; $bar < 5; $bar++)
        <span @class([
            $customization['wave.bar'],
            $customization['wave.sizes.bar.' . $size],
            $customization['delays.' . $bar],
        ])></span>
    @endfor
</span>
