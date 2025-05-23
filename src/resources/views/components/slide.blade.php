@php
    $personalize = $classes();
@endphp

<div x-cloak
     @if ($wire)
         x-data="tallstackui_slide(@entangle($entangle), @js($configurations['overflow'] ?? false))"
     @else
         x-data="tallstackui_slide(false, @js($configurations['overflow'] ?? false))"
     @endif
     x-show="show"
     @if (!$configurations['persistent']) x-on:keydown.escape.window="show = false;" @endif
     x-on:slide:{{ $open }}.window="show = true;"
     x-on:slide:{{ $close }}.window="show = false;"
     @class(['relative', $configurations['zIndex']])
     {{ $attributes->whereStartsWith('x-on:') }}>
    <div x-show="show"
         x-transition:enter="ease-in-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in-out duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @class([$personalize['wrapper.first'], $personalize['blur.'.($configurations['blur'] === true ? 'sm' : $configurations['blur'])] ?? null => $configurations['blur']])></div>
    <div class="{{ $personalize['wrapper.second'] }}">
        <div class="{{ $personalize['wrapper.third'] }}">
            <div @class([
                    $personalize['wrapper.fourth'],
                    'inset-y-0' => !$configurations['bottom'],
                    'bottom-0' => $configurations['bottom'],
                    'left-0' => $configurations['left'],    
                    'pr-10' => $configurations['left'] && $configurations['size'] !== 'full',
                    'right-0' => $configurations['left'] === false,
                    'pl-10' =>
                        $configurations['left'] === false &&
                        $configurations['size'] !== 'full' &&
                        $configurations['top'] === false && 
                        $configurations['bottom'] === false,
                    $configurations['size'] => $configurations['top'] || $configurations['bottom'],
                    'h-full' => !$configurations['top'] || !$configurations['bottom'],
                    'w-[100dvw]' => $configurations['top'] || $configurations['bottom'],
                ])>
                <div x-show="show"
                    x-transition:enter="transform transition ease-in-out duration-700"
                    x-transition:enter-start="@if ($configurations['left']) -translate-x-full @elseif ($configurations['top']) -translate-y-full @elseif ($configurations['bottom']) translate-y-full @else translate-x-full @endif"
                    x-transition:enter-end="@if ($configurations['left']) translate-x-0 @elseif ($configurations['top']) translate-y-0 @elseif ($configurations['bottom']) translate-y-0 @else translate-x-0 @endif"
                    x-transition:leave="transform transition ease-in-out duration-700"
                    x-transition:leave-start="@if ($configurations['left']) translate-x-0 @elseif ($configurations['top']) translate-y-0 @elseif ($configurations['bottom']) translate-y-0 @else translate-x-0 @endif"
                    x-transition:leave-end="@if ($configurations['left']) -translate-x-full @elseif ($configurations['top']) -translate-y-full @elseif ($configurations['bottom']) translate-y-full @else translate-x-full @endif"
                     @class(['pointer-events-auto w-screen', $configurations['size'],  'h-full' => !$configurations['top'] || !$configurations['bottom']])
                     @if (!$configurations['persistent']) x-on:mousedown.away="show = false" @endif>
                    <div @class([
                            $personalize['wrapper.fifth'], 
                            $configurations['size'],
                            'h-full' => !$configurations['top'] || !$configurations['bottom']
                        ])>
                        <div class="{{ $personalize['header'] }}">
                            <div @class(['flex items-start', 'justify-between' => $title !== null, 'justify-end' => $title === null])>
                                @if ($title)
                                    <h2 @if ($title instanceof \Illuminate\View\ComponentSlot)
                                            {{ $title->attributes->class($personalize['title.text']) }}
                                        @else
                                            class="{{ $personalize['title.text'] }}"
                                        @endif>{{ $title }}</h2>
                                @endif
                                <button type="button" x-on:click="show = false">
                                    <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                         :icon="TallStackUi::icon('x-mark')"
                                                         internal
                                                         class="{{ $personalize['title.close'] }}" />
                                </button>
                            </div>
                        </div>
                        <div class="{{ $personalize['body'] }}">
                            {{ $slot }}
                        </div>
                        @if ($footer)
                            <div @if ($footer instanceof \Illuminate\View\ComponentSlot) {{ $footer->attributes->class([
                                    $personalize['footer'],
                                    'justify-start' => $footer->attributes->get('start', false),
                                    'justify-end' => $footer->attributes->get('end', false),
                                ]) }} @else class="{{ $personalize['footer'] }}" @endif>
                                {{ $footer }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
