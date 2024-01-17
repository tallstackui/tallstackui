@php
    [$property, $error, $id] = $bind($attributes, $errors ?? null, $livewire);
    $personalize = $classes();
@endphp

<x-dynamic-component :component="TallStackUi::component('wrapper.input')" :$id :$property :$error :$label :$hint :$invalidate>
    <div x-data="tallstackui_datepicker(@js($range), @js($format), @js($min), @js($max), @js($disabledDates))" x-cloak>
        <div @class([
                $personalize['input.class.wrapper'],
                $personalize['input.class.color.base'] => !$error,
                $personalize['input.class.color.background'] => !$attributes->get('disabled') && !$attributes->get('readonly'),
                $personalize['input.class.color.disabled'] => $attributes->get('disabled') || $attributes->get('readonly'),
                $personalize['error'] => $error === true
            ])
            x-ref="anchor">
            <input @if ($id) id="{{ $id }}" @endif
               type="text"
               readonly
               x-on:click="open = !open; showYearPicker=false;" 
               x-model="datePickerValue"
               x-on:keydown.escape="open = false"
               x-ref="datePickerInput"
               {{ $attributes->class($personalize['input.class.base'])}}>
            <div x-on:click="open=!open; if(open){ $refs.datePickerInput.focus() }"
                class="px-1 py-2 cursor-pointer text-secondary-500 dark:text-dark-400">
                <x-dynamic-component :component="TallStackUi::component('icon')" icon="calendar" class="w-5 h-5" />
            </div>
            <div x-show="open" x-anchor.bottom-end.offset.10="$refs.anchor" x-transition x-on:click.away="datePickerAway()"
                class="absolute z-10 max-w-lg p-4 antialiased bg-white dark:bg-dark-700 border rounded-lg shadow w-[17rem] border-gray-200 dark:border-dark-600">
                <div class="flex items-center justify-between mb-4">
                    <div x-on:click="toggleYearPicker()"
                        class="text-sm rounded-lg flex items-center justify-between text-gray-900 dark:text-white font-semibold py-1 px-2 hover:bg-dark-100 dark:hover:bg-dark-600 focus:outline-none focus:ring-2 focus:ring-gray-200 cursor-pointer">
                        <!-- Year label, clicking toggles the year picker -->
                        <span>
                            <span x-text="datePickerMonthNames[datePickerMonth]" class="text-lg font-bold text-gray-800 dark:text-dark-100"></span>
                            <span x-text="datePickerYear" class="ml-1 text-lg font-normal text-gray-600 dark:text-dark-400"></span>
                        </span>

                        <!-- Year picker dropdown/modal -->
                        <template x-if="showYearPicker">
                            <div class="absolute top-0 left-0 flex w-full h-full p-3 bg-white dark:bg-dark-700 rounded-lg" x-cloak>
                                <div class="flex flex-wrap w-full">
                                    <div class="w-full flex items-center justify-between mb-2 px-1">
                                        <div class="text-sm rounded-lg flex items-center justify-between text-gray-900 dark:text-white bg-white dark:bg-gray-700 font-semibold py-1 px-2 hover:bg-dark-100 dark:hover:bg-dark-600 focus:outline-none focus:ring-2 focus:ring-gray-200 cursor-pointer">
                                            <span x-text="yearRangeStart" class="text-lg font-bold text-gray-800 dark:text-dark-100"></span>
                                            <span class="mx-1">-</span> 
                                            <span x-text="yearRangeStart + 19" class="text-lg font-bold text-gray-800 dark:text-dark-100"></span>
                                        </div>
                                        <div>
                                            <button x-on:click="previousYearRange($event)" class="inline-flex p-1 transition duration-100 ease-in-out rounded-full cursor-pointer focus:outline-none focus:shadow-outline hover:bg-dark-100 dark:hover:bg-dark-600">
                                                <x-dynamic-component :component="TallStackUi::component('icon')" icon="chevron-left" class="w-5 h-5 text-gray-600 dark:text-dark-300" />
                                            </button>
                                            <button x-on:click="nextYearRange($event)" class="inline-flex p-1 transition duration-100 ease-in-out rounded-full cursor-pointer focus:outline-none focus:shadow-outline hover:bg-dark-100 dark:hover:bg-dark-600">
                                                <x-dynamic-component :component="TallStackUi::component('icon')" icon="chevron-right" class="w-5 h-5 text-gray-600 dark:text-dark-300" />
                                            </button>
                                        </div>
                                    </div>
                                    <template x-for="year in generateYearRange()">
                                        <div class="flex items-center justify-center w-1/4 p-1 text-center cursor-pointer hover:bg-dark-100 dark:hover:bg-dark-600 font-normal rounded-md text-gray-600 dark:text-dark-400"
                                            x-on:click="selectYear($event, year)" x-text="year"></div>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>
                    <div>
                        <button x-on:click="datePickerPreviousMonth()" type="button"
                            class="inline-flex p-1 transition duration-100 ease-in-out rounded-full cursor-pointer focus:outline-none focus:shadow-outline hover:bg-dark-100 dark:hover:bg-dark-600">
                            <x-dynamic-component :component="TallStackUi::component('icon')" icon="chevron-left" class="w-5 h-5 text-gray-600 dark:text-dark-300" />
                        </button>
                        <button x-on:click="datePickerNextMonth()" type="button"
                            class="inline-flex p-1 transition duration-100 ease-in-out rounded-full cursor-pointer focus:outline-none focus:shadow-outline hover:bg-dark-100 dark:hover:bg-dark-600">
                            <x-dynamic-component :component="TallStackUi::component('icon')" icon="chevron-right" class="w-5 h-5 text-gray-600 dark:text-dark-300" />
                        </button>
                    </div>
                </div>
                <div class="grid grid-cols-7 mb-3">
                    <template x-for="(day, index) in datePickerDays" :key="index">
                        <div class="px-0.5">
                            <div x-text="day" class="text-xs font-medium text-center text-gray-800 dark:text-dark-300"></div>
                        </div>
                    </template>
                </div>
                <div class="grid grid-cols-7">
                    <template x-for="blankDay in datePickerBlankDaysInMonth">
                        <div class="p-1 text-sm text-center border border-transparent"></div>
                    </template>
                    <template x-for="(dayObj, dayIndex) in datePickerDaysInMonth" :key="dayIndex">
                        <div class="mb-1" 
                            :class="{
                                'rounded-l-full': new Date(dayObj.full).getTime() === new Date(startDate).getTime(),
                                'rounded-r-full w-7 h-7': new Date(dayObj.full).getTime() === new Date(endDate).getTime(),
                                'bg-gray-200 dark:bg-dark-600': dateInterval(dayObj.full) === true,
                            }">
                            <button x-text="dayObj.day"
                                x-on:click="dayObj.isDisabled ? null : datePickerDayClicked(dayObj.day)"
                                x-bind:disabled="dayObj.isDisabled"
                                :class="{
                                    'text-primary-500': datePickerIsToday(dayObj.day) == true,
                                    'text-gray-600 dark:text-gray-400 hover:bg-gray-200': datePickerIsToday(dayObj.day) == false && datePickerIsSelectedDate(dayObj.day) == false && !dayObj.isDisabled,
                                    'bg-primary-500 text-white hover:bg-opacity-75': datePickerIsSelectedDate(dayObj.day) == true
                                }"
                                class="flex items-center justify-center text-sm leading-none text-center rounded-full h-7 w-7 focus:shadow-outline active:text-white disabled:text-gray-400 disabled:cursor-not-allowed dark:active:bg-primary-500 ring-primary-500 focus:bg-primary-600 dark:focus:ring-offset-dark-900 dark:focus:ring-primary-600 dark:hover:bg-dark-600 dark:hover:ring-primary-600 outline-none transition-all duration-200 ease-in-out hover:shadow-sm focus:ring-2 focus:ring-offset-2 focus:ring-offset-white">
                            </button>
                        </div>
                    </template>
                </div>

                <!-- Additional Time Picker -->
                @if ($time)
                    <div class="flex items-center justify-between my-2">
                        <input x-model="datePickerHour" type="number" min="0" max="12"
                            class="w-16 h-8 px-1 mr-2 text-sm bg-transparent border rounded-md text-gray-600 border-gray-300 dark:border-gray-600 dark:text-white ring-offset-background placeholder:text-gray-400 focus:border-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 text-center"
                            placeholder="HH" />
                        <span class="text-lg font-bold text-gray-600 dark:text-dark-200">:</span>
                        {{-- <input x-model="datePickerMinute" type="number" min="0" max="59"
                            class="w-16 h-8 px-1 ml-2 text-sm bg-transparent border rounded-md text-gray-600 border-gray-300 dark:border-gray-600 dark:text-white ring-offset-background placeholder:text-gray-400 focus:border-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 text-center"
                            placeholder="MM" /> --}}
                        <select x-model="datePickerMinute"
                            class="h-8 py-0 ml-2 text-sm bg-transparent border rounded-md text-gray-600 border-gray-300 dark:border-gray-600 dark:text-white ring-offset-background focus:border-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400">
                            <option value="AM">AM</option>
                        </select>
                        <select x-model="datePickerAmPm"
                            class="h-8 py-0 ml-2 text-sm bg-transparent border rounded-md text-gray-600 border-gray-300 dark:border-gray-600 dark:text-white ring-offset-background focus:border-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400">
                            <option value="AM">AM</option>
                            <option value="PM">PM</option>
                        </select>
                    </div>
                @endif
                <!-- Buttons for Yesterday, Today, and Tomorrow -->
                @if ($helpers)
                    <div class="flex items-center justify-between mt-4">
                        <button x-on:click="setDate('yesterday')"
                            class="px-2 py-1 text-sm font-medium text-gray-500 dark:text-gray-300 bg-gray-200 dark:bg-dark-600 rounded-md hover:bg-gray-300 dark:hover:bg-dark-500">Yesterday</button>
                        <button x-on:click="setDate('today')"
                            class="px-2 py-1 text-sm font-medium text-gray-500 dark:text-gray-300 bg-gray-200 dark:bg-dark-600 rounded-md hover:bg-gray-300 dark:hover:bg-dark-500">Today</button>
                        <button x-on:click="setDate('tomorrow')"
                            class="px-2 py-1 text-sm font-medium text-gray-500 dark:text-gray-300 bg-gray-200 dark:bg-dark-600 rounded-md hover:bg-gray-300 dark:hover:bg-dark-500">Tomorrow</button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-dynamic-component>