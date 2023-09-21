@php
    use Illuminate\Support\Str;

    $event = Str::slug(Str::kebab($id));
    $open = $event . '-open';
    $close = $event . '-close';
@endphp

<div @if ($id) id="{{ $id }}" @endif
     class="relative {{ $zIndex }}"
     aria-labelledby="modal-title"
     role="dialog"
     aria-modal="true"
     x-data="{ show : @if ($wire) @entangle($entangle) @else false @endif }"
     x-show="show"
     x-on:{{ $open }}.window="show = true"
     x-on:{{ $close }}.window="show = false"
     x-cloak>
    <div x-show="show"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @class(['fixed inset-0 bg-gray-400 bg-opacity-50 transition-opacity', 'backdrop-blur-sm' => $blur])></div>
    <div class="fixed inset-0 z-50 w-screen overflow-y-auto">
        <div @class(['w-full min-h-full transform flex items-end justify-center mx-auto sm:items-start p-4', $size])>
            <div x-show="show"
                 @if ($closeable) x-on:click.outside="show = false" @endif
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 @class(['relative flex w-full transform flex-col overflow-auto rounded-lg bg-white text-left shadow-xl transition-all', $size])>
                @if ($title)
                    <div class="flex items-center justify-between border-b px-4 py-2.5 dark:border-0">
                        <h3 class="whitespace-normal font-medium text-md text-secondary-600">{{ $title }}</h3>
                        <button class="rounded-full p-1 text-secondary-300 focus:ring-secondary-200 focus:outline-none focus:ring-0"
                                x-on:click="show = false"
                                tabindex="-1">
                            <x-icon icon="x-mark" class="h-5 w-5 text-secondary-300" />
                        </button>
                    </div>
                @endif
                <div class="px-2 py-5 md:px-4 text-secondary-700 rounded-b-xl grow dark:text-secondary-400">
                    {{ $slot }}
                </div>
                @if ($footer)
                <div class="border-t border-t-gray-100 bg-gray-50 px-6 py-3">
                    {{ $footer }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
