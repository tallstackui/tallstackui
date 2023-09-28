@php($customize = tasteui_personalization('alert', $customization()))

<div @class($customize['base'])
     x-data="{ show : true }"
     x-show="show"
     x-transition.delay.50ms>
    @if ($title)
        <div @class($customize['title.wrapper'])>
            <h3 @class($customize['title.base'])>{{ $title }}</h3>
            @if ($closeable)
                <div @class($customize['title.icon.wrapper'])>
                    <button id="close" x-on:click="show = false">
                        <x-icon icon="x-mark" @class($customize['title.icon.classes']) />
                    </button>
                </div>
            @endif
        </div>
    @endif
    <div @class($customize['text.wrapper'])>
        <div @class($customize['text.title.wrapper'])>
            <p>{{ $text ?? $slot }}</p>
        </div>
        @if (!$title && $closeable)
            <div @class($customize['text.title.icon.wrapper'])>
                <button id="close" x-on:click="show = false">
                    <x-icon icon="x-mark" @class($customize['text.title.icon.classes']) />
                </button>
            </div>
        @endif
    </div>
</div>
