@php
    use Illuminate\Support\Str;
    $event = Str::slug(Str::kebab($id));
    $open = $event . '-open';
    $close = $event . '-close';

    // When the slide is opened in left we prefix the -
    // to invert the animation direction in x-transition
    $animation = $configurations['left'] ? '-' : '';
@endphp

<div x-cloak
     x-data="{ show : @if ($wire) @entangle($entangle) @else false @endif }"
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
         @class(['fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity', 'backdrop-blur-sm' => $configurations['blur'] === true])></div>
    <div class="fixed inset-0 overflow-hidden">
        <div class="absolute inset-0 overflow-hidden">
            <div @class([
                    'pointer-events-none fixed inset-y-0 flex max-w-full',
                    'left-0' => $configurations['left'],
                    'pr-10' => $configurations['left'] && $configurations['size'] !== 'full',
                    'right-0' => $configurations['left'] === false,
                    'pl-10' => $configurations['left'] === false && $configurations['size'] !== 'full',
                ])>
                <div x-show="show"
                     x-transition:enter="transform transition ease-in-out duration-700"
                     x-transition:enter-start="{{ $animation }}translate-x-full"
                     x-transition:enter-end="{{ $animation }}translate-x-0"
                     x-transition:leave="transform transition ease-in-out duration-700"
                     x-transition:leave-start="{{ $animation }}translate-x-0"
                     x-transition:leave-end="{{ $animation }}translate-x-full"
                     @class(['pointer-events-auto w-screen', $configurations['size']])
                     @if (!$configurations['persistent']) x-on:click.outside="show = false" @endif>
                    <div class="flex h-full flex-col overflow-y-scroll bg-white py-6 shadow-xl soft-scrollbar dark:bg-dark-700">
                        <div class="px-4 sm:px-6">
                            <div @class(['flex items-start', 'justify-between' => $title !== null, 'justify-end' => $title === null])>
                                @if ($title)
                                    <h2 class="whitespace-normal font-medium text-md text-secondary-600 dark:text-dark-300">{{ $title }}</h2>
                                @endif
                                <div class="ml-3 flex h-7 items-center">
                                    <x-icon name="x-mark"
                                            x-on:click="show = false"
                                            class="h-5 w-5 cursor-pointer text-secondary-300"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="grow rounded-b-xl px-6 py-5 text-gray-700 dark:text-dark-300">
                            {{ $slot }}
                        </div>
                        @if ($footer)
                            <div class="flex flex-shrink-0 justify-end border-t border-t-gray-200 px-2 pt-6 dark:border-t-dark-600">
                                <div {{ $footer->attributes }}>
                                    {{ $footer }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
