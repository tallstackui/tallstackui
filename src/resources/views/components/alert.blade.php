@php
    $personalize = $classes();
@endphp

<div @class([$personalize['wrapper'], $colors['background']])
     x-data="{ show : true }"
     x-show="show">
    <div @class([$personalize['content.wrapper'], 'items-start' => $title !== null])>
        <div @class([$personalize['content.base']])>
            @if ($icon)
                <div @class(['mr-2' => $icon, 'mt-1' => $icon && $title])>
                    @if ($icon)
                        <x-dynamic-component :component="TallStackUi::component('icon')"
                                             :$icon
                                             @class([$personalize['icon.size'], $colors['text']]) />
                    @endif
                </div>
            @endif
            <div @class([$colors['text']])>
                @if ($title)
                    <h3 @class([$personalize['text.title'], $colors['text'] => $title !== null, 'mb-2' => $title])>{{ $title }}</h3>
                @endif
                <p @class([$personalize['text.description']])>{{ $text ?? $slot }}</p>
            </div>
        </div>
        @if ($close)
            <div @class($personalize['close.wrapper'])>
                <button type="button" dusk="alert-close-button" class="cursor-pointer" x-on:click="show = false">
                    <x-dynamic-component :component="TallStackUi::component('icon')"
                                         :icon="TallStackUi::icon('x-mark')"
                                         @class([$personalize['close.size'], $colors['text']]) />
                </button>
            </div>
        @endif
    </div>
    @if ($footer)
        {{ $footer }}
    @endif
</div>
