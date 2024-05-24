@php
    [$property, $error, $id, $entangle] = $bind($attributes, $errors ?? null, $livewire);
    $personalize = $classes();
    $value = $sanitize($attributes, $property, $livewire);
    $validating($livewire ? ($property ? data_get($this, $property) : null) : $value);
@endphp

@if (!$livewire && $property)
    <input hidden name="{{ $property }}">
@endif

<div x-data="tallstackui_formDate(
     {!! $entangle !!},
     @js($range),
     @js($multiple),
     @js($format),
     {...@js($dates())},
     @js($disable->toArray()),
     @js($livewire),
     @js($property),
     @js($value),
     @js($monthYearOnly),
     @js(__('tallstack-ui::messages.date.calendar')),
     @js($change($attributes, $__livewire ?? null, $livewire)))"
     x-cloak x-on:click.outside="show = false">
    <x-dynamic-component :component="TallStackUi::component('input')"
                         {{ $attributes->except(['name', 'value']) }}
                         :$label
                         :$hint
                         :$invalidate
                         :alternative="$attributes->get('name')"
                         floatable
                         x-ref="input"
                         x-on:click="show = !show"
                         x-on:keydown="$event.preventDefault()"
                         dusk="tallstackui_date_input"
                         class="cursor-pointer caret-transparent">
        <x-slot:suffix>
            <div class="flex items-center gap-1.5">
                <button type="button" x-on:click="clear()" x-show="quantity > 0" {{ $attributes->only('x-on:clear') }} dusk="tallstackui_date_clear">
                    <x-dynamic-component :component="TallStackUi::component('icon')"
                                         :icon="TallStackUi::icon('x-mark')"
                                         @class([$personalize['icon.size'], $personalize['icon.clear']])/>
                </button>
                <button type="button" x-on:click="show = !show" dusk="tallstackui_date_open_close">
                    <x-dynamic-component :component="TallStackUi::component('icon')"
                                         :icon="TallStackUi::icon('calendar')"
                                         @class($personalize['icon.size']) />
                </button>
            </div>
        </x-slot:suffix>
    </x-dynamic-component>
    <x-dynamic-component :component="TallStackUi::component('floating')"
                         class="p-3 w-[17rem]"
                         x-bind:class="{ 'h-[17rem]' : picker.year || picker.month }">
        <div @class($personalize['box.picker.button'])>
            <span>
                <button type="button" x-text="calendar.months[month]" x-on:click="picker.month = true" @class($personalize['label.month'])></button>
                <button type="button" x-text="year" x-on:click="picker.year = true; range.year.start = (year - 11)" @class($personalize['label.year'])></button>
            </span>
            <template x-if="picker.month">
                <div @class($personalize['box.picker.wrapper.first']) x-cloak>
                    <div @class($personalize['box.picker.wrapper.second'])>
                        <div @class($personalize['box.picker.wrapper.third'])>
                            <button type="button" @class($personalize['box.picker.label']) x-on:click="if (monthYearOnly) {return false}; picker.month = false">
                                <span x-text="calendar.months[month]" @class($personalize['label.month'])></span>
                            </button>
                            <button type="button" class="mr-2" x-on:click="now()" x-show="!monthYearOnly">
                                {{ __('tallstack-ui::messages.date.helpers.today') }}
                            </button>
                        </div>
                        <template x-for="(months, index) in calendar.months" :key="index">
                            <button @class($personalize['box.picker.range'])
                                    type="button"
                                    x-bind:class="{ '{{ $personalize['button.today'] }}': month === index }"
                                    x-on:click="selectMonth($event, index)"
                                    x-text="months.substring(0, 3)">
                            </button>
                        </template>
                    </div>
                </div>
            </template>
            <template x-if="picker.year">
                <div @class($personalize['box.picker.wrapper.first']) x-cloak>
                    <div @class($personalize['box.picker.wrapper.second'])>
                        <div @class($personalize['box.picker.wrapper.third'])>
                            <div @class($personalize['box.picker.label'])>
                                <span x-text="range.year.first" @class($personalize['label.month'])></span>
                                <span class="mx-1">-</span>
                                <span x-text="range.year.last" @class($personalize['label.month'])></span>
                            </div>
                            <button type="button" x-on:click="now()" x-show="!monthYearOnly">
                                {{ __('tallstack-ui::messages.date.helpers.today') }}
                            </button>
                            <div>
                                <button type="button"
                                        dusk="tallstackui_date_previous_year"
                                        @class($personalize['button.navigate'])
                                        x-on:click="previousYear($event)"
                                        x-on:mousedown="if (!interval) interval = setInterval(() => previousYear($event), 200);"
                                        x-on:touchstart="if (!interval) interval = setInterval(() => previousYear($event), 200);"
                                        x-on:mouseup="if (interval) { clearInterval(interval); interval = null; }"
                                        x-on:mouseleave="if (interval) { clearInterval(interval); interval = null; }"
                                        x-on:touchend="if (interval) { clearInterval(interval); interval = null; }">
                                    <x-dynamic-component :component="TallStackUi::component('icon')"
                                                         :icon="TallStackUi::icon('chevron-left')"
                                                         @class($personalize['icon.navigate']) />
                                </button>
                                <button type="button"
                                        dusk="tallstackui_date_next_year"
                                        @class($personalize['button.navigate'])
                                        x-on:click="nextYear($event)"
                                        x-on:mousedown="if (!interval) interval = setInterval(() => nextYear($event), 200);"
                                        x-on:touchstart="if (!interval) interval = setInterval(() => nextYear($event), 200);"
                                        x-on:mouseup="if (interval) { clearInterval(interval); interval = null; }"
                                        x-on:mouseleave="if (interval) { clearInterval(interval); interval = null; }"
                                        x-on:touchend="if (interval) { clearInterval(interval); interval = null; }">
                                    <x-dynamic-component :component="TallStackUi::component('icon')"
                                                         :icon="TallStackUi::icon('chevron-right')"
                                                         @class($personalize['icon.navigate']) />
                                </button>
                            </div>
                        </div>
                        <template x-for="(range, index) in yearRange()" :key="index">
                            <button type="button" @class($personalize['box.picker.range'])
                                    x-bind:class="{ '{{ $personalize['button.today'] }}': range.year === year }"
                                    x-bind:disabled="range.disabled"
                                    x-on:click="selectYear($event, range.year)"
                                    x-text="range.year">
                            </button>
                        </template>
                    </div>
                </div>
            </template>
            <div>
                <button type="button"
                        dusk="tallstackui_date_previous_month"
                        @class($personalize['button.navigate'])
                        x-on:click="previousMonth()"
                        x-on:mousedown="if (!interval) interval = setInterval(() => previousMonth(), 200);"
                        x-on:touchstart="if (!interval) interval = setInterval(() => previousMonth(), 200);"
                        x-on:mouseup="if (interval) { clearInterval(interval); interval = null; }"
                        x-on:mouseleave="if (interval) { clearInterval(interval); interval = null; }"
                        x-on:touchend="if (interval) { clearInterval(interval); interval = null; }">
                    <x-dynamic-component :component="TallStackUi::component('icon')"
                                         :icon="TallStackUi::icon('chevron-left')"
                                         @class($personalize['icon.navigate']) />
                </button>
                <button type="button"
                        @class($personalize['button.navigate'])
                        dusk="tallstackui_date_next_month"
                        x-on:click="nextMonth()"
                        x-on:mousedown="if (!interval) interval = setInterval(() => nextMonth(), 200);"
                        x-on:touchstart="if (!interval) interval = setInterval(() => nextMonth(), 200);"
                        x-on:mouseup="if (interval) { clearInterval(interval); interval = null; }"
                        x-on:mouseleave="if (interval) { clearInterval(interval); interval = null; }"
                        x-on:touchend="if (interval) { clearInterval(interval); interval = null; }">
                    <x-dynamic-component :component="TallStackUi::component('icon')"
                                         :icon="TallStackUi::icon('chevron-right')"
                                         @class($personalize['icon.navigate']) />
                </button>
            </div>
        </div>
        <x-slot:footer>
            <div class="grid grid-cols-7 mb-3" x-show="!monthYearOnly">
                <template x-for="(day, index) in calendar.week" :key="index">
                    <div class="px-0.5">
                        <div x-text="day.slice(0, 3)" @class($personalize['label.days'])></div>
                    </div>
                </template>
            </div>
            <div class="grid grid-cols-7" x-show="!monthYearOnly">
                <template x-for="(blank, index) in blanks" :key="index">
                    <div @class($personalize['button.blank'])></div>
                </template>
                <template x-for="(day, index) in days" :key="index">
                    <div class="mb-2"
                         x-bind:class="{
                            'rounded-l-full': new Date(day.instance).getTime() === new Date(date.start).getTime(),
                            'rounded-r-full w-7 h-7': new Date(day.instance).getTime() === new Date(date.end).getTime(),
                            '{{ $personalize['range'] }}': between(day.instance) === true,
                         }">
                        <button x-text="day.day"
                                {{ $attributes->only('x-on:select') }}
                                x-on:click="select($event, day.day);"
                                x-bind:disabled="day.disabled"
                                x-bind:class="{
                                    '{{ $personalize['button.today'] }}': today(day.day) === true,
                                    '{{ $personalize['button.select'] }}': today(day.day) === false && selected(day.day) === false,
                                    '{{ $personalize['button.selected'] }}': selected(day.day) === true
                                }" @class($personalize['button.day']) x-show="!picker.year && !picker.month">
                        </button>
                    </div>
                </template>
            </div>
            @if ($helpers)
                <div @class($personalize['wrapper.helpers'])>
                    @foreach (['yesterday', 'today', 'tomorrow'] as $helper)
                        <button type="button"
                                dusk="tallstackui_date_helper_{{ $helper }}"
                                x-on:click="helper($event, @js($helper))"
                                @class($personalize['button.helpers'])>
                            {{ __('tallstack-ui::messages.date.helpers.' . $helper) }}
                        </button>
                    @endforeach
                </div>
            @endif
        </x-slot:footer>
    </x-dynamic-component>
</div>
