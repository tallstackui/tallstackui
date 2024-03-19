<div class="relative">
    <div @class($personalize['floating.wrapper']) style="margin-inline-start: calc({{ $percent }}% - 1.25rem);">
        {{ $percent }}%
    </div>
    <div @class([$personalize['floating.progress'], $personalize['sizes.' . $size]])
         role="progressbar"
         aria-valuenow="{{ $percent }}"
         aria-valuemin="0"
         aria-valuemax="100">
        <div @class([$personalize['floating.float'], $colors['background']]) style="width: {{ $percent }}%"></div>
    </div>
</div>
