@php
$titleElement = $titleElement();
$textElement  = $textElement();
@endphp

<div @class($baseClass())
     x-data="{ show : true }"
     x-show="show"
     x-transition.delay.50ms>
    @if ($title)
        <div @class($titleElement ['wrapper'])>
            <h3 @class($titleElement ['base'])>{{ $title }}</h3>
            @if ($closeable)
                <div @class($titleElement ['icon']['wrapper'])>
                    <button x-on:click="show = false">
                        <x-icon name="x-mark" style="{{ $titleElement ['icon']['style'] }}" :class="$titleElement ['icon']['class']" />
                    </button>
                </div>
            @endif
        </div>
    @endif
    @if ($text)
    <div @class($textElement['wrapper'])>
        <div @class($textElement['title']['wrapper'])>
            <p>{{ $text ?? $slot }}</p>
        </div>
        @if (!$title && $closeable)
        <div @class($textElement['title']['icon']['wrapper'])>
            <button x-on:click="show = false">
                <x-icon name="x-mark" style="{{ $textElement['title']['icon']['style'] }}" :class="$textElement['title']['icon']['class']" />
            </button>
        </div>
        @endif
    </div>
    @endif
</div>
