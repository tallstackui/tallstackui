@php
    use Illuminate\Support\Str;

    $event = Str::slug(Str::kebab($id));
    $open  = $event . '-open';
    $close = $event . '-close';

    $customize = $customization();

    $customize['wrapper'] ??= [...$customWrapperClasses()];
    $customize['title']['wrapper'] ??= $customTitleWrapperClasses();
    $customize['title']['base'] ??= $customTitleBaseClasses();

    $customize['body'] ??= $customBodyClasses();
    $customize['footer'] ??= $customFooterClasses();
@endphp

<div @if ($id) id="{{ $id }}" @endif
     class="relative {{ $zIndex }}"
     aria-labelledby="modal-title"
     role="dialog"
     aria-modal="true"
     x-data="{ show : @if ($wire) @entangle($entangle) @else false @endif }"
     x-show="show"
     x-on:modal:{{ $open }}.window="show = true"
     x-on:modal:{{ $close }}.window="show = false"
     x-cloak>
    <div x-show="show"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @class($customize['wrapper']['first'])></div>
    <div class="fixed inset-0 z-50 w-screen overflow-y-auto">
        <div @class($customize['wrapper']['second'])>
            <div x-show="show"
                 @if ($closeable) x-on:click.outside="show = false" @endif
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 @class($customize['wrapper']['third'])>
                @if ($title)
                    <div @class($customize['title']['wrapper'])>
                        <h3 @class($customize['title']['base'])>{{ $title }}</h3>
                        <button class="rounded-full p-1 text-secondary-300 focus:ring-secondary-200 focus:outline-none focus:ring-0"
                                x-on:click="show = false"
                                tabindex="-1">
                            <x-icon name="x-mark" class="h-5 w-5 text-secondary-300" />
                        </button>
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
