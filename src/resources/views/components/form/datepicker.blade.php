@php
    [$property, $error, $id] = $bind($attributes, $errors ?? null, $livewire);
    $personalize = $classes();
@endphp

<x-dynamic-component :component="TallStackUi::component('wrapper.input')" :$id :$property :$error :$label :$hint :$invalidate>
    <div x-data="tallstackui_datepicker(@js($range), @js($format), @js($min), @js($max), @js($disabledDates))" x-cloak x-on:click.outside="open = false">
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
            <div class="cursor-pointer text-secondary-500 dark:text-dark-400 flex items-center gap-2">
                <x-dynamic-component :component="TallStackUi::component('icon')" icon="x-mark" class="w-5 h-5 hover:text-red-500" x-show="datePickerValue" x-on:click="clear()" />
                <x-dynamic-component :component="TallStackUi::component('icon')" icon="calendar" class="w-5 h-5" x-on:click="open = !open; if(open){ $refs.datePickerInput.focus() }" />
            </div>
        </div>
        <div x-show="open" x-anchor.bottom-end.offset.10="$refs.anchor" x-transition x-on:click.away="datePickerAway()" class="absolute z-10 max-w-lg p-4 antialiased bg-white dark:bg-dark-700 border rounded-lg shadow w-[17rem] border-gray-200 dark:border-dark-600">
            <div class="flex items-center justify-between mb-4">
                <div x-on:click="toggleYearPicker()"
                    class="text-sm rounded-lg flex items-center justify-between text-gray-900 dark:text-white font-semibold py-1 px-2 hover:bg-dark-100 dark:hover:bg-dark-600 focus:outline-none focus:ring-2 focus:ring-gray-200 cursor-pointer">
                    <!-- Year label, clicking toggles the year picker -->
                    <span>
                        <span x-text="datePickerMonthNames[datePickerMonth]" @class($personalize['label.month'])></span>
                        <span x-text="datePickerYear" @class($personalize['label.year'])></span>
                    </span>

                    <!-- Year picker dropdown/modal -->
                    <template x-if="showYearPicker">
                        <div class="absolute top-0 left-0 flex w-full h-full p-3 bg-white dark:bg-dark-700 rounded-lg" x-cloak>
                            <div class="flex flex-wrap w-full">
                                <div class="w-full flex items-center justify-between mb-2 px-1">
                                    <div class="text-sm rounded-lg flex items-center justify-between text-gray-900 dark:text-white bg-white dark:bg-gray-700 font-semibold py-1 px-2 hover:bg-dark-100 dark:hover:bg-dark-600 focus:outline-none focus:ring-2 focus:ring-gray-200 cursor-pointer">
                                        <span x-text="yearRangeStart" @class($personalize['label.month'])></span>
                                        <span class="mx-1">-</span> 
                                        <span x-text="yearRangeStart + 19" @class($personalize['label.month'])></span>
                                    </div>
                                    <div>
                                        <button x-on:click="previousYearRange($event)" @class($personalize['button.navigate'])>
                                            <x-dynamic-component :component="TallStackUi::component('icon')" icon="chevron-left" @class($personalize['icon.navigate']) />
                                        </button>
                                        <button x-on:click="nextYearRange($event)" @class($personalize['button.navigate'])>
                                            <x-dynamic-component :component="TallStackUi::component('icon')" icon="chevron-right" @class($personalize['icon.navigate']) />
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
                    <button x-on:click="datePickerPreviousMonth()" type="button" @class($personalize['button.navigate'])>
                        <x-dynamic-component :component="TallStackUi::component('icon')" icon="chevron-left" @class($personalize['icon.navigate']) />
                    </button>
                    <button x-on:click="datePickerNextMonth()" type="button" @class($personalize['button.navigate'])>
                        <x-dynamic-component :component="TallStackUi::component('icon')" icon="chevron-right" @class($personalize['icon.navigate']) />
                    </button>
                </div>
            </div>

            <!-- days of the week -->
            <div class="grid grid-cols-7 mb-3">
                <template x-for="(day, index) in datePickerDays" :key="index">
                    <div class="px-0.5">
                        <div x-text="day" @class($personalize['label.days'])></div>
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
                            '{{ $personalize['range'] }}': dateInterval(dayObj.full) === true,
                        }">
                        <button x-text="dayObj.day"
                            x-on:click="dayObj.isDisabled ? null : datePickerDayClicked(dayObj.day)"
                            x-bind:disabled="dayObj.isDisabled"
                            :class="{
                                '{{ $personalize['button.today'] }}': datePickerIsToday(dayObj.day) == true,
                                '{{ $personalize['button.select'] }}': datePickerIsToday(dayObj.day) == false && datePickerIsSelectedDate(dayObj.day) == false && !dayObj.isDisabled,
                                '{{ $personalize['button.selected'] }}': datePickerIsSelectedDate(dayObj.day) == true
                            }"
                            @class($personalize['button.day'])>
                        </button>
                    </div>
                </template>
            </div>

            <!-- Additional Time Picker -->
            @if ($time)
                <div class="flex items-center justify-between my-2">
                    {{-- <input x-model="datePickerHour" type="number" min="0" max="12"
                        class="w-16 h-8 px-1 mr-2 text-sm bg-transparent border rounded-md text-gray-600 border-gray-300 dark:border-gray-600 dark:text-white ring-offset-background placeholder:text-gray-400 focus:border-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 text-center"
                        placeholder="HH" /> --}}
                    <select x-model="datePickerHour" class="h-8 py-0 text-sm bg-transparent border rounded-md text-gray-600 border-gray-300 dark:border-gray-600 dark:text-white ring-offset-background focus:border-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400">
                        @for ($min = 0; $min <= 12; $min += 1)
                            <option value="{{ str_pad($min, 2, '0', STR_PAD_LEFT) }}">{{ str_pad($min, 2, '0', STR_PAD_LEFT) }}</option>
                        @endfor
                    </select>
                    <span @class($personalize['colon'])>:</span>
                    {{-- <input x-model="datePickerMinute" type="number" min="0" max="59"
                        class="w-16 h-8 px-1 ml-2 text-sm bg-transparent border rounded-md text-gray-600 border-gray-300 dark:border-gray-600 dark:text-white ring-offset-background placeholder:text-gray-400 focus:border-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 text-center"
                        placeholder="MM" /> --}}
                    <select x-model="datePickerMinute" class="h-8 py-0 text-sm bg-transparent border rounded-md text-gray-600 border-gray-300 dark:border-gray-600 dark:text-white ring-offset-background focus:border-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400">
                        @for ($min = 0; $min < 60; $min += 5)
                            <option value="{{ str_pad($min, 2, '0', STR_PAD_LEFT) }}">{{ str_pad($min, 2, '0', STR_PAD_LEFT) }}</option>
                        @endfor
                    </select>
                    <select x-model="datePickerAmPm" class="h-8 py-0 ml-1 text-sm bg-transparent border rounded-md text-gray-600 border-gray-300 dark:border-gray-600 dark:text-white ring-offset-background focus:border-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400">
                        <option value="AM">AM</option>
                        <option value="PM">PM</option>
                    </select>
                </div>
            @endif
            <!-- Buttons Helpers -->
            @if ($helpers)
                <div @class($personalize['wrapper.helpers'])>
                    @foreach ($helpers as $helper)
                        @if ($helpers->contains($helper))
                            <button x-on:click="setDate('{{ $helper }}')" @class($personalize['button.helpers'])>{{ __('tallstack-ui::messages.datepicker.helpers.' . $helper) }}</button>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-dynamic-component>