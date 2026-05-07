@php
    $customization = $classes();
    $flash = session()->pull('ts-ui:banner');
    $wire = $flash ? true : $wire;
@endphp

@if ($show)
    <div x-data="tallstackui_banner(@js($flash), @js($animated), @js($wire), @js($text ??= $slot->toHtml()), @js($enter), @js($leave), @js($close))"
         @class([
             $customization['wire.base'] => $wire,
             $customization['wrapper'],
             $customization['sizes.' . $size],
             $colors['background'] ?? $color['background'] => !$wire
         ])
         @if ($wire)
             x-bind:class="{
            '{{ $customization['wire.background.success'] }}' : type === 'success',
            '{{ $customization['wire.background.error'] }}' : type === 'error',
            '{{ $customization['wire.background.warning'] }}' : type === 'warning',
            '{{ $customization['wire.background.info'] }}' : type === 'info'
         }" @endif
         x-show="show && text !== ''"
         x-cloak
         @if ($wire) x-on:ts-ui:banner.window="add($event)" @endif
         @if (($animated || $close || $wire) && !$ts_ui__flash)
             x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="-translate-y-10"
         x-transition:enter-end="translate-y-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="translate-y-0"
         x-transition:leave-end="-translate-y-10"
            @endif>
        @if ($left)
            <span @if (!is_string($left)) {{
                    $left->attributes->class([$customization['slot.left'], $colors['text'] ?? '' => !$wire])
                }} x-bind:class="{
                    '{{ $customization['wire.text.success'] }}' : type === 'success',
                    '{{ $customization['wire.text.error'] }}' : type === 'error',
                    '{{ $customization['wire.text.warning'] }}' : type === 'warning',
                    '{{ $customization['wire.text.info'] }}' : type === 'info'
                }" @endif>
                {!! $left !!}
            </span>
        @endif
        @if ($wire)
            <div @class([$customization['text'], $customization['wire.content']])>
                <div x-show="type === 'success'">
                    <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                         :icon="TallStackUi::icon('check-circle')"
                                         outline
                                         internal
                                         class="{{ $customization['icon'] }}" />
                </div>
                <div x-show="type === 'error'">
                    <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                         :icon="TallStackUi::icon('x-circle')"
                                         outline
                                         internal
                                         class="{{ $customization['icon'] }}" />
                </div>
                <div x-show="type === 'info'">
                    <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                         :icon="TallStackUi::icon('information-circle')"
                                         outline
                                         internal
                                         class="{{ $customization['icon'] }}" />
                </div>
                <div x-show="type === 'warning'">
                    <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                         :icon="TallStackUi::icon('exclamation-circle')"
                                         outline
                                         internal
                                         class="{{ $customization['icon'] }}" />
                </div>
                <span class="{{ $customization['wire.text.base'] }}" x-html="text"></span>
            </div>
        @elseif ($rotate)
            <div @class([
                $customization['rotate.viewport'],
                $customization['rotate.spacing.left'] => $left,
                $customization['rotate.spacing.right'] => $close,
            ])>
                <div @class([
                    $customization['rotate.track'],
                    $customization['rotate.speeds.'.$speed],
                ])>
                    <span @class([$customization['rotate.item'], $colors['text'] ?? $color['text']])>{!! $text ??= $slot->toHtml() !!}</span>
                </div>
            </div>
        @else
            <span @class([$customization['text'], $colors['text'] ?? $color['text']])>
                {!! $text ??= $slot->toHtml() !!}
            </span>
        @endif
        <button type="button" x-on:click="show = false" x-show="close" dusk="tallstackui_banner_close">
            <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                 :icon="TallStackUi::icon('x-mark')"
                                 internal
                                 @class([$customization['close'], $colors['text'] ?? '' => !$wire])
                                 x-bind:class="{
                                    '{{ $customization['wire.text.success'] }}': type === 'success',
                                    '{{ $customization['wire.text.error'] }}': type === 'error',
                                    '{{ $customization['wire.text.warning'] }}': type === 'warning',
                                    '{{ $customization['wire.text.info'] }}': type === 'info'
                                 }" />
        </button>
    </div>
@endif
