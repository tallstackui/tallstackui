@php
    $customize = $customize();

    $customize['main'] ??= $customMainClasses();
    $customize['title'] ??= $customTitleClasses();
    $customize['text'] ??= $customTextClasses();
@endphp

<div @class($customize['main'])
     x-data="{ show : true }"
     x-show="show"
     x-transition.delay.50ms>
    @if ($title)
        <div @class($customize['title']['wrapper'])>
            <h3 @class($customize['title']['base'])>{{ $title }}</h3>
            @if ($closeable)
                <div @class($customize['title']['icon']['wrapper'])>
                    <button x-on:click="show = false">
                        <x-icon name="x-mark" @class($customize['title']['icon']['class']) />
                    </button>
                </div>
            @endif
        </div>
    @endif
    @if ($text)
    <div @class($customize['text']['wrapper'])>
        <div @class($customize['text']['title']['wrapper'])>
            <p>{{ $text ?? $slot }}</p>
        </div>
        @if (!$title && $closeable)
        <div @class($customize['text']['title']['icon']['wrapper'])>
            <button x-on:click="show = false">
                <x-icon name="x-mark" @class($customize['text']['title']['icon']['class']) />
            </button>
        </div>
        @endif
    </div>
    @endif
</div>
