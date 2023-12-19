<div x-data="tallstackui_datepicker()" x-cloak>
    <div class="container px-4 py-2 mx-auto md:py-10">
        <div class="w-full mb-5">
            <label for="datepicker" class="block mb-1 text-sm font-medium text-neutral-500">Select Date</label>
            <div class="relative w-[17rem]">
                <input x-ref="datePickerInput" type="text"
                    @click="datePickerOpen=!datePickerOpen; showYearPicker=false;" x-model="datePickerValue"
                    x-on:keydown.escape="datePickerOpen=false"
                    class="flex w-full h-10 px-3 py-2 text-sm bg-white border rounded-md text-neutral-600 border-neutral-300 ring-offset-background placeholder:text-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50"
                    placeholder="Select date" readonly />
                <div @click="datePickerOpen=!datePickerOpen; if(datePickerOpen){ $refs.datePickerInput.focus() }"
                    class="absolute top-0 right-0 px-3 py-2 cursor-pointer text-neutral-400 hover:text-neutral-500">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div x-show="datePickerOpen" x-transition @click.away="datePickerOpen = false"
                    class="absolute top-0 left-0 max-w-lg p-4 mt-12 antialiased bg-white border rounded-lg shadow w-[17rem] border-neutral-200/70">
                    <div class="flex items-center justify-between mb-2">
                        <button @click="datePickerPreviousMonth()" type="button"
                            class="inline-flex p-1 transition duration-100 ease-in-out rounded-full cursor-pointer focus:outline-none focus:shadow-outline hover:bg-gray-100">
                            <svg class="w-4 h-4 text-gray-800 rtl:rotate-180 dark:text-white" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="M13 5H1m0 0 4 4M1 5l4-4"></path>
                            </svg>
                        </button>
                        <div @click="toggleYearPicker"
                            class="text-sm rounded-lg text-gray-900 dark:text-white bg-white dark:bg-gray-700 font-semibold py-2.5 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-200 view-switch cursor-pointer">
                            <!-- Year label, clicking toggles the year picker -->
                            <span class="">
                                <span x-text="datePickerMonthNames[datePickerMonth]"></span>
                                <span x-text="datePickerYear"></span>
                            </span>

                            <!-- Year picker dropdown/modal -->
                            <template x-if="showYearPicker">
                                <div class="absolute top-0 left-0 flex w-full h-full p-3 bg-white rounded shadow"
                                    x-cloak>
                                    <button @click="previousYearRange()" class="p-1">
                                        <svg class="w-4 h-4 text-gray-800 rtl:rotate-180 dark:text-white"
                                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 14 10">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2" d="M13 5H1m0 0 4 4M1 5l4-4"></path>
                                        </svg>
                                    </button>
                                    <div class="flex flex-wrap w-full">
                                        <template x-for="year in generateYearRange()">
                                            <div class="flex items-center justify-center w-1/4 p-1 text-center cursor-pointer hover:bg-gray-100"
                                                @click="selectYear(year)" x-text="year"></div>
                                        </template>
                                    </div>
                                    <button @click="nextYearRange()" class="p-1">
                                        <svg class="w-4 h-4 text-gray-800 rtl:rotate-180 dark:text-white"
                                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 14 10">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"></path>
                                        </svg>
                                    </button>
                                </div>
                            </template>
                        </div>
                        <button @click="datePickerNextMonth()" type="button"
                            class="inline-flex p-1 transition duration-100 ease-in-out rounded-full cursor-pointer focus:outline-none focus:shadow-outline hover:bg-gray-100">
                            <svg class="w-4 h-4 text-gray-800 rtl:rotate-180 dark:text-white" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"></path>
                            </svg>
                        </button>
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
                        <template x-for="(day, dayIndex) in datePickerDaysInMonth" :key="dayIndex">
                            <div class="px-0.5 mb-1 aspect-square">
                                <div x-text="day" @click="datePickerDayClicked(day)"
                                    :class="{
                                        'bg-neutral-200': datePickerIsToday(day) == true,
                                        'text-gray-600 hover:bg-neutral-200': datePickerIsToday(day) == false &&
                                            datePickerIsSelectedDate(day) == false,
                                        'bg-neutral-800 text-white hover:bg-opacity-75': datePickerIsSelectedDate(
                                            day) == true
                                    }"
                                    class="flex items-center justify-center text-sm leading-none text-center rounded-full cursor-pointer h-7 w-7">
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Additional Time Picker -->
                    <div>
                        <div class="flex items-center justify-center">
                            <input x-model="datePickerHour" type="number" min="0" max="23"
                                class="h-6 px-1 mr-2 text-sm bg-white border rounded-md w-11 text-neutral-600 border-neutral-300 ring-offset-background placeholder:text-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400"
                                placeholder="HH" />
                            <span class="text-sm font-medium text-neutral-600">:</span>
                            <input x-model="datePickerMinute" type="number" min="0" max="59"
                                class="h-6 px-1 ml-2 text-sm bg-white border rounded-md w-11 text-neutral-600 border-neutral-300 ring-offset-background placeholder:text-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400"
                                placeholder="MM" />
                            <select x-model="datePickerAmPm"
                                class="h-6 py-0 ml-2 text-sm bg-white border rounded-md text-neutral-600 border-neutral-300 ring-offset-background focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400">
                                <option value="AM">AM</option>
                                <option value="PM">PM</option>
                            </select>
                        </div>
                    </div>

                    <!-- Buttons for Yesterday, Today, and Tomorrow -->
                    <div class="flex items-center justify-center mt-4 space-x-2">
                        <button @click="setDate('yesterday')"
                            class="px-3 py-2 text-sm font-medium text-white bg-gray-500 rounded-md hover:bg-gray-600 focus:outline-none focus:shadow-outline-gray active:bg-gray-700">Yesterday</button>
                        <button @click="setDate('today')"
                            class="px-3 py-2 text-sm font-medium text-white bg-blue-500 rounded-md hover:bg-blue-600 focus:outline-none focus:shadow-outline-blue active:bg-blue-700">Today</button>
                        <button @click="setDate('tomorrow')"
                            class="px-3 py-2 text-sm font-medium text-white bg-green-500 rounded-md hover:bg-green-600 focus:outline-none focus:shadow-outline-green active:bg-green-700">Tomorrow</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
