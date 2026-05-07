@php
    $customization = $classes();
@endphp

<div @class([
        $customization['wrapper'],
        $colors['background'],
        $customization['square'] => $square,
        ($customization['rounded.'.$rounded] ?? '') => ! $square && $rounded,
        ($customization['bordered.'.$borderedAttributes['side']] ?? '') => $borderedAttributes['side'] !== null,
        $colors['bordered'] => $borderedAttributes['side'] !== null && $colors['bordered'],
     ])
     x-data="tallstackui_alert(@js($dismiss))"
     x-show="show"
     x-transition:leave="transition ease-in duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">
    <div @class([$customization['content.wrapper'], $customization['content.wrapper-with-title'] => $title !== null])>
        <div class="{{ $customization['content.base'] }}">
            @if ($icon)
                <div @class([$customization['icon.wrapper'] => $icon, $customization['icon.wrapper-with-title'] => $icon && $title])>
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
                    <h3 @class([$customization['text.title'], $colors['text'] => $title !== null, $customization['text.title-spacing'] => $title])>{!! $title !!}</h3>
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
