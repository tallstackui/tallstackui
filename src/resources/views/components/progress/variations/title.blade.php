<div>
    <div @class($customization['title.wrapper'])>
        <h3 @class($customization['title.title'])>{{ $title }}</h3>
        <span @class($customization['title.percent'])>{{ $percent }}%</span>
    </div>
    <div @class([$customization['title.progress'], $customization['sizes.' . $size]])
         role="progressbar"
         aria-valuenow="{{ $percent }}"
         aria-valuemin="0"
         aria-valuemax="100">
        <div @class([$customization['title.bar'], $colors['background']]) style="width: {{ $percent }}%"></div>
    </div>
</div>
