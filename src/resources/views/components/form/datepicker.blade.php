@php
    [$property, $error, $id, $entangle] = $bind($attributes, $errors ?? null, $livewire);
    $personalize = $classes();
@endphp

<x-dynamic-component :component="TallStackUi::component('wrapper.input')" :$id :$property :$error :$label :$hint :$invalidate>
    <div x-data="tallstackui_datepicker({!! $entangle !!}, @js($range), @js($format), @js($min), @js($max), @js($disabledDates), @js($placeholders['days']), @js($placeholders['months']))" 
         x-cloak 
         x-on:click.outside="open = false">
        <div x-ref="anchor"
             @class([
                $personalize['input.class.wrapper'],
                $personalize['input.class.color.base'] => !$error,
                $personalize['input.class.color.background'] => !$attributes->get('disabled') && !$attributes->get('readonly'),
                $personalize['input.class.color.disabled'] => $attributes->get('disabled') || $attributes->get('readonly'),
                $personalize['error'] => $error
            ])>
            <input @if ($id) id="{{ $id }}" @endif
               type="text"
               readonly
               x-on:click="open = !open; showYearPicker=false;" 
               x-model="datePickerValue"
               x-on:keydown.escape="open = false"
                              x-ref="datePickerInput"
               {{ $attributes->class($personalize['input.class.base'])}}>
            <div @class($personalize['icon.input'])>
                <x-dynamic-component :component="TallStackUi::component('icon')" icon="x-mark" class="w-5 h-5 hover:text-red-500" x-show="datePickerValue" x-on:click="clear()" />
                <x-dynamic-component :component="TallStackUi::component('icon')" icon="calendar" class="w-5 h-5" x-on:click="open = !open" />
            </div>
        </div>
        <div x-show="open" 
             x-anchor.bottom-end.offset.10="$refs.anchor"
             x-transition:enter="transition ease-out duration-75"
             x-transition:enter-start="opacity-0 -translate-y-1"
             x-transition:enter-end="opacity-100" 
             x-on:click.away="datePickerAway()" 
             @class($personalize['box.wrapper'])>
            <div class="flex items-center justify-between mb-4">
                <div x-on:click="toggleYearPicker()"
                    @class($personalize['box.picker.button'])>
                    <!-- Year label, clicking toggles the year picker -->
                    <span>
                        <span x-text="datePickerMonthNames[datePickerMonth]" @class($personalize['label.month'])></span>
                        <span x-text="datePickerYear" @class($personalize['label.year'])></span>
                    </span>

                    <!-- Year picker dropdown/modal -->
                    <template x-if="showYearPicker">
                        <div @class($personalize['box.picker.wrapper.first']) x-cloak>
                            <div @class($personalize['box.picker.wrapper.second'])>
                                <div @class($personalize['box.picker.wrapper.third'])>
                                    <div @class($personalize['box.picker.label'])>
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
                                    <div @class($personalize['box.picker.range']) x-on:click="selectYear($event, year)" x-text="year"></div>
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
                    <div class="mb-2" 
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
            
            <!-- Buttons Helpers -->
            @if (!$helpers->isEmpty())
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