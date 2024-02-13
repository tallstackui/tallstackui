@php($personalize = $classes())

<div @class($personalize['wrapper.first'])
     x-cloak
     x-data="tallstackui_stats(@js($number), @js($duration))"
     x-intersect:enter.full="visible = true"
     x-intersect:leave="visible = false; start = 0">
    @if ($header)
        <div>{{ $header }}</div>
    @endif
    <div @class($personalize['wrapper.second'])>
        @if ($icon)
            <div @class([$personalize['wrapper.third'], $colors['background']])>
                <x-dynamic-component :component="TallStackUi::component('icon')"
                                     :$icon
                                     @class($personalize['icon']) />
            </div>
        @endif
        <div class="flex-grow">
            <h2 @class($personalize['title'])>{{ $title }}</h2>
            <h2 @class($personalize['number']) x-ref="number">{{ $number }}</h2>
        </div>
        @if ($side)
            <div>{{ $side }}</div>
        @endif
    </div>
    @if ($footer)
        <div>{{ $footer }}</div>
    @endif
</div>
