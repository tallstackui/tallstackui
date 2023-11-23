@php
    use Illuminate\Support\Str;

    $personalize = tallstackui_personalization('slide', $personalization());

    $event = Str::slug(Str::kebab($id));
    $open = $event . '-open';
    $close = $event . '-close';
@endphp

<div x-cloak
     @if ($wire)
         x-data="tallstackui_slide(@entangle($entangle))"
     @else
         x-data="tallstackui_slide(false)"
     @endif
     x-show="show"
     x-on:slide:{{ $open }}.window="show = true;"
     x-on:slide:{{ $close }}.window="show = false;"
     @class(['relative', $configurations['zIndex']])>
    <div x-show="show"
         x-transition:enter="ease-in-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in-out duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @class([$personalize['wrapper.first'], 'backdrop-blur-sm' => $configurations['blur'] === true])></div>
    <div @class($personalize['wrapper.second'])>
        <div @class($personalize['wrapper.third'])>
            <div @class([
                    $personalize['wrapper.fourth'],
                    'left-0' => $configurations['left'],
                    'pr-10' => $configurations['left'] && $configurations['size'] !== 'full',
                    'right-0' => $configurations['left'] === false,
                    'pl-10' => $configurations['left'] === false && $configurations['size'] !== 'full',
                ])>
                <div x-show="show"
                     x-transition:enter="transform transition ease-in-out duration-700"
                     x-transition:enter-start="@if ($configurations['left']) -translate-x-full @else translate-x-full @endif"
                     x-transition:enter-end="@if ($configurations['left']) -translate-x-0 @else translate-x-0 @endif"
                     x-transition:leave="transform transition ease-in-out duration-700"
                     x-transition:leave-start="@if ($configurations['left']) -translate-x-0 @else translate-x-0 @endif"
                     x-transition:leave-end="@if ($configurations['left']) -translate-x-full @else translate-x-full @endif"
                     @class(['pointer-events-auto w-screen', $configurations['size']])
                     @if (!$configurations['persistent']) x-on:click.outside="show = false" @endif>
                    <div @class($personalize['wrapper.fifth'])>
                        <div class="px-6">
                            <div @class(['flex items-start', 'justify-between' => $title !== null, 'justify-end' => $title === null])>
                                @if ($title)
                                    <h2 @if ($title instanceof \Illuminate\View\ComponentSlot)
                                            {{ $title->attributes->class($personalize['title.text']) }}
                                        @else
                                            @class($personalize['title.text'])
                                        @endif>{{ $title }}</h2>
                                @endif
                                <x-icon name="x-mark"
                                        x-on:click="show = false"
                                        @class($personalize['title.close'])
                                />
                            </div>
                        </div>
                        <div @class($personalize['body'])>
                            {{ $slot }}
                        </div>
                        @if ($footer)
                            <div @if ($footer instanceof \Illuminate\View\ComponentSlot) {{ $footer->attributes->class([
                                    $personalize['footer'],
                                    'justify-start' => $footer->attributes->get('start', false),
                                    'justify-end' => $footer->attributes->get('end', false),
                                ]) }} @else @class($personalize['footer']) @endif>
                                {{ $footer }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
