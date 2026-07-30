<span @class([$customization['typing.wrapper'], $customization['typing.sizes.wrapper.' . $size]])
      dusk="spinner-typing">
    @for ($dot = 0; $dot < 3; $dot++)
        <span @class([
            $customization['typing.dot'],
            $customization['typing.sizes.dot.' . $size],
            $customization['delays.' . $dot],
        ])></span>
    @endfor
</span>
