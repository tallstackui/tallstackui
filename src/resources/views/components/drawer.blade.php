@php
    $personalize = $classes();
@endphp

<div x-cloak
     @if ($id) id="{{ $id }}" @endif
     @class(['relative', $configurations['zIndex']])
     aria-labelledby="drawer-title"
     role="dialog"
     aria-drawer="true"
     @if ($wire)
         x-data="tallstackui_drawer(@entangle($entangle), @js($configurations['overflow'] ?? false))"
     @else
         x-data="tallstackui_drawer(false, @js($configurations['overflow'] ?? false))"
     @endif
     x-show="show"
     @if (!$configurations['persistent']) x-on:keydown.escape.window="show = false;" @endif
     x-on:drawer:{{ $open }}.window="show = true;"
     x-on:drawer:{{ $close }}.window="show = false;"
     {{ $attributes->whereStartsWith('x-on:') }}>

     <div x-show="show"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @class([$personalize['wrapper.first'], $personalize['blur.'.($configurations['blur'] === true ? 'sm' : $configurations['blur'])] ?? null => $configurations['blur']])>
        <div class="{{ $personalize['wrapper.second'] }}">
            <div @class([
                    $personalize['wrapper.third'],
                ])
            >
                <div x-show="show"
                    @if (!$configurations['persistent']) x-on:mousedown.away="show = false" @endif
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    @class([
                        $personalize['wrapper.fourth'], 
                        $configurations['size'] => $configurations['position'] == 'left' || $configurations['position'] == 'right',
                        $personalize['positions.left'] => $configurations['position'] == 'left',
                        $personalize['positions.right'] => $configurations['position'] == 'right',
                        $personalize['positions.top'] => $configurations['position'] == 'top',
                        $personalize['positions.bottom'] => $configurations['position'] == 'bottom',
                        $personalize['blur.'.($configurations['blur'] === true ? 'sm' : $configurations['blur'])] ?? null => $configurations['blur']
                    ])
                    
                >
                    @if ($title)
                        <div class="{{ $personalize['title.wrapper'] }}">
                            <h5 class="{{ $personalize['title.text'] }}">{{ $title }}</h5>
                            <button class="{{ $personalize['title.button'] }}" type="button" x-on:click="show = false">
                                <svg class="{{ $personalize['title.close'] }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                </svg>
                            </button>
                        </div>
                    @endif
                    <div class="{{ $personalize['body'] }}">
                        {{ $slot }}
                    </div>
                    @if ($footer)
                        <div class="{{ $personalize['footer'] }}">
                            {{ $footer }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
     </div>
</div>