<div {{ $attributes->merge(['class' => $colors['base']]) }}
     x-data="{ show : true }"
     x-show="show"
     x-transition.delay.50ms>
    @if ($title)
        <div class="flex justify-between">
            <h3 @class($colors['title'])>{{ $title }}</h3>
            @if ($closeable)
                <div class="ml-auto pl-3">
                    <button x-on:click="show = false">
                        <x-icon name="x-mark" :class="$colors['icon']" />
                    </button>
                </div>
            @endif
        </div>
    @endif
    <div class="flex items-center justify-between">
        <div @class($colors['text'])>
            {{ $text ?? $slot }}
        </div>
        @if (!$title && $closeable)
        <div class="flex items-center">
            <button x-on:click="show = false">
                <x-icon name="x-mark" :class="$colors['icon']" />
            </button>
        </div>
        @endif
    </div>
</div>
