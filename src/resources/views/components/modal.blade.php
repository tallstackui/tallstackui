@php
    use Illuminate\Support\Str;
    $event = Str::slug(Str::kebab($id));
    $open = $event . '-open';
    $close = $event . '-close';

    $customize = tallstackui_personalization('modal', $personalization());
@endphp

<div x-cloak
     @if ($id) id="{{ $id }}" @endif
     @class(['relative', $zIndex])
     aria-labelledby="modal-title"
     role="dialog"
     aria-modal="true"
     @if ($wire)
         x-data="tallstackui_modal(@entangle($entangle))"
     @else
         x-data="tallstackui_modal(false)"
     @endif
     x-show="show"
     x-on:modal:{{ $open }}.window="show = true;"
     x-on:modal:{{ $close }}.window="show = false;">
    <div x-show="show"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @class([
            $customize['wrapper.first'],
            $customize['blur'] => $blur,
         ])></div>
    <div @class($customize['wrapper.second'])>
        <div @class([$customize['wrapper.third'], $size])>
            <div x-show="show"
                 @if (!$uncloseable) x-on:click.outside="show = false" @endif
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 @class([$customize['wrapper.fourth'], $size])>
                @if ($title)
                    <div @class($customize['title.wrapper'])>
                        <h3 @class($customize['title.text'])>{{ $title }}</h3>
                        <x-icon name="x-mark"
                                x-on:click="show = false"
                                @class($customize['title.close'])
                        />
                    </div>
                @endif
                <div @class($customize['body'])>
                    {{ $slot }}
                </div>
                @if ($footer)
                    <div @class($customize['footer'])>
                        {{ $footer }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
