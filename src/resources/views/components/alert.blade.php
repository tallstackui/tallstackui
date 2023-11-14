@php($personalize = tallstackui_personalization('alert', $personalization()))

<div @class([$personalize['wrapper'], 'animate-pulse' => $pulse, $colors['background']])
     x-data="{ show : true }"
     x-show="show">
    @if ($title)
        <div @class($personalize['title.wrapper'])>
            <h3 @class([$personalize['title.text'], $colors['text'] => $title !== null])>{{ $title }}</h3>
            @if ($closeable)
                <div @class($personalize['title.close.wrapper'])>
                    <button dusk="alert-close-button" class="cursor-pointer" x-on:click="show = false">
                        <x-icon icon="x-mark" @class([$personalize['title.close.size'], $colors['text']]) />
                    </button>
                </div>
            @endif
        </div>
    @endif
    <div @class($personalize['text.wrapper'])>
        <div @class([
                'text-sm',
                'inline-flex' => !$title && $icon,
                'mt-2' => $title,
                $colors['text'],
            ])>
            @if (!$title && $icon)
                <div @class($personalize['icon.wrapper'])>
                    <x-icon :$icon @class([$personalize['icon.size'], $colors['text']]) />
                </div>
            @endif
            <p>{{ $text ?? $slot }}</p>
        </div>
        @if (!$title && $closeable)
            <div @class($personalize['text.close.wrapper'])>
                <button dusk="alert-close-button" class="cursor-pointer" x-on:click="show = false">
                    <x-icon icon="x-mark" @class([$personalize['text.close.size'], $colors['text']]) />
                </button>
            </div>
        @endif
    </div>
</div>
