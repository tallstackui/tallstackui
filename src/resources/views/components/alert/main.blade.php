@php
    $customization = $classes();
@endphp

<div @class([$customization['wrapper'], $colors['background']])
     x-data="{ show : true }"
     x-show="show">
    <div @class([$customization['content.wrapper'], 'items-start' => $title !== null])>
        <div class="{{ $customization['content.base'] }}">
            @if ($icon)
                <div @class([$customization['icon.wrapper'] => $icon, 'mt-1' => $icon && $title])>
                    @if ($icon)
                        <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                             :$icon
                                             internal
                                             @class([$customization['icon.size'], $colors['text']]) />
                    @endif
                </div>
            @endif
            <div class="{{ $colors['text'] }}">
                @if ($title)
                    <h3 @class([$customization['text.title'], $colors['text'] => $title !== null, 'mb-2' => $title])>{!! $title !!}</h3>
                @endif
                <p class="{{ $customization['text.description'] }}">{!! $text ?? $slot !!}</p>
            </div>
        </div>
        @if ($close)
            <div class="{{ $customization['close.wrapper'] }}">
                <button type="button" dusk="alert-close-button" class="cursor-pointer" x-on:click="show = false">
                    <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                         :icon="TallStackUi::icon('x-mark')"
                                         internal
                                         @class([$customization['close.size'], $colors['text']]) />
                </button>
            </div>
        @endif
    </div>
    @if ($footer)
        {!! $footer !!}
    @endif
</div>
