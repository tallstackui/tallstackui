@php($customize = tallstackui_personalization('alert', $customization()))

<div @class([$customize['wrapper'], $customize['internal.wrapper.color']])
     x-data="{ show : true }"
     x-show="show">
    @if ($title)
        <div @class($customize['title.wrapper'])>
            <h3 @class([$customize['title.base'], $customize['internal.title.base.color']])>{{ $title }}</h3>
            @if ($closeable)
                <div @class($customize['title.icon.wrapper'])>
                    <button id="close" x-on:click="show = false">
                        <x-icon icon="x-mark" @class([$customize['title.icon.size'], $customize['internal.title.icon.color']]) />
                    </button>
                </div>
            @endif
        </div>
    @endif
    <div @class($customize['text.wrapper'])>
        <div @class([$customize['text.title.wrapper'], $customize['internal.text.title.wrapper.color']])>
            @if (!$title && $icon)
                <div @class($customize['icon.wrapper'])>
                    <x-icon :$icon @class([$customize['icon.size'], $customize['internal.icon.color']]) />
                </div>
            @endif
            <p>{{ $text ?? $slot }}</p>
        </div>
        @if (!$title && $closeable)
            <div @class($customize['text.title.icon.wrapper'])>
                <button id="close" x-on:click="show = false">
                    <x-icon icon="x-mark" @class([$customize['text.title.icon.size'], $customize['internal.text.title.icon.color']]) />
                </button>
            </div>
        @endif
    </div>
</div>
