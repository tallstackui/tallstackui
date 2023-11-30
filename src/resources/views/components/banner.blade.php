@php
    /** @var \Illuminate\View\ComponentSlot|string $left */
    $text ??= $slot->toHtml();
    $personalize = tallstackui_personalization('banner', $personalization());
@endphp

@if ($show)
    <div x-data="tallstackui_banner(@js($animated), @js($wire), @js($text), @js($enter), @js($leave), @js($close))"
         @class([$personalize['wrapper'], $personalize['sizes.' . $size], $colors['background'] => !$wire])
         @if ($wire)
         x-bind:class="{
            'bg-green-600' : type === 'success',
            'bg-red-600' : type === 'error',
            'bg-yellow-600' : type === 'warning',
            'bg-blue-600' : type === 'info'
         }" @endif
         x-show="show && text !== ''"
         x-cloak
         @if ($wire) x-on:tallstackui:navbar.window="add($event)" @endif
         @if ($animated || $close || $wire)
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="-translate-y-10"
             x-transition:enter-end="translate-y-0"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="translate-y-0"
             x-transition:leave-end="-translate-y-10"
        @endif>
        @if ($left)
            <span @if (!is_string($left)) {{
                    $left->attributes->class([$personalize['slot.left'], $colors['text'] => !$wire])
                }} x-bind:class="{
                    'text-green-50' : type === 'success',
                    'text-red-50' : type === 'error',
                    'text-yellow-50' : type === 'warning',
                    'text-blue-50' : type === 'info'
                }" @endif>
                {!! $left !!}
            </span>
        @endif
        <span @class([$personalize['text'], $colors['text'] => !$wire])
              x-bind:class="{
                    'text-green-50' : type === 'success',
                    'text-red-50' : type === 'error',
                    'text-yellow-50' : type === 'warning',
                    'text-blue-50' : type === 'info'
              }"
              x-text="text"></span>
        <x-icon name="x-mark"
                @class([$personalize['close'], $colors['text'] => !$wire])
                x-bind:class="{
                    'text-green-50' : type === 'success',
                    'text-red-50' : type === 'error',
                    'text-yellow-50' : type === 'warning',
                    'text-blue-50' : type === 'info'
                }" x-on:click="show = false" x-show="close" />
    </div>
@endif
