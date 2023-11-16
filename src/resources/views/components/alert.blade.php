@php($personalize = tallstackui_personalization('alert', $personalization()))

<div @class([$personalize['wrapper'], $colors['background']])
     x-data="{ show : true }"
     x-show="show">
    @if ($title)
        <div @class($personalize['title.wrapper'])>
            <div @class($personalize['title.icon'])>
                @if ($icon)
                    <div @class($personalize['icon.wrapper'])>
                        <x-icon :$icon @class([$personalize['icon.size'], $colors['text']]) />
                    </div>
                @endif
                <h3 @class([$personalize['title.text'], $colors['text'] => $title !== null])>{{ $title }}</h3>
            </div>
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
                'mt-2' => $title && $text,
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
