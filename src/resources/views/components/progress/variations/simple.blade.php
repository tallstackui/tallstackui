<div @class([$customization['simple.wrapper'], $customization['sizes.' . $size]])
     role="progressbar"
     aria-valuenow="{{ $percent }}"
     aria-valuemin="0"
     aria-valuemax="100">
    <div @class([$customization['simple.progress'], $colors['background']])
         style="width: {{ $percent }}%">
        @if (!$withoutText)
            {{ $percent }}%
        @endif
    </div>
</div>
