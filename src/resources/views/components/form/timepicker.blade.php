@php
    [$property, $error, $id, $entangle] = $bind($attributes, $errors ?? null, $livewire);
    $personalize = $classes();
@endphp

<div x-data="tallstackui_formTimePicker({!! $entangle !!}, @js($fullTime))"
    x-ref="wrapper"
    x-cloak>
    <x-dynamic-component :component="TallStackUi::component('input')"
                         :$label
                         :$hint
                         :$invalidate
                         icon="clock"
                         position="right"
                         x-on:click="show = !show"
                         readonly
                         x-ref="input"
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
        class="absolute top-full z-50 mt-2 overflow-hidden rounded-md border border-gray-200 shadow-lg w-[18rem] dark:border-dark-600">
        <div class="overflow-auto rounded-md bg-white p-4 shadow-xs soft-scrollbar dark:bg-dark-800">
            <div class="flex select-none items-center justify-center gap-1">
                <span x-text="formatted.hours"
                    x-ref="hours"
                    class="w-20 rounded-full p-2 text-center text-4xl font-medium transition text-primary-600 dark:text-dark-300 dark:border-dark-700">
                </span>
                <span class="h-14 text-5xl text-gray-300 dark:text-dark-700">:</span>
                <span x-text="formatted.minutes"
                    x-ref="minutes"
                    class="w-20 rounded-full p-2 text-center text-4xl font-medium transition text-primary-600 dark:text-dark-300 dark:border-dark-700">
                </span>
                @if (!$fullTime)
                    <div class="m-2 flex h-14 flex-col justify-between">
                        <div class="w-12">
                            <input type="radio"
                                   id="am"
                                   x-model="interval"
                                   value="AM"
                                   class="hidden peer">
                            <label for="am"
                                class="inline-flex w-full cursor-pointer items-center justify-between rounded-t-lg border border-gray-300 bg-white p-1 text-gray-500 peer-checked:bg-primary-50 peer-checked:border-primary-200 peer-checked:text-primary-600 peer-checked:font-bold hover:bg-gray-100 hover:text-gray-600 dark:peer-checked:text-dark-100 peer-checked:dark:bg-dark-700 peer-checked:dark:border-dark-500 dark:border-dark-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:text-dark-300 dark:hover:bg-gray-700">
                                <div class="w-full text-center text-sm">AM</div>
                            </label>
                        </div>
                        <div class="w-12">
                            <input type="radio"
                                   id="pm"
                                   x-model="interval"
                                   value="PM"
                                   class="hidden peer">
                            <label for="pm"
                                class="inline-flex w-full cursor-pointer items-center justify-between rounded-b-lg border border-t-0 border-gray-300 bg-white p-1 text-gray-500 peer-checked:bg-primary-50 peer-checked:border-primary-200 peer-checked:text-primary-600 peer-checked:font-bold hover:bg-gray-100 hover:text-gray-600 dark:peer-checked:text-dark-100 peer-checked:dark:bg-dark-700 peer-checked:dark:border-dark-500 dark:border-dark-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:text-dark-300 dark:hover:bg-gray-700">
                                <div class="w-full text-center text-sm font-medium">PM</div>
                            </label>
                        </div>
                    </div>
                @endif
            </div>
            <div wire:ignore.self @class(['mt-2 flex flex-col space-y-6 outline-none'])>
                <input type="range"
                       min="{{ !$fullTime ? 1 : 0 }}"
                       max="{{ !$fullTime ? 12 : 23 }}"
                       step="{{ $stepHour ?? 1 }}"
                       x-model="hours"
                       x-on:mouseenter="$refs.hours.classList.add('bg-primary-50', 'border-primary-600', 'dark:bg-dark-700')"
                       x-on:mouseleave="$refs.hours.classList.remove('bg-primary-50', 'border-primary-600', 'dark:bg-dark-700')"
                       @class(['focus:outline-none', $personalize['range.base'], $personalize['range.thumb']])>
                <input type="range"
                       min="0"
                       max="59"
                       step="{{ $stepMinute ?? 1 }}"
                       x-model="minutes"
                       x-on:mouseenter="$refs.minutes.classList.add('bg-primary-50', 'border-primary-600', 'dark:bg-dark-700')"
                       x-on:mouseleave="$refs.minutes.classList.remove('bg-primary-50', 'border-primary-600', 'dark:bg-dark-700')"
                       @class(['focus:outline-none', $personalize['range.base'], $personalize['range.thumb']])>
            </div>
            @if ($helper)
                <div class="mt-4">
                    <x-dynamic-component :component="TallStackUi::component('button')"
                                         class="w-full uppercase"
                                         x-on:click="current()"
                                         xs>current time</x-dynamic-component>
                </div>
            @endif
        </div>
    </div>
</div>
