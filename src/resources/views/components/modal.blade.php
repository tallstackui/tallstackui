@php
    use Illuminate\Support\Str;
    $event = Str::slug(Str::kebab($id));
    $open = $event . '-open';
    $close = $event . '-close';
    $personalize = $classes();
    $events = $attributes->whereStartsWith('x-on:');
@endphp

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
     @if (!$configurations['persistent']) x-on:keydown.escape.window="show = false;" @endif
     x-on:modal:{{ $open }}.window="show = true;"
     x-on:modal:{{ $close }}.window="show = false;"
     {!! $events !!}>
    <div x-show="show"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @class([$personalize['wrapper.first'], $personalize['blur.'.($configurations['blur'] === true ? 'sm' : $configurations['blur'])] ?? null => $configurations['blur']])></div>
    <div @class($personalize['wrapper.second'])>
        <div @class([
                $personalize['wrapper.third'],
                $configurations['size'],
                $personalize['positions.top'] => !$configurations['center'],
                $personalize['positions.center'] => $configurations['center'],
            ])>
            <div x-show="show"
                 @if (!$configurations['persistent']) x-on:mousedown.away="show = false" @endif
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 @class([$personalize['wrapper.fourth'], $configurations['size']])>
                @if ($title)
                    <div @class($personalize['title.wrapper'])>
                        <h3 @class($personalize['title.text'])>{{ $title }}</h3>
                         <button type="button" x-on:click="show = false">
                            <x-dynamic-component :component="TallStackUi::component('icon')"
                                                 :icon="TallStackUi::icon('x-mark')"
                                                 @class($personalize['title.close']) />
                         </button>
                    </div>
                @endif
                <div @class($personalize['body'])>
                    {{ $slot }}
                </div>
                @if ($footer)
                    <div @class($personalize['footer'])>
                        {{ $footer }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
