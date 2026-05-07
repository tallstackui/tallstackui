@php
    $customization = $classes();
@endphp

<template x-teleport="body">
<div x-cloak
     @if ($id) id="{{ $id }}" @endif
     @class(['relative', $configurations['zIndex']])
     aria-labelledby="modal-title"
     role="dialog"
     aria-modal="true"
     @if ($wire)
         x-data="tallstackui_modal(@entangle($entangle), @js($configurations['overflow'] ?? false))"
     @else
         x-data="tallstackui_modal(false, @js($configurations['overflow'] ?? false))"
     @endif
     x-show="show"
     @if (!$configurations['persistent']) x-on:keydown.escape.window="top_ui && (show = false)" @endif
     x-on:modal:{{ $open }}.window="show = true;"
     x-on:modal:{{ $close }}.window="show = false;"
        {{ $attributes->whereStartsWith('x-on:') }}>
    <div x-show="show"
         @if (!$ts_ui__flash)
             x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
            @endif
            @class([$customization['wrapper.first'], ($configurations['blur'] ? $customization['blur.'.($configurations['blur'] === true ? 'sm' : $configurations['blur'])] : '') => (bool) $configurations['blur']])></div>
    <div class="{{ $customization['wrapper.second'] }}">
        <div @class([
                $customization['wrapper.third'],
                $configurations['size'],
                $customization['positions.top'] => !$configurations['center'],
                $customization['positions.center'] => $configurations['center'],
                $customization['wrapper.third-centered-padding'] => $configurations['center'],
            ])>
            <div x-show="show"
                 @if (!$configurations['persistent']) x-on:mousedown.away="top_ui && (show = false)" @endif
                 @if (!$ts_ui__flash)
                     x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    @endif
                    @class([$customization['wrapper.fourth'], $configurations['size'], $customization['wrapper.scrollable'] => $configurations['scrollable'], $customization['wrapper.fourth-centered-rounded'] => $configurations['center']])>
                @if ($title)
                    <div class="{{ $customization['title.wrapper'] }}">
                        <h3 class="{{ $customization['title.text'] }}">{{ $title }}</h3>
                        <button type="button" x-on:click="show = false" dusk="tallstackui_modal_close">
                            <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                 :icon="TallStackUi::icon('x-mark')"
                                                 internal
                                                 class="{{ $customization['title.close'] }}" />
                        </button>
                    </div>
                @endif
                <div @class([
                        $customization['body'],
                        $customization['body.scrollable'] => $configurations['scrollable'],
                        $customization['scrollbar.thin'] => $configurations['scrollable'] && $configurations['scrollbar'] === 'thin',
                        $customization['scrollbar.thick'] => $configurations['scrollable'] && $configurations['scrollbar'] === 'thick',
                    ])>
                    {{ $slot }}
                </div>
                @if ($footer)
                    <div @class([$customization['footer'], $customization['footer.scrollable'] => $configurations['scrollable']])>
                        {{ $footer }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
</template>
