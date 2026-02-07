<div class="relative">
    <div @class($customization['floating.wrapper']) style="margin-inline-start: calc({{ $percent }}% - 1.25rem);">
        {{ $percent }}%
    </div>
    <div @class([$customization['floating.progress'], $customization['sizes.' . $size]])
         role="progressbar"
         aria-valuenow="{{ $percent }}"
         aria-valuemin="0"
         aria-valuemax="100">
        <div @class([$customization['floating.float'], $colors['background']]) style="width: {{ $percent }}%"></div>
    </div>
</div>
