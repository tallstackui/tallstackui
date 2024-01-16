@php
    [$property, $error, $id] = $bind($attributes, $errors ?? null, $livewire);
    $personalize = $classes();
@endphp

<x-dynamic-component :component="TallStackUi::component('wrapper.input')" :$id :$property :$error :$label :$hint :$invalidate>
    <div x-data="tallstackui_datepicker(@js($range), @js($disabledDates))" x-cloak>
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
               x-on:click="open = !open; showYearPicker=false;" x-model="datePickerValue"
               x-on:keydown.escape="open=false"
               x-ref="datePickerInput"
               {{ $attributes->class($personalize['input.class.base'])}}>
            <div x-on:click="open=!open; if(open){ $refs.datePickerInput.focus() }"
                class="absolute top-0 right-0 px-3 py-2 cursor-pointer text-neutral-400 hover:text-neutral-500">
                <x-dynamic-component :component="TallStackUi::component('icon')" icon="calendar" class="w-5 h-5" />
            </div>
            <div x-show="open" x-anchor.bottom-end.offset.10="$refs.anchor" x-transition x-on:click.away="datePickerAway()"
                class="absolute z-10 max-w-lg p-4 antialiased bg-white border rounded-lg shadow w-[17rem] border-neutral-200/70">
                <div class="flex items-center justify-between mb-2">
                    <div x-on:click="toggleYearPicker()"
                        class="text-sm rounded-lg text-gray-900 dark:text-white bg-white dark:bg-gray-700 font-semibold py-1 px-2 hover:bg-gray-100 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-200 view-switch cursor-pointer">
                        <!-- Year label, clicking toggles the year picker -->
                        <span class="">
                            <span x-text="datePickerMonthNames[datePickerMonth]" class="text-lg font-bold text-gray-800"></span>
                            <span x-text="datePickerYear" class="ml-1 text-lg font-normal text-gray-600"></span>
                        </span>

                        <!-- Year picker dropdown/modal -->
                        <template x-if="showYearPicker">
                            <div class="absolute top-0 left-0 flex w-full h-full p-3 bg-white rounded shadow"
                                x-cloak>
                                <button x-on:click="previousYearRange($event)" class="self-center p-1">
                                   <x-dynamic-component :component="TallStackUi::component('icon')" icon="chevron-left" class="w-5 h-5" />
                                </button>
                                <div class="flex flex-wrap w-full">
                                    <template x-for="year in generateYearRange()">
                                        <div class="flex items-center justify-center w-1/4 p-1 text-center cursor-pointer hover:bg-gray-100 font-normal rounded-md text-gray-600"
                                            x-on:click="selectYear($event, year)" x-text="year"></div>
                                    </template>
                                </div>
                                <button x-on:click="nextYearRange($event)" class="self-center p-1">
                                    <x-dynamic-component :component="TallStackUi::component('icon')" icon="chevron-right" class="w-5 h-5" />
                                </button>
                            </div>
                        </template>
                    </div>
                    <div>
                        <button x-on:click="datePickerPreviousMonth()" type="button"
                            class="inline-flex p-1 transition duration-100 ease-in-out rounded-full cursor-pointer focus:outline-none focus:shadow-outline hover:bg-gray-100">
                            <x-dynamic-component :component="TallStackUi::component('icon')" icon="chevron-left" class="w-5 h-5" />
                        </button>
                        <button x-on:click="datePickerNextMonth()" type="button"
                            class="inline-flex p-1 transition duration-100 ease-in-out rounded-full cursor-pointer focus:outline-none focus:shadow-outline hover:bg-gray-100">
                            <x-dynamic-component :component="TallStackUi::component('icon')" icon="chevron-right" class="w-5 h-5" />
                        </button>
                    </div>
                </div>
                <div class="grid grid-cols-7 mb-3">
                    <template x-for="(day, index) in datePickerDays" :key="index">
                        <div class="px-0.5">
                            <div x-text="day" class="text-xs font-medium text-center text-gray-800"></div>
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
                                'bg-neutral-200': dateInterval(dayObj.full) === true,
                            }">
                            <div x-text="dayObj.day"
                                x-on:click="dayObj.isDisabled ? null : datePickerDayClicked(dayObj.day)"
                                :class="{
                                    'bg-neutral-200': datePickerIsToday(dayObj.day) == true,
                                    'text-gray-600 hover:bg-neutral-200': datePickerIsToday(dayObj.day) == false && datePickerIsSelectedDate(dayObj.day) == false && !dayObj.isDisabled,
                                    'bg-primary-500 text-white hover:bg-opacity-75': datePickerIsSelectedDate(dayObj.day) == true,
                                    'text-gray-400 cursor-not-allowed': dayObj.isDisabled
                                }"
                                class="flex items-center justify-center text-sm leading-none text-center rounded-full cursor-pointer h-7 w-7">
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Additional Time Picker -->
                @if ($timePicker)
                    <div>
                        <div class="flex items-center justify-center">
                            <input x-model="datePickerHour" type="number" min="0" max="12"
                                class="w-12 h-6 px-1 mr-2 text-sm bg-white border rounded-md text-neutral-600 border-neutral-300 ring-offset-background placeholder:text-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400"
                                placeholder="HH" />
                            <span class="text-sm font-medium text-neutral-600">:</span>
                            <input x-model="datePickerMinute" type="number" min="0" max="59"
                                class="w-12 h-6 px-1 ml-2 text-sm bg-white border rounded-md text-neutral-600 border-neutral-300 ring-offset-background placeholder:text-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400"
                                placeholder="MM" />
                            <select x-model="datePickerAmPm"
                                class="h-6 py-0 ml-2 text-sm bg-white border rounded-md text-neutral-600 border-neutral-300 ring-offset-background focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400">
                                <option value="AM">AM</option>
                                <option value="PM">PM</option>
                            </select>
                        </div>
                    </div>
                @endif
                <!-- Buttons for Yesterday, Today, and Tomorrow -->
                @if ($helpers)
                    <div class="flex items-center justify-center mt-4 space-x-2">
                        <button x-on:click="setDate('yesterday')"
                            class="px-3 py-2 text-sm font-medium text-white bg-gray-500 rounded-md hover:bg-gray-600 focus:outline-none focus:shadow-outline-gray active:bg-gray-700">Yesterday</button>
                        <button x-on:click="setDate('today')"
                            class="px-3 py-2 text-sm font-medium text-white bg-blue-500 rounded-md hover:bg-blue-600 focus:outline-none focus:shadow-outline-blue active:bg-blue-700">Today</button>
                        <button x-on:click="setDate('tomorrow')"
                            class="px-3 py-2 text-sm font-medium text-white bg-green-500 rounded-md hover:bg-green-600 focus:outline-none focus:shadow-outline-green active:bg-green-700">Tomorrow</button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-dynamic-component>