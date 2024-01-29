@php
    [$property, $error, $id, $entangle] = $bind($attributes, $errors ?? null, $livewire);
    $personalize = $classes();
@endphp

<div x-data="tallstackui_formTimePicker()"
     x-ref="wrapper"
     x-cloak>
    <x-input :$label
        :$hint
        :$invalidate
        icon="clock"
        position="right"
        x-on:click="show = !show"
        readonly
        class="cursor-pointer" />
    <div x-cloak
        x-show="show"
        x-on:click.away="show = false"
        x-transition:enter="transition duration-100 ease-out"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        x-anchor.bottom-end="$refs.wrapper"
        class="dark:border-dark-600 absolute top-full z-50 mt-2 overflow-hidden rounded-md border border-gray-200 shadow-lg">
        <div class="shadow-xs dark:bg-dark-800 soft-scrollbar overflow-auto rounded-md bg-white p-4">
            <div class="flex space-x-4 items-center justify-center">
                <div class="flex items-center select-none">
                    <span x-text="hours.padStart(2, '0')" 
                          x-ref="hours" 
                          class="text-2xl rounded-md w-10 text-center">
                    </span>
                    <span class="text-2xl">:</span>
                    <span x-text="minutes.padStart(2, '0')" 
                          x-ref="minutes" 
                          class="text-2xl rounded-md w-10 text-center">
                    </span>
                    <span class="text-sm">PM</span>
                </div>
                <div class="flex flex-col space-y-4">
                    <input type="range"
                           min="1"
                           max="12"
                           x-model="hours"
                           x-on:mouseenter="$refs.hours.classList.add('bg-gray-200', 'dark:bg-dark-700')" 
                           x-on:mouseleave="$refs.hours.classList.remove('bg-gray-200', 'dark:bg-dark-700')" 
                           @class([$personalize['range.base'], $personalize['range.thumb']])>
                    <input type="range"
                           min="0"
                           max="59"
                           x-model="minutes"
                           x-on:mouseenter="$refs.minutes.classList.add('bg-gray-200', 'dark:bg-dark-700')" 
                           x-on:mouseleave="$refs.minutes.classList.remove('bg-gray-200', 'dark:bg-dark-700')" 
                           @class([$personalize['range.base'], $personalize['range.thumb']])>
                </div>
            </div>
        </div>
    </div>

</div>
