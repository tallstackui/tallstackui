@php
    $customization = $classes();
    $flash = session()->pull('ts-ui:banner');
    $wire = $flash ? true : $wire;
@endphp

@if ($show)
    <div x-data="tallstackui_banner(@js($flash), @js($animated), @js($wire), @js($text ??= $slot->toHtml()), @js($enter), @js($leave), @js($close))"
         @class([
             $customization['wire'] => $wire,
             $customization['wrapper'],
             $customization['sizes.' . $size],
             $colors['background'] ?? $color['background'] => !$wire
         ])
         @if ($wire)
             x-bind:class="{
            'bg-green-600' : type === 'success',
            'bg-red-600' : type === 'error',
            'bg-yellow-600' : type === 'warning',
            'bg-blue-600' : type === 'info'
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
                    'text-green-50' : type === 'success',
                    'text-red-50' : type === 'error',
                    'text-yellow-50' : type === 'warning',
                    'text-blue-50' : type === 'info'
                }" @endif>
                {!! $left !!}
            </span>
        @endif
        @if ($wire)
            <div @class([$customization['text'], 'flex justify-center items-center gap-2'])>
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
                <span class="text-white" x-html="text"></span>
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
                                    'text-green-50': type === 'success',
                                    'text-red-50': type === 'error',
                                    'text-yellow-50': type === 'warning',
                                    'text-blue-50': type === 'info'
                                 }" />
        </button>
    </div>
@endif
