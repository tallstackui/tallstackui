<div>
    <div @class($personalize['title.wrapper'])>
        <h3 @class($personalize['title.title'])>{{ $title }}</h3>
        <span @class($personalize['title.percent'])>{{ $percent }}%</span>
    </div>
    <div @class([$personalize['title.progress'], $personalize['sizes.' . $size]])
         role="progressbar"
         aria-valuenow="{{ $percent }}"
         aria-valuemin="0"
         aria-valuemax="100">
        <div @class([$personalize['title.bar'], $colors['background']]) style="width: {{ $percent }}%"></div>
    </div>
</div>
