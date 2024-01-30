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
        x-on:click="show = !show"
        readonly
        class="cursor-pointer">
        <x-slot:suffix>
            <x-dynamic-component :component="TallStackUi::component('icon')"
                icon="clock"
                x-on:click="show = !show"
                class="w-5 h-5 cursor-pointer" />
        </x-slot:suffix>
    </x-input>
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
        class="dark:border-dark-600 absolute top-full z-50 mt-2 overflow-hidden rounded-md border border-gray-200 shadow-lg w-[18rem]">
        <div class="shadow-xs dark:bg-dark-800 soft-scrollbar overflow-auto rounded-md bg-white p-4">
            <div class="flex items-center justify-center gap-1 select-none">
                <span x-text="hours.padStart(2, '0')"
                    x-ref="hours"
                    class="text-4xl rounded-md w-20 text-center border p-2 font-semibold dark:border-dark-700">
                </span>
                <span class="text-5xl h-14">:</span>
                <span x-text="minutes.padStart(2, '0')"
                    x-ref="minutes"
                    class="text-4xl rounded-md w-20 text-center border p-2 font-semibold dark:border-dark-700">
                </span>
                <div class="flex flex-col justify-between h-14 m-2">
                    <div class="w-12">
                        <input type="radio" id="am" x-model="interval" value="AM" class="hidden peer">
                        <label for="am"
                            class="inline-flex items-center justify-between w-full p-1 rounded-t-lg text-gray-500 bg-white border border-gray-200 cursor-pointer dark:hover:text-dark-300 dark:border-dark-700 dark:peer-checked:text-dark-100 peer-checked:bg-primary-100 peer-checked:dark:bg-dark-700 peer-checked:border-primary-600 peer-checked:dark:border-dark-500 peer-checked:text-primary-600 hover:text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">
                            <div class="w-full text-sm text-center font-semibold">AM</div>
                        </label>
                    </div>
                    <div class="w-12">
                        <input type="radio" id="pm" x-model="interval" value="PM" class="hidden peer">
                        <label for="pm"
                            class="inline-flex items-center justify-between w-full p-1 rounded-b-lg text-gray-500 bg-white border border-gray-200 cursor-pointer dark:hover:text-dark-300 dark:border-dark-700 dark:peer-checked:text-dark-100 peer-checked:bg-primary-100 peer-checked:dark:bg-dark-700 peer-checked:border-primary-600 peer-checked:dark:border-dark-500 peer-checked:text-primary-600 hover:text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">
                            <div class="w-full text-sm text-center font-semibold">PM</div>
                        </label>
                    </div>
                </div>
            </div>
            <div class="flex flex-col space-y-6 mt-5">
                <input type="range"
                    min="1"
                    max="12"
                    x-model="hours"
                    x-on:mouseenter="$refs.hours.classList.add('bg-primary-100', 'border-primary-600', 'dark:bg-dark-700')"
                    x-on:mouseleave="$refs.hours.classList.remove('bg-primary-100', 'border-primary-600', 'dark:bg-dark-700')"
                    @class([$personalize['range.base'], $personalize['range.thumb']])>
                <input type="range"
                    min="0"
                    max="59"
                    x-model="minutes"
                    x-on:mouseenter="$refs.minutes.classList.add('bg-primary-100', 'border-primary-600', 'dark:bg-dark-700')"
                    x-on:mouseleave="$refs.minutes.classList.remove('bg-primary-100', 'border-primary-600', 'dark:bg-dark-700')"
                    @class([$personalize['range.base'], $personalize['range.thumb']])>
            </div>
            <div class="mt-5">
                <x-button class="w-full" sm>Current Time</x-button>
            </div>
        </div>
    </div>

</div>
