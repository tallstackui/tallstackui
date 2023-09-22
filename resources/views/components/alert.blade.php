@php
$baseTitle = $getBaseTitle();
$baseText  = $getBaseText();
@endphp

<div @class($getBaseClass())
     x-data="{ show : true }"
     x-show="show"
     x-transition.delay.50ms>
    @if ($title)
        <div @class($baseTitle['wrapper'])>
            <h3 @class($baseTitle['title'])>{{ $title }}</h3>
            @if ($closeable)
                <div @class($baseTitle['icon']['wrapper'])>
                    <button x-on:click="show = false">
                        <x-icon name="x-mark" style="{{ $baseTitle['icon']['style'] }}" :class="$baseTitle['icon']['class']" />
                    </button>
                </div>
            @endif
        </div>
    @endif
    @if ($text)
    <div @class($baseText['wrapper'])>
        <div @class($baseText['title']['wrapper'])>
            {{ $text ?? $slot }}
        </div>
        @if (!$title && $closeable)
        <div @class($baseText['title']['icon']['wrapper'])>
            <button x-on:click="show = false">
                <x-icon name="x-mark" style="{{ $baseText['title']['icon']['style'] }}" :class="$baseText['title']['icon']['class']" />
            </button>
        </div>
        @endif
    </div>
    @endif
</div>
