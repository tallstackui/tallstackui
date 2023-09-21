<div {{ $attributes->merge(['class' => $colors['base']]) }} x-data="{ show : true }" x-show="show" x-transition>
    <div class="ml-3">
        <div class="flex justify-between">
            @if ($title)
                <h3 @class($colors['title'])>{{ $title }}</h3>
            @endif
            @if ($closeable)
                <button x-on:click="show = !show">
                    <x-icon name="x-mark" :class="$colors['icon']" />
                </button>
            @endif
        </div>
        <div @class($colors['text'])>
            {{ $message ?? $slot }}
        </div>
    </div>
</div>
